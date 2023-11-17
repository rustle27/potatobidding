<?php 
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid'])){
    $controller = new showAllocationBoundary($conn);
    $controller->handleBoundaryRequest();
} else if (isset($_GET['sessionid'])){
    $controller = new managerAllocateWorkslotController($conn);
    $controller->handleManagerAllocateWorkslotRequest();
}

class showAllocationBoundary{
    private $entity;
    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function handleBoundaryRequest(){
        $userData = $this->entity->getUsers();
        $_SESSION['id'] = $_GET['userid'];
        header("Location: ../Boundary/managerAllocateWorkslotBoundary.php?userData=" . urlencode(json_encode($userData)));
    }
}

class managerAllocateWorkslotController{
    private $entity;
    private $entity2;
    private $entity3;

    public function __construct($conn){
        $this->entity = new Bids($conn);
        $this->entity2 = new Workslot($conn);
        $this->entity3 = new Account($conn);
    }

    public function handleManagerAllocateWorkslotRequest(){
        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $selectedStaff = $_POST["staff"];
        $selectedMonth = $_POST["selectedMonth"];
        $selectedDay = $_POST["selectedDay"];
        $selectedTime = $_POST["selectedTime"];
        $selectedRole = $_POST["role"];
        //$status = $_POST["status"];
        $staff = $this->entity3->getUserById($selectedStaff);
        $staffUname = $staff['username'];
        $staffName = $staff['name'];
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

        $allWorkslot = $this->entity2->getAllWorkslot();
        if ($allWorkslot !== false) {
            $_SESSION['id'] = $_GET['sessionid'];
            foreach ($allWorkslot as $workslot) {
                $month = $workslot['month']; // Get the 'month' value for the current row
                $day = $workslot['day']; // Get the 'day' value for the current row
                if ($month == $selectedMonth && $day == $selectedDay) {
                    $createBid = $this->entity->managerCreateBid($staffUname, $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek, $selectedRole);
                    if ($createBid !== false) {
                        $_SESSION['success_message'] = 'Allocation done';
                        $this->entity2->updateWorkslotRole($selectedMonth, $selectedDay, $selectedTime, $staffName, $selectedRole);
                        $workslotData = $this->entity2->searchWorkslotByOtherInfo($selectedMonth, $selectedDay, $selectedTime);
                        $workslotid = $workslotData['id'];
                        $this->entity2->updateWorkslotStatus($workslotid);
                        header("Location: ../Controller/viewWorkSlotController.php?userid=" . $_SESSION['id']);
                        exit();
                    } else {
                        $_SESSION['error_message'] = 'Staff already allocated in the timeslot';
                        header("Location: ../Controller/managerAllocateWorkslotController.php?userid=" . $_SESSION['id']);
                        exit();
                    }
                }
            }
            $_SESSION['error_message'] = 'No workslot created for your bid. Please contact the owner.';
            header("Location: ../Controller/viewWorkSlotController.php?userid=" . $_SESSION['id']);
            exit();
        }
    }
}