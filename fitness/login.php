<?php 
include '../fitness/components/connect.php';
session_start(); // Start the session

if (isset($_POST['submit'])) {
    // Fetch and sanitize user input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = sha1($_POST['pass']); // Consider using password_hash for better security

    // Check if the email and password match a record in the database
    $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE email = ? AND password = ?");
    $select_seller->execute([$email, $pass]);
    $row = $select_seller->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $_SESSION['success_msg'] = "login successfully!";
        
        // Store user details in session
        $_SESSION['name'] = $row['name']; // Store username
        $_SESSION['image'] = $row['image']; // Store profile image
        $_SESSION['email'] = $row['email']; 
        $_SESSION['user_id'] = $row['id']; // Store user ID

        header('location:client.php'); // Redirect to client page
        exit();
    } else {
        $warning_msg[] = 'Incorrect email or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnessZone-ClientLogin</title>
    <link rel="shortcut icon" href="./favivon.png" type="image/svg+xml">
    <link rel="stylesheet" type="text/css" href="../fitness/css/registers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

<div class="form-container">
    <form action="" method="post" enctype="multipart/form-data" class="login">
        <h3>login now</h3>
        <div class="input-field">
            <p>your email<span>*</span></p>
            <input type="email" name="email" placeholder="enter your email" maxlength="50"
            required class="box">
        </div>
        <div class="input-field">
            <p>your password<span>*</span></p>
            <input type="password" name="pass" placeholder="enter your password" maxlength="50"
            required class="box">
        </div>
        <p class="link">do not have an account?<a href="register.php">register now<a></p>
        <input type="submit" name="submit" value="login now" class="btn"><a href="/fitness/client.php"></a>
        <input type="button" name="cancel" value="Cancel" class="btn" style="margin-top: 20px;" onclick="window.location.href='index.php';">
    </form>   
</div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../fitness/js/register.js"></script>
    <?php include '../fitness/components/alert.php'; ?>
</body>
</html>