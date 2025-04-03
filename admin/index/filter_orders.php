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

$address = isset($_GET['address']) ? $myconn->real_escape_string($_GET['address']) : '';
$date_from = isset($_GET['date_from']) && !empty($_GET['date_from']) ? $myconn->real_escape_string($_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) && !empty($_GET['date_to']) ? $myconn->real_escape_string($_GET['date_to']) : '';
$order_status = isset($_GET['order_status']) ? $myconn->real_escape_string($_GET['order_status']) : 'all';
$district = isset($_GET['district']) ? $myconn->real_escape_string($_GET['district']) : 'all';
$province = isset($_GET['city']) ? $myconn->real_escape_string($_GET['city']) : 'all'; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

$page = max(1, $page);
$limit = max(1, $limit);

$offset = ($page - 1) * $limit;

$conditions = [];
if ($address) {
    $conditions[] = "u.Address LIKE '%$address%'";
}
if ($date_from) {
    $conditions[] = "o.CreatedAt >= '$date_from 00:00:00'";
}
if ($date_to) {
    $conditions[] = "o.CreatedAt <= '$date_to 23:59:59'";
}
if ($order_status !== 'all') {
    $conditions[] = "o.OrderStatus = '$order_status'";
}
if ($district !== 'all') {
    $conditions[] = "u.District = '$district'";
}
if ($province !== 'all') {
    $conditions[] = "u.Province = '$province'";
}

$whereClause = !empty($conditions) ? 'AND ' . implode(' AND ', $conditions) : '';

$countSql = "SELECT COUNT(*) as total 
             FROM orders o 
             JOIN users u ON o.UserID = u.UserID 
             WHERE EXISTS (
                 SELECT 1 
                 FROM orderdetails od 
                 WHERE od.OrderID = o.OrderID
             ) $whereClause";
$countResult = $myconn->query($countSql);
$totalOrders = $countResult ? $countResult->fetch_assoc()['total'] : 0;

$totalPages = ceil($totalOrders / $limit);

$sql = "SELECT o.OrderID, o.TotalAmount, o.OrderStatus, o.CreatedAt, 
               u.FullName, u.Address, u.Province, u.District 
        FROM orders o 
        JOIN users u ON o.UserID = u.UserID
        WHERE EXISTS (
            SELECT 1 
            FROM orderdetails od 
            WHERE od.OrderID = o.OrderID
        ) $whereClause 
        ORDER BY o.CreatedAt DESC 
        LIMIT $limit OFFSET $offset";

$result = $myconn->query($sql);
$orders = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = [
            'fullname' => $row['FullName'],
            'address' => $row['Address'],
            'city' => $row['Province'], 
            'district' => $row['District'],
            'giatien' => number_format($row['TotalAmount'], 0, ',', '.'),
            'madonhang' => $row['OrderID'],
            'ngaytao' => $row['CreatedAt'],
            'trangthai' => $row['OrderStatus'],
        ];
    } 
    $response = [
        'success' => true,
        'orders' => $orders,
        'total_orders' => $totalOrders,
        'total_pages' => $totalPages,
        'current_page' => $page
    ];
} else {
    $response = [
        'success' => false,
        'error' => 'Query failed: ' . $myconn->error,
        'orders' => [],
        'total_orders' => 0,
        'total_pages' => 0,
        'current_page' => $page
    ];
}

echo json_encode($response);
$myconn->close();
?>