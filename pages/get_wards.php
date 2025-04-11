<?php
// get_wards.php: Lấy danh sách Phường/Xã theo district_id
$conn = new mysqli("localhost", "root", "", "webdb");
if ($conn->connect_error) die("Lỗi kết nối: " . $conn->connect_error);

header('Content-Type: application/json');

if (!isset($_GET['district_id'])) {
    echo json_encode(['error' => 'Thiếu district_id']);
    exit;
}

$district_id = intval($_GET['district_id']);

// Giả sử bảng wards có các trường: wards_id, district_id, name
$sql = "SELECT * FROM wards WHERE district_id = {$district_id}";
$result = mysqli_query($conn, $sql);

$data = [];
$data[] = [
    'id'   => '',
    'name' => 'Chọn một Phường/Xã'
];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'id'   => $row['wards_id'],
        'name' => $row['name']
    ];
}

echo json_encode($data);
$conn->close();
?>
