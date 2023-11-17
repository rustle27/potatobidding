<?php 
session_start();
require '../Entity/entity.php';

if (isset($_GET['bidid'])){
    $bidid = $_GET['bidid'];
    $controller = new managerRejectBidController($conn);
    $controller->RejectBidRequest($bidid);
}

class managerRejectBidController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Bids($conn);
        $this->entity2 = new Workslot($conn);
        $this->entity3 = new Account($conn);
    }

    public function RejectBidRequest($bidid){
        $userData = $this->entity->rejectBid($bidid);

        $Allworkslot = $this->entity->viewWorkslot();
        if ($userData !== false){
            $_SESSION['success_message'] = "Bid rejected. Please check the status.";
            header("Location: ../Boundary/managerViewBidBoundary.php?userData=" . urlencode(json_encode($Allworkslot)));
            exit();
        } else {
            $_SESSION['success_message'] = "Error rejecting bid.";
            header("Location: ../Boundary/managerViewBidBoundary.php?userData=" . urlencode(json_encode($Allworkslot)));
            exit();
        }
    }
}