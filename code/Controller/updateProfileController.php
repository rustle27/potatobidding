<?php 
session_start();
require '../Entity/entity.php';

if (isset($_GET['action']) && $_GET['action'] == 'handleUpdateProfileRequest'){
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $id = validate($_POST['id']);
    $username = validate($_POST['username']);
    $name = validate($_POST['name']);
    $days = validate($_POST['days']);
    $role = strtolower(validate($_POST['role']));
    $controller = new userUpdateProfileController($conn);
    $controller->handleUserUpdateProfileRequest($id, $username, $name, $days, $role);
}

class userUpdateProfileController{
    private $entity;
    private $entity2;

    public function __construct($conn){
        $this->entity = new Account($conn);
        $this->entity2 = new Workslot($conn);
    }

    public function handleUserUpdateProfileRequest($id, $username, $name, $days, $role){
        $userData2 = $this->entity->getUserById($id);
        $oldName = $userData2['name'];
        
        $userData = $this->entity->updateUser($id, $username, $name, $days, $role);
        if ($userData === true){
            // After successfully updating the profile
            $_SESSION['success_message'] = 'Profile updated successfully';
            // Extract updated information from database
            $userData3 = $this->entity->getUserById($id);
            $newName = $userData3['name'];
            $workslots = $this->entity2->retriveWorkslotRoleIdByName($oldName);
            foreach ($workslots as $row){
                $workslotid = $row['id'];
                // Update from old name to new name
                $this->entity2->updateWorkslotCashierRoleById($workslotid, $oldName, $newName);
                $this->entity2->updateWorkslotCleanerRoleById($workslotid, $oldName, $newName);
                $this->entity2->updateWorkslotWaiterRoleById($workslotid, $oldName, $newName);
            }
            header("Location: ../Boundary/updateProfileBoundary.php?userData=" . urlencode(json_encode($userData3)));
            return true;
        } else {
            $_SESSION['error_message'] = 'Error updating profile';
            header("Location: ../Boundary/updateProfileBoundary.php?userData=" . urlencode(json_encode($userData2)));
            return false;
        }
    }
}