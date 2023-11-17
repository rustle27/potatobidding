<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid'])){
    $id = $_GET['userid'];
    $controller = new userViewBidController($conn);
    $username = $controller->getUsername($id);
    $controller->handleViewBidRequest($username);
}

class userViewBidController{
    private $entity;
    private $entity2;

    public function __construct($conn){
        $this->entity = new Account($conn);
        $this->entity2 = new Bids($conn);
    }

    public function getUsername($id){
        $userData = $this->entity->getUserById($id);
        $username = $userData['username'];
        return $username;
    }
    
    public function getName(){
        $id = $_GET['userid'];
        $userData = $this->entity->getUserById($id);
        $name = $userData['name'];
        return $name;
    }

    public function handleViewBidRequest($username){
        $userData = $this->entity2->getBidsByUName($username);
        
        if (empty($userData)){
            header("Location: ../Boundary/userViewBidBoundary.php?userData=" . urlencode(json_encode($userData)));
        } else {
            $_SESSION['name'] = $this->getName();
            $_SESSION['id'] = $_GET['userid'];
            header("Location: ../Boundary/userViewBidBoundary.php?userData=" . urlencode(json_encode($userData)));
            exit();
        }
    }
}
?>