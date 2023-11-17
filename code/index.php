<?php
// Include or require the LoginBoundary class file.
require 'Boundary/loginBoundary.php';
// Create an instance of the LoginBoundary class.
$loginBoundary = new loginBoundary();

// Call the method to display the login form.
$loginBoundary->displayLoginForm();
?>
