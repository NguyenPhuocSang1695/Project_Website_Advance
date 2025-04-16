<?php
header('Content-Type: application/json');
require_once 'connect.php';

try {
    if (!isset($_GET['userId'])) {
        throw new Exception("Thiếu thông tin người dùng");
    }

    $username = $_GET['userId'];

    // Lấy thông tin người dùng
    $stmt = $myconn->prepare("SELECT Username, FullName, Email, Phone, Address, Province, District, Ward, Status FROM users WHERE Username = ?");
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
        'Status' => $user['Status']
    ];

    echo json_encode($response);

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$myconn->close();
?>