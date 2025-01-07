<?php 
include '../fitness/components/connect.php';
session_start(); // Start the session to store success message

// Fetching membership plans from the database
$select_plans = $conn->prepare("SELECT * FROM `memberships`");
$select_plans->execute();
$plans = $select_plans->fetchAll(PDO::FETCH_ASSOC);

$warning_msg = []; // Initialize an array to store error messages

if(isset($_POST['submit'])) {
    $id = unique_id(); // Assuming this function generates a unique ID
    
    // Fetching and sanitizing user inputs
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    // Date of birth and gender sanitization
    $dob = filter_var($_POST['dob'], FILTER_SANITIZE_STRING);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);

    // New fields for specializations and experience
    $specializations = filter_var($_POST['specializations'], FILTER_SANITIZE_STRING);
    $experience = filter_var($_POST['experience'], FILTER_SANITIZE_STRING);

    // Fetching and sanitizing membership plan
    $plan = filter_var($_POST['membership_plan'], FILTER_SANITIZE_STRING);

    // File processing
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = unique_id() . "." . $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_trainer/' . $rename;

    // Checking if email already exists
    $select_trainer = $conn->prepare("SELECT * FROM `trainers` WHERE email = ?");
    $select_trainer->execute([$email]);

    if ($select_trainer->rowCount() > 0) {
        $warning_msg[] = 'Email already exists!';
    } elseif (strlen($_POST['pass']) < 6) {
        $warning_msg[] = 'Password must be at least 6 characters long!';
    } elseif ($pass != $cpass) {
        $warning_msg[] = 'Confirm password does not match!';
    } else {
        // Inserting new trainer into the database
        $insert_trainer = $conn->prepare("INSERT INTO `trainers` (id, name, email, password, image, dob, gender, specializations, experience) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($insert_trainer->execute([$id, $name, $email, $pass, $rename, $dob, $gender, $specializations, $experience])) {
            // Move the uploaded image to the specified folder
            if (move_uploaded_file($image_tmp_name, $image_folder)) {
                // Set success message in session
                $_SESSION['success_msg'] = 'New trainer registered! Please log in now.';
                
                // Redirect to admin.php after successful registration
                header('Location: admin.php');
                exit(); // Ensure that the script stops execution after redirection
            } else {
                $warning_msg[] = 'Failed to upload image. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnessZone_TrainerRegister</title>
    <link rel="shortcut icon" href="./favivon.png" type="image/svg+xml">
    <link rel="stylesheet" type="text/css" href="../fitness/css/registers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        /* Style for the password hint */
        .password-hint {
            display: none;
            color: #ff0000;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <div class="form-container">

        <?php 
        // Display success message if set
        if (isset($_SESSION['success_msg'])) { 
            echo '<div class="success-messages"><p>' . $_SESSION['success_msg'] . '</p></div>';
            unset($_SESSION['success_msg']); // Clear the message after displaying
        } 
        ?>

        <form action="" method="post" enctype="multipart/form-data" class="register" onsubmit="return validateForm()">
            <h3>Register Trainer</h3>

            <div class="flex">
                <div class="col">
                    <div class="input-field">
                        <p>Your Name<span>*</span></p>
                        <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Your Email<span>*</span></p>
                        <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
                    </div>
                </div>
                <div class="col">
                    <div class="input-field">
                        <p>Your Password<span>*</span></p>
                        <input type="password" id="password" name="pass" placeholder="Enter your password" maxlength="50" minlength="6" required class="box">
                        <p class="password-hint" id="password-hint">Password must be at least 6 characters long.</p>
                    </div>
                    <div class="input-field">
                        <p>Confirm Password<span>*</span></p>
                        <input type="password" id="confirm-password" name="cpass" placeholder="Confirm your password" maxlength="50" minlength="6" required class="box">
                    </div>
                </div>  
            </div>
            <div class="input-field">
                <p>Date of Birth<span>*</span></p>
                <input type="date" name="dob" required class="box">
            </div>
            <div class="input-field">
                <p>Gender<span>*</span></p>
                <select name="gender" required class="box">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="input-field">
                <p>Specializations<span>*</span></p>
                <input type="text" name="specializations" placeholder="Enter specializations" maxlength="100" required class="box">
            </div>
            <div class="input-field">
                <p>Experience (in years)<span>*</span></p>
                <input type="number" name="experience" placeholder="Enter experience in years" min="0" required class="box">
            </div>
            <div class="input-field">
                <p>Membership Plan<span>*</span></p>
                <select name="membership_plan" required class="box">
                    <?php foreach ($plans as $plan) { ?>
                        <option value="<?= $plan['id']; ?>">
                            <?= $plan['plan_name']; ?> - Rs.<?= $plan['price']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="input-field">
                <p>Your Profile Picture<span>*</span></p>
                <input type="file" name="image" accept="image/*" required class="box">
            </div>
            <input type="submit" name="submit" value="Register Now" class="btn">
            <input type="button" name="cancel" value="Cancel" class="btn" style="margin-top: 20px;" onclick="window.location.href='admin.php';">
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../fitness/js/register.js"></script>
    <?php include '../fitness/components/alert.php'; ?>

    <!-- JavaScript Validation -->
    <script>
        function validateForm() {
            // Get form inputs
            const name = document.querySelector('input[name="name"]').value.trim();
            const email = document.querySelector('input[name="email"]').value.trim();
            const password = document.querySelector('#password').value.trim();
            const confirmPassword = document.querySelector('#confirm-password').value.trim();

            // Validate if fields are not empty
            if (!name || !email || !password || !confirmPassword) {
                alert("Please fill out all required fields.");
                return false;
            }

            // Validate password length
            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }

            // Validate password and confirm password match
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            return true; // Submit form if all validations pass
        }

        // Show password hint when password field is focused
        const passwordField = document.getElementById('password');
        const passwordHint = document.getElementById('password-hint');

        passwordField.addEventListener('focus', () => {
            passwordHint.style.display = 'block';
        });

        passwordField.addEventListener('blur', () => {
            passwordHint.style.display = 'none';
        });
    </script>
</body>
</html>

