<?php 
session_start();
include '../fitness/components/connect.php';

// Check if the user is logged in and has a valid role
if (!isset($_SESSION['username']) && !isset($_SESSION['name'])) {
    header('location:adminLogin.php'); // Redirect to admin login if no admin session exists
    exit();
}

// Handle form submission for query resolution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query_id = $_POST['query_id'];

    // Prepare the SQL query to update the query status and resolved date
    $resolved_date = date('Y-m-d H:i:s');
    $status = 'resolved';

    $query = $conn->prepare("UPDATE queries SET status = :status, resolved_date = :resolved_date WHERE id = :id");
    $query->bindParam(':status', $status);
    $query->bindParam(':resolved_date', $resolved_date);
    $query->bindParam(':id', $query_id);
    
    if ($query->execute()) {
        // Check if the user is an admin or trainer for redirecting
        if (isset($_SESSION['username'])) {
            // Admin is logged in
            header('Location: admin.php#queries'); // Redirect to admin queries page
        } elseif (isset($_SESSION['name'])) {
            // Trainer is logged in
            header('Location: trainers.php#queries'); // Redirect to trainer queries page
        } else {
            // No valid role found, redirect to login or handle error
            header('Location: admin.php#queries'); // Fallback to admin
        }
        exit();
    } else {
        // Handle error if the query execution fails
        echo "Error resolving query.";
    }
}
?>
