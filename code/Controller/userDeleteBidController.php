<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['bidid'])) {
    $bidid = $_GET['bidid'];
    $controller = new userDeleteBidController($conn);
    $controller->handleUserDeleteBidRequest($bidid);
}

class userDeleteBidController{
    private $entity;
    private $entity2;

    public function __construct($conn){
        $this->entity = new Account($conn);
        $this->entity2 = new Bids($conn);
    }

    public function handleUserDeleteBidRequest($bidid){
        $bidData = $this->entity2->getBidsById($bidid);
        $uname = $bidData['username'];
        $userData2 = $this->entity->getUserByUname($uname);
        $userid = $userData2['id'];
        $result = $this->entity2->deleteBid($bidid);
        $userData = $this->entity2->getBidsById($bidid);

        

        if ($result !== false){
            $_SESSION['success_message'] = 'Successful deleting bid';
            header("Location: ../Controller/userViewBidController.php?userid=" . $userid);
            return true;
        } else {
            $_SESSION['error_message'] = 'Error deleting bid';
            header("Location: ../Controller/userViewBidController.php?userid=" . $userid);
            return false;
        }
    }

}
?>