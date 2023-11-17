<?php
session_start();
require '../Entity/entity.php';

if (isset($_GET['userid'])){
    $userid = $_GET['userid'];
    $controller = new viewWorkslotController($conn);
    $controller->handleViewWorkslotRequest();
}

class viewWorkslotController{
    private $entity;
    private $entity2;
    private $startDate;
    private $endDate;

    public function __construct($conn){
        $this->entity = new Bids($conn);
        $this->entity2 = new Account($conn);

        // Initialize $startDate and $endDate in the constructor
        $currentDate = new DateTime();
        $dayOfWeek = $currentDate->format('w');
        $sunday = $currentDate->modify('-' . $dayOfWeek . ' day');
        $saturday = clone $sunday;
        $saturday->modify('+6 days');
        $this->startDate = $sunday->format('d/m/Y');
        $this->endDate = $saturday->format('d/m/Y');
    }

    public function handleViewWorkslotRequest(){
        $userid = $_GET['userid'];
        $userData = $this->entity->viewWorkslot();
        $userData2 = $this->entity2->getUserById($userid);

        if (empty($userData)){
            header("Location: ../Boundary/viewWorkslotBoundary.php?userData=" . urlencode(json_encode($userData)));
            exit();
        } else {
            $bidData = array();
            foreach ($userData as $row) {
                $month = $row['month'];
                $day = $row['day'];
                $monthNames = [
                    'january' => 1,
                    'february' => 2,
                    'march' => 3,
                    'april' => 4,
                    'may' => 5,
                    'june' => 6,
                    'july' => 7,
                    'august' => 8,
                    'september' => 9,
                    'october' => 10,
                    'november' => 11,
                    'december' => 12,
                ];
                $monthNumeric = $monthNames[$month];
                $currentYear = date('Y');
                $dateToCheck = DateTime::createFromFormat('d/m/Y', $day . '/' . $monthNumeric . '/' . $currentYear);
                $startDate = DateTime::createFromFormat('d/m/Y', $this->startDate);
                $endDate = DateTime::createFromFormat('d/m/Y', $this->endDate);

                if ($dateToCheck >= $startDate && $dateToCheck <= $endDate) {
                // Date is within the range, so add it to the bidData array
                    $bidData[] = $row;
                }

            }
            $_SESSION['name'] = $userData2['name'];
            $_SESSION['id'] = $userData2['id'];
            header("Location: ../Boundary/viewWorkslotBoundary.php?userData=" . urlencode(json_encode($bidData)));
            exit();
        }
    }
}
?>