<?php
// Include DB connection
include("../database/db.php"); 
include("login.html");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['home'])) {
        header("Location: ../home/home.html"); 
        exit;
    }

    if (isset($_POST['submit'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Fetch user data
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password_hash'])) {
                
                // Set session variable for email
                $_SESSION['email'] = $user['email']; // Store user's email in session
                $_SESSION['username'] = $username;  // You can keep username if you prefer

                echo "<p class='message'>✅ Login successful!</p>";
                header("Location: ../main/main.html"); // Adjust path to your dashboard
                exit;
            } else {
                echo "<p class='message'>❌ Invalid password.</p>";
            }
        } else {
            echo "<p class='message'>❌ User not found.</p>";
        }
    }
}

mysqli_close($conn);
?>
