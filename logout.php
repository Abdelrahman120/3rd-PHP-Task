<?php
session_start();
if ($_SESSION['login']) {
    $_SESSION = array();
    session_destroy();
} else {
    session_destroy();
}
header('Location: login.php');
