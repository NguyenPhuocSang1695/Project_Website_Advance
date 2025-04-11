<?php
// get_district.php: Lấy danh sách Quận/Huyện theo province_id
$conn = new mysqli("localhost", "root", "", "webdb");
if ($conn->connect_error) die("Lỗi kết nối: " . $conn->connect_error);

// Đặt header trả về JSON
header('Content-Type: application/json');

if (!isset($_GET['province_id'])) {
    echo json_encode(['error' => 'Thiếu province_id']);
    exit;
}

$province_id = intval($_GET['province_id']);

// Truy vấn bảng district theo province_id (theo cấu trúc: district_id, province_id, name)
$sql = "SELECT * FROM district WHERE province_id = {$province_id}";
$result = mysqli_query($conn, $sql);

// Mảng dữ liệu khởi tạo có phần tử mặc định
$data = [];
$data[] = [
    'id'   => '',
    'name' => 'Chọn một Quận/Huyện'
];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'id'   => $row['district_id'],
        'name' => $row['name']
    ];
}

echo json_encode($data);
$conn->close();
?>
