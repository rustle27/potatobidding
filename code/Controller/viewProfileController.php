<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid'])){
    $userId = $_GET['userid'];
    $controller = new userViewProfileController($conn);
    $controller->handleViewProfileRequest($userId);
}

class userViewProfileController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function handleViewProfileRequest($id){
        $userData = $this->entity->getUserById($id);
        if ($userData !== false){
            header("Location: ../Boundary/viewProfileBoundary.php?userData=" . urlencode(json_encode($userData)));
            exit();
        } else {
            header("Location: ../Boundary/viewProfileBoundary.php?userData=" . urlencode(json_encode($userData)));
        }
    }
}