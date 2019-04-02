<?php

require "requireAuth.inc.php";
requireAuth(["admin", "manager"]);
require "../../includes/config.inc.php";

if (isset($_POST['edit-player-submit'])) {

    require "../../includes/connection.inc.php";

    $email = $_POST['playerEmail'];
    $playerName = $_POST['playerName'];

    if (empty($email) || empty($playerName)) {
        header("Location: " . $baseURL . "edit-player.php?error=emptyfields");
        exit();
    } else {
        $sql = "UPDATE players SET playerName=? WHERE email=?;";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $playerName, $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header("Location: " . $baseURL . "admin/players.php");
            exit();

        } else {
            header("Location: " . $baseURL . "admin/edit-player.php?error=unknownerror");
            exit();
        }
    }
} else {
    header("Location: " . $baseURL);
    exit();
}

?>