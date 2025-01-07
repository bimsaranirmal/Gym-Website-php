<?php 
session_start();
if (!isset($_SESSION['name'])) {
    header('location:login.php');
    exit();
}

include '../fitness/components/connect.php';

$profile_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';
$profile_image = isset($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg';

$logged_in_customer = $_SESSION['name']; // You can use customer ID if available, e.g., $_SESSION['customer_id'] 

// Fetch appointments for the logged-in customer
$query = $conn->prepare("SELECT * FROM appointments WHERE customer_name = :customer_name");
$query->bindParam(':customer_name', $logged_in_customer, PDO::PARAM_STR);
$query->execute();
$appointments = $query->fetchAll(PDO::FETCH_ASSOC);

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
        <h2>Customer</h2>
        <div class="trainer-email">
            <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
        </div>
        <div class="profile-section">
            <img src="../uploaded_files/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="profile-image">
        </div>
        <ul>
            <li><a href="#appointments">Appointments</a></li>
            <li><a href="#queries">Customer Queries</a></li>
            <li><a href="#payments">Payments</a></li>
            <li><a href="#memberships">Membership Plans</a></li>
            <li><a href="#trainers">Trainers</a></li>
            <li><a href="#sessions">Sessions</a></li>
            <li><a href="#re_sessions">Registered Sessions</a></li>
            <li><a href="#promotions">Promotions</a></li>
        </ul>
        <div class="nav__btns">
            <form action="logout_client.php" method="post">
                <button class="btn" type="submit" onclick="return confirm('Are you sure you want to logout?');">Logout</button>
            </form>
        </div>  
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

                <!-- Appointments Form -->
                <div class="form-wrapper">
                    <h3>Add New Appointment</h3>
                    <form action="appointmentSubmit.php" method="post" class="appointmentForm">
                        <div class="form-group">
                            <label for="customer_name">Customer Name:</label>
                            <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($logged_in_customer); ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="appointment_date">Appointment Date:</label>
                            <input type="date" id="appointment_date" name="appointment_date" required>
                        </div>
                        <div class="form-group">
                            <label for="service">Service:</label>
                            <input type="text" id="service" name="service" required>
                        </div>
                        <div class="form-group">
                            <label for="trainer_name">Trainer Name:</label>
                            <input type="text" id="trainer_name" name="trainer_name" required>
                        </div>
                        <div class="form-group">
                            <label for="session_type">Session Type:</label>
                            <select id="session_type" name="session_type" required>
                                <option value="personal">Personal</option>
                                <option value="group">Group</option>
                            </select>
                        </div>
                        <button type="submit" name="add_appointment" class="btna2">Add Appointment</button>
                    </form>
                </div>

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
                            </tr>
                        </thead>
                        <tbody id="appointment-list">
                            <?php
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
                                    $statusColor = 'red'; // Change color for denied
                                }

                                echo "<td style='color: $statusColor;'>" . htmlspecialchars(ucfirst($appointment['status'])) . "</td>";
                                echo "<td>" . htmlspecialchars($appointment['action_taken_date'] ? date('Y-m-d H:i:s', strtotime($appointment['action_taken_date'])) : 'N/A') . "</td>";
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
                
                <!-- Query Submission Form -->
                <div class="query-form-wrapper">
                    <form action="querySubmit.php" method="post" id="queryForm">
                        <div class="form-group">
                            <label for="query_text">Submit Your Query:</label>
                            <textarea id="query_text" name="query_text" required></textarea>
                        </div>
                        <button type="submit" name="submit_query" class="btna2">Submit Query</button>
                    </form>
                </div>
                
                <div class="table-wrapper">
                    <table class="queries-table">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Query</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th>Resolved Date</th>
                            </tr>
                        </thead>
                        <tbody id="query-list">
                            <?php
                            // Fetch customer queries for the logged-in customer
                            $logged_in_customer = $_SESSION['name'];

                            // Fetch queries for the logged-in customer
                            $query = $conn->prepare("SELECT * FROM queries WHERE customer_name = :customer_name");
                            $query->bindParam(':customer_name', $logged_in_customer, PDO::PARAM_STR);
                            $query->execute();
                            $queries = $query->fetchAll(PDO::FETCH_ASSOC);

                            // Display each query
                            foreach ($queries as $query) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($query['customer_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($query['query_text']) . "</td>";
                                echo "<td>" . htmlspecialchars($query['date_submitted']) . "</td>";
                                echo "<td>" . htmlspecialchars(ucfirst($query['status'])) . "</td>";
                                echo "<td>" . htmlspecialchars($query['resolved_date'] ? date('Y-m-d H:i:s', strtotime($query['resolved_date'])) : 'N/A') . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="dashboard-section" id="payments">
                <h2>Client Payment Details</h2>

                <!-- Payment Submission Form -->
                <div class="action-container">
                    <form method="POST" action="">
                        <h3>Add New Payment</h3>
                        <input type="text" name="client_name" placeholder="Client Name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" readonly required>
                        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly required>
                        
                        <!-- Membership Plan Dropdown -->
                        <select name="membership_plan" id="membership-plan" required>
                            <option value="">Select Membership Plan</option>
                            <?php
                            // Fetch membership plans from the database
                            $planQuery = $conn->prepare("SELECT plan_name, price FROM memberships");
                            $planQuery->execute();
                            $plans = $planQuery->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($plans as $plan) {
                                echo "<option value='" . htmlspecialchars($plan['price']) . "' data-plan-name='" . htmlspecialchars($plan['plan_name']) . "'>" . htmlspecialchars($plan['plan_name']) . " - Rs." . htmlspecialchars($plan['price']) . "/month</option>";
                            }
                            ?>
                        </select>
                        <input type="hidden" name="amount" id="amount" required>
                        
                        <select name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="PayPal">PayPal</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <!-- Add other payment methods as needed -->
                        </select>
                        <button type="submit" name="add_payment" class="btna2">Add Payment</button>
                    </form>
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
                            // Get the logged-in user's name and email from the session
                            $logged_in_customer = $_SESSION['name'];

                                            // Handle form submission for adding a payment
                            if (isset($_POST['add_payment'])) {
                            $client_name = $_SESSION['name']; // Use session value
                            $email = $_SESSION['email']; // Use session value
                            $amount = $_POST['amount']; // This will be set via JavaScript
                            $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : ''; // Check if payment method is set

                            // Only proceed if the payment method is selected
                            if (empty($payment_method)) {
                                echo "<p>Please select a payment method.</p>";
                            } else {
                                // Prepare SQL statement to insert payment
                                $insertQuery = $conn->prepare("INSERT INTO payments (name, email, amount, payment_date, payment_method) VALUES (:name, :email, :amount, NOW(), :payment_method)");
                                $insertQuery->bindValue(':name', $client_name, PDO::PARAM_STR);
                                $insertQuery->bindValue(':email', $email, PDO::PARAM_STR);
                                $insertQuery->bindValue(':amount', $amount, PDO::PARAM_INT);
                                $insertQuery->bindValue(':payment_method', $payment_method, PDO::PARAM_STR);

                                // Execute the insert query
                                if ($insertQuery->execute()) {
                                    echo "<p>Payment added successfully!</p>";
                                } else {
                                    echo "<p>Failed to add payment. Please try again.</p>";
                                }
                            }
                            }

                            // Fetch payment details for the logged-in user
                            if (isset($_POST['search_payment'])) {
                                $search_client = $_POST['search_client'];
                                $query = $conn->prepare("SELECT * FROM payments WHERE name = :name AND name LIKE :search_name");
                                $query->bindValue(':name', $logged_in_customer, PDO::PARAM_STR);
                                $query->bindValue(':search_name', '%' . $search_client . '%', PDO::PARAM_STR);
                            } else {
                                $query = $conn->prepare("SELECT * FROM payments WHERE name = :name");
                                $query->bindValue(':name', $logged_in_customer, PDO::PARAM_STR);
                            }

                            $query->execute();
                            $payments = $query->fetchAll(PDO::FETCH_ASSOC);

                            // Check if there are any payments to display
                            if (count($payments) > 0) {
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
                            } else {
                                echo "<tr><td colspan='6'>No payments found for the logged-in user.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <script>
                // JavaScript to update the amount field based on selected membership plan
                document.getElementById('membership-plan').addEventListener('change', function() {
                    var amount = this.value; // Get the selected plan's price
                    document.getElementById('amount').value = amount; // Set it to the hidden amount input
                });
            </script>

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
                        echo "<div class='description'><p>Features:</p><ul>";

                        // Split the description into an array using a comma as the delimiter
                        $features = explode(',', $membership['description']); // Adjust if necessary

                        // Loop through each feature and display it
                        foreach ($features as $feature) {
                            echo "<li>" . htmlspecialchars(trim($feature)) . "</li>"; // Trim to remove extra spaces
                        }

                        echo "</ul></div>"; // Close the unordered list and description div
                        echo "</div>"; // Close the membership-item div
                    }
                    ?>
                </div>
            </section>


            <!-- Trainer Management Section -->
            <section class="dashboard-section" id="trainers">
                <h2>Trainers Management</h2>

                <!-- Add Trainer Form and Search Bar -->
                <div class="action-container">
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
                                        <a href='viewTrainer_client.php?id=" . $trainer['id'] . "' class='btn'>View</a>
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


                <!-- Table for displaying sessions -->
                <div class="popular__grid">
                    <?php
                        // Query to fetch non-expired sessions from the database
                        $sql = "SELECT session_id, session_name, session_type, start_time, trainer_name, current_participants, max_participants 
                                FROM sessions 
                                WHERE start_time > NOW()"; // Fetch only sessions in the future

                        $result = $conn->query($sql);

                        // Display session details dynamically
                        if ($result && $result->rowCount() > 0) {
                            // Loop through each row of the results
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                $session_full = $row["current_participants"] >= $row["max_participants"];
                                
                                // Create session registration link or disable if full
                                echo "<a href='" . ($session_full ? "#" : "client_session.php?session_id=" . $row['session_id']) . "'>";
                                
                                echo "<div class='popular__card'>";
                                echo "<div>";
                                echo "<h3>" . htmlspecialchars($row["session_name"]) . "</h3>"; // Display the session name
                                echo "<h4>" . htmlspecialchars($row["session_type"]) . "</h4>";
                                echo "<p>Start time: " . htmlspecialchars($row["start_time"]) . "</p>";
                                echo "<p>Trainer: " . htmlspecialchars($row["trainer_name"]) . "</p>";

                                // Display if session is full
                                if ($session_full) {
                                    echo "<p style='color: red;'>Session Full</p>";
                                }

                                echo "</div>";
                                echo "<span><i class='ri-arrow-right-fill'></i></span>";
                                echo "</div>";
                                echo "</a>";  // Close the <a> tag here
                            }
                        } else {
                            echo "No sessions available.";
                        }
                    ?>
                </div>
            </section>

            <!-- Registered Sessions Section -->
            <section class="dashboard-section" id="re_sessions">
                <h2>Registered Sessions</h2>
                
                <div class="table-wrapper">
                    <table class="queries-table">
                        <thead>
                            <tr>
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
                            $logged_in_user = $_SESSION['name']; // You can also use email ($_SESSION['email']) if you prefer.

                            // Fetch registered sessions for the logged-in user
                            $query = $conn->prepare("SELECT * FROM registrations WHERE user_name = :user_name");
                            $query->bindParam(':user_name', $logged_in_user, PDO::PARAM_STR);
                            $query->execute();
                            $registrations = $query->fetchAll(PDO::FETCH_ASSOC);

                            // Display each registered session
                            if ($registrations) {
                                foreach ($registrations as $registration) {
                                    echo "<tr>";
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
                <h2>Current Promotions</h2>

                <div class="table-wrapper">
                    <table class="promotions-table">
                        <thead>
                            <tr>
                                <th>Promotion Title</th>
                                <th>Description</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch current promotions where end_date is greater than the current date
                            $currentDate = date('Y-m-d'); // Get the current date in Y-m-d format
                            $query = $conn->prepare("SELECT * FROM promotions WHERE end_date > :current_date");
                            $query->bindParam(':current_date', $currentDate);
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
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No current promotions available.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </main>

        <div class="footer__bar">
            Copyright © 2024 Web Design Mastery. All rights reserved.
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
                    section.style.display = 'none'; // Hide all sections
                });

                var activeSection = document.getElementById(sectionId);
                if (activeSection) {
                    activeSection.style.display = 'block'; // Show the clicked section
                }
            }

            document.querySelectorAll('.sidebar ul li a').forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the default anchor behavior
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



