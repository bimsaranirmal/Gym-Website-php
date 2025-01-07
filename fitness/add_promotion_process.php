<?php
session_start();
include '../fitness/components/connect.php';

// Ensure the admin is logged in
if (!isset($_SESSION['username'])) {
    header('location:adminLogin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['promotion_title'];
    $description = $_POST['promotion_description'];
    $start_date = $_POST['promotion_start_date'];
    $end_date = $_POST['promotion_end_date'];

    // Prepare the SQL query to insert the promotion
    $query = $conn->prepare("INSERT INTO promotions (title, description, start_date, end_date) VALUES (:title, :description, :start_date, :end_date)");
    $query->bindParam(':title', $title);
    $query->bindParam(':description', $description);
    $query->bindParam(':start_date', $start_date);
    $query->bindParam(':end_date', $end_date);
    
    if ($query->execute()) {
        $_SESSION['success_msg'] = "Promotion added successfully!";
        // Redirect to the client.php page
        header("Location: admin.php");
        exit();
    } else {
        // Handle error
        echo "Error adding promotion.";
    }
}
?>
