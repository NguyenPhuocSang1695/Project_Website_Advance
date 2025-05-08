<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  if (isset($data['updatedCartCount'])) {
    $_SESSION['cart_count'] = $data['updatedCartCount'];
    echo json_encode(['success' => true]);
    exit;
  }
}

echo json_encode(['success' => false]);
exit;