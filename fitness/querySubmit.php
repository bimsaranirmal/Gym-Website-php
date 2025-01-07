<?php
session_start(); // Ensure that session is started
// Include the database connection
include '../fitness/components/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_query'])) {
    // Check if the session contains 'name'
    if (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
        // Get the logged-in customer name from the session
        $customer_name = $_SESSION['name'];
    } else {
        // Handle the case where the customer is not logged in
        echo "<p>Error: Customer name not found in session. Please log in first.</p>";
        exit();
    }

    // Get the query text from the form
    $query_text = trim($_POST['query_text']);
    
    // Set the current date for date_submitted
    $date_submitted = date('Y-m-d H:i:s');
    
    // Default status for new queries
    $status = 'pending';
    
    // Insert the query into the database
    $insert_query = $conn->prepare("
        INSERT INTO queries (customer_name, query_text, date_submitted, status)
        VALUES (:customer_name, :query_text, :date_submitted, :status)
    ");
    $insert_query->bindParam(':customer_name', $customer_name, PDO::PARAM_STR);
    $insert_query->bindParam(':query_text', $query_text, PDO::PARAM_STR);
    $insert_query->bindParam(':date_submitted', $date_submitted, PDO::PARAM_STR);
    $insert_query->bindParam(':status', $status, PDO::PARAM_STR);
    
    if ($insert_query->execute()) {
        // If successful, you can set a success message and optionally redirect
        $_SESSION['success_msg'] = "Your query has been submitted successfully.";
        header('Location: client.php'); // Change to your dashboard page
        exit();
    } else {
        echo "<p>Failed to submit your query. Please try again.</p>";
    }
}
?>
