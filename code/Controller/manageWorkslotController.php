<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid'])){
    $controller = new ownerManageWorkslotController($conn);
    $controller->handleOwnerManageWorkslotRequest();
}

class ownerManageWorkslotController{
    private $entity;
    private $entity2;

    public function __construct($conn){
        $this->entity = new Account($conn);
        $this->entity2 = new Workslot($conn);
    }

    public function handleOwnerManageWorkslotRequest(){
        $userid = $_GET['userid'];
        $userData = $this->entity->getUserById($userid);
        $username = $userData['username'];
        $userRole = $userData['role'];

        $workslotData = $this->entity2->getAllWorkslot();
        $_SESSION['name'] = $userData['name'];
        $_SESSION['id'] = $userData['id'];
        header("Location: ../Boundary/manageWorkslotBoundary.php?userData=" . urlencode(json_encode($workslotData)));
        exit();
    }
}
?>