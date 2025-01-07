<?php
session_start();
include '../fitness/components/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];

    // Prepare the SQL query to delete the member
    $query = $conn->prepare("DELETE FROM sellers WHERE id = :id");
    $query->bindParam(':id', $member_id);

    if ($query->execute()) {
        // Set success message in the session
        $_SESSION['success_msg'] = "Member deleted successfully!";
        header('Location: admin.php#members');
        exit();
    } else {
        // Set error message in the session
        $_SESSION['error_msg'] = "Error deleting the member.";
        header('Location: admin.php#members');
        exit();
    }
}
?>
