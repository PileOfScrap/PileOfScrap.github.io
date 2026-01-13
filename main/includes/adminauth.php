<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    if (!($_SESSION['user_type'] === 2)) {
        header('Location: login.php');
        exit();
    }
} // sends you back to the login page if you are not logged in as an admin
?>