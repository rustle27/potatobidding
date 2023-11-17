<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid'])) {
    $userId = $_GET['userid'];
    $controller = new deleteUserController($conn);
    $controller->handleDeleteUserProfile($userId);
}

class deleteUserController{
    private $entity;
    private $entity2;
    private $entity3;

    public function __construct($conn){
        $this->entity = new Account($conn);
        $this->entity2 = new Bids($conn);
        $this->entity3 = new Workslot($conn);
    }

    public function handleDeleteUserProfile($userId){
        // Retrieve the deleting account username and name
        $userData = $this->entity->getUserById($userId);
        $uname = $userData['username'];
        $name = $userData['name'];
        // Delete user by id
        $result = $this->entity->deleteUser($userId);
        // Delete bid by username
        $result2 = $this->entity2->deleteBidByUname($uname);
        // Array of all the workslot that have deleting account name
        $userArray = $this->entity3->retriveWorkslotRoleIdByName($name);
        foreach ($userArray as $row){
            $workslotid = $row['id'];
            $this->entity3->deleteWorkslotCashierRoleById($workslotid, $name);
            $this->entity3->deleteWorkslotCleanerRoleById($workslotid, $name);
            $this->entity3->deleteWorkslotWaiterRoleById($workslotid, $name);
            $this->entity3->updateWorkslotStatus($workslotid);
        }

        $users = $this->entity->getUsers();
        if ($result === true){
            $_SESSION['success_message'] = 'Successful deleting user profile';
            header("Location: ../Boundary/sysViewProfileBoundary.php?userData=" . urlencode(json_encode($users)));
            return true;
        } else {
            $_SESSION['error_message'] = 'Error deleting user profile';
            header("Location: ../Boundary/sysViewProfileBoundary.php?userData=" . urlencode(json_encode($users)));
            return false;
        }
    }

}
?>