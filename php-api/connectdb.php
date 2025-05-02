<?php
function connect_db()
{
    $server = "localhost";
    $username = "c01u";
    $password = "KtdVb9kNDRutbwFB";
    $database = "c01db";

    // Create connection
    $conn = new mysqli($server, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}
