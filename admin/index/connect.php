<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "c01db"; 
$myconn = new mysqli($server, $user, $password, $database);
if ($myconn->connect_error) {
    die("Connection failed: " . $myconn->connect_error);
}
$myconn->set_charset("utf8mb4");
?>