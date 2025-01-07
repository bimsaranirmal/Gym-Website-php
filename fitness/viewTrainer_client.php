<?php
include '../fitness/components/connect.php';

if (isset($_GET['id'])) {
    $trainer_id = $_GET['id'];

    // Fetch the trainer's details
    $select_trainer = $conn->prepare("SELECT * FROM trainers WHERE id = ?");
    $select_trainer->execute([$trainer_id]);
    $trainer = $select_trainer->fetch(PDO::FETCH_ASSOC);

    if (!$trainer) {
        // Handle the case when the trainer is not found
        header('Location: trainers.php');
        exit();
    }
} else {
    // Redirect if no ID is set
    header('Location: trainers.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnessZone-TrainerDetails</title>
    <link rel="shortcut icon" href="./favivon.png" type="image/svg+xml">
    <link rel="stylesheet" type="text/css" href="../fitness/css/viewTrainer.css">
</head>
<body>

<div class="trainer-details">
    <h3>Trainer Details</h3>
    <p><strong>Name:</strong> <?= htmlspecialchars($trainer['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($trainer['email']) ?></p>
    <p><strong>Date of Birth:</strong> <?= htmlspecialchars($trainer['dob']) ?></p>
    <p><strong>Gender:</strong> <?= htmlspecialchars($trainer['gender']) ?></p>
    <p><strong>Specializations:</strong> <?= htmlspecialchars($trainer['specializations']) ?></p>
    <p><strong>Experience:</strong> <?= htmlspecialchars($trainer['experience']) ?> years</p>
    <img src="../uploaded_trainer/<?= htmlspecialchars($trainer['image']) ?>" alt="Trainer Image" style="width:200px;height:auto;">
    
    <a href="client.php" class="btn">Back</a>
</div>

</body>
</html>
