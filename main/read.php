<?php
session_start();
include("../database/db.php");

// Check if user is logged in
if (empty($_SESSION['email'])) {
    header("Location: ../login/login.php");
    exit;
}

$user_email = $_SESSION['email'];

// Fetch only the messages received by the user
$sql = "SELECT sender_email, message, sent_at 
        FROM Messages 
        WHERE receiver_email = '$user_email'
        ORDER BY sent_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inbox</title>
    <link rel="stylesheet" href="read.css">
    <link rel="icon" type="image/x-icon" href="resources/medical-25_icon-icons.com_73900.ico">

</head>
<body>
    <h2>Inbox for <?php echo htmlspecialchars($user_email); ?></h2>
    <?php
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1' cellpadding='8'>";
        echo "<tr><th>From</th><th>Message</th><th>Received At</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['sender_email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['message']) . "</td>";
            echo "<td>" . htmlspecialchars($row['sent_at']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No messages received.</p>";
    }

    mysqli_close($conn);
    ?>

    <!-- Back Button to go to the main page -->
    <button onclick="window.location.href='../main/main.html';">Back</button>
</body>
</html>
