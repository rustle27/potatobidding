<?php
session_start();

$logoutProcess = new UserLogout();
$logoutProcess->logout();

class UserLogout {
    public function logout() {
        if ($this->isUserLoggedIn()) {
            $this->clearUserData();
            $this->invalidateSession();
            $this->redirectToLogin();
        } else {
            $this->redirectToLogin();
        }
    }

    private function isUserLoggedIn() {
        return isset($_SESSION['id']);
    }

    private function clearUserData() {
        unset($_SESSION['id']);
        unset($_SESSION['name']);
        unset($_SESSION['username']);
        unset($_SESSION['role']);
    }

    private function invalidateSession() {
        session_unset();
        session_destroy();
    }

    private function redirectToLogin() {
        header("Location: ../index.php");
        exit();
    }
}

