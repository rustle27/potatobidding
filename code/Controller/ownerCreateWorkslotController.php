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
    $controller = new ownerCreateWorkslotController($conn);
    $controller->handleCreateWorkslotRequest($selectedDay, $selectedMonth, $selectedTime, $dayOfWeek);
} 

class ownerCreateWorkslotController{
    private $entity;
    private $entity2;

    public function __construct($conn){
        $this->entity = new Workslot($conn);
        $this->entity2 = new Account($conn);
    }

    public function handleCreateWorkslotRequest($selectedDay, $selectedMonth, $selectedTime, $dayOfWeek){
        $userid = $_POST['userid'];
    
        $userData = $this->entity2->getUserById($userid);
        if ($selectedDay !=="" && $dayOfWeek !== "") {
            // Both $selectedDay and $dayOfWeek are set (not null)
            $workslotData = $this->entity->createWorkslot($selectedMonth, $selectedDay, $selectedTime, $dayOfWeek);
        } else {
            // Handle the case when one or both of the variables are not set (null)
            $_SESSION['error_message'] = 'Please select the day before submitting';
            header("Location: ../Boundary/ownerCreateWorkslotBoundary.php?userid=" . urlencode(json_encode($_SESSION['id'])));
            return false;
        }
        if ($workslotData === true) {
            $_SESSION['success_message'] = 'Workslot been sucessfully created';
            header("Location: ../Boundary/ownerCreateWorkslotBoundary.php?userid=" . urlencode(json_encode($_SESSION['id'])));
            return true;
        } else {
            $_SESSION['error_message'] = 'Error creating workslot';
            header("Location: ../Boundary/ownerCreateWorkslotBoundary.php?userid=" . urlencode(json_encode($_SESSION['id'])));
            return false;
        }
    }
}
?>