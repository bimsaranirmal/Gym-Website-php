<?php
include '../fitness/components/connect.php';

$search_member = isset($_GET['search_member']) ? $_GET['search_member'] : '';

if (!empty($search_member)) {
    // Fetch members matching the search query
    $query = $conn->prepare("SELECT sellers.*, memberships.plan_name 
                             FROM sellers 
                             INNER JOIN memberships ON sellers.membership_plan = memberships.id 
                             WHERE sellers.name LIKE ?");
    $query->execute(['%' . $search_member . '%']);
} else {
    // Fetch all members if no search query
    $query = $conn->prepare("SELECT sellers.*, memberships.plan_name 
                             FROM sellers 
                             INNER JOIN memberships ON sellers.membership_plan = memberships.id");
    $query->execute();
}

$members = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($members as $member) {
    echo "<tr id='member-" . htmlspecialchars($member['id']) . "'>";
    echo "<td>" . htmlspecialchars($member['name']) . "</td>";
    echo "<td>" . htmlspecialchars($member['email']) . "</td>";
    echo "<td>" . htmlspecialchars($member['plan_name']) . "</td>";
    echo "<td>
            <button class='delete-btn btna1' data-id='" . htmlspecialchars($member['id']) . "'>Delete</button>
          </td>";
    echo "</tr>";
}
?>
