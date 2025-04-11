<?php
header('Content-Type: application/json');
include 'connect.php';

if ($myconn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $myconn->connect_error]);
    exit;
}
$myconn->set_charset("utf8");

// Lấy các tham số từ request
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
$offset = ($page - 1) * $limit;

$dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
$orderStatus = isset($_GET['order_status']) ? $_GET['order_status'] : '';
$provinceId = isset($_GET['province_id']) ? intval($_GET['province_id']) : 0;
$districtId = isset($_GET['district_id']) ? intval($_GET['district_id']) : 0;

// Sửa phần query SELECT 
$selectQuery = "SELECT 
    o.OrderID AS madonhang,
    o.DateGeneration AS ngaytao,
    o.Status AS trangthai,
    o.TotalAmount AS giatien,
    u.FullName AS buyer_name,
    o.CustomerName AS receiver_name,
    o.Address AS receiver_address,
    dr.name AS shipping_district,  
    pr.name AS shipping_province     
FROM orders o
LEFT JOIN users u ON o.Username = u.Username
LEFT JOIN province pr ON o.Province = pr.province_id 
LEFT JOIN district dr ON o.District = dr.district_id
WHERE 1=1";

$params = [];
$types = '';

if ($dateFrom) {
    $selectQuery .= " AND o.DateGeneration >= ?";
    $params[] = $dateFrom;
    $types .= 's';
}

if ($dateTo) {
    $selectQuery .= " AND o.DateGeneration <= ?";
    $params[] = $dateTo;
    $types .= 's';
}

if ($orderStatus && $orderStatus !== 'all') {
    $selectQuery .= " AND o.Status = ?";
    $params[] = $orderStatus;
    $types .= 's';
}

if ($provinceId > 0) {
    $selectQuery .= " AND o.Province = ?";
    $params[] = $provinceId;
    $types .= 'i';
}

if ($districtId > 0) {
    $selectQuery .= " AND o.District = ?";
    $params[] = $districtId;
    $types .= 'i';
}

$selectQuery .= " ORDER BY o.DateGeneration DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= 'ii';

// Sửa lại phần xử lý query đếm
$countQuery = "SELECT COUNT(*) as total FROM orders o";

// Thêm các JOIN cần thiết
$countQuery .= " LEFT JOIN users u ON o.Username = u.Username
                 LEFT JOIN province pr ON o.Province = pr.province_id
                 LEFT JOIN district dr ON o.District = dr.district_id
                 WHERE 1=1";

// Thêm điều kiện WHERE (không bao gồm LIMIT và OFFSET)
if ($dateFrom) {
    $countQuery .= " AND o.DateGeneration >= ?";
}

if ($dateTo) {
    $countQuery .= " AND o.DateGeneration <= ?";
}

if ($orderStatus && $orderStatus !== 'all') {
    $countQuery .= " AND o.Status = ?";
}

if ($provinceId > 0) {
    $countQuery .= " AND o.Province = ?";
}

if ($districtId > 0) {
    $countQuery .= " AND o.District = ?";
}

$countParams = [];
$countTypes = '';

if ($dateFrom) {
    $countParams[] = $dateFrom;
    $countTypes .= 's';
}

if ($dateTo) {
    $countParams[] = $dateTo;
    $countTypes .= 's';
}

if ($orderStatus && $orderStatus !== 'all') {
    $countParams[] = $orderStatus;
    $countTypes .= 's';
}

if ($provinceId > 0) {
    $countParams[] = $provinceId;
    $countTypes .= 'i';
}

if ($districtId > 0) {
    $countParams[] = $districtId;
    $countTypes .= 'i';
}

$stmt = $myconn->prepare($countQuery);
if (!empty($countParams)) {
    $stmt->bind_param($countTypes, ...$countParams);
}

$stmt->execute();
$totalRecords = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);

$stmt = $myconn->prepare($selectQuery);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $receiver_address_parts = array_filter([
        $row['receiver_address'],
        $row['shipping_district'],
        $row['shipping_province']
    ]);

    $orders[] = [
        'madonhang' => $row['madonhang'],
        'ngaytao' => $row['ngaytao'],
        'trangthai' => $row['trangthai'],
        'giatien' => $row['giatien'],
        'buyer_name' => $row['buyer_name'] ?? 'Không xác định',
        'receiver_name' => $row['receiver_name'] ?? 'Không xác định',
        'shipping_district' => $row['shipping_district'] ?? '',
        'shipping_province' => $row['shipping_province'] ?? '',
        'receiver_address' => implode(', ', $receiver_address_parts)
    ];
}

echo json_encode([
    'success' => true,
    'orders' => $orders,
    'total_pages' => $totalPages,
    'current_page' => $page
]);

$stmt->close();
$myconn->close();
?>