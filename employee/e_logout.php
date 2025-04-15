<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$_SESSION = array();
session_destroy();

header("Location: ../home.php");
exit();
?>