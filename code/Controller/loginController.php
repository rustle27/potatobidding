<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['action']) && $_GET['action'] == 'handleLoginRequest') {
    $controller = new loginController($conn);
    #Getting the value from the username and password textbox.
    if (isset($_POST['uname']) && isset($_POST['pword'])) {
    
        function validate($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    
        $uname = validate($_POST['uname']);
        $pass = validate($_POST['pword']);
    }
    $controller->handleLoginRequest($uname, $pass);
}

class loginController {
    private $entity;

    public function __construct($conn) {
        $this->entity = new Account($conn);
    }

    public function handleLoginRequest($uname, $pass) {
        #verifying username and password
        if (empty($uname)) {
            header("Location: ../index.php?error=Username is required");
            exit();
        } else if (empty($pass)) {
            header("Location: ../index.php?error=Password is required");
            exit();
        } else {
            $userData = $this->entity->authenticateUser($uname, $pass);
            $_SESSION['id'] = $userData['id'];
            $_SESSION['name'] = $userData['name'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['role'] = $userData['role'];
            if ($userData !== null) {
                header("Location: ../Controller/homeController.php?userid=" . $_SESSION['id']);
                exit();
            } else {
                header("Location: ../index.php?error=Incorrect username or password");
            }
        }
    }
}
?>