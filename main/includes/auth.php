<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
} // sends you back to the login page if you are not logged in/the session is expired
?>