<?php

require(dirname(__FILE__) . '/../../includes/config.inc.php');

session_start();
if (!isset($_SESSION['userID']) || $_SESSION['userID'] == '') {
    header("Location: " . $baseURL . "admin/login.php");
    exit();
}

?>