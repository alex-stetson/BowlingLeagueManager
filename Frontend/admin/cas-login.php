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

//Assign the GTAccount returned by GTLogin to a variable.
$casUser = phpCAS::getUser();

$sql = "SELECT * FROM casUsers WHERE userID=?;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $casUser);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        session_start();
        $_SESSION['userID'] = $casUser;
        header("Location: " . $baseURL);
        exit();
    } else {
        exit("You are not authorized to login to this site");
    }
} else {
    header("Location: " . $baseURL . "admin/login.php?error=unknownerror");
    exit();
}

?>