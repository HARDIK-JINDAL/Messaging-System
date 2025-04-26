<?php
session_start(); // Start the session at the very beginning

// Include DB connection
include("../database/db.php"); 

// Check if the user is logged in (if session['email'] exists)
if (empty($_SESSION['email'])) {
    // If the email session variable is not set, redirect to the login page
    header("Location: ../login/login.php");
    exit;
}

// Get the logged-in user's email from the session
$sender_email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Send'])) {
        // Get message and receiver email from form input
        $receiver_email = $_POST['receiver_email'];
        $message = $_POST['message'];

        // Insert message into the database
        $sql = "INSERT INTO Messages (sender_email, receiver_email, message) 
                VALUES ('$sender_email', '$receiver_email', '$message')";

        if (mysqli_query($conn, $sql)) {
            echo "<p class='success'>✅ Message sent successfully!</p>";
        } else {
            echo "<p class='error'>❌ Error: " . mysqli_error($conn) . "</p>";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compose Message</title>
    <link rel="stylesheet" href="send.css">
    <link rel="icon" type="image/x-icon" href="resources/medical-25_icon-icons.com_73900.ico">
</head>
<body>
    <div class="container">
        <h2>Send Message</h2>
        <form action="send.php" method="POST">
            <label for="receiver_email">Receiver Email:</label>
            <input type="email" name="receiver_email" required><br><br>
            
            <label for="message">Message:</label>
            <textarea name="message" rows="4" cols="50" required></textarea><br><br>
            
            <input type="submit" name="Send" value="Send">
        </form>
        
        <!-- Use a simple button outside the form to go back -->
        <button onclick="window.location.href='/DBMS_PROJECT/main/main.html';">Back</button>
    </div>
</body>
</html>
