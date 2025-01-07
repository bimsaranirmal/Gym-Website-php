<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Session</title>
    <!-- Link the CSS file -->
    <link rel="stylesheet" href="css/client_session.css"> <!-- Adjust path as needed -->
</head>
<body>

<?php
include '../fitness/components/connect.php'; // Include database connection

session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header('Location: login.php');
    exit();
}

$user_name = $_SESSION['name'];
$user_email = $_SESSION['email'];

// Check if session_id is provided in the URL
if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];

    // Fetch session details
    $stmt = $conn->prepare("SELECT session_name, session_type, start_time, trainer_name, current_participants, max_participants FROM sessions WHERE session_id = :session_id");
    $stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
    $stmt->execute();
    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if session exists
    if ($session) {
        // Check if the session is full
        $session_full = $session['current_participants'] >= $session['max_participants'];

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get the form data
            $user_name = $_POST['user_name'];
            $email = $_POST['email'];
            $session_name = $_POST['session_name'];
            $session_type = $_POST['session_type'];
            $start_time = $_POST['start_time'];
            $trainer_name = $_POST['trainer_name'];
            $registered_date = $_POST['registered_date'];
        
            // Insert registration data into the database (assuming a 'registrations' table)
            $insertStmt = $conn->prepare("INSERT INTO registrations (user_name, email, session_name, session_type, start_time, trainer_name, registered_date) 
                                          VALUES (:user_name, :email, :session_name, :session_type, :start_time, :trainer_name, :registered_date)");
            $insertStmt->bindParam(':user_name', $user_name);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':session_name', $session_name);
            $insertStmt->bindParam(':session_type', $session_type);
            $insertStmt->bindParam(':start_time', $start_time);
            $insertStmt->bindParam(':trainer_name', $trainer_name);
            $insertStmt->bindParam(':registered_date', $registered_date);
        
            if ($insertStmt->execute()) {
                // Increment the current participants count
                $updateStmt = $conn->prepare("UPDATE sessions SET current_participants = current_participants + 1 WHERE session_id = :session_id");
                $updateStmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
                $updateStmt->execute();
                // Set success message in session
                $_SESSION['success_msg'] = 'Registration successful!';

                // Redirect to client.php
                header('Location: client.php');
                exit();
        
                echo "<p>Registration successful!</p>";
            } else {
                echo "<p>Failed to register. Please try again.</p>";
            }
        }
    } else {
        echo "<p>Session not found.</p>";
    }
} else {
    echo "<p>Invalid session.</p>";
}
?>


<!-- Display registration form if the session is available -->
<?php if (isset($session_full) && !$session_full && $session): ?>
    

    <!-- Registration form -->
    <form action="" method="POST">
        <div class="register"><h1>Register Session</h1><div>
    <h2>Session: <?php echo htmlspecialchars($session['session_name']); ?></h2>
    <h2>Type: <?php echo htmlspecialchars($session['session_type']); ?></h2>
    <p>Trainer: <?php echo htmlspecialchars($session['trainer_name']); ?></p>
    <p>Start Time: <?php echo htmlspecialchars($session['start_time']); ?></p>
        <label for="user_name">Your Name:</label>
        <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name); ?>" required readonly>

        <label for="email">Your Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required readonly>

        <!-- Hidden fields for session details -->
        <input type="hidden" name="session_name" value="<?php echo htmlspecialchars($session['session_name']); ?>">
        <input type="hidden" name="session_type" value="<?php echo htmlspecialchars($session['session_type']); ?>">
        <input type="hidden" name="start_time" value="<?php echo htmlspecialchars($session['start_time']); ?>">
        <input type="hidden" name="trainer_name" value="<?php echo htmlspecialchars($session['trainer_name']); ?>">
        <input type="hidden" name="registered_date" value="<?php echo date('Y-m-d H:i:s'); ?>"> <!-- Registered date -->

        <button type="submit">Register</button>
    </form>
<?php endif; ?>

</body>
</html>
