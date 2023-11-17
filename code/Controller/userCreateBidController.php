<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid'])){
    $userid = $_GET['userid'];
    $controller = new createBidController($conn);
    $uname = $controller->getUsername($userid);
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $selectedMonth = validate($_POST['selectedMonth']);
    $selectedDay = validate($_POST['selectedDay']);
    $selectedTime = validate($_POST['time']);
    $selectedRole = strtolower(validate($_POST['role']));

    $currentYear = date("Y"); // Get the current year
    $date = "$currentYear-$selectedMonth-$selectedDay";
    $dayOfWeek = date("l", strtotime($date));

    if ($selectedMonth == 1) {
        $selectedMonth = 'january';
    } elseif ($selectedMonth == 2) {
        $selectedMonth = 'february';
    } elseif ($selectedMonth == 3) {
        $selectedMonth = 'march';
    } elseif ($selectedMonth == 4) {
        $selectedMonth = 'april';
    } elseif ($selectedMonth == 5) {
        $selectedMonth = 'may';
    } elseif ($selectedMonth == 6) {
        $selectedMonth = 'june';
    } elseif ($selectedMonth == 7) {
        $selectedMonth = 'july';
    } elseif ($selectedMonth == 8) {
        $selectedMonth = 'august';
    } elseif ($selectedMonth == 9) {
        $selectedMonth = 'september';
    } elseif ($selectedMonth == 10) {
        $selectedMonth = 'october';
    } elseif ($selectedMonth == 11) {
        $selectedMonth = 'november';
    } elseif ($selectedMonth == 12) {
        $selectedMonth = 'december';
    }

    $controller->handleCreateBidRequest($uname, $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek, $selectedRole);
}

class createBidController{
    private $entity;
    private $entity2;
    private $entity3;

    public function __construct($conn){
        $this->entity = new Bids($conn);
        $this->entity2 = new Account($conn);
        $this->entity3 = new Workslot($conn);
    }

    public function getUsername($userid){
        $userData = $this->entity2->getUserById($userid);
        return $userData['username'];
    }

    public function handleCreateBidRequest($uname, $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek, $selectedRole){
        $allWorkslot = $this->entity3->getAllWorkslot();
        if ($allWorkslot !== false) {
            foreach ($allWorkslot as $workslot) {
                $month = $workslot['month']; // Get the 'month' value for the current row
                $day = $workslot['day']; // Get the 'day' value for the current row
                if ($month == $selectedMonth && $day == $selectedDay) {
                    $userData = $this->entity->createBid($uname, $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek, $selectedRole);
                    if ($userData !== false) {
                        $_SESSION['success_message'] = 'Bid created successfully';
                        header("Location: ../Boundary/userCreateBidBoundary.php?userid=" . $_GET['userid']);
                        return true;
                    } else {
                        $_SESSION['error_message'] = 'Error creating bid';
                        header("Location: ../Boundary/userCreateBidBoundary.php?userid=" . $_GET['userid']);
                        return false;
                    }
                }
            }
            $_SESSION['error_message'] = 'No workslot created for your bid. Please contact your manager.';
            header("Location: ../Boundary/userCreateBidBoundary.php?userid=" . $_GET['userid']);
            return false;
        }
    }
}
?>