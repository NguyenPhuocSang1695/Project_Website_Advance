<?php
header('Content-Type: application/json');

include 'connect.php';

if ($myconn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $myconn->connect_error]));
}

$query = isset($_GET['query']) ? $myconn->real_escape_string($_GET['query']) : '';

$sql = "SELECT DISTINCT District 
        FROM users 
        WHERE District IS NOT NULL";
if ($query) {
    $sql .= " AND District LIKE '%$query%'";
}
$sql .= " ORDER BY District LIMIT 10";

$result = $myconn->query($sql);

$districts = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $districts[] = $row['District'];
    }
} else {
    $districts = ['error' => 'Query failed: ' . $myconn->error];
}

echo json_encode($districts);

$myconn->close();
?>