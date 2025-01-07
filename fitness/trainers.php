<?php 
session_start();
if (!isset($_SESSION['name'])) {
    header('location:trainerLogin.php');
    exit();
}


$profile_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';
$profile_image = isset($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg';

// Include the database connection
include '../fitness/components/connect.php';

$logged_in_trainer = $_SESSION['name'];
$query = $conn->prepare("SELECT * FROM trainers WHERE name = :trainer_name");
$query->bindParam(':trainer_name', $logged_in_trainer, PDO::PARAM_STR);
$query->execute();
$trainer = $query->fetch(PDO::FETCH_ASSOC);

// Update profile handling
// Update profile handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Validate and sanitize inputs
    $name = trim($_POST['name']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $specializations = trim($_POST['specializations']);
    $experience = (int)$_POST['experience']; // Cast to integer for safety

    // Update the trainer's profile in the database, excluding the email
    $update_query = $conn->prepare("
        UPDATE trainers 
        SET name = :name, dob = :dob, gender = :gender, 
            specializations = :specializations, experience = :experience 
        WHERE name = :trainer_name
    ");
    $update_query->bindParam(':name', $name, PDO::PARAM_STR);
    $update_query->bindParam(':dob', $dob, PDO::PARAM_STR);
    $update_query->bindParam(':gender', $gender, PDO::PARAM_STR);
    $update_query->bindParam(':specializations', $specializations, PDO::PARAM_STR);
    $update_query->bindParam(':experience', $experience, PDO::PARAM_INT);
    $update_query->bindParam(':trainer_name', $logged_in_trainer, PDO::PARAM_STR);

    if ($update_query->execute()) {
        $_SESSION['success_msg'] = 'Profile updated successfully!';
        header("Location: trainers.php"); // Redirect to your profile page
        exit();
    } else {
        // Handle error
        echo "<p>Failed to update profile. Please try again.</p>";
    }
}


if (isset($_SESSION['success_msg'])) {
    $success_msg = $_SESSION['success_msg'];
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const successMessage = document.getElementById("success-message");
            successMessage.innerHTML = "' . addslashes(htmlspecialchars($success_msg)) . '"; // Display the success message
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
} else {
    // If there's no message to display, ensure the div is hidden
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const successMessage = document.getElementById("success-message");
            successMessage.style.display = "none"; // Ensure the message div is hidden
        });
    </script>';
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
    <title>FitnessZone-TrainerPanel</title>
    <link rel="shortcut icon" href="./favivon.png" type="image/svg+xml">
  <link rel="stylesheet" href="../fitness/css/trainers.css">
</head>
<body>

<div class="sidebar">
    <h2>Trainer</h2>
    <div class="profile-section">
        <img src="../uploaded_trainer/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="profile-image">
    </div>
        <ul>
            <li><a href="#appointments">Appointments</a></li>
            <li><a href="#queries">Queries</a></li>
            <li><a href="#profile">Profile</a></li>
            <li><a href="#trainers">Trainers</a></li>
            <li><a href="#sessions">Sessions</a></li>
            <li><a href="#re_sessions">Client-Sessions</a></li>
            <div class="nav__btns">
                <form action="trainerLogout.php" method="post">
                <button class="btn" type="submit">Logout</button>
                </form>
            </div>
        </ul>
    <div class="trainer-email">
        <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
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
        </nav>
        <div id="success-message" style="display: none;"></div>
    </header>

    <main>
        <!-- Appointments Management Section -->
        <section class="dashboard-section" id="appointments">
            <h2>Appointments Management</h2>
                <div class="table-wrapper">
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
                        // Ensure the trainer is logged in and their name is stored in the session
                        if (isset($_SESSION['name'])) {
                            $logged_in_trainer = $_SESSION['name'];
                        
                            // Fetch only the logged-in trainer's appointments
                            $query = $conn->prepare("SELECT * FROM appointments WHERE trainer_name = :trainer_name");
                            $query->bindParam(':trainer_name', $logged_in_trainer, PDO::PARAM_STR);
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
                        } else {
                            echo "<tr><td colspan='8'>No appointments available for this trainer.</td></tr>";
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

        <!-- Trainer Profile Section -->
        <div class="dashboard-section" id="profile">
            <h2>Trainer Profile</h2>
            <div class="profile-details">
                <form action="" method="post" class="profileForm">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($trainer['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($trainer['email']); ?>" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($trainer['dob']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender" required>
                            <option value="Male" <?php echo $trainer['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $trainer['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo $trainer['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="specializations">Specializations:</label>
                        <input type="text" id="specializations" name="specializations" value="<?php echo htmlspecialchars($trainer['specializations']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="experience">Experience (in years):</label>
                        <input type="number" id="experience" name="experience" value="<?php echo htmlspecialchars($trainer['experience']); ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="pUpdate">Update Profile</button>
                </form>
            </div>
        </div>

        <!-- Trainer Management Section -->
        <section class="dashboard-section" id="trainers">
            <h2>Trainers Management</h2>

            <!-- Add Trainer Form and Search Bar -->
            <div class="action-container">
                <div class="search">
                    <form action="trainers.php#trainers" method="get" style="display: inline-block;">
                        <input type="text" name="search_query" placeholder="Search Trainer by Name" class="searcht_input" >
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
                        $search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';

                        // Prepare the SQL query based on the search query
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

                        // Display trainers
                        if (count($trainers) > 0) {
                            foreach ($trainers as $trainer) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($trainer['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($trainer['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($trainer['specializations']) . "</td>";
                                echo "<td>
                                        <form action='delete_trainer.php' method='post' style='display:inline-block;'>
                                            <a href='viewtrainer.php?id=" . $trainer['id'] . "' class='btnts'>View</a>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No trainers found.</td></tr>"; // Message when no trainers are found
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
                

                <!-- Add Search Form -->
                <div class="search">
                    <form action="trainers.php#sessions" method="get" style="display: inline-block;">
                        <input type="text" name="search_trainer" value="<?php echo isset($_GET['search_trainer']) ? htmlspecialchars($_GET['search_trainer']) : ''; ?>" placeholder="Search by Trainer Name" style="display: inline-block; width: auto;" class="input">
                        <input type="text" name="search_type" value="<?php echo isset($_GET['search_type']) ? htmlspecialchars($_GET['search_type']) : ''; ?>" placeholder="Search by Session Type" style="display: inline-block; width: auto;" class="input">
                        <select name="search_status" style="display: inline-block; width: auto; margin-right: 10px;">
                            <option value="">Select Status</option>
                            <option value="Pending" <?php echo (isset($_GET['search_status']) && $_GET['search_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Expired" <?php echo (isset($_GET['search_status']) && $_GET['search_status'] == 'Expired') ? 'selected' : ''; ?>>Expired</option>
                        </select>
                        <button type="submit" class="btnts" style="display: inline-block;">Search</button>
                    </form>
                    <div class="action-container">
                        <a href="../fitness/register_sessions.php" class="btnSm">Add Session</a>
                    </div>
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
                            echo "<tr><td colspan='6'>No sessions found.</td></tr>";
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

                $logged_in_trainer = $_SESSION['name'];

                // Fetch registered sessions for the logged-in trainer
                $query = $conn->prepare("SELECT * FROM registrations WHERE trainer_name = :trainer_name");
                $query->bindParam(':trainer_name', $logged_in_trainer, PDO::PARAM_STR);
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
                    echo "<tr><td colspan='7'>No sessions registered for this trainer.</td></tr>";
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
    <script src="../fitness/js/trainer.js"></script>
    <script src="../fitness/js/"></script>
</body>
</html>



