<?php

require "../includes/config.inc.php";

session_start();
session_unset();
session_destroy();
header("Location: " . $baseURL);

?>