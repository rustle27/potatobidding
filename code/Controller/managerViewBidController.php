<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid'])){
    $controller = new managerViewBidController($conn);
    $controller->handleViewBidRequest();
}

class managerViewBidController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Bids($conn);
    }

    public function handleViewBidRequest(){
        $userData = $this->entity->viewWorkslot();
        
        if (empty($userData)){
            header("Location: ../Boundary/managerViewBidBoundary.php?userData=" . urlencode(json_encode($userData)));
        } else {
            $_SESSION['id'] = $_GET['userid'];
            header("Location: ../Boundary/managerViewBidBoundary.php?userData=" . urlencode(json_encode($userData)));
            exit();
        }
    }
}
?>