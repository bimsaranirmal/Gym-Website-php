<?php
include '../fitness/components/connect.php';

if (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];

    // Fetch member details
    $query = $conn->prepare("SELECT * FROM sellers WHERE id = :id");
    $query->bindParam(':id', $member_id);
    $query->execute();
    $member = $query->fetch(PDO::FETCH_ASSOC);

    // Fetch membership plans
    $select_plans = $conn->prepare("SELECT * FROM memberships");
    $select_plans->execute();
    $plans = $select_plans->fetchAll(PDO::FETCH_ASSOC);

    if ($member) {
        // Send JSON response with member data and membership plans
        echo json_encode([
            'id' => $member['id'],
            'name' => $member['name'],
            'email' => $member['email'],
            'dob' => $member['dob'],
            'gender' => $member['gender'],
            'membership_plan' => $member['membership_plan'],
            'plans' => $plans
        ]);
    } else {
        echo json_encode(['error' => 'Member not found.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>
