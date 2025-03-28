<?php
header('Content-Type: application/json');

include 'connect.php';

if ($myconn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $myconn->connect_error]));
}

// Lấy tham số từ query string
$address = isset($_GET['address']) ? $myconn->real_escape_string($_GET['address']) : '';
$date_from = isset($_GET['date_from']) && !empty($_GET['date_from']) ? $myconn->real_escape_string($_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) && !empty($_GET['date_to']) ? $myconn->real_escape_string($_GET['date_to']) : '';
$order_status = isset($_GET['order_status']) ? $myconn->real_escape_string($_GET['order_status']) : 'all';
$district = isset($_GET['district']) ? $myconn->real_escape_string($_GET['district']) : 'all';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Số trang hiện tại
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5; // Số đơn hàng trên mỗi trang

// Đảm bảo page và limit là số dương
$page = max(1, $page);
$limit = max(1, $limit);

$offset = ($page - 1) * $limit;

// Tạo điều kiện lọc
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

$whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Đếm tổng số đơn hàng phù hợp với điều kiện lọc
$countSql = "SELECT COUNT(*) as total 
             FROM orders o 
             JOIN users u ON o.UserID = u.UserID 
             $whereClause";
$countResult = $myconn->query($countSql);
$totalOrders = $countResult ? $countResult->fetch_assoc()['total'] : 0;

// Tính tổng số trang
$totalPages = ceil($totalOrders / $limit);

// Truy vấn đơn hàng với phân trang
$sql = "SELECT o.OrderID, o.UserID, o.TotalAmount, o.OrderStatus, o.CreatedAt, 
               u.FullName, u.Address 
        FROM orders o 
        JOIN users u ON o.UserID = u.UserID 
        $whereClause 
        ORDER BY o.CreatedAt DESC 
        LIMIT $limit OFFSET $offset";

$result = $myconn->query($sql);

// Mảng chứa danh sách đơn hàng
$orders = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = [
            'madonhang' => $row['OrderID'],
            'tenkhachhang' => $row['FullName'],
            'ngaytao' => $row['CreatedAt'],
            'giatien' => number_format($row['TotalAmount'], 0, ',', '.'),
            'trangthai' => $row['OrderStatus'],
            'diachi' => $row['Address']
        ];
    }
} else {
    $orders = ['error' => 'Query failed: ' . $myconn->error];
}

// Trả về dữ liệu với thông tin phân trang
$response = [
    'orders' => $orders,
    'total_orders' => $totalOrders,
    'total_pages' => $totalPages,
    'current_page' => $page
];

echo json_encode($response);

$myconn->close();
?>