<?php 
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid']) && isset($_GET['sessionid'])) {
    $userid = $_GET['userid'];
    $controller = new showSysChangePasswordBoundary($conn);
    $controller->handleSysChangePasswordBoundary($userid);
} else if (isset($_GET['sessionid'])) {
    $controller = new sysChangePasswordController($conn);
    $controller->handleSysChangePasswordRequest();
}

class showSysChangePasswordBoundary{
    private $entity;

    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function handleSysChangePasswordBoundary($userid){
        $userData = $this->entity->getUserById($userid);
        $_SESSION['id'] = $_GET['sessionid'];
        header("Location: ../Boundary/sysChangePasswordBoundary.php?userData=" . urlencode(json_encode($userData)));
    }
}

class sysChangePasswordController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function handleSysChangePasswordRequest(){
        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $id = validate($_POST['id']);
        $uname = validate($_POST['uname']);
        $name = validate($_POST['name']);
        $npass = validate($_POST['npass']);
        $cnpass = validate($_POST['cnpass']);
        $days = validate($_POST['days']);
        $role = validate($_POST['role']);

        $userData2 = $this->entity->getUserById($id);

        if (empty($npass)){
            $_SESSION['error_message'] = 'Password field empty';
            header("Location: ../Boundary/sysChangePasswordBoundary.php?userData=" . urlencode(json_encode($userData2)));
            exit();
        } else if (empty($cnpass)){
            $_SESSION['error_message'] = 'Confirmation password field empty';
            hheader("Location: ../Boundary/sysChangePasswordBoundary.php?userData=" . urlencode(json_encode($userData2)));
            exit();
        } else if ($npass !== $cnpass){
            $_SESSION['error_message'] = 'Password does not match';
            header("Location: ../Boundary/sysChangePasswordBoundary.php?userData=" . urlencode(json_encode($userData2)));
            exit();
        } else {
            $userData = $this->entity->changePassword($id, $npass);
            if ($userData === true){
                // After successfully updating the profile
                $_SESSION['id'] = $_GET['sessionid'];
                $_SESSION['success_message'] = 'Password changed successfully';
                header("Location: ../Boundary/sysUpdateProfileBoundary.php?userData=" . urlencode(json_encode($userData2)));
                return true;
            } else {
                $_SESSION['id'] = $_GET['sessionid'];
                $_SESSION['error_message'] = 'Error changing password';
                header("Location: ../Boundary/sysUpdateProfileBoundary.php?userData=" . urlencode(json_encode($userData2)));
                return false;
            }
        }
    }
}