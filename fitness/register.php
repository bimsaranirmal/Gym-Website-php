<?php 
    include '../fitness/components/connect.php';
    include '../fitness/components/alert.php';

    // Fetching membership plans from the database
    $select_plans = $conn->prepare("SELECT * FROM `memberships`");
    $select_plans->execute();
    $plans = $select_plans->fetchAll(PDO::FETCH_ASSOC);

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

        // Fetching and sanitizing membership plan
        $plan = filter_var($_POST['membership_plan'], FILTER_SANITIZE_STRING);

        // File processing
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = unique_id() . "." . $ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/' . $rename;

        // Checking if email already exists
        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE email = ?");
        $select_seller->execute([$email]);

        if ($select_seller->rowCount() > 0) {
            $warning_msg[] = 'Email already exists!';
        } else {
            // Check if passwords match
            if (strlen($_POST['pass']) < 6) {
                $warning_msg[] = 'Password must be at least 6 characters long!';
            } elseif ($pass != $cpass) {
                $warning_msg[] = 'Confirm password does not match!';
            } else {
                // Proceed with the registration process
                // Ensure directory exists before moving the file
                if (!is_dir('../uploaded_files/')) {
                    mkdir('../uploaded_files/', 0777, true); // Create directory if it doesn't exist
                }
            
                // Inserting new seller into the database
                $insert_seller = $conn->prepare("INSERT INTO `sellers` (id, name, email, password, image, dob, gender, membership_plan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_seller->execute([$id, $name, $email, $pass, $rename, $dob, $gender, $plan]);
            
                // Move the uploaded image to the specified folder
                if (move_uploaded_file($image_tmp_name, $image_folder)) {
                    $success_msg[] = 'New user registered! Please log in now.';
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
    <title>FitnessZone-Register</title>
    <link rel="shortcut icon" href="./favivon.png" type="image/svg+xml">
    <link rel="stylesheet" type="text/css" href="../fitness/css/registers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        .password-hint {
            display: none;
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }

        .input-field:focus-within .password-hint {
            display: block;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form action="" method="post" enctype="multipart/form-data" class="register">
        <h3>Register Now</h3>
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
                    <div class="password-hint">Password must be at least 6 characters.</div> <!-- Hint added here -->
                </div>
                <div class="input-field">
                    <p>Confirm Password<span>*</span></p>
                    <input type="password" name="cpass" placeholder="Confirm your password" maxlength="50" minlength="6" required class="box">
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
            <p>Your Profile<span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
        </div>
        <p class="link">Already have an account? <a href="login.php">Login Now</a></p>
        <input type="submit" name="submit" value="Register Now" class="btn">
        <input type="button" name="cancel" value="Cancel" class="btn" style="margin-top: 20px;" onclick="window.location.href='index.php';">
    </form>
</div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../fitness/js/register.js"></script>
    <?php include '../fitness/components/alert.php'; ?>
</body>
</html>


