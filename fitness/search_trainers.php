<?php
session_start();
include '../fitness/components/connect.php';

// Fetch trainers based on search
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = $_GET['search_query'];
    $query = $conn->prepare("SELECT * FROM trainers WHERE name LIKE :name");
    $query->bindValue(':name', '%' . $search_query . '%', PDO::PARAM_STR);
    $query->execute();
} else {
    $query = $conn->prepare("SELECT * FROM trainers");
    $query->execute();
}

$trainers = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($trainers as $trainer) {
    echo "<tr id='trainer-".$trainer['id']."'>";
    echo "<td>" . htmlspecialchars($trainer['name']) . "</td>";
    echo "<td>" . htmlspecialchars($trainer['email']) . "</td>";
    echo "<td>" . htmlspecialchars($trainer['specializations']) . "</td>";
    echo "<td>
            <form class='delete-trainer-form' method='POST' style='display:inline-block;'>
                <input type='hidden' name='trainer_id' value='" . $trainer['id'] . "'>
                <button type='button' class='delete-btn btna1' data-id='" . $trainer['id'] . "'>Delete</button>
            </form>
            <a href='view_trainer.php?id=" . $trainer['id'] . "' class='btn'>View</a>
          </td>";
    echo "</tr>";
}
?>
