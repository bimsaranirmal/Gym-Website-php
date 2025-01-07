<?php
session_start();
include '../fitness/components/connect.php';

// Ensure the admin is logged in
if (!isset($_SESSION['username'])) {
    header('location:adminLogin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $membership_id = $_POST['membership_id'];

    // Delete the membership plan from the database
    $deleteQuery = $conn->prepare("DELETE FROM memberships WHERE id = :id");
    $deleteQuery->bindParam(':id', $membership_id);

    if ($deleteQuery->execute()) {
        // Redirect back to the membership management page or display a success message
        echo "Membership plan deleted successfully.";
        header('location:admin.php');
        exit();
    } else {
        echo "Failed to delete the membership plan.";
    }
}
?>
