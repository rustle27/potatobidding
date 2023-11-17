<?php
session_start();
$profileBoundary = new sysChangePasswordBoundary();
$profileBoundary->sysChangePasswordForm();

class sysChangePasswordBoundary{
    public function sysChangePasswordForm() {
        if ((isset($_GET['userData']))){
            $userData = json_decode($_GET['userData'], true);
            echo '
            <!DOCTYPE html>
            <html>
                <head>
                    <title>PROFILE</title>
                    <link rel="stylesheet" type="text/css" href="../CSS/syschangepasswordboundaryphp.css">
                </head>
                <body>
                    <div id="changepasswordwordbox"><a id="changepasswordword">Change Password for ' . $userData['name'] . ': </a></div>
                    <form action="../Controller/sysChangePasswordController.php?sessionid=' . $_SESSION['id'] .'" method="post">';
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
                        <label>Username: </label>
                        <input type="text" name="uname" value="' . $userData['username'] . '" readonly><br>

                        <label>Name: </label>
                        <input type="text" name="name" value="' . $userData['name'] . '" readonly><br>

                        <label>New Password</label>
                        <input type="password" name="npass" placeholder="New Password"><br>

                        <label>Confirm New Password</label>
                        <input type="password" name="cnpass" placeholder="Confirm New Password"><br>

                        <input type="hidden" name="id" value="' . $userData['id'] . '"><br>
                        <input type="hidden" name="days" value="' . $userData['days'] . '"><br>
                        <input type="hidden" name="role" value="' . $userData['role'] . '"><br>
                        
                        <a href="../Controller/sysUpdateProfileController.php?userData=' . urlencode(json_encode($userData)) . '&sessionid=' . $_SESSION['id'] . '" class="ca">BACK</a>
                        <button type="submit">Submit</button>
                    </form>
                    <br>
                </body>
                </html>';
        }
    }
}
?>