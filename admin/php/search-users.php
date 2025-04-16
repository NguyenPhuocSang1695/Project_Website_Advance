<?php
require_once 'connect.php';

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 5;
$offset = ($page - 1) * $recordsPerPage;

// Get total matching records
$countSql = "SELECT COUNT(*) as total FROM users 
             WHERE role = 'customer' AND FullName LIKE ?";
$searchPattern = "%$searchTerm%";
$stmt = $myconn->prepare($countSql);
$stmt->bind_param("s", $searchPattern);
$stmt->execute();
$totalResult = $stmt->get_result()->fetch_assoc();
$totalRecords = $totalResult['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Get paginated search results
$sql = "SELECT Username, FullName, Phone, Email, Status 
        FROM users 
        WHERE role = 'customer' AND FullName LIKE ?
        LIMIT ?, ?";
$stmt = $myconn->prepare($sql);
$stmt->bind_param("sii", $searchPattern, $offset, $recordsPerPage);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode([
    'users' => $users,
    'totalPages' => $totalPages,
    'currentPage' => $page
]);

$stmt->close();
$myconn->close();
?>