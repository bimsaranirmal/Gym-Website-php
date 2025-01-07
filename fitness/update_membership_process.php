<?php
session_start();
include '../fitness/components/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $membership_id = $_POST['membership_id'];
    $plan_name = filter_var($_POST['plan_name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Update the membership plan in the database
    $sql = "UPDATE memberships SET plan_name = :plan_name, price = :price, description = :description WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':plan_name', $plan_name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $membership_id);

    if ($stmt->execute()) {
        // Store the success message in the session
        $_SESSION['success_msg'] = "Membership plan updated successfully!";
    } else {
        $_SESSION['error_msg'] = "Failed to update the membership plan.";
    }

    // Redirect back to the admin panel
    header('Location: admin.php');
    exit();
}
?>
