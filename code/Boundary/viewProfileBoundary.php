<?php
session_start();
$profileBoundary = new userViewProfileBoundary();
$profileBoundary->displayUserViewProfileForm();

class userViewProfileBoundary{
    public function displayUserViewProfileForm() {
        if ((isset($_GET['userData']))){
            $userData = json_decode($_GET['userData'], true);
            echo '
            <!DOCTYPE html>
            <html>
                <head>
                    <title>PROFILE</title>
                    <link rel="stylesheet" type="text/css" href="../CSS/viewProfileBoundaryphp.css">
                </head>
                <body>';
                    if ($userData) {
                        echo 
                        '<a id="yourprofile">Information of your profile: </a>
                        <div id="viewprofilebox">';
                            if (isset($_GET["error"])) {
                                echo '<p class="error">' . $_GET["error"] . '</p>';
                            }
                            if (isset($_GET["success"])) {
                                echo '<p class="success">' . $_GET["success"] . '</p>';
                            }
                            echo '
                                <label>ID: </label>
                                <input type="number" name="id" value="' . $userData['id'] . '" readonly><br>

                                <label>User Name: </label>
                                <input type="text" name="uname" value="' . $userData['username'] . '" readonly><br>
                                
                                <label>Name: </label>
                                <input type="text" name="name" value="' . $userData['name'] . '" readonly><br>
                                
                                <label>Numer of working slots: </label>
                                <input type="number" name="days" value="' . $userData['days'] . '" readonly><br>
                                
                                <label>Role: </label>
                                <input type="text" name="role" value="' . $userData['role'] . '" readonly><br>
                                
                                <a href="../Controller/homeController.php?userid=' . $userData['id'] . '" class="ca">HOME</a>
                                <a href="../Boundary/updateProfileBoundary.php?userData=' . urlencode(json_encode($userData)) . '" class="updateprofilebtn">Update Profile</a>
                        </div>';
                    }
                echo '</body>
                </html>';
        }
    }
}
?>