<?php
// src/php/logout.php
setcookie("token", "", time() - 3600, "/");

// Quay về đúng trang index.php nằm trong src/php/
header("Location: ../../index.php");
exit();

?>
