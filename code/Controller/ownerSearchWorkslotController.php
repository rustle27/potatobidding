<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid'])){
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $search = validate($_POST['search']);
    $controller = new ownerSearchWorkslotController($conn);
    $controller->handleSearchWorkslotRequest($search);
}

class ownerSearchWorkslotController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Workslot($conn);
    }

    public function handleSearchWorkslotRequest($search){
        if (empty($search)){
            $Allworkslot = $this->entity->getAllWorkslot();
            $_SESSION['error_message'] = "Search Field is empty";
            $_SESSION['id'] = $_GET['userid'];
            header("Location: ../Boundary/manageWorkslotBoundary.php?userData=" . urlencode(json_encode($Allworkslot)));
        } else {
            $workslots = $this->entity->searchWorkslot($search);
            if ($result !== false){
                $_SESSION['id'] = $_GET['userid'];
                header("Location: ../Boundary/manageWorkslotBoundary.php?userData=" . urlencode(json_encode($workslots)));
                exit();
            } else {
                $_SESSION['error_message'] = "No result found";
                $_SESSION['id'] = $_GET['userid'];
                header("Location: ../Boundary/manageWorkslotBoundary.php?userData=" . urlencode(json_encode($Allworkslot)));
                exit();
            }
        }
    }
}
?>