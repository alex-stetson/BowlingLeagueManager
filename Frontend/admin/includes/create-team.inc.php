<?php

require "requireAuth.inc.php";
requireAuth(["admin", "manager"]);
require "../../includes/config.inc.php";

if (isset($_POST['create-team-submit'])) {

    require "../../includes/connection.inc.php";

    $teamName = $_POST['teamName'];
    $teamPlayers = $_POST['teamPlayers'];
    if (empty($teamName)) {
        header("Location: " . $baseURL . "admin/create-team.php?error=emptyfields");
        exit();
    } else {
        $sql = "INSERT INTO teams (`teamName`) VALUES (?);";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $teamName);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            header("Location: " . $baseURL . "admin/create-team.php?error=unknownerror");
            exit();
        }
        $teamId = mysqli_insert_id($link);
        $sql = "INSERT INTO teamMembers (`playerEmail`, `teamId`) VALUES (?, ?);";
        foreach ($teamPlayers as $player) {
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $player, $teamId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
        header("Location: " . $baseURL . "admin/teams.php");
        exit();
    }
} else {
    header("Location: " . $baseURL);
    exit();
}

?>