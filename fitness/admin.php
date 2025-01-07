<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header('location:adminLogin.php');
    exit();
}

// Include the database connection
include '../fitness/components/connect.php';

// Check if there is a success message in the session
if (isset($_SESSION['success_msg'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const successMessage = document.getElementById("success-message");
            successMessage.innerHTML = "' . $_SESSION['success_msg'] . '"; // Display the success message
            successMessage.style.display = "block"; // Show the message
            successMessage.style.backgroundColor = "#d4edda"; // Success message background
            successMessage.style.color = "#155724"; // Success message text color
            successMessage.style.padding = "10px"; // Add some padding
            successMessage.style.marginBottom = "20px"; // Add some margin below

             setTimeout(function() {
                successMessage.style.display = "none"; // Hide the message
            }, 5000); // 5000 milliseconds = 5 seconds
        });
    </script>';

    // Clear the message after displaying it
    unset($_SESSION['success_msg']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/styles.css" />
    <title>FitnessZone-AdminPanel</title>
    <link rel="shortcut icon" href="./favivon.png" type="image/svg+xml">
  <link rel="stylesheet" href="../fitness/css/trainers.css">
  <script>
        function confirmLogout(event) {
            // Prevent the form from submitting immediately
            event.preventDefault();
            // Display confirmation dialog
            if (confirm("Are you sure you want to logout?")) {
                // If confirmed, submit the form
                event.target.closest('form').submit();
            }
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#appointments">Appointments</a></li>
            <li><a href="#queries">Customer Queries</a></li>
            <li><a href="#members">Members</a></li>
            <li><a href="#payments">Payments</a></li>
            <li><a href="#memberships">Membership Plans</a></li>
            <li><a href="#trainers">Trainers</a></li>
            <li><a href="#sessions">Sessions</a></li>
            <li><a href="#re_sessions">Register-sessions</a><li>
            <li><a href="#promotions">Promotions</a><li>
            <div class="nav__btns">
                <form action="logout.php" method="post">
                    <button class="btn" type="submit" onclick="return confirm('Are you sure you want to logout?');">Logout</button>
                </form>
            </div>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <nav>
                <div class="nav__header">
                    <div class="nav__logo">
                        <a href="#">
                            <img src="assets/head1.png" alt="logo" class="logo-dark" />
                        </a>
                    </div>
                    <div class="nav__menu__btn" id="menu-btn">
                        <i class="ri-menu-line"></i>
                    </div>
                </div>
                <div id="success-message" style="display: none;"></div>  
            </nav>
        </header>

        <main>
            <!-- Appointments and Messages Management Section -->
            <section class="dashboard-section" id="appointments">
                <h2>Appointments and Messages Management</h2>

                <!-- Appointments Table -->
                <div class="table-wrapper">
                    <h3>Appointments</h3>
                    <table class="appointments-table">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Appointment Date</th>
                                <th>Service</th>
                                <th>Trainer Name</th>
                                <th>Session Type</th>
                                <th>Status</th>
                                <th>Action Taken Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="appointment-list">
                            <?php
                            // Fetch appointments
                            $query = $conn->prepare("SELECT * FROM appointments");
                            $query->execute();
                            $appointments = $query->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($appointments as $appointment) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($appointment['customer_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($appointment['appointment_date']) . "</td>";
                                echo "<td>" . htmlspecialchars($appointment['service']) . "</td>";
                                echo "<td>" . htmlspecialchars($appointment['trainer_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($appointment['session_type']) . "</td>";

                                // Determine status color based on the appointment status
                                $statusColor = 'green'; // Default color
                                if ($appointment['status'] == 'denied') {
                                    $statusColor = 'red'; // Change color for rejected
                                }

                                echo "<td style='color: $statusColor;'>" . htmlspecialchars(ucfirst($appointment['status'])) . "</td>";
                                echo "<td>" . htmlspecialchars($appointment['action_taken_date'] ? date('Y-m-d H:i:s', strtotime($appointment['action_taken_date'])) : 'N/A') . "</td>";

                                echo "<td>
                                        <form action='update_appointment.php' method='post' class='btna'>
                                            <input type='hidden' name='appointment_id' value='" . $appointment['id'] . "'>
                                            <button type='submit' name='approve' value='approved' class='btna2'>Approve</button>
                                            <button type='submit' name='deny' value='denied' class='btna1'>Reject</button>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Messages Table -->
                <div class="table-wrapper">
                    <h3>Messages</h3>
                    <table class="messages-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="message-list">
                            <?php
                            // Fetch messages
                            $query = $conn->prepare("SELECT * FROM messages");
                            $query->execute();
                            $messages = $query->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($messages as $message) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($message['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($message['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($message['message']) . "</td>";
                                echo "<td>" . htmlspecialchars(date('Y-m-d H:i:s', strtotime($message['created_at']))) . "</td>";

                                // Check if the message has been read
                                $status = 'Pending'; // Default status
                                $statusColor = 'orange'; // Default color
                                if (isset($message['status']) && $message['status'] == 'read') {
                                    $status = 'Read';
                                    $statusColor = 'green'; // Change color for read messages
                                }

                                echo "<td style='color: $statusColor;'>" . htmlspecialchars($status) . "</td>";
                                echo "<td>
                                        <form action='update_message.php' method='post' class='btna'>
                                            <input type='hidden' name='message_id' value='" . $message['id'] . "'>
                                            <button type='submit' name='mark_read' class='btna2'>Mark as Read</button>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Customer Queries Section -->
            <section class="dashboard-section" id="queries">
                <h2>Customer Queries</h2>
                <div class="table-wrapper">
                <table class="queries-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Query</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Resolved Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="query-list">
                        <?php
                        // Fetch customer queries
                        $query = $conn->prepare("SELECT * FROM queries");
                        $query->execute();
                        $queries = $query->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($queries as $query) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($query['customer_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($query['query_text']) . "</td>";
                            echo "<td>" . htmlspecialchars($query['date_submitted']) . "</td>";
                            echo "<td>" . htmlspecialchars(ucfirst($query['status'])) . "</td>";
                            echo "<td>" . htmlspecialchars($query['resolved_date'] ? date('Y-m-d H:i:s', strtotime($query['resolved_date'])) : 'N/A') . "</td>";
                            echo "<td>
                                    <form action='update_query.php' method='post'>
                                        <input type='hidden' name='query_id' value='" . $query['id'] . "'>
                                        <button type='submit' name='resolve' value='resolved' class='btna1'>Resolve</button>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </section>

            <!-- Registered Members Management Section -->
            <section class="dashboard-section" id="members">
                <h2>Registered Members Management</h2>
                
                <!-- Add Search Bar for Members -->
                <div class="action-container">
                <div >
                        <button type="submit"><a href="clientRegister.php" class="btnSm">Add Member</a></button>
                    </div>
                    <div class="search">
                        <form action="" method="get" style="display: inline-block;">
                            <input type="text" name="search_member" placeholder="Search Member by Name">
                            <button type="submit" class="btnts">Search</button>
                        </form>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="members-table">
                        <thead>
                            <tr>
                                <th>Member Name</th>
                                <th>Email</th>
                                <th>Membership Plan</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="member-list">
                            <?php
                            // Get the search query if it exists
                            $search_member = isset($_GET['search_member']) ? $_GET['search_member'] : '';

                            if (!empty($search_member)) {
                                // Fetch members matching the search query
                                $query = $conn->prepare("SELECT sellers.*, memberships.plan_name 
                                                        FROM sellers 
                                                        INNER JOIN memberships ON sellers.membership_plan = memberships.id 
                                                        WHERE sellers.name LIKE ?");
                                $query->execute(['%' . $search_member . '%']);
                            } else {
                                // Fetch all members if no search query
                                $query = $conn->prepare("SELECT sellers.*, memberships.plan_name 
                                                        FROM sellers 
                                                        INNER JOIN memberships ON sellers.membership_plan = memberships.id");
                                $query->execute();
                            }

                            $members = $query->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($members as $member) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($member['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($member['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($member['plan_name']) . "</td>";
                                echo "<td>
                                        <form action='update_member.php' method='post' style='display:inline-block;'>
                                            <input type='hidden' name='member_id' value='" . $member['id'] . "'>
                                            <button type='submit' name='edit' value='edit' class='btna2'>Edit</button>
                                        </form>
                                        <form action='delete_member.php' method='post' style='display:inline-block;'>
                                            <input type='hidden' name='member_id' value='" . $member['id'] . "'>
                                            <button type='submit' name='delete' value='delete' class='btna1' onclick=\"return confirm('Are you sure you want to delete this member?');\">Delete</button>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Payment Section -->
            <section class="dashboard-section" id="payments">
                <h2>Client Payment Details</h2>

                <!-- Search Bar -->
                <div class="action-container">
                <div class="search">
                <form method="POST" action="">
                
                    <input type="text" name="search_client" placeholder="Search by Client Name">
                    <button type="submit" name="search_payment" class="btnts">Search</button>
                        
                </form>
                </div>
                </div>

                <div class="table-wrapper">
                    <table class="client-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client Name</th>
                                <th>Email</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                                <th>Payment Method</th>
                            </tr>
                        </thead>
                        <tbody id="payment-list">
                            <?php
                            // Fetch payment details
                            if (isset($_POST['search_payment'])) {
                                $search_client = $_POST['search_client'];
                                $query = $conn->prepare("SELECT * FROM payments WHERE name LIKE :name");
                                $query->bindValue(':name', '%' . $search_client . '%', PDO::PARAM_STR);
                            } else {
                                $query = $conn->prepare("SELECT * FROM payments");
                            }
                            $query->execute();
                            $payments = $query->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($payments as $payment) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($payment['id']) . "</td>"; // Display ID
                                echo "<td>" . htmlspecialchars($payment['name']) . "</td>"; // Display Client Name
                                echo "<td>" . htmlspecialchars($payment['email']) . "</td>"; // Display Email
                                echo "<td>" . htmlspecialchars($payment['amount']) . "</td>"; // Display Amount
                                echo "<td>" . htmlspecialchars(date('Y-m-d H:i:s', strtotime($payment['payment_date']))) . "</td>"; // Display Payment Date
                                echo "<td>" . htmlspecialchars($payment['payment_method']) . "</td>"; // Display Payment Method
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Membership Plans Management Section -->
            <section class="dashboard-section" id="memberships">
                <h2>Membership Plans Management</h2>
                <div class="membership-list">
                    <?php
                    // Fetch membership plans
                    $query = $conn->prepare("SELECT * FROM memberships");
                    $query->execute();
                    $memberships = $query->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($memberships as $membership) {
                        echo "<div class='membership-item'>";
                        echo "<div class='name'><h3>" . htmlspecialchars($membership['plan_name']) . "</h3></div>";
                        echo "<div class='price'><p>Price: Rs." . htmlspecialchars($membership['price']) . "/month</p></div>";
                        echo "<div class='description'><p>" . htmlspecialchars($membership['description']) . "</p></div>";
                        echo "<form class='' action='update_membership.php' method='post'>
                                <input type='hidden' name='membership_id' value='" . $membership['id'] . "'>
                                <button  type='submit' class='btna2'>Edit</button>
                            </form>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </section>

            <!-- Trainer Management Section -->
            <section class="dashboard-section" id="trainers">
                <h2>Trainers Management</h2>

                <!-- Add Trainer Form and Search Bar -->
                <div class="action-container">
                    <div >
                        <button type="submit"><a href="trainerRegister.php" class="btnSm">Add Trainer</a></button>
                    </div>
                    <div class="search">
                        <form action="" method="get" style="display: inline-block;">
                            <input type="text" name="search_query" placeholder="Search Trainer by Name">
                            <button type="submit" class="btnts">Search</button>
                        </form>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="trainers-table">
                        <thead>
                            <tr>
                                <th>Trainer Name</th>
                                <th>Email</th>
                                <th>Specialization</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="trainer-list">
                            <?php
                            // Fetch trainers from the database
                            $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

                            if (!empty($search_query)) {
                                // If search query exists, fetch trainers matching the query
                                $query = $conn->prepare("SELECT * FROM trainers WHERE name LIKE ?");
                                $query->execute(['%' . $search_query . '%']);
                            } else {
                                // If no search query, fetch all trainers
                                $query = $conn->prepare("SELECT * FROM trainers");
                                $query->execute();
                            }

                            $trainers = $query->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($trainers as $trainer) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($trainer['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($trainer['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($trainer['specializations']) . "</td>";
                                echo "<td>
                                        <form action='delete_trainer.php' method='post' style='display:inline-block;'>
                                            <input type='hidden' name='trainer_id' value='" . $trainer['id'] . "'>
                                            <button type='submit' name='delete' value='delete' class='btna1' onclick=\"return confirm('Are you sure you want to delete this trainer?');\">Delete</button>
                                        
                                        <a href='view_trainer.php?id=" . $trainer['id'] . "' class='btn'>View</a>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Sessions Management Section -->
            <section class="dashboard-section" id="sessions">
                <h2>Sessions Management</h2>

                <!-- Add button to open session_management.php -->
                <div class="action-container">
                    <div>
                        <button type="submit"><a href="adminSession.php" class="btnSm">Add Session</a></button>
                    </div>

                    <!-- Add Search Form -->
                    <div class="search">
                        <form action="" method="get" style="display: inline-block;">
                            <input type="text" name="search_trainer" value="<?php echo isset($_GET['search_trainer']) ? htmlspecialchars($_GET['search_trainer']) : ''; ?>" placeholder="Search by Trainer Name" class="input">
                            <input type="text" name="search_type" value="<?php echo isset($_GET['search_type']) ? htmlspecialchars($_GET['search_type']) : ''; ?>" placeholder="Search by Session Type" class="input">
                            <select name="search_status" class="input">
                                <option value="">Select Status</option>
                                <option value="Pending" <?php echo (isset($_GET['search_status']) && $_GET['search_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Expired" <?php echo (isset($_GET['search_status']) && $_GET['search_status'] == 'Expired') ? 'selected' : ''; ?>>Expired</option>
                            </select>
                            <button type="submit" class="btnts">Search</button>
                        </form>
                    </div>
                </div>

                <!-- Table for displaying sessions -->
                <div class="table-wrapper">
                    <table class="sessions-table">
                        <thead>
                            <tr>
                                <th>Session ID</th>
                                <th>Trainer Name</th>
                                <th>Session Type</th>
                                <th>Participants</th>
                                <th>Session Date</th>
                                <th>Expiration Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="session-list">
                            <?php
                            // Fetch current date and time
                            $current_date = date('Y-m-d H:i:s');

                            // Prepare the SQL query with search filters
                            $search_trainer = isset($_GET['search_trainer']) ? trim($_GET['search_trainer']) : '';
                            $search_type = isset($_GET['search_type']) ? trim($_GET['search_type']) : '';
                            $search_status = isset($_GET['search_status']) ? trim($_GET['search_status']) : '';

                            // Build the base query
                            $sql = "SELECT * FROM sessions WHERE 1=1";

                            // Add filters based on user input
                            if (!empty($search_trainer)) {
                                $sql .= " AND trainer_name LIKE :search_trainer";
                            }
                            if (!empty($search_type)) {
                                $sql .= " AND session_type LIKE :search_type";
                            }

                            // Filter by session status (Expired or Pending)
                            if (!empty($search_status)) {
                                if ($search_status == 'Expired') {
                                    $sql .= " AND expiration_date < :current_date";
                                } elseif ($search_status == 'Pending') {
                                    $sql .= " AND expiration_date > :current_date";
                                }
                            }

                            // Prepare and execute the query
                            $query = $conn->prepare($sql);

                            // Bind the parameters
                            if (!empty($search_trainer)) {
                                $query->bindValue(':search_trainer', '%' . $search_trainer . '%', PDO::PARAM_STR);
                            }
                            if (!empty($search_type)) {
                                $query->bindValue(':search_type', '%' . $search_type . '%', PDO::PARAM_STR);
                            }
                            if (!empty($search_status)) {
                                $query->bindValue(':current_date', $current_date, PDO::PARAM_STR);
                            }

                            // Execute the query
                            $query->execute();
                            $sessions = $query->fetchAll(PDO::FETCH_ASSOC);

                            // Display the sessions
                            if (!empty($sessions)) {
                                foreach ($sessions as $session) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($session['session_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($session['trainer_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($session['session_type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($session['current_participants']) . "</td>";
                                    echo "<td>" . htmlspecialchars(date('Y-m-d H:i:s', strtotime($session['start_time']))) . "</td>";
                                    echo "<td>" . htmlspecialchars(date('Y-m-d H:i:s', strtotime($session['expiration_date']))) . "</td>";

                                    $isExpired = strtotime($session['expiration_date']) < strtotime($current_date);
                                    if ($isExpired) {
                                        echo "<td style='color: red;'>Expired</td>";
                                    } else {
                                        echo "<td style='color: green;'>Pending</td>";
                                    }

                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No sessions found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Registered Sessions Section -->
            <section class="dashboard-section" id="re_sessions">
                <h2>Registered Sessions</h2>
                
                <div class="table-wrapper">
                    <table class="queries-table">
                        <thead>
                            <tr>
                                <th>Member Name</th>
                                <th>Email</th>
                                <th>Session Name</th>
                                <th>Session Type</th>
                                <th>Start Time</th>
                                <th>Trainer Name</th>
                                <th>Registered Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch registered sessions for the logged-in user
                            

                            // Fetch registered sessions for the logged-in user
                            $query = $conn->prepare("SELECT * FROM registrations");
                            $query->execute();
                            $registrations = $query->fetchAll(PDO::FETCH_ASSOC);

                            // Display each registered session
                            if ($registrations) {
                                foreach ($registrations as $registration) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($registration['user_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($registration['email']) . "</td>";
                                    echo "<td>" . htmlspecialchars($registration['session_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($registration['session_type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($registration['start_time']) . "</td>";
                                    echo "<td>" . htmlspecialchars($registration['trainer_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($registration['registered_date']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>You have not registered for any sessions.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Promotions Section -->
            <section class="dashboard-section" id="promotions">
                <h2>Add Promotions</h2>
                
                <form action="add_promotion_process.php" method="post" class="promotion-form">
                    <label for="promotion_title">Promotion Title:</label>
                    <input type="text" name="promotion_title" id="promotion_title" required>

                    <label for="promotion_description">Description:</label>
                    <textarea name="promotion_description" id="promotion_description" required></textarea>

                    <label for="promotion_start_date">Start Date:</label>
                    <input type="date" name="promotion_start_date" id="promotion_start_date" required>

                    <label for="promotion_end_date">End Date:</label>
                    <input type="date" name="promotion_end_date" id="promotion_end_date" required>

                    <button type="submit" class="btna2">Add Promotion</button>
                </form>

                <div class="table-wrapper">
                    <h3>Current Promotions</h3>
                    <table class="promotions-table">
                        <thead>
                            <tr>
                                <th>Promotion Title</th>
                                <th>Description</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch current promotions from the database
                            $query = $conn->prepare("SELECT * FROM promotions");
                            $query->execute();
                            $promotions = $query->fetchAll(PDO::FETCH_ASSOC);

                            // Display each promotion
                            if ($promotions) {
                                foreach ($promotions as $promotion) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($promotion['title']) . "</td>";
                                    echo "<td>" . htmlspecialchars($promotion['description']) . "</td>";
                                    echo "<td>" . htmlspecialchars($promotion['start_date']) . "</td>";
                                    echo "<td>" . htmlspecialchars($promotion['end_date']) . "</td>";
                                    echo "<td>
                                            <form action='delete_promotion_process.php' method='post'>
                                                <input type='hidden' name='promotion_id' value='" . htmlspecialchars($promotion['id']) . "'>
                                                <button type='submit' class='btna1' onclick=\"return confirm('Are you sure you want to delete this promotion?');\">Delete</button>
                                            </form>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No promotions available.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <div class="footer" id="footer">
            <div class="footer__bar">
                    Copyright © 2024 Web Design Mastery. All rights reserved.
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (window.location.hash === "#queries") {
                    const queriesSection = document.getElementById("queries");
                    if (queriesSection) {
                        queriesSection.scrollIntoView({ behavior: "smooth" });
                    }
                }
            });

            function showSection(sectionId) {
                var sections = document.querySelectorAll('.dashboard-section');
                sections.forEach(function(section) {
                    section.style.display = 'none'; 
                });

                var activeSection = document.getElementById(sectionId);
                if (activeSection) {
                    activeSection.style.display = 'block'; 
                }
            }

            document.querySelectorAll('.sidebar ul li a').forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault(); 
                    var sectionId = this.getAttribute('href').substring(1);
                    showSection(sectionId);
                });
            });

                // Get the menu button and sidebar
            const menuBtn = document.getElementById('menu-btn');
            const sidebar = document.querySelector('.sidebar');

            // Toggle sidebar visibility when the menu button is clicked
            menuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        </script>
        <script src="https://unpkg.com/scrollreveal"></script>
        <script src="../fitness/js/admin.js"></script>
</body>
</html>



