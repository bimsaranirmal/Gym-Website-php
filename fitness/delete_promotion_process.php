<?php
session_start();
include '../fitness/components/connect.php'; // Make sure this path is correct

// Ensure the admin is logged in (or any user with permissions to delete)
if (!isset($_SESSION['username'])) {
    header('location:adminLogin.php'); // Redirect if not logged in
    exit();
}

// Check if the promotion ID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promotion_id'])) {
    $promotion_id = $_POST['promotion_id'];

    // Prepare and execute the deletion query
    $query = $conn->prepare("DELETE FROM promotions WHERE id = :id");
    $query->bindParam(':id', $promotion_id, PDO::PARAM_INT);

    try {
        $query->execute();
        $_SESSION['success_msg'] = "Promotion deleted successfully!";
        // Redirect to the client.php page
        header("Location: admin.php");
        exit();
    } catch (PDOException $e) {
        // Handle any errors (e.g., log the error or display a message)
        echo "Error deleting promotion: " . $e->getMessage();
    }
} else {
    // Handle the case where no promotion ID is provided
    echo "Invalid request.";
}
?>
