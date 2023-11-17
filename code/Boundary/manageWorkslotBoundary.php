<?php
session_start();
$manageWorkslotBoundary = new ownerManageWorkslotBoundary();
$manageWorkslotBoundary->displayOwnerManageWorkslotBoundary();

class ownerManageWorkslotBoundary{
    public function displayOwnerManageWorkslotBoundary(){
        if (isset($_SESSION['id'])){
            if ((isset($_GET['userData']))){
                $userData = json_decode($_GET['userData'], true);               
                echo'
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>Manage workslot</title>
                        <link rel="stylesheet" type="text/css" href="../CSS/manageworkslotboundaryphp.css">
                    </head>
                    <body>
                        <form id="usersearchbidform" action="../Controller/ownerSearchWorkslotController.php?userid=' . $_SESSION['id'] . '" method="post">
                                <h2>Search for workslot: </h2>
                                <input type="text" name="search" placeholder="Search for some workslot...">
                                <button type="submit">Search</button>
                        </form>
                        <div class="workslot">';
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
                            echo '
                            <table class="deletetable">
                                <thead>
                                    <tr class="headerrow">
                                        <th class="headerrow" scope="col">#ID</th>
                                        <th class="headerrow" scope="col">Day</th>
                                        <th class="headerrow" scope="col">Month</th>
                                        <th class="headerrow" scope="col">Time</th>
                                        <th class="headerrow" scope="col">Cashier</th>
                                        <th class="headerrow" scope="col">Cleaner</th>
                                        <th class="headerrow" scope="col">Waiter</th>
                                        <th class="headerrow" scope="col">Status</th>
                                        <th class="headerrow" scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                if (is_array($userData)) {
                                    foreach ($userData as $row) {
                                        $id = $row['id'];
                                        $month = $row['month'];
                                        $day = $row['day'];
                                        $time = $row['time'];
                                        $cashier = $row['cashier'];
                                        $cleaner = $row['cleaner'];
                                        $waiter = $row['waiter'];
                                        $status = $row['status'];
                                        echo '<tr class="tablebody">
                                            <td>' . $id . '</td>
                                            <td>' . $day . '</td>
                                            <td>' . $month . '</td>
                                            <td>' . $time . '</td>
                                            <td>' . $cashier . '</td>
                                            <td>' . $cleaner . '</td>
                                            <td>' . $waiter . '</td>
                                            <td>' . $status . '</td>
                                            <td>';
                                            if (empty($cashier) && empty($cleaner) && empty($waiter)) {
                                                echo '<a href="../Controller/ownerUpdateWorkslotController.php?workslotid=' . $id . '&sessionid=' . $_SESSION['id'] .'" class="updatebtn">Update</a>';
                                                echo '<a href="../Controller/ownerDeleteWorkslotController.php?workslotid=' . $id . '" class="deletebtn">Delete</a>';
                                            }
                                            echo '
                                            </td>
                                        </tr>';
                                    }
                                } else {
                                    //ignoring error
                                }
                                echo '</tbody>
                            </table>
                        </div>
                        <div id="userviewbidbackbtnbox"><a href="../Controller/viewWorkslotController.php?userid=' . $_SESSION['id'] . '" id="userviewbidbackbtn">BACK</a></div>
                </body>
                </html>';
            }
        } else {
            echo'<a>ID not found</a>';
        }
    }
}