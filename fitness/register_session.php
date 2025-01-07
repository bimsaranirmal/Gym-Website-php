<?php
// Start the session at the beginning of the file
session_start();

// Include your database connection here
include('../fitness/components/connect.php');
include('../fitness/components/alert.php');

// Initialize messages
$success_msg = $_SESSION['success_msg'] ?? '';
$error_msg = $_SESSION['error_msg'] ?? '';
unset($_SESSION['success_msg'], $_SESSION['error_msg']); // Clear messages after displaying

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $session_name = $_POST['session_name'] ?? '';
    $session_type = $_POST['session_type'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $expiration_date = $_POST['expiration_date'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $max_participants = $_POST['max_participants'] ?? '';
    $trainer_name = $_POST['trainer_name'] ?? '';

    // Validate input
    if (!empty($session_name) && !empty($session_type) && !empty($start_time) && 
        !empty($expiration_date) && !empty($end_time) && 
        !empty($max_participants) && !empty($trainer_name) && 
        is_numeric($max_participants) && $max_participants > 0) {
        
        // Insert the new session into the database
        $sql = "INSERT INTO sessions (session_name, session_type, start_time, expiration_date, end_time, max_participants, trainer_name) 
                VALUES (:session_name, :session_type, :start_time, :expiration_date, :end_time, :max_participants, :trainer_name)";
        
        $stmt = $conn->prepare($sql);
        
        // Bind the parameters
        $stmt->bindParam(':session_name', $session_name);
        $stmt->bindParam(':session_type', $session_type);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':expiration_date', $expiration_date);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->bindParam(':max_participants', $max_participants);
        $stmt->bindParam(':trainer_name', $trainer_name);

        // Execute the query
        if ($stmt->execute()) {
            // Store success message in session
            $_SESSION['success_msg'] = "Session registered successfully!";
            // Redirect to trainer.php
            header('Location: trainers.php');
            exit();
        } else {
            $_SESSION['error_msg'] = "Error registering session.";
        }
    } else {
        $_SESSION['error_msg'] = "Please fill in all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Sessions</title>
    <link rel="stylesheet" href="../fitness/css/session.css">
</head>
<body>

    <!-- Display Success/Error Messages -->
    <?php if ($success_msg): ?>
        <div class="alert success">
            <p><?php echo htmlspecialchars($success_msg); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="alert error">
            <p><?php echo htmlspecialchars($error_msg); ?></p>
        </div>
    <?php endif; ?>

    <!-- Session Registration Form -->
    <div class="session-registration-form">
        <h3>Register New Training Session</h3>
        <form action="" method="POST">
            <div class="form-group">
                <label for="session_name">Session Name:</label>
                <select name="session_name" id="session_name" required>
                    <option value="Yoga">Yoga</option>
                    <option value="Cardio">Cardio</option>
                    <option value="Strength">Strength</option>
                    <option value="Fitness">Fitness</option>
                </select>
            </div>

            <div class="form-group">
                <label for="session_type">Session Type:</label>
                <select name="session_type" id="session_type" required>
                    <option value="Personal">Personal</option>
                    <option value="Group">Group</option>
                </select>
            </div>

            <div class="form-group">
                <label for="start_time">Session Date:</label>
                <input type="datetime-local" name="start_time" id="start_time" required>
            </div>

            <div class="form-group">
                <label for="expiration_date">Expiration Date:</label>
                <input type="datetime-local" name="expiration_date" id="expiration_date" required>
            </div>

            <div class="form-group">
                <label for="end_time">End Time:</label>
                <input type="datetime-local" name="end_time" id="end_time" required>
            </div>

            <div class="form-group">
                <label for="max_participants">Max Participants:</label>
                <input type="number" name="max_participants" id="max_participants" required min="1">
            </div>

            <div class="form-group">
                <label for="trainer_name">Trainer Name:</label>
                <input type="text" name="trainer_name" id="trainer_name" required>
            </div>

            <button type="submit">Register Session</button>
        </form>
    </div>

</body>
</html>
