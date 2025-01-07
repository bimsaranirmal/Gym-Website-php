<?php 
session_start();
include '../fitness/components/connect.php';

// Check if the user is logged in
if (!isset($_SESSION['username']) && !isset($_SESSION['name'])) {
    header('location:adminLogin.php'); // Redirect to admin login if no admin session exists
    exit();
}

// Handle form submission for appointment status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the appointment_id is set in the POST request
    if (isset($_POST['appointment_id'])) {
        $appointment_id = $_POST['appointment_id'];
        $action = '';

        // Determine the action based on the submitted button
        if (isset($_POST['approve'])) {
            $action = 'approved';
        } elseif (isset($_POST['deny'])) {
            $action = 'denied';
        }

        // Prepare the SQL query to update the appointment status and action taken date
        if ($action) {
            try {
                $action_taken_date = date('Y-m-d H:i:s');
                $query = $conn->prepare("UPDATE appointments SET status = :status, action_taken_date = :action_taken_date WHERE id = :id");
                $query->bindParam(':status', $action);
                $query->bindParam(':action_taken_date', $action_taken_date);
                $query->bindParam(':id', $appointment_id, PDO::PARAM_INT); // Bind appointment ID as an integer
                $query->execute();

                // Redirect back to the appointments page after a successful update
                if (isset($_SESSION['username'])) {
                    header('Location: admin.php'); // Redirect to admin appointments page
                } else if (isset($_SESSION['name'])) {
                    header('Location: trainers.php'); // Redirect to trainer appointments page
                }
                exit();
            } catch (PDOException $e) {
                // Log the error message (optional)
                error_log("Database update failed: " . $e->getMessage());
                // You can also display an error message to the user if needed
                echo "<script>alert('Failed to update appointment status. Please try again later.');</script>";
            }
        }
    } else {
        echo "<script>alert('Invalid appointment ID.');</script>";
    }
}
?>
