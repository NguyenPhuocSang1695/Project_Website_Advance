<?php
header('Content-Type: application/json');
require_once 'connect.php';

try {
    $username = $_GET['username'] ?? '';
    if (empty($username)) {
        throw new Exception('Username is required');
    }

    // Get user details with location names
    $sql = "SELECT u.*, 
            p.name as province_name, 
            d.name as district_name, 
            w.name as ward_name,
            p.province_id,
            d.district_id,
            w.wards_id
            FROM users u
            LEFT JOIN province p ON u.Province = p.province_id
            LEFT JOIN district d ON u.District = d.district_id
            LEFT JOIN wards w ON u.Ward = w.wards_id
            WHERE u.Username = ?";

    // Lấy thông tin người dùng và tên địa chỉ
    $stmt = $myconn->prepare("
        SELECT u.Username, u.FullName, u.Email, u.Phone, u.Address, 
               u.Province, u.District, u.Ward, u.Status,
               p.name as province_name,
               d.name as district_name,
               w.name as ward_name
        FROM users u
        LEFT JOIN province p ON u.Province = p.province_id
        LEFT JOIN district d ON u.District = d.district_id
        LEFT JOIN wards w ON u.Ward = w.wards_id
        WHERE u.Username = ?");

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('User not found');
    }

    $user = $result->fetch_assoc();

    // Chuyển đổi dữ liệu để phù hợp với form
    $response = [
        'Username' => $user['Username'],
        'FullName' => $user['FullName'],
        'Email' => $user['Email'],
        'Phone' => $user['Phone'],
        'Address' => $user['Address'],
        'Province' => $user['Province'],
        'District' => $user['District'],
        'Ward' => $user['Ward'],
        'Status' => $user['Status'],
        'province_name' => $user['province_name'],
        'district_name' => $user['district_name'],
        'ward_name' => $user['ward_name']
    ];

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($myconn)) {
        $myconn->close();
    }
}
