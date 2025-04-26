<?php
session_start();
include("../database/db.php");

// Make sure the user is logged in
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

// Step 2: Handle message selection and update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_message'])) {
    $message_id = $_POST['message_id'];
    $new_message = trim($_POST['new_message']);

    // Update the message in the database
    $update = $conn->prepare("UPDATE Messages SET message = ? WHERE message_id = ? AND sender_email = ?");
    $update->bind_param("sis", $new_message, $message_id, $user_email);
    if ($update->execute()) {
        $success = "✅ Message updated successfully!";
    } else {
        $error = "❌ Failed to update message.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Your Sent Messages</title>
    <link rel="stylesheet" href="edit.css">
    <link rel="icon" type="image/x-icon" href="resources/medical-25_icon-icons.com_73900.ico">

</head>
<body>
    <h2>Edit Your Sent Messages</h2>

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
                        <td><input type="checkbox" name="message_id" value="<?php echo $row['message_id']; ?>"></td>
                        <td><?php echo htmlspecialchars($row['receiver_email']); ?></td>
                        <td><?php echo $row['sent_at']; ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <br>
            <h3>Edit Selected Message:</h3>
            <textarea name="new_message" rows="4" cols="50" placeholder="Enter new message here"></textarea>
            <br><br>
            <input type="submit" name="update_message" value="Update Message">
        </form>
    <?php else: ?>
        <p>You haven't sent any messages yet.</p>
    <?php endif; ?>

    <!-- Back Button to Previous Main Page -->
    <button onclick="window.location.href='/DBMS_PROJECT/main/main.html';">Back</button>
</body>
</html>
