<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "c01db");
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Get search term from POST request
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Build the query
$query = "SELECT p.*, c.CategoryName as CategoryName 
          FROM products p 
          LEFT JOIN categories c ON p.CategoryID = c.CategoryID 
          WHERE (p.Status = 'appear' OR p.Status = 'hidden')";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (p.ProductName LIKE '%$search%' OR c.CategoryName LIKE '%$search%' OR p.Description LIKE '%$search%')";
}

$query .= " ORDER BY p.ProductID DESC";

$result = $conn->query($query);

if ($result) {
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['ProductID'],
            'name' => $row['ProductName'],
            'category' => $row['CategoryName'],
            'price' => number_format($row['Price'], 0, ',', '.') . ' VNĐ',
            'image' => '../..' . $row['ImageURL']
        ];
    }
    echo json_encode(['success' => true, 'products' => $products]);
} else {
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
}

$conn->close();
?> 