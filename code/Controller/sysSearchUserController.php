<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['action']) && $_GET['action'] == 'handleSearchRequest') {
    $controller = new searchUserController($conn);

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $search = validate($_POST['search']);

    $controller->handleSearchRequest($search);
}

class searchUserController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function handleSearchRequest($search){
        if (isset($_POST['search'])){
            
            $userData = $this->entity->searchUsers($search);
            if (empty($search)){
                $_SESSION['error_message'] = "Search field is empty";
                header("Location: ../Boundary/sysViewProfileBoundary.php?userData=" . urlencode(json_encode($userData)));
            } else {
                if ($userData !== false){
                    header("Location: ../Boundary/sysViewProfileBoundary.php?userData=" . urlencode(json_encode($userData)));
                    exit();
                } else {
                    $_SESSION['error_message'] = "No records found";
                    header("Location: ../Boundary/sysViewProfileBoundary.php?userData=" . urlencode(json_encode($userData)));
                    exit();
                }
            }
        }
    }
}
?>