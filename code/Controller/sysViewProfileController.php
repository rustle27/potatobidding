<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid'])){
    $controller = new sysViewProfileController($conn);
    $controller->handleSysViewProfileRequest();
}

class sysViewProfileController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function handleSysViewProfileRequest(){
        $userid = $_GET['userid'];
        $userData = $this->entity->getUsers();
        $userData2 = $this->entity->getUserById($userid);
        if (empty($userData)){
            header("Location: ../Boundary/sysViewProfileBoundary.php?error=Something is wrong");
        } else {
            $_SESSION['id'] = $userData2['id'];
            $_SESSION['name'] = $userData2['name'];
            $_SESSION['role'] = $userData2['role'];
            header("Location: ../Boundary/sysViewProfileBoundary.php?userData=" . urlencode(json_encode($userData)));
            exit();
        }
    }
}
?>