<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

if ($myconn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $myconn->connect_error]);
    exit;
}

$myconn->set_charset("utf8");

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['orderId']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    exit;
}

$orderId = $myconn->real_escape_string($data['orderId']);
$newStatus = $myconn->real_escape_string($data['status']);

// Kiểm tra xem $newStatus có nằm trong danh sách enum hợp lệ không
$validStatuses = ['pending', 'processing', 'shipped', 'completed', 'canceled'];
if (!in_array($newStatus, $validStatuses)) {
    echo json_encode(['success' => false, 'error' => 'Invalid status value']);
    exit;
}

$sql = "UPDATE orders SET OrderStatus = '$newStatus' WHERE OrderID = '$orderId'";
if ($myconn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Update failed: ' . $myconn->error]);
}

$myconn->close();
?>