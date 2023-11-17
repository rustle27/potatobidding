<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['workslotid'])) {
    $workslotid = $_GET['workslotid'];
    $controller = new ownerDeleteWorkslotController($conn);
    $controller->handleDeleteWorkslotRequest($workslotid);
}

class ownerDeleteWorkslotController{
    private $entity;
    private $entity2;

    public function __construct($conn){
        $this->entity = new Account($conn);
        $this->entity2 = new Workslot($conn);
    }
    
    public function handleDeleteWorkslotRequest($workslotid){
        $deleteWorkslot = $this->entity2->deleteWorkslotById($workslotid);
        $Allworkslot = $this->entity2->getAllWorkslot();

        if ($deleteWorkslot === true) {
            $_SESSION['success_message'] = 'Successful deleting workslot';
            header("Location: ../Boundary/manageWorkslotBoundary.php?userData=" . urlencode(json_encode($Allworkslot)));
            return true;
        } else {
            $_SESSION['error_message'] = 'Error deleting workslot';
            header("Location: ../Boundary/manageWorkslotBoundary.php?userData=" . urlencode(json_encode($Allworkslot)));
            return false;
        }
    }
}
?>