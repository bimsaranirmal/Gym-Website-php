<?php
session_start();
include '../fitness/components/connect.php';

// Ensure the admin is logged in
if (!isset($_SESSION['username'])) {
    header('location:adminLogin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $membership_id = $_POST['membership_id'];

    // Fetch the membership plan details for editing
    $query = $conn->prepare("SELECT * FROM memberships WHERE id = :id");
    $query->bindParam(':id', $membership_id);
    $query->execute();
    $membership = $query->fetch(PDO::FETCH_ASSOC);

    // If the membership plan exists
    if ($membership) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>FitnessZone-MembershipManage</title>
            <link rel="shortcut icon" href="./favivon.png" type="image/svg+xml">
            <link rel="stylesheet" href="../fitness/css/membership.css">
        </head>
        <body class="b">
            <div class="memberhead">
                <h2>Edit Membership Plan</h2>
                <div class="member">
                    <form action="update_membership_process.php" method="post">
                        <input type="hidden" name="membership_id" value="<?php echo htmlspecialchars($membership['id']); ?>">

                        <label for="plan_name">Plan Name:</label>
                        <input type="text" name="plan_name" id="plan_name" value="<?php echo htmlspecialchars($membership['plan_name']); ?>" required>

                        <label for="price">Price ($):</label>
                        <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($membership['price']); ?>" required>

                        <label for="description">Description:</label>
                        <textarea name="description" id="description" required><?php echo htmlspecialchars($membership['description']); ?></textarea>

                        <button type="submit" class="btna2">Update Membership Plan</button>
                    </form>

                    <form action="delete_membership_process.php" method="post">
                        <input type="hidden" name="membership_id" value="<?php echo htmlspecialchars($membership['id']); ?>">
                        <button type="submit" class="btna1" onclick="return confirm('Are you sure you want to delete this membership plan?');">Delete Membership Plan</button>
                    </form>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Membership plan not found.";
    }
    exit();
}
?>
