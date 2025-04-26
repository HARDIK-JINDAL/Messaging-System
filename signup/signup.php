<?php
    include("signup.html");
    include("../database/db.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Check if 'home' button was clicked
        if (isset($_POST["home"])) {
            header("Location: ../home/home.html");
            exit;
        }

        if (isset($_POST["submit"])) {
            if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["email"])) {
                echo "<div class='display'><p>Please enter all the fields</p></div>";
            } else {
                $password = $_POST["password"];
                $username=$_POST["username"];
                $email=$_POST["email"];

                $hash = password_hash($password,PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, password_hash, email) 
                        VALUES ('$username', '$hash', '$email')";

                mysqli_query($conn,$sql);
                echo "<div class='display'><p>New user registered</p></div>";
            }
        }
    }
    mysqli_close($conn);
?>
