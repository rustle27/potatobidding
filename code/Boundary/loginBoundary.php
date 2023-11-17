<?php
class loginBoundary
{
    public function displayLoginForm() {
        echo '
            <!DOCTYPE html>
            <html>
            <head>
                <title>SINS CAFE</title>
                <link rel="stylesheet" type="text/css" href="CSS/loginboundaryphp.css">
            </head>
            <body>
                <form class="form" action="Controller/loginController.php?action=handleLoginRequest" method="post">
                    <img src="Images/sins.png"  alt="Johnny Sins" class="johnnysins"><br><br>
                    <h2>LOGIN TO SINS CAFE  </h2>';
                    if (isset($_GET["error"])) {
                        echo '<p class="error">' . $_GET["error"] . '</p>';
                    }
                    if (isset($_GET["success"])) {
                        echo '<p class="success">' . $_GET["success"] . '</p>';
                    }
        echo '
                    <label>User Name: </label>
                    <input type="text" name="uname" placeholder="User Name"><br>
                    <label>Password: </label>
                    <input type="password" name="pword" placeholder="Password"><br>
                    <button type="submit">Login</button>
                </form>
            </body>
            </html>
        ';
    }
}
?>