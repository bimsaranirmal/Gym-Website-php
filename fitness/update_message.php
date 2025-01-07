<?php
session_start();
include '../fitness/components/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id'])) {
    $message_id = $_POST['message_id'];

    // Update the status of the message to 'read'
    $sql = "UPDATE messages SET status = 'read' WHERE id = :message_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':message_id', $message_id);

    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Message marked as read successfully!";
    } else {
        $_SESSION['error_msg'] = "Failed to update message status.";
    }

    header('Location: admin.php'); // Redirect back to the admin page
    exit();
}
