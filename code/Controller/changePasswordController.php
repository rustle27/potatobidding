<?php 
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid']) && isset($_GET['sessionid'])) {
    $userId = $_GET['userid'];
    $controller = new getUserDetails($conn);
    $controller->getUserDetails($userId);
} else if (isset($_GET['action']) && $_GET['action'] == 'handleChangePasswordRequest'){
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $id = validate($_POST['id']);
    $npass = validate($_POST['npass']);
    $cnpass = validate($_POST['cnpass']);
    $controller = new changePasswordController($conn);
    $controller->handleChangePasswordRequest($id, $npass, $cnpass);
}

class getUserDetails{
    private $entity;

    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function getUserDetails($userId){
        $userData = $this->entity->getUserById($userId);
        if ($userData !== false){
            $_SESSION['id'] = $_GET['sessionid'];
            header("Location: ../Boundary/changePasswordBoundary.php?userData=" . urlencode(json_encode($userData)));
            exit();
        } else {
            header("Location: ../Boundary/changePasswordBoundary.php?userData=" . urlencode(json_encode($userData)));
        }
    }
}

class changePasswordController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function handleChangePasswordRequest($id, $npass, $cnpass){
        $userData2 = $this->entity->getUserById($id);

        if (empty($npass)){
            $_SESSION['error_message'] = 'Password field empty';
            header("Location: ../Boundary/changePasswordBoundary.php?userData=" . urlencode(json_encode($userData2)));
            exit();
        } else if (empty($cnpass)){
            $_SESSION['error_message'] = 'Confirmation password field empty';
            header("Location: ../Boundary/changePasswordBoundary.php?userData=" . urlencode(json_encode($userData2)));
            exit();
        } else if ($npass !== $cnpass){
            $_SESSION['error_message'] = 'Password does not match';
            header("Location: ../Boundary/changePasswordBoundary.php?userData=" . urlencode(json_encode($userData2)));
            exit();
        } else {
            $userData = $this->entity->changePassword($id, $npass);
            if ($userData !== false){
                // After successfully updating the profile
                $_SESSION['success_message'] = 'Password changed successfully';
                header("Location: ../Boundary/updateProfileBoundary.php?userData=" . urlencode(json_encode($userData2)));
                return true;
            } else {
                $_SESSION['error_message'] = 'Error changing password';
                header("Location: ../Boundary/updateProfileBoundary.php?userData=" . urlencode(json_encode($userData2)));
                return false;
            }
        }
    }
}