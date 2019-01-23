<?php

require(dirname(__FILE__) . '/../../includes/config.inc.php');

session_start();
if (!isset($_SESSION['userEmail']) || $_SESSION['userEmail'] == '') {
    header("Location: " . $baseURL . "admin/login.php");
    exit();
}

?>