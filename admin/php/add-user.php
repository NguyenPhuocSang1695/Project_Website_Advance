<?php
header('Content-Type: application/json');
include 'connect.php';

// Lấy dữ liệu từ request
$data = json_decode(file_get_contents('php://input'), true);

// Validate dữ liệu
if (
    !isset($data['username']) || !isset($data['fullname']) || !isset($data['password'])
    || !isset($data['phone']) || !isset($data['address']) || !isset($data['province'])
    || !isset($data['district'])
) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin bắt buộc']);
    exit;
}

$username = $data['username'];
$fullname = $data['fullname'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);
$email = isset($data['email']) ? $data['email'] : '';
$phone = $data['phone'];
$address = $data['address'];
$province = $data['province'];
$district = $data['district'];
$ward = isset($data['ward']) ? $data['ward'] : '';
$status = isset($data['status']) ? $data['status'] : 'Active';

// Kiểm tra username đã tồn tại chưa
$checkUser = $myconn->prepare("SELECT Username FROM users WHERE Username = ?");
$checkUser->bind_param("s", $username);
$checkUser->execute();
if ($checkUser->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Tên tài khoản đã tồn tại']);
    exit;
}

// Thêm người dùng mới
$sql = "INSERT INTO users (Username, FullName, PasswordHash, Email, Phone, Address, Province, District, Ward, Status, Role) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'customer')";
$stmt = $myconn->prepare($sql);
$stmt->bind_param("ssssssssss", $username, $fullname, $password, $email, $phone, $address, $province, $district, $ward, $status);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Thêm người dùng thành công']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm người dùng: ' . $myconn->error]);
}

$stmt->close();
$myconn->close();
