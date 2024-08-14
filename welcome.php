<?php
session_start();
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header('Location: login.php');
    exit();
}

echo "<h1>Welcome to Home Page</h1>";
echo "<a href='logout.php'>Logout</a>";