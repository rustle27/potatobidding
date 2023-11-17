<?php 
session_start();
require '../Entity/entity.php';

if (isset($_GET['workslotid']) && isset($_GET['sessionid'])){
    $workslotid = $_GET['workslotid'];
    $controller = new showUpdateWorkslotBoundary($conn);
    $controller->handleShowUpdateWorkslotBoundaryRequest($workslotid);
} else if (isset($_GET['wsid']) && isset($_GET['sid'])){
    $workslotid = $_GET['wsid'];
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $selectedDay = validate($_POST['selectedDay']);
    $selectedMonth = validate($_POST['selectedMonth']);
    $selectedTime = validate($_POST['time']);

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
    $controller = new ownerUpdateWorkslotController($conn);
    $controller->handleOwnerUpdateWorkslotController($workslotid, $selectedDay, $selectedMonth, $selectedTime, $dayOfWeek);
}

class showUpdateWorkslotBoundary{
    private $entity;

    public function __construct($conn){
        $this->entity = new Workslot($conn);
    }

    public function handleShowUpdateWorkslotBoundaryRequest($workslotid){
        $_SESSION['id'] = $_GET['sessionid'];
        header("Location: ../Boundary/ownerUpdateWorkslotBoundary.php?workslotid=" . $workslotid);
        exit();
    }
}

class ownerUpdateWorkslotController{
    private $entity;

    public function __construct($conn){
        $this->entity = new Workslot($conn);
    }

    public function handleOwnerUpdateWorkslotController($workslotid, $selectedDay, $selectedMonth, $selectedTime, $dayOfWeek){
        $userid = $_GET['sid'];
        if ($selectedDay !== "" && $dayOfWeek !== "") {
            // Both $selectedDay and $dayOfWeek are set (not null)
            $workslotData = $this->entity->updateWorkslot($workslotid, $selectedDay, $selectedMonth, $selectedTime, $dayOfWeek);
            if ($workslotData === true) {
                $_SESSION['success_message'] = 'Workslot been sucessfully updated';
                $_SESSION['id'] = $userid;
                header("Location: ../Boundary/ownerUpdateWorkslotBoundary.php?workslotid=" . $workslotid);
                return true;
            } else {
                $_SESSION['error_message'] = 'Error updating workslot';
                $_SESSION['id'] = $userid;
                header("Location: ../Boundary/ownerUpdateWorkslotBoundary.php?workslotid=" . $workslotid);
                return false;
            }
        } else {
            // Handle the case when one or both of the variables are not set (null)
            $_SESSION['error_message'] = 'Please select the day before submitting';
            $_SESSION['id'] = $userid;
            header("Location: ../Boundary/ownerUpdateWorkslotBoundary.php?workslotid=" . $workslotid);
            return false;
        }
    }
}