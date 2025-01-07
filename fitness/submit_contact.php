<?php
// Start the session
session_start();

// Database connection
include '../fitness/components/connect.php'; // Make sure this file creates a $conn using PDO

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Prepare SQL query to insert data into the messages table
    $sql = "INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);

    // Execute the statement
    if ($stmt->execute()) {
        // Store the success message in the session
        $_SESSION['success_msg'] = 'Thank you for contacting us, ' . $name . '! We\'ll get back to you shortly.';
        
        // Redirect to index.html
        header('Location: index.php');
        exit(); // Make sure to call exit after redirection
    } else {
        echo "<p>Something went wrong, please try again later.</p>";
        error_log(print_r($stmt->errorInfo(), true));
    }

    // No need to close the statement or connection, as PDO handles it automatically
}
?>