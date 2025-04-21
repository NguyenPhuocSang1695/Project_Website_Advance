<?php
header('Content-Type: application/json');
require_once 'connect.php';

try {
    if (!isset($_GET['userId'])) {
        throw new Exception("Thiếu thông tin người dùng");
    }

    $username = $_GET['userId'];

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
        throw new Exception("Không tìm thấy người dùng");
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
    echo json_encode(['error' => $e->getMessage()]);
}

$myconn->close();
?>