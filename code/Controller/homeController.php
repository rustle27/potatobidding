<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid'])) {
    $userId = $_GET['userid'];
    $controller = new homeController($conn);
    $controller->handleHomePage($userId);
}

class homeController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function handleHomePage($userId){
        $result = $this->entity->getUserById($userId);
        if ($result !== false){
            header("Location: ../Boundary/homeBoundary.php?userData=" . urlencode(json_encode($result)));
            exit();
        } else {
            header("Location: ../Boundary/homeBoundary.php?userData=" . urlencode(json_encode($result)));
            exit();
        }
    }

}
?>