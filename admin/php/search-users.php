<?php
header('Content-Type: application/json');
require_once 'connect.php';

// Get search term from GET request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) && is_numeric($_GET['per_page']) ? (int)$_GET['per_page'] : 5;

// Prepare the search pattern
$searchPattern = "%$searchTerm%";

// Count total records for pagination
$countSql = "SELECT COUNT(*) as total FROM users WHERE (Username LIKE ? OR FullName LIKE ? OR Phone LIKE ? OR Email LIKE ?)";
$countStmt = $myconn->prepare($countSql);
$countStmt->bind_param("ssss", $searchPattern, $searchPattern, $searchPattern, $searchPattern);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRecords = $countResult->fetch_assoc()['total'];
$countStmt->close();

$totalPages = max(1, ceil($totalRecords / $perPage));
$page = max(1, min($page, $totalPages));
$offset = ($page - 1) * $perPage;

// Prepare the SQL query - added Role to SELECT
$sql = "SELECT Username, FullName, Phone, Email, Status, Role 
        FROM users 
        WHERE (Username LIKE ? OR FullName LIKE ? OR Phone LIKE ? OR Email LIKE ?)
        ORDER BY CASE 
            WHEN Role = 'admin' THEN 1
            WHEN Role = 'employee' THEN 2
            ELSE 3
        END, Username
        LIMIT ? OFFSET ?";

$stmt = $myconn->prepare($sql);
$stmt->bind_param("ssssii", $searchPattern, $searchPattern, $searchPattern, $searchPattern, $perPage, $offset);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    // Format the status text
    $statusText = $row['Status'] === 'Active' ? 'Hoạt động' : 'Đã khóa';
    $statusClass = $row['Status'] === 'Active' ? 'text-success' : 'text-danger';

    $users[] = [
        'username' => $row['Username'],
        'fullname' => $row['FullName'],
        'phone' => $row['Phone'],
        'email' => $row['Email'],
        'role' => $row['Role'], // Added role to response
        'status' => $statusText,
        'statusClass' => $statusClass
    ];
}

$response = [
    'success' => true,
    'users' => $users,
    'pagination' => [
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'totalRecords' => $totalRecords
    ]
];

echo json_encode($response);

$stmt->close();
$myconn->close();
