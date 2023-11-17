<?php
session_start();
$registerBoundary = new registerBoundary();
$registerBoundary->displayRegisterForm();

class registerBoundary{
    public function displayRegisterForm(){
        $userid = $_GET['userid'];
        echo '
        <!DOCTYPE html>
        <html>
            <head>
                <title>Register</title>
                <link rel="stylesheet" type="text/css" href="../CSS/registerboundaryphp.css">
            </head>
            <body>
                <form action="../Controller/registerController.php?userid=' . $userid . '" method="post">
                    <h2>Register for a new staff</h2>';
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
                    <label>User Name: </label>
                    <input type="text" name="uname" placeholder="User Name"><br>
                    <label>Password: </label>
                    <input type="password" name="pword" placeholder="Password"><br>
                    <label>Re-Password: </label>
                    <input type="password" name="repword" placeholder="Re-Password"><br>
                    <label>Name:</label>
                    <input type="text" name="name" placeholder="Name"><br>
                    <label>Role:</label>
                    <input type="text" name="role" placeholder="Role"><br>
                    <a href="../Controller/homeController.php?userid=' . $userid . '" class="ca">HOME</a>
                    <button type="submit">Register</button>
                </form>
            </body>
        </html>
        ';
    }
}
?>