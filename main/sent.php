<?php
session_start();
include("../database/db.php");

// Make sure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_email = $_SESSION['email'];
$messages = [];

// Fetch sent messages
$sql = $conn->prepare("SELECT receiver_email, message, sent_at FROM Messages WHERE sender_email = ? ORDER BY sent_at DESC");
$sql->bind_param("s", $user_email);
$sql->execute();
$result = $sql->get_result();

// Store messages in an array
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sent Messages</title>
    <link rel="stylesheet" href="sent.css">
    <link rel="icon" type="image/x-icon" href="resources/medical-25_icon-icons.com_73900.ico">
</head>
<body>
    <h2>Messages You Have Sent</h2>

    <?php if (!empty($messages)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Receiver</th>
                    <th>Message</th>
                    <th>Sent At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($message['receiver_email']); ?></td>
                        <td><?php echo htmlspecialchars($message['message']); ?></td>
                        <td><?php echo htmlspecialchars($message['sent_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No messages have been sent yet.</p>
    <?php endif; ?>

    <br>
    <a href="main.php">Back to Dashboard</a>
</body>
</html>
