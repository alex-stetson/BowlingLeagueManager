<?php

require "../includes/config.inc.php";

if (!$casEnabled) {
    header("Location: " . $baseURL . "admin/login.php");
    exit();
}

require_once "../assets/cas/CAS.php";
require "../includes/cas-config.inc.php";

//initialize phpCAS (CAS Version, CAS Server, Port, Base URI, Should phpCAS start a PHP Session?)
phpCAS::client($casVersion, $casHostname, $casPort, $casURI, TRUE);

//phpCAS::setNoCasServerValidation();
phpCAS::setCasServerCACert($casCACertPath);

//force CAS authentication.
phpCAS::forceAuthentication();

//Assign the account returned by the CAS login to a variable.
$casUser = phpCAS::getUser();

require "../includes/connection.inc.php";

$sql = "SELECT * FROM users WHERE username=? AND casUser=1;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $casUser);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        session_start();
        $_SESSION['userId'] = $row['userId'];
        $_SESSION['userRole'] = $row['role'];
        header("Location: " . $baseURL);
        exit();
    } else {
        header("Location: " . $baseURL . "admin/login.php?error=unauthorizedCASUser");
        exit();
    }
} else {
    header("Location: " . $baseURL . "admin/login.php?error=unknownerror");
    exit();
}

?>