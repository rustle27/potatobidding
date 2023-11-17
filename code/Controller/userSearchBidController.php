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
    $userid = $_GET['userid'];
    $search = validate($_POST['search']);
    $controller = new userSearchBidController($conn);
    $username = $controller->getUsername($userid);
    $controller->handleUserSearchBidRequest($search, $username);
}

class userSearchBidController{
    private $entity;
    private $entity2;

    public function __construct($conn){
        $this->entity = new Bids($conn);
        $this->entity2 = new Account($conn);
    }

    public function getUsername($userid){
        $userData = $this->entity2->getUserById($userid);
        $username = $userData['username'];
        return $username;
    }

    public function handleUserSearchBidRequest($search, $username){
        $result = $this->entity->searchBid($search, $username);
        if ($result !== false){
            $_SESSION['id'] = $_GET['userid'];
            $_SESSION['success_message'] = $username;
            header("Location: ../Boundary/userViewBidBoundary.php?userData=" . urlencode(json_encode($result)));
            exit();
        } else {
            $_SESSION['error_message'] = "No Bid Found";
            header("Location: ../Controller/userViewBidController.php?userid=" . $_SESSION['id']);
            exit();
        }
    }
}
?>