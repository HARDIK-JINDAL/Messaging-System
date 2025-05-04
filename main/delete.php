<?php
session_start();
include("../database/db.php");

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_email = $_SESSION['email'];
$success = "";
$error = "";

// Fetch messages sent by the user
$sql = $conn->prepare("SELECT message_id, receiver_email, message, sent_at FROM Messages WHERE sender_email = ? ORDER BY sent_at DESC");
$sql->bind_param("s", $user_email);
$sql->execute();
$result = $sql->get_result();

// Handle message deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'])) {
    $message_id = $_POST['message_id'];

    // Delete the message
    $delete = $conn->prepare("DELETE FROM Messages WHERE message_id = ? AND sender_email = ?");
    $delete->bind_param("is", $message_id, $user_email);
    if ($delete->execute()) {
        $log_message = "$user_email deleted message (ID: $message_id)"; // Custom log message
        $action = "Deleted message";
        $log_stmt = $conn->prepare("INSERT INTO Log (sender_email, receiver_email, message_id, log_message, action, log_time) VALUES (?, ?, ?, ?, ?, NOW())");
        $log_stmt->bind_param("ssiss", $user_email, $receiver_email, $message_id, $log_message, $action);
        $log_stmt->execute();
        
        $success = "✅ Message deleted successfully!";
    } else {
        $error = "❌ Failed to delete message.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Your Sent Messages</title>
    <link rel="stylesheet" href="delete.css">
    <link rel="icon" type="image/x-icon" href="resources/medical-25_icon-icons.com_73900.ico">
</head>
<body>
    <h2>Delete Your Sent Messages</h2>

    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php elseif ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <h3>Your Sent Messages:</h3>
        <form method="POST" action="">
            <table border="1">
                <tr>
                    <th>Select</th>
                    <th>To</th>
                    <th>Sent At</th>
                    <th>Message</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><input type="radio" name="message_id" value="<?php echo $row['message_id']; ?>"></td>
                        <td><?php echo htmlspecialchars($row['receiver_email']); ?></td>
                        <td><?php echo $row['sent_at']; ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <br>
            <input type="submit" name="delete_message" value="Delete Selected Message">
        </form>
    <?php else: ?>
        <p>You haven't sent any messages yet.</p>
    <?php endif; ?>

    <!-- Back Button to Previous Main Page -->
    <button onclick="window.location.href='../main/main.html';">Back</button>
</body>
</html>
