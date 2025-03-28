<?php
header('Content-Type: application/json');

include 'connect.php';

if ($myconn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Connection failed: ' . $myconn->connect_error]));
}

$data = json_decode(file_get_contents('php://input'), true);
$orderId = $myconn->real_escape_string($data['orderId']);
$newStatus = $myconn->real_escape_string($data['status']);

$sql = "UPDATE orders SET OrderStatus = '$newStatus' WHERE OrderID = '$orderId'";
if ($myconn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Update failed: ' . $myconn->error]);
}

$myconn->close();
?>