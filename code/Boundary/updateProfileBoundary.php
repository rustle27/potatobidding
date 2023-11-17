<?php
session_start();
$profileBoundary = new userUpdateProfileBoundary();
$profileBoundary->displayUpdateProfileForm();

class userUpdateProfileBoundary{
    public function displayUpdateProfileForm() {
        if ((isset($_GET['userData']))){
            $userData = json_decode($_GET['userData'], true);
            echo '
            <!DOCTYPE html>
            <html>
                <head>
                    <title>PROFILE</title>
                    <link rel="stylesheet" type="text/css" href="../CSS/updateprofileboundaryphp.css">
                </head>
                <body>
                    <div id="updateprofilebox"><a id="updateprofile">Update your profile: </a></div>
                    <form action="../Controller/updateProfileController.php?action=handleUpdateProfileRequest" method="post">';
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
                        <input type="hidden" name="username" value="' . $userData['username'] . '"><br>
                        <input type="hidden" name="role" value="' . $userData['role'] . '"><br>

                        <label>Name: </label>
                        <input type="text" name="name" value="' . $userData['name'] . '" required><br>
                        
                        <label>Number of working slots: </label>
                        <input type="number" name="days" value="' . $userData['days'] . '" required><br>
                        
                        <a href="../Controller/viewProfileController.php?userid=' . $userData['id'] . '" class="ca">BACK</a>
                        <button type="submit">Submit</button>
                    </form>
                    <br>
                    <a href="../Controller/changePasswordController.php?userid=' . $userData['id'] . '&sessionid=' . $userData['id'] . '" id="changepassword">Change Password</a>
                </body>
                </html>';
        }
    }
}
?>