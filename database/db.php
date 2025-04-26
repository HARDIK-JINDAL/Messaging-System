<?php
$servername = "localhost";
$username = "root";
$password = "***";  // XAMPP default
$dbname = "messagingSystem";  // Replace with your DB name

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
