<?php 
session_start();
require '../Entity/entity.php';

if (isset($_GET['bidid'])){
    $bidid = $_GET['bidid'];
    $controller = new managerAcceptBidController($conn);
    $controller->AcceptBidRequest($bidid);
}

class managerAcceptBidController{
    private $entity;
    private $entity2;
    private $entity3;

    public function __construct($conn){
        $this->entity = new Bids($conn);
        $this->entity2 = new Workslot($conn);
        $this->entity3 = new Account($conn);
    }

    public function AcceptBidRequest($bidid){
        $userData = $this->entity->acceptBid($bidid);
        $bid = $this->entity->getBidsById($bidid);
        $uname = $bid['username'];
        $month = $bid['month'];
        $day = $bid['day'];
        $time = $bid['time'];
        $role = $bid['role'];
        $workslot = $this->entity2->findWorkslot($month, $day, $time);
        $workslotid = $workslot['id'];
        $user = $this->entity3->getUserByUname($uname);
        $name = $user['name'];

        // Update workslot with username when bid accepted
        $updateWorkslot = $this->entity2->updateWorkslotRole($month, $day, $time, $name, $role);
        // Update status of workslot whenever a bid is accepted.
        if ($updateWorkslot === true){
            $updateWorkslotStatus = $this->entity2->updateWorkslotStatus($workslotid);
        } else {
            $userData = $this->entity->rejectBid($bidid);
        }
        

        $Allworkslot = $this->entity->viewWorkslot();
        if ($userData !== false && $updateWorkslot !== false && $updateWorkslotStatus !== false){
            $_SESSION['success_message'] = "Bid accepted. Please check the status.";
            header("Location: ../Boundary/managerViewBidBoundary.php?userData=" . urlencode(json_encode($Allworkslot)));
            exit();
        } else {
            $_SESSION['error_message'] = "Bid not accepted.";
            header("Location: ../Boundary/managerViewBidBoundary.php?userData=" . urlencode(json_encode($Allworkslot)));
            exit();
        }
    }
}