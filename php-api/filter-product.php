<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
// require_once "./db_connect.php"; // Đảm bảo file này tồn tại và được include đúng
$server = "localhost";
$username = "root";
$password = "";
$database = "webdb";

// Create connection
$conn = mysqli_connect($server, $username, $password, $database);
// Check connection


if (!isset($conn)) {
    echo json_encode(["error" => "Database connection error."]);
    exit();
}

if (isset($_GET["category_id"])) {
    $category_id = intval($_GET["category_id"]);

    $stmt = $conn->prepare("SELECT ProductName, Price, DescriptionBrief, ImageURL FROM products WHERE CategoryID = ?");
    if (!$stmt) {
        echo json_encode(["error" => "SQL error: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("i", $category_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode($products);
} else {
    echo json_encode(["error" => "Missing category_id"]);
}

$stmt->close();
$conn->close();
