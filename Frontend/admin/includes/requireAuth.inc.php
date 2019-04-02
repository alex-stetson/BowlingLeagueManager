<?php

function requireAuth($role)
{
    require(dirname(__FILE__) . '/../../includes/config.inc.php');
    session_start();
    if (!isset($_SESSION['userID']) || $_SESSION['userID'] == '' ||
        !isset($_SESSION['userRole']) || $_SESSION['userRole'] == '') {
        header("Location: " . $baseURL . "admin/login.php");
        exit();
    }
    if (!in_array($_SESSION['userRole'], $role)) {
        exit("You are unauthorized to visit this page.");
    }
}

?>