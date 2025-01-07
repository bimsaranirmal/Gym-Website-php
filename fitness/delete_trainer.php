<?php
session_start();
include '../fitness/components/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the trainer ID is provided
    if (isset($_POST['trainer_id']) && !empty($_POST['trainer_id'])) {
        $trainer_id = $_POST['trainer_id'];

        // Prepare and execute the delete statement
        $stmt = $conn->prepare("DELETE FROM trainers WHERE id = :id");
        $stmt->bindParam(':id', $trainer_id);

        if ($stmt->execute()) {
            // Set success message in session and redirect
            $_SESSION['success_msg'] = 'Trainer deleted successfully.';
            header('Location: admin.php#trainers'); // Redirect to trainers section
            exit(); // Make sure to exit after redirection
        } else {
            // Set error message in session and redirect
            $_SESSION['error_msg'] = 'Failed to delete trainer. Please try again.';
            header('Location: admin.php#trainers'); // Redirect to trainers section
            exit();
        }
    } else {
        // Set error message in session and redirect
        $_SESSION['error_msg'] = 'No trainer ID provided.';
        header('Location: admin.php#trainers'); // Redirect to trainers section
        exit();
    }
} else {
    // Set error message in session and redirect
    $_SESSION['error_msg'] = 'Invalid request method.';
    header('Location: admin.php#trainers'); // Redirect to trainers section
    exit();
}
?>
