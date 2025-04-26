<?php
    include("main.html");
    include("../database/db.php"); 

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Redirect based on which button is pressed
        if (isset($_POST['Send'])) {
            header("Location: send.php");
            exit;
        } elseif (isset($_POST['Read'])) {
            header("Location: read.php");
            exit;
        } elseif (isset($_POST['Edit'])) {
            header("Location: edit.php");
            exit;
        } elseif (isset($_POST['Home'])) {
            header("Location: ../home/home.html");
            exit;
        } elseif (isset($_POST['Sent'])) {
            header("Location: sent.php");
            exit;
        } 
    }

    // Close the database connection
    mysqli_close($conn);
?>
