<?php
// Connect to the database
require_once(__DIR__ . '/connection.php');


// Query the database
$stmt = $connection->prepare("SELECT * FROM items");
$stmt->execute();
$result = $stmt->get_result();

// Fetch and store data in an array
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

$connection->close();
// Close the database connection

?>