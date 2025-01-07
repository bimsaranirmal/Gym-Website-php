<?php
session_start();
include '../fitness/components/connect.php';

// Fetch payment details based on search
if (isset($_POST['search_client']) && !empty($_POST['search_client'])) {
    $search_client = $_POST['search_client'];
    $query = $conn->prepare("SELECT * FROM payments WHERE name LIKE :name");
    $query->bindValue(':name', '%' . $search_client . '%', PDO::PARAM_STR);
} else {
    $query = $conn->prepare("SELECT * FROM payments");
}
$query->execute();
$payments = $query->fetchAll(PDO::FETCH_ASSOC);

// Output payment rows
foreach ($payments as $payment) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($payment['id']) . "</td>"; // Display ID
    echo "<td>" . htmlspecialchars($payment['name']) . "</td>"; // Display Client Name
    echo "<td>" . htmlspecialchars($payment['email']) . "</td>"; // Display Email
    echo "<td>" . htmlspecialchars($payment['amount']) . "</td>"; // Display Amount
    echo "<td>" . htmlspecialchars(date('Y-m-d H:i:s', strtotime($payment['payment_date']))) . "</td>"; // Display Payment Date
    echo "<td>" . htmlspecialchars($payment['payment_method']) . "</td>"; // Display Payment Method
    echo "</tr>";
}
?>
