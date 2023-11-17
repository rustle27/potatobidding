<?php
session_start();
require '../Entity/entity.php';

function validate($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$id = validate($_POST['id']);
$uname = validate($_POST['uname']);
$name = validate($_POST['name']);
$days = validate($_POST['days']);
$role = strtolower(validate($_POST['role']));

if (isset($_GET['userid']) && isset($_GET['sessionid'])){
    $id = $_GET['userid'];
    $controller = new getUserDetails($conn);
    $controller->getUserDetails($id);
} else if (isset($_GET['userid'])){
    $controller = new sysUpdateProfileController($conn);
    $controller->handleSysUpdateProfileRequest($id , $uname, $name, $days, $role);
} else if (isset($_GET['userData']) && isset($_GET['sessionid'])){
    $userData = $_GET['userData'];
    $controller = new Back($conn);
    $controller->handleBackRequest($userData);
}

class getUserDetails{
    private $entity;

    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function getUserDetails($userId){
        $userData = $this->entity->getUserById($userId);
        if($userData !== false){
            $_SESSION['id'] = $_GET['sessionid'];
            $userData2 = $this->entity->getUserById($_SESSION['id']);
            $_SESSION['role'] = $userData2['role'];
            header("Location: ../Boundary/sysUpdateProfileBoundary.php?userData=" . urlencode(json_encode($userData)));
            exit();
        } else {
            $_SESSION['error_message'] = 'Error retrieving profile details';
            header("Location: ../Boundary/sysViewProfileBoundary.php");
        }
    }
}

class sysUpdateProfileController{
    private $entity;
    private $entity2;

    public function __construct($conn){
        $this->entity = new Account($conn);
        $this->entity2 = new Workslot($conn);
    }

    public function handleSysUpdateProfileRequest($id, $uname, $name, $days, $role){
        $userData3 = $this->entity->getUserById($id);
        $oldName = $userData3['name'];

        $userData = $this->entity->updateUser($id, $uname, $name, $days, $role);
        if ($userData !== false){
            // After successfully updating the profile
            $_SESSION['success_message'] = 'Profile updated successfully';
            $_SESSION['id'] = $_GET['userid'];
            // Extract updated account details from database
            $userData2 = $this->entity->getUserById($id);
            $newName = $userData2['name'];
            $workslots = $this->entity2->retriveWorkslotRoleIdByName($oldName);
            foreach ($workslots as $row){
                $workslotid = $row['id'];
                // Update from old name to new name
                $this->entity2->updateWorkslotCashierRoleById($workslotid, $oldName, $newName);
                $this->entity2->updateWorkslotCleanerRoleById($workslotid, $oldName, $newName);
                $this->entity2->updateWorkslotWaiterRoleById($workslotid, $oldName, $newName);
            }
            header("Location: ../Boundary/sysUpdateProfileBoundary.php?userData=" . urlencode(json_encode($userData2)));
            return true;
        } else {
            $_SESSION['error_message'] = 'Error updating profile';
            header("Location: ../Boundary/sysUpdateProfileBoundary.php?userData=" . urlencode(json_encode($userData2)));
            return false;
        }
    }
}

class Back{
    private $entity;

    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function handleBackRequest($userData){
        $_SESSION['id'] = $_GET['sessionid'];
        header("Location: ../Boundary/sysUpdateProfileBoundary.php?userData=" . $userData);
    }
}
?>