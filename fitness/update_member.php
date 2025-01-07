<?php
session_start();
include '../fitness/components/connect.php';

// Ensure the admin is logged in
if (!isset($_SESSION['username'])) {
    header('location:adminLogin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['member_id'])) {
        $member_id = $_POST['member_id'];

        // Fetch the member's data
        $query = $conn->prepare("SELECT * FROM sellers WHERE id = :id");
        $query->bindParam(':id', $member_id);
        $query->execute();
        $member = $query->fetch(PDO::FETCH_ASSOC);

        // Fetch membership plans
        $select_plans = $conn->prepare("SELECT * FROM memberships");
        $select_plans->execute();
        $plans = $select_plans->fetchAll(PDO::FETCH_ASSOC);

        if ($member) {
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>FitnessZone-MembershipUpdate</title>
                <link rel="shortcut icon" href="./favivon.png" type="image/svg+xml">
                <link rel="stylesheet" href="../fitness/css/updateMember.css">
            </head>
            <body>
                <div class="memberhead">
                    <h2>Edit Member Details</h2>
                    <div class="member">
                        <form action="update_member_process.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="member_id" value="<?php echo htmlspecialchars($member['id']); ?>">

                            <label for="name">Name:</label>
                            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($member['name']); ?>" required>

                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>

                            <label for="dob">Date of Birth:</label>
                            <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($member['dob']); ?>" required>

                            <label for="gender">Gender:</label>
                            <select name="gender" id="gender" required>
                                <option value="Male" <?php if ($member['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                <option value="Female" <?php if ($member['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                            </select>

                            <label for="membership_plan">Membership Plan:</label>
                            <select name="membership_plan" id="membership_plan" required>
                                <?php foreach ($plans as $plan) { ?>
                                    <option value="<?php echo $plan['id']; ?>" <?php if ($member['membership_plan'] == $plan['id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($plan['plan_name']); ?> - Rs.<?php echo htmlspecialchars($plan['price']); ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <label for="image">Profile Image:</label>
                            <input type="file" name="image" id="image" accept="image/*">

                            <button type="submit" name="update_member" class='btna2'>Update Member</button>
                        </form>
                    </div>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "Member not found.";
        }
    } else {
        echo "Invalid request.";
    }
} else {
    echo "Invalid request method.";
}
?>
