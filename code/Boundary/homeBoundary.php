<?php
session_start();
$homeBoundary = new homeBoundary();
$homeBoundary->displayHome();

class homeBoundary {
    public function displayHome(){
        if ((isset($_GET['userData']))){
            $userData = json_decode($_GET['userData'], true);
            echo '
            <!DOCTYPE html>
            <html>
                <head>
                    <title>HOME</title>
                    <link rel="stylesheet" type="text/css" href="../CSS/homeboundaryphp.css">
                </head>
                <body>';
            if ($userData){
                echo '
                <div id="homeform">
                    <a id="welcome">Hello, ' . $userData['name'] . '</a>
                    <p class="homeboundaryp">1. <a href="../Controller/viewProfileController.php?userid=' . $userData['id'] . '" class="homeboundarya">View profile</a></p>';
                    if ($userData['role'] !== "system admin"){
                        echo '<p class="homeboundaryp">2. <a href="../Controller/viewWorkslotController.php?userid=' . $userData['id'] . '" class="homeboundarya">View workslot</a></p>
                            <p class="homeboundaryp">3. <a href="../Controller/logoutController.php" class="homeboundarya">Logout</a></p>';
                    }
                    if ($userData['role'] === "system admin") {
                        echo '<p class="homeboundaryp">2. <a href="../Controller/sysViewProfileController.php?userid=' . $userData['id'] . '" class="homeboundarya">View user</a></p>
                            <p class="homeboundaryp">3. <a href="../Boundary/registerBoundary.php?userid=' . $userData['id'] . '" class="homeboundarya">Register user</a></p>
                            <p class="homeboundaryp">4. <a href="../Controller/logoutController.php" class="homeboundarya">Logout</a></p>';
                    }
                echo '</div>';
            }
            echo '
                </body>
            </html>';
        }
    }
}