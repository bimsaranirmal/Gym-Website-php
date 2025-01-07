<?php
session_start();

// Include the database connection
include '../fitness/components/connect.php';

// Assuming the logged-in customer is in the session
$logged_in_customer = $_SESSION['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_appointment'])) {
    // Get the form data and sanitize it
    $customer_name = htmlspecialchars($_POST['customer_name']);
    $appointment_date = htmlspecialchars($_POST['appointment_date']);
    $service = htmlspecialchars($_POST['service']);
    $trainer_name = htmlspecialchars($_POST['trainer_name']);
    $session_type = htmlspecialchars($_POST['session_type']);
    $status = 'pending'; // Default status is pending
    $action_taken_date = null; // No action taken yet

    // Insert the appointment into the database
    $insert_query = $conn->prepare("
        INSERT INTO appointments (customer_name, appointment_date, service, trainer_name, session_type, status, action_taken_date)
        VALUES (:customer_name, :appointment_date, :service, :trainer_name, :session_type, :status, :action_taken_date)
    ");

    // Bind the parameters
    $insert_query->bindParam(':customer_name', $customer_name, PDO::PARAM_STR);
    $insert_query->bindParam(':appointment_date', $appointment_date, PDO::PARAM_STR);
    $insert_query->bindParam(':service', $service, PDO::PARAM_STR);
    $insert_query->bindParam(':trainer_name', $trainer_name, PDO::PARAM_STR);
    $insert_query->bindParam(':session_type', $session_type, PDO::PARAM_STR);
    $insert_query->bindParam(':status', $status, PDO::PARAM_STR);
    $insert_query->bindParam(':action_taken_date', $action_taken_date, PDO::PARAM_STR);

    // Execute the query and check if it was successful
    if ($insert_query->execute()) {
        // Set a success message in the session
        $_SESSION['success_msg'] = "Appointment added successfully!";
        // Redirect to the client.php page
        header("Location: client.php");
        exit();
    } else {
        echo "Failed to add appointment. Please try again.";
    }
}
?>
