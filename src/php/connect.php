<?php
$servername = "localhost";
$username1 = "c01u";
$password = "KtdVb9kNDRutbwFB";
$dbname = "C01DB";
$conn = new mysqli($servername, $username1, $password, $dbname);
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}
