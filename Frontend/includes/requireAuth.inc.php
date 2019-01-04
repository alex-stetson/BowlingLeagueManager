<?php

session_start();
if (!isset($_SESSION['userEmail']) || $_SESSION['userEmail'] == '') {
    header("Location: /login.php");
    exit();
}

?>