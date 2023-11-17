<?php
session_start();
$viewBidBoundary = new userViewBidBoundary();
$viewBidBoundary->displayUserViewBidBoundary();

class userViewBidBoundary{
    public function displayUserViewBidBoundary(){
        if (isset($_SESSION['id'])){
            if ((isset($_GET['userData']))){
                $userData = json_decode($_GET['userData'], true);
                echo'
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>VIEW BIDS</title>
                        <link rel="stylesheet" type="text/css" href="../CSS/viewbidboundaryphp.css">
                    </head>
                    <body>
                        <div id="viewbidheaderbox"><a id="viewbidheader">Hi ' . $_SESSION['name'] . ', this is all your bids: </a></div>                      
                        <form id="usersearchbidform" action="../Controller/userSearchBidController.php?userid=' . $_SESSION['id'] . '" method="post">
                                <h2>Search for bid: </h2>
                                <input type="text" name="search" placeholder="Search for some bid..." required>
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
                                        <th class="headerrow" scope="col">Username</th>
                                        <th class="headerrow" scope="col">Day</th>
                                        <th class="headerrow" scope="col">Month</th>
                                        <th class="headerrow" scope="col">Time</th>
                                        <th class="headerrow" scope="col">Role</th>
                                        <th class="headerrow" scope="col">Status</th>
                                        <th class="headerrow" scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                    foreach ($userData as $row){
                                        $id = $row['id'];
                                        $uname = $row['username'];
                                        $day = $row['day'];
                                        $month = $row['month'];
                                        $time = $row['time'];
                                        $role = $row['role'];
                                        $status = $row['status'];
                                        if ($role !== "system admin"){
                                            echo '<tr class="tablebody">
                                                <th scope = "row">' . $id . '</th>
                                                <td>' . $uname . '</td>
                                                <td>' . $day . '</td>
                                                <td>' . $month . '</td>
                                                <td>' . $time . '</td>
                                                <td>' . $role . '</td>
                                                <td>' . $status . '</td>
                                                <td>';
                                                if ($status !== "success" && $status !== "rejected"){
                                                    echo '<a href="../Controller/userUpdateBidController.php?bidid=' . $id . '" class="updatebtn">Update</a>
                                                    <a href="../Controller/userDeleteBidController.php?bidid=' . $id . '" class="deletebtn">Delete</a>';
                                                }
                                                echo '
                                                </td>
                                            </tr>';
                                        }
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