<?php
header('Content-Type: application/json');
require_once 'connect.php';

// Validate và sanitize input
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

try {
    // Validate required fields
    $required_fields = ['username', 'fullname', 'password', 'phone', 'address', 'province', 'district', 'ward'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Vui lòng điền đầy đủ thông tin bắt buộc");
        }
    }

    // Validate và sanitize input
    $username = validateInput($_POST['username']);
    $fullname = validateInput($_POST['fullname']);
    $email = isset($_POST['email']) ? validateInput($_POST['email']) : null;
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = validateInput($_POST['phone']);
    $address = validateInput($_POST['address']);
    $province = validateInput($_POST['province']);
    $district = validateInput($_POST['district']);
    $ward = validateInput($_POST['ward']);
    $role = 'customer';
    $status = 'Active';

    // Validate email format if provided
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Email không hợp lệ");
    }

    // Validate phone format
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        throw new Exception("Số điện thoại phải có 10 chữ số");
    }

    // Kiểm tra username đã tồn tại chưa
    $stmt = $myconn->prepare("SELECT Username FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        throw new Exception("Tên tài khoản đã tồn tại");
    }

    // Kiểm tra email đã tồn tại chưa (nếu có email)
    if (!empty($email)) {
        $stmt = $myconn->prepare("SELECT Email FROM users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("Email đã tồn tại");
        }
    }

    // Thêm người dùng vào database
    $stmt = $myconn->prepare("INSERT INTO users (Username, FullName, Email, PasswordHash, Phone, Address, Province, District, Ward, Role, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssssssss", $username, $fullname, $email, $passwordHash, $phone, $address, $province, $district, $ward, $role, $status);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thêm người dùng thành công']);
    } else {
        throw new Exception("Lỗi khi thêm người dùng: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$myconn->close();
?>