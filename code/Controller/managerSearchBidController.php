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
    $controller = new managerSearchBidController($conn);
    $controller->handleManagerSearchBidRequest($search);
}

class managerSearchBidController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Bids($conn);
    }

    public function handleManagerSearchBidRequest($search){
        $result = $this->entity->managerSearchBid($search);
        if ($result !== false){
            $_SESSION['id'] = $_GET['userid'];
            $_SESSION['success_message'] = "Bid Found";
            header("Location: ../Boundary/managerViewBidBoundary.php?userData=" . urlencode(json_encode($result)));
            exit();
        } else {
            $_SESSION['error_message'] = "No Bid Found";
            header("Location: ../Controller/managerViewBidController.php?userid=" . $_SESSION['id']);
            exit();
        }
    }
}
?>