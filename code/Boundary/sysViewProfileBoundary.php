<?php
session_start();
$profileBoundary = new sysViewProfileBoundary();
$profileBoundary->displayProfileBoundary();

class sysViewProfileBoundary{
    public function displayProfileBoundary(){
        if($_SESSION['role'] === "system admin"){
            if ((isset($_GET['userData']))){
                $userData = json_decode($_GET['userData'], true);
                echo'
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>VIEW USER</title>
                        <link rel="stylesheet" type="text/css" href="../CSS/sysviewprofileboundaryphp.css">
                    </head>
                    <body>
                        <a id="sysviewprofilewelcome">Hi ' . $_SESSION['name'] . ', These are all users in system: </a>
                        <div id="sysviewprofilebox">
                            <form action="../Controller/sysSearchUserController.php?action=handleSearchRequest" id="syssearchprofileform" method="post">
                                <h2>Search for user: (either name or username)</h2>
                                <input type="text" name="search" placeholder="Search for someone...">
                                <button type="submit">Search</button>
                                <br><br>
                            </form>';
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
                            <br><br>
                            <table id="profiletable">
                                <thead>
                                    <tr class="headerrow">
                                        <th class="headerrow" scope="col">#ID</th>
                                        <th class="headerrow" scope="col">Username</th>
                                        <th class="headerrow" scope="col">Name</th>
                                        <th class="headerrow" scope="col">Work Slots</th>
                                        <th class="headerrow" scope="col">Role</th>
                                        <th class="headerrow" scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                if (!empty($userData)){
                                    foreach ($userData as $row){
                                        $id = $row['id'];
                                        $uname = $row['username'];
                                        $name = $row['name'];
                                        $days = $row['days'];
                                        $role = $row['role'];
                                        if ($role !== "system admin"){
                                            echo '<tr class="tablebody">
                                                <td>' . $id . '</td>
                                                <td>' . $uname . '</td>
                                                <td>' . $name . '</td>
                                                <td>' . $days . '</td>
                                                <td>' . $role . '</td>
                                                <td>
                                                    <a href="../Controller/deleteUserController.php?userid=' . $id . '" class="deletebtn">Delete</a>
                                                    <a href="../Controller/sysUpdateProfileController.php?userid=' . $id . '&sessionid=' . $_SESSION['id'] . '" class="deletebtn">Update</a>
                                                </td>
                                            </tr>';
                                        }
                                    }
                                }
                                echo '</tbody>
                            </table>
                            <br><br>
                        </div>
                        <a href="../Controller/homeController.php?userid=' . $_SESSION['id'] . '" id="sysviewprofilebackbtn">HOME</a>
                </body>
                </html>';
            }
        } else {
            header("Location: ../403.php");
        }
    }
}