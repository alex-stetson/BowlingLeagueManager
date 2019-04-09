<?php

function requireAuth($roleArray)
{
    require(dirname(__FILE__) . '/../../includes/config.inc.php');
    session_start();
    if (!isset($_SESSION['userId']) || $_SESSION['userId'] == '' ||
        !isset($_SESSION['userRole']) || $_SESSION['userRole'] == '') {
        header("Location: " . $baseURL . "admin/login.php");
        exit();
    }
    if (!in_array($_SESSION['userRole'], $roleArray)) {
        exit("You are unauthorized to visit this page.");
    }
}

?>