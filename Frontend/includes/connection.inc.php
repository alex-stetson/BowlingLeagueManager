<?php

$servername = "localhost";
$dbUsername = "";
$dbPassword = "";
$dbName = "bowling_league_database";

$link = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);

if (!$link) {
    die ("Database Connection Failed");
}
?>