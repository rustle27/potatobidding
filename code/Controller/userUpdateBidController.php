<?php 
session_start();
require '../Entity/entity.php';

if (isset($_GET['bidid'])){
    $bidid = $_GET['bidid'];
    $controller = new viewUserBidsController($conn);
    $controller->showUserBidsRequest($bidid);
} else if (isset($_GET['action']) && $_GET['action'] == 'handleUpdateBidRequest'){
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $id = validate($_POST['id']);
    $uname = validate($_POST['uname']);
    $selectedDay = validate($_POST['selectedDay']);
    $selectedMonth = validate($_POST['selectedMonth']);
    $selectedTime = validate($_POST['time']);
    $role = validate($_POST['role']);

    $currentYear = date("Y"); // Get the current year
    $date = "$currentYear-$selectedMonth-$selectedDay";
    $dayOfWeek = date("l", strtotime($date));

    if ($selectedMonth == '1') {
        $selectedMonth = 'january';
    } elseif ($selectedMonth == '2') {
        $selectedMonth = 'february';
    } elseif ($selectedMonth == '3') {
        $selectedMonth = 'march';
    } elseif ($selectedMonth == '4') {
        $selectedMonth = 'april';
    } elseif ($selectedMonth == '5') {
        $selectedMonth = 'may';
    } elseif ($selectedMonth == '6') {
        $selectedMonth = 'june';
    } elseif ($selectedMonth == '7') {
        $selectedMonth = 'july';
    } elseif ($selectedMonth == '8') {
        $selectedMonth = 'august';
    } elseif ($selectedMonth == '9') {
        $selectedMonth = 'september';
    } elseif ($selectedMonth == '10') {
        $selectedMonth = 'october';
    } elseif ($selectedMonth == '11') {
        $selectedMonth = 'november';
    } elseif ($selectedMonth == '12') {
        $selectedMonth = 'december';
    }

    $controller = new userUpdateBidController($conn);
    $controller->handleUserUpdateBidRequest($id, $uname, $selectedDay, $selectedMonth, $selectedTime, $role);
}

class viewUserBidsController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Bids($conn);
    }

    public function showUserBidsRequest($bidid){
        $userData = $this->entity->getBidsById($bidid);
        if ($userData !== false){
            header("Location: ../Boundary/userUpdateBidBoundary.php?userData=" . urlencode(json_encode($userData)));
            exit();
        } else {
            header("Location: ../Boundary/userUpdateBidBoundary.php?userData=" . urlencode(json_encode($userData)));
        }
    }
}

class userUpdateBidController{
    private $entity;
    private $entity2;

    public function __construct($conn){
        $this->entity = new Account($conn);
        $this->entity2 = new Bids($conn);
        $this->entity3 = new Workslot($conn);
    }

    public function handleUserUpdateBidRequest($id, $uname, $selectedDay, $selectedMonth, $selectedTime, $role){        
        $allWorkslot = $this->entity3->getAllWorkslot();
        if ($allWorkslot !== false) {
            foreach ($allWorkslot as $workslot) {
                $month = $workslot['month']; // Get the 'month' value for the current row
                $day = $workslot['day']; // Get the 'day' value for the current row
                if ($month == $selectedMonth && $day == $selectedDay) {
                    $userData = $this->entity2->updateBid($id, $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek, $role);
                    if ($userData !== false) {
                        $_SESSION['success_message'] = 'Profile updated successfully';
                        $userData2 = $this->entity->getUserByUname($uname);
                        $userid = $userData2['id'];
                        header("Location: ../Controller/userViewBidController.php?userid=" . $userid);
                        return true;
                    } else {
                        $_SESSION['error_message'] = 'Error updating profile';
                        $userid = $userData2['id'];
                        header("Location: ../Controller/userViewBidController.php?userid=" . $userid);
                        return false;
                    }
                }
            }
            $userData2 = $this->entity->getUserByUname($uname);
            $userid = $userData2['id'];
            $_SESSION['error_message'] = 'No workslot created for your updated bid. Update failed. Please contact your manager.';
            header("Location: ../Controller/userViewBidController.php?userid=" . $userid);
            return false;
        }  
    }
}