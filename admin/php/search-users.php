<?php
header('Content-Type: application/json');
require_once 'connect.php';

// Get search term from GET request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the search pattern
$searchPattern = "%$searchTerm%";

// Prepare the SQL query - added Role to SELECT
$sql = "SELECT Username, FullName, Phone, Email, Status, Role 
        FROM users 
        WHERE (Username LIKE ? OR FullName LIKE ? OR Phone LIKE ? OR Email LIKE ?)
        ORDER BY CASE 
            WHEN Role = 'admin' THEN 1
            WHEN Role = 'employee' THEN 2
            ELSE 3
        END, Username";

$stmt = $myconn->prepare($sql);
$stmt->bind_param("ssss", $searchPattern, $searchPattern, $searchPattern, $searchPattern);
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

echo json_encode(['success' => true, 'users' => $users]);

$stmt->close();
$myconn->close();
