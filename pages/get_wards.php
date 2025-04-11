<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "webdb";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) die("Lỗi kết nối: " . $conn->connect_error);

$data = [];

if (!isset($_GET['district_id'])) {
    echo "Thiếu district_id";
    exit;
}
if (isset($_GET['district_id'])) {
    $district_id = intval($_GET['district_id']);

    $stmt = $conn->prepare("SELECT wards_id, name FROM wards WHERE district_id = ?");
    $stmt->bind_param("i", $district_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = ['id' => $row['wards_id'], 'name' => $row['name']];
    }
    $stmt->close();
}
echo json_encode($data);
$conn->close();
?>
