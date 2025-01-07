<?php
include '../fitness/components/connect.php';

if (isset($_POST['plan_name'])) {
    // Get the form data
    $plan_name = filter_var($_POST['plan_name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Handle file upload
    $cover_image = $_FILES['cover_image']['name'];
    $cover_image = filter_var($cover_image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($cover_image, PATHINFO_EXTENSION);
    $rename = unique_id() . "." . $ext;  // Generate a unique name for the file
    $image_tmp_name = $_FILES['cover_image']['tmp_name'];
    $image_folder = '../uploaded_files/membership_images/' . $rename;

    // Ensure the directory exists
    if (!is_dir('../uploaded_files/membership_images/')) {
        mkdir('../uploaded_files/membership_images/', 0777, true);
    }

    // Insert membership data into the database
    $insert_plan = $conn->prepare("INSERT INTO memberships (plan_name, price, description, cover_image) VALUES (?, ?, ?, ?)");
    $insert_plan->execute([$plan_name, $price, $description, $rename]);

    // Move the uploaded image to the specified folder
    if (move_uploaded_file($image_tmp_name, $image_folder)) {
        $success_msg = "Membership plan added successfully!";
    } else {
        $warning_msg = "Failed to upload cover image. Please try again.";
    }
}

header('Location: admin.php');  // Redirect back to memberships page
exit();
?>
