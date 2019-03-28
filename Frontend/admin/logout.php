<?php

require "../includes/config.inc.php";

session_start();
session_unset();
session_destroy();

if ($casEnabled) {
    require_once "../assets/cas/CAS.php";
    require "../includes/cas-config.inc.php";
    phpCAS::client($casVersion, $casHostname, $casPort, $casURI, TRUE);
    phpCAS::setCasServerCACert($casCACertPath);

    if (phpCAS::isAuthenticated()) {
        phpCAS::logout();
    }
}

header("Location: " . $baseURL);

?>