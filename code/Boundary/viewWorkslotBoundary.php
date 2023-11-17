<?php
session_start();
$workSlotBoundary = new viewWorkslotBoundary();
$workSlotBoundary->displayWorkslot();

class viewWorkslotBoundary{
    public function displayWorkslot(){
        if (isset($_SESSION['name'])){
            if ((isset($_GET['userData']))){
                $userData = json_decode($_GET['userData'], true);
                // Get the current date as a DateTime object
                $currentDate = new DateTime();
                // Find the current day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
                $dayOfWeek = $currentDate->format('w');
                // Calculate the date of the current Sunday
                $sunday = $currentDate->modify('-' . $dayOfWeek . ' day');
                // Calculate the date of the next Saturday (6 days from the current Sunday)
                $saturday = clone $sunday;
                $saturday->modify('+6 days');
                // Format the dates as desired (e.g., in d/m format)
                $startDate = $sunday->format('d/m/Y');
                $endDate = $saturday->format('d/m/Y');

                // Sort the $userData array by the "Username" column
                usort($userData, function ($a, $b) {
                    return strcmp($a['username'], $b['username']);
                });
                echo '
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>WORKSLOT</title>
                        <link rel="stylesheet" type="text/css" href="../CSS/viewworkslotboundaryphp.css">
                    </head>
                    <body>
                        <a id="workslotwelcome">Hello, ' . $_SESSION['name'] . '</a>
                        <div id="btnbox">
                            <a href="../Controller/homeController.php?userid=' . $_SESSION['id'] . '" id="workslotbackbtn">BACK</a>';
                            if ($_SESSION['role'] === "owner") {
                                echo '<a href="../Controller/manageWorkslotController.php?userid=' . $_SESSION['id'] . '" id="viewbidbtn">Manage Workslot</a>';
                                echo '<a href="../Boundary/ownerCreateWorkslotBoundary.php?userid=' . $_SESSION['id'] . '" id="createworkslotbtn">Create Workslot</a>';
                            }
                            if ($_SESSION['role'] === "manager") {
                                echo '<a href="../Controller/managerViewBidController.php?userid=' . $_SESSION['id'] . '" id="viewbidbtn">View Created Bids</a>';
                                echo '<a href="../Controller/managerAllocateWorkslotController.php?userid=' . $_SESSION['id'] . '" id="allocateworkslotbtn">Allocate Workslot</a>';
                            }
                            if ($_SESSION['role'] === "staff") {
                                echo '<a href="../Boundary/userCreateBidBoundary.php?userid=' . $_SESSION['id'] . '" id="createbidbtn">Create Bid</a>';
                                echo '<a href="../Controller/userViewBidController.php?userid=' . $_SESSION['id'] . '" id="viewbidbtn">View My Bids</a>';
                            }
                        echo' </div>
                        <div id="scheduledatewordbox"><a id="scheduledateword">Schedule for ' . $startDate . ' -> ' . $endDate . '</a></div>';
                        if (isset($_SESSION['error_message'])) {
                            $error_message = $_SESSION['error_message'];
                            echo '<p class="error">' . $error_message . '</p>';
                            unset($_SESSION['error_message']); // Clear the session variable
                        }
                        if (isset($_SESSION['success_message'])) {
                            $success_message = $_SESSION['success_message'];
                            echo '<p class="success">' . $success_message . '</p>';
                            unset($_SESSION['success_message']); // Clear the session variable
                        }
                        echo' <div id="viewworkslotbox">';
                            // Define an array to store the workslot information for each day
                            $dayBoxes = [
                                'Sunday' => [],
                                'Monday' => [],
                                'Tuesday' => [],
                                'Wednesday' => [],
                                'Thursday' => [],
                                'Friday' => [],
                                'Saturday' => [],
                            ];

                            // Distribute the data into the respective day boxes
                            foreach ($userData as $row) {
                                $day = $row['dayofweek'];
                                // Append the data to the appropriate day box in the $dayBoxes array
                                $dayBoxes[$day][] = $row;
                            }

                            // Loop through each day of the week and display the day boxes
                            $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            foreach ($daysOfWeek as $day) {
                                echo '<div class="daybox">';
                                echo '<div class="day"><p>' . $day . '</p></div>';
                                echo '<span class="confirmspan">Schedule: </span>';
                                // Check if there is data for the current day
                                if (!empty($dayBoxes[$day])) {
                                    foreach ($dayBoxes[$day] as $workslot) {
                                        // Display the workslot information for the current day
                                        $uname = $workslot['username'];
                                        $time = $workslot['time'];
                                        $role = $workslot['role'];
                                        $status = $workslot['status'];
                                        if ($status !== "rejected"){
                                            echo '<div class="schedulebox" name="' . $day . '">';
                                            echo '<table>
                                                    <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Time</th>
                                                        <th>Role</th>
                                                        <th>Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="tablebody">
                                                            <td>' . $uname . '</td>
                                                            <td>' . $time . '</td>
                                                            <td>' . $role . '</td>
                                                            <td>' . $status . '</td>
                                                        </tr>
                                                    </tbody>
                                                </table>';
                                            echo '</div>';
                                        }
                                    }
                                }
                            echo '</div>';
                        }
                        echo '</div>
                        </body>
                    </html>';
            }
        }
    }
}
?>
