<?php
session_start();
include '../fitness/components/connect.php';

// Ensure the admin is logged in
if (!isset($_SESSION['username'])) {
    header('location:adminLogin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_member'])) {
        $member_id = $_POST['member_id'];
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $dob = $_POST['dob'];
        $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
        $membership_plan = filter_var($_POST['membership_plan'], FILTER_SANITIZE_NUMBER_INT);

        // Handle profile image update if a new image is uploaded
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $image = filter_var($image, FILTER_SANITIZE_STRING);
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $rename = unique_id() . "." . $ext;
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_folder = '../uploaded_files/' . $rename;

            // Move the uploaded image to the specified folder
            if (move_uploaded_file($image_tmp_name, $image_folder)) {
                // Update the image field in the database
                $update_image = $conn->prepare("UPDATE sellers SET image = ? WHERE id = ?");
                $update_image->execute([$rename, $member_id]);
            } else {
                echo "Failed to upload image. Please try again.";
                exit();
            }
        }

        // Update member data
        $update_member = $conn->prepare("UPDATE sellers SET name = ?, email = ?, dob = ?, gender = ?, membership_plan = ? WHERE id = ?");
        $update_member->execute([$name, $email, $dob, $gender, $membership_plan, $member_id]);

        // Store success message in session
        $_SESSION['success_msg'] = "Member updated successfully.";

        // Redirect to admin.php
        header('Location: admin.php');
        exit();
    } else {
        echo "Invalid request.";
    }
} else {
    echo "Invalid request method.";
}
?>
