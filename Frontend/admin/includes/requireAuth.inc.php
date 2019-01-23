<?php

# Cannot include in an include
$baseURL = "/";

session_start();
if (!isset($_SESSION['userEmail']) || $_SESSION['userEmail'] == '') {
    header("Location: " . $baseURL . "admin/login.php");
    exit();
}

?>