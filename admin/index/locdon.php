<?php
include("connect.php");

$date_from = "";
$date_end = "";
$order_status = "";
$district = "";

if (isset($_POST['btn'])) {
  $date_from = $_POST['date-from1'];
  $date_end = $_POST['end-date1'];
  $order_status = $_POST['order-status']; 
  $district = $_POST['district'];
  // Debugging output
  echo "<br> Date From: " . $date_from . "<br>";
  echo "Date End: " . $date_end . "<br>";
  echo "Order Status: " . $order_status . "<br>";
  echo "District: " . $district . "<br>";

  // Prepare the SQL query to filter by date range
  $sql = "SELECT * FROM donhang WHERE ngaytao >= '$date_from' AND ngaytao <= '$date_end'";

  // Add order status filtering if not 'all'
  if ($order_status != "all") {
    $sql .= " AND trangthai = '$order_status'";
  }

  // Add district filtering if not 'all'
  if ($district != "all") {
    $sql .= " AND diachi LIKE '%$district%'"; // Assuming 'diachi' contains district information
  }

  $result = mysqli_query($myconn, $sql);

  // Check if any results were returned
  if (mysqli_num_rows($result) > 0) {
    // Loop through the results and display the order IDs
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<br> Mã đơn hàng: " . $row["madonhang"] . " Tên khách hàng: " . $row["tenkhachhang"] . "<br>"; // Display order ID
    }
  } else {
    echo "Không có đơn hàng nào trong khoảng thời gian này."; 
  }
}
?>
