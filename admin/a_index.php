<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<h2>Welcome, Admin!</h2>
<p>You are logged in as: <?= $_SESSION['admin']; ?></p>
<p><a href="logout.php">Logout</a></p>
