<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid'])){
    $controller = new registerController($conn);
    $controller->handleRegisterRequest();
}

class registerController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function handleRegisterRequest(){
        # Getting value of username, password and name from textbox
        if (isset($_POST['uname']) && isset($_POST['pword']) && isset($_POST['name']) && isset($_POST['role'])){
            
            function validate($data){
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
        
            $uname = validate($_POST['uname']);
            $pass = validate($_POST['pword']);
            $repass = validate($_POST['repword']);
            $name = validate($_POST['name']);
            $role = strtolower(validate($_POST['role']));
        }

        $userid = $_GET['userid'];

        # Verifying username and password
        if (empty($uname)){
            $_SESSION['error_message'] = 'Username is required';
            header("Location: ../Boundary/registerBoundary.php?userid=" . $userid);
            return false;
        } else if (empty($pass)){
            $_SESSION['error_message'] = 'Password is required';
            header("Location: ../Boundary/registerBoundary.php?userid=" . $userid);
            return false;
        } else if (empty($repass)){
            $_SESSION['error_message'] = 'Re-password is required';
            header("Location: ../Boundary/registerBoundary.php?userid=" . $userid);
            return false;
        } else if (empty($name)){
            $_SESSION['error_message'] = 'Name is required';
            header("Location: ../Boundary/registerBoundary.php?userid=" . $userid);
            return false;
        } else if (empty($role)){
            $_SESSION['error_message'] = 'Role is required';
            header("Location: ../Boundary/registerBoundary.php?userid=" . $userid);
            return false;
        } else if ($pass !== $repass){
            $_SESSION['error_message'] = 'Password does not match';
            header("Location: ../Boundary/registerBoundary.php?userid=" . $userid);
            return false;
        } else {
            $userData = $this->entity->registerUser($uname, $pass, $name, $role); 
            if ($userData !== false) {
                $_SESSION['success_message'] = 'Profile registered successfully';
                header("Location: ../Boundary/registerBoundary.php?userid=" . $userid);
                return true;
            } else {
                $_SESSION['error_message'] = 'Error registering profile';
                header("Location: ../Boundary/registerBoundary.php?userid=" . $userid);
                return false;
            }
        }
    }
}
?>