<?php
include "connect.php";
session_start();

$where = "1=1";
if (isset($_POST['btn'])) {
  $date_from = $_POST['date-from'];
  $date_to = $_POST['end-date'];
  $status = $_POST['order-status'];
  $district = $_POST['district'];

  if (!empty($date_from)) {
    $where .= " AND ngaytao >= '$date_from'";
  }
  if (!empty($date_to)) {
    $where .= " AND ngaytao <= '$date_to'";
  }

  if ($status != 'all') {
    $status_map = [
      'pending' => 'Đang xử lý',
      'confirmed' => 'Đã xác nhận',
      'delivered' => 'Đã giao',
      'cancelled' => 'Đã hủy'
    ];
    $where .= " AND trangthai = '" . $status_map[$status] . "'";
  }
  if ($district != 'all') {
    if ($district === 'Quận 1') {
      $where .= " AND (diachi LIKE '%Quận 1%' OR diachi LIKE '%Quận 1, %'
      OR diachi LIKE '%Quận1' OR diachi LIKE '%Quận 1 ')
      AND diachi NOT LIKE '%Quận 10%' 
      AND diachi NOT LIKE '%Quận 11%' 
      AND diachi NOT LIKE '%Quận 12%'";
    } else {
      $where .= " AND diachi LIKE '%$district%'";
    }
  }
}

// Lưu điều kiện lọc vào session
$_SESSION['filter_where'] = $where;
header("Location: orderPage1.php");
exit;
