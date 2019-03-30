<?php

$servername = "localhost"; // MySQL server address
$dbUsername = ""; // Database username
$dbPassword = ""; // Database user's password
$dbName = ""; // Database name

$link = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);

if (!$link) {
    die ("Database Connection Failed");
}
?>