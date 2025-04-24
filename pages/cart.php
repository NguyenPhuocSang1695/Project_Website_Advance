<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false]); exit;
}

$id = $data['id'];
$name = $data['name'];
$price = $data['price'];
$image = $data['image'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['ProductID'] == $id) {
        $item['Quantity']++;
        $found = true;
        break;
    }
}
if (!$found) {
    $_SESSION['cart'][] = [
        'ProductID' => $id,
        'ProductName' => $name,
        'Price' => $price,
        'ImageURL' => $image,
        'Quantity' => 1
    ];
}

// Đếm lại số lượng
$cart_count = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_count += $item['Quantity'];
}

// Tạo lại HTML dropdown
$cart_html = '';
foreach ($_SESSION['cart'] as $item) {
    $cart_html .= '
    <div class="cart-item">
        <img src="..' . $item['ImageURL'] . '" alt="' . $item['ProductName'] . '" class="cart-thumb"/>
        <div class="cart-item-details">
            <h5>' . $item['ProductName'] . '</h5>
            <p>Giá: ' . number_format($item['Price'], 0, ',', '.') . ' VNĐ</p>
            <p>' . $item['Quantity'] . ' × ' . number_format($item['Price'], 0, ',', '.') . ' VNĐ</p>
        </div>
    </div>';
}

echo json_encode([
    'success' => true,
    'cart_count' => $cart_count,
    'cart_html' => $cart_html
]);
