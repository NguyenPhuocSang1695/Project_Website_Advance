<?php
session_start();
if(!isset($_SESSION['Username'])) {
    header("Location: ../index.php");
    exit();
}
?>