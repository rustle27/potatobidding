<?php
session_start();
$profileBoundary = new sysUpdateProfileBoundary();
$profileBoundary->displaySysUpdateProfileForm();

class sysUpdateProfileBoundary{
    public function displaySysUpdateProfileForm(){
        if ($_SESSION['role'] === "system admin"){
            if ((isset($_GET['userData']))){
                $userData = json_decode($_GET['userData'], true);
                echo '
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>PROFILE</title>
                        <link rel="stylesheet" type="text/css" href="../CSS/sysupdateprofileboundaryphp.css">
                    </head>
                    <body>
                        <div id="sysupdateprofilewordbox"><a id="sysupdateprofileword">Updating profile for ' . $userData['name'] . '</a></div>
                        <form action="../Controller/sysUpdateProfileController.php?userid=' . $_SESSION['id'] . '" method="post">';
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
                            <input type="hidden" name="id" value="' . $userData['id'] . '"><br>
                            <input type="hidden" name="uname" value="' . $userData['username'] . '"><br>

                            <label>Name: </label>
                            <input type="text" name="name" value="' . $userData['name'] . '" required><br>
                            
                            <label>Number of working slots: </label>
                            <input type="number" name="days" value="' . $userData['days'] . '" required><br>
                            
                            <label>Role: </label>
                            <input type="text" name="role" value="' . $userData['role'] . '" required><br>
                            
                            <a href="../Controller/sysViewProfileController.php?userid=' . $_SESSION['id'] . '" class="ca">BACK</a>
                            <button type="submit">Submit</button>
                        </form>
                        <br>
                        <a href="../Controller/sysChangePasswordController.php?userid=' . $userData['id'] . '&sessionid=' . $_SESSION['id'] . '" id="sysupdateprofilechangepasswordbtn">Change Password</a>
                    </body>
                    </html>';
            }
        } else {
            header("Location: ../403.php");
        }
    }
}
?>