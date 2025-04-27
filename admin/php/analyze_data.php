<?php
header('Content-Type: application/json');
include 'connect.php'; 
if ($myconn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Kết nối database thất bại: ' . $myconn->connect_error]);
    exit;
}

$myconn->set_charset("utf8mb4");

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] . ' 00:00:00' : date('Y-m-01') . ' 00:00:00';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';

// Truy vấn khách hàng mua nhiều nhất
$customer_query = "SELECT 
    u.FullName AS customer_name,
    MAX(o.DateGeneration) AS latest_order_date,
    COUNT(o.OrderID) AS order_count,
    MAX(o.OrderID) AS latest_order_id,
    SUM(o.TotalAmount) AS total_amount,
    GROUP_CONCAT(DISTINCT o.OrderID) AS order_ids
FROM users u
JOIN orders o ON u.Username = o.Username
WHERE o.DateGeneration >= ? AND o.DateGeneration <= ?
    AND o.TotalAmount >= 0
    AND o.Status = 'success'
GROUP BY u.Username, u.FullName
HAVING COUNT(o.OrderID) > 0
ORDER BY order_count DESC, total_amount DESC 
LIMIT 5";

$stmt = $myconn->prepare($customer_query);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$customer_result = $stmt->get_result();
$customers = [];
while ($row = $customer_result->fetch_assoc()) {
    $order_ids = explode(',', $row['order_ids']);
    $order_links = array_map(function($order_id) {
        return [
            'id' => $order_id,
            'url' => "orderDetail2.php?code_Product=" . $order_id
        ];
    }, $order_ids);

    $customers[] = [
        'customer_name' => htmlspecialchars($row['customer_name']),
        'latest_order_date' => $row['latest_order_date'],
        'order_count' => (int)$row['order_count'],
        'latest_order_id' => $row['latest_order_id'],
        'total_amount' => (float)$row['total_amount'],
        'order_links' => $order_links
    ];
}

// Truy vấn mặt hàng bán chạy
$product_query = "SELECT 
    p.ProductID AS product_id,
    p.ProductName AS product_name,
    SUM(od.Quantity) AS quantity_sold,
    SUM(od.TotalPrice) AS total_amount,
    COUNT(DISTINCT od.OrderID) AS invoice_count,
    GROUP_CONCAT(DISTINCT o.OrderID) AS order_ids
FROM products p
JOIN orderdetails od ON p.ProductID = od.ProductID
JOIN orders o ON od.OrderID = o.OrderID
WHERE o.DateGeneration >= ? AND o.DateGeneration <= ?
    AND od.Quantity >= 0
    AND od.TotalPrice >= 0
    AND o.Status = 'success'
GROUP BY p.ProductID, p.ProductName
HAVING SUM(od.Quantity) >= 0
    AND SUM(od.TotalPrice) >= 0
ORDER BY quantity_sold DESC, total_amount DESC
LIMIT 5";

$stmt = $myconn->prepare($product_query);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$product_result = $stmt->get_result();
$products = [];
while ($row = $product_result->fetch_assoc()) {
    $order_ids = explode(',', $row['order_ids']);
    $order_links = array_map(function($order_id) {
        return [
            'id' => $order_id,
            'url' => "orderDetail2.php?code_Product=" . $order_id
        ];
    }, $order_ids);

    $products[] = [
        'product_id' => $row['product_id'],
        'product_name' => htmlspecialchars($row['product_name']),
        'quantity_sold' => (int)$row['quantity_sold'],
        'total_amount' => (float)$row['total_amount'],
        'invoice_count' => (int)$row['invoice_count'],
        'order_links' => $order_links
    ];
}

// Tính tổng doanh thu 
$total_revenue_query = "SELECT 
    SUM(TotalAmount) AS total_revenue,
    COUNT(DISTINCT OrderID) as total_orders,
    COUNT(DISTINCT Username) as total_customers 
FROM orders 
WHERE DateGeneration >= ? AND DateGeneration <= ?
    AND TotalAmount >= 0
    AND Status = 'success'";

$stmt = $myconn->prepare($total_revenue_query);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$total_revenue_result = $stmt->get_result();
$revenue_data = $total_revenue_result->fetch_assoc();
$total_revenue = $revenue_data['total_revenue'] ?? 0;
$total_orders = $revenue_data['total_orders'] ?? 0;
$total_customers = $revenue_data['total_customers'] ?? 0;

$stmt->close();
$myconn->close();

echo json_encode([
    'success' => true,
    'customers' => $customers,
    'products' => $products,
    'total_revenue' => (float)$total_revenue,
    'total_orders' => (int)$total_orders,
    'total_customers' => (int)$total_customers,
    'best_selling' => !empty($products) ? $products[0]['product_name'] : "Chưa có dữ liệu",
    'worst_selling' => !empty($products) ? end($products)['product_name'] : "Chưa có dữ liệu",
    'date_range' => [
        'start' => $start_date,
        'end' => $end_date
    ]
], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
?>