<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "webdb";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) die("Lỗi kết nối: " . $conn->connect_error);

$data = [];


if (!isset($_GET['province_id'])) {
    echo "Thiếu province_id";
    exit;
}

if (isset($_GET['province_id'])) {
    $province_id = intval($_GET['province_id']);

    $stmt = $conn->prepare("SELECT district_id, name FROM districts WHERE province_id = ?");
    $stmt->bind_param("i", $province_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = ['id' => $row['district_id'], 'name' => $row['name']];
    }

    $stmt->close();
}

echo json_encode($data);
$conn->close();
?>
