<?php
    include("delete.html");
    include("../database/db.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Redirect to home
        if (isset($_POST["home"])) {
            header("Location: ../home/home.html");
            exit;
        }

        // On Submit
        if (isset($_POST["submit"])) {
            if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["email"])) {
                echo "<div class='display'><p>Please enter all the fields</p></div>";
            } else {
                $password = $_POST["password"];
                $username = $_POST["username"];
                $email = $_POST["email"];

                // Check if user exists first
                $sql_check = "SELECT * FROM users WHERE username='$username' AND email='$email'";
                $result = mysqli_query($conn, $sql_check);

                if ($row = mysqli_fetch_assoc($result)) {
                    if (password_verify($password, $row['password_hash'])) {
                        // Delete the user
                        $sql_delete = "DELETE FROM users WHERE username='$username' AND email='$email'";
                        mysqli_query($conn, $sql_delete);
                        echo "<div class='display'><p>User deleted successfully</p></div>";
                    } else {
                        echo "<div class='display'><p>Incorrect password</p></div>";
                    }
                } else {
                    echo "<div class='display'><p>User not found</p></div>";
                }
            }
        }
    }

    mysqli_close($conn);
?>
