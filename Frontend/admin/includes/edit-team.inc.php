<?php

require "requireAuth.inc.php";
require "../../includes/config.inc.php";

if (isset($_POST['edit-team-submit'])) {

    require "../../includes/connection.inc.php";

    $teamId = $_POST['teamId'];
    $teamName = $_POST['teamName'];
    $teamPlayers = $_POST['teamPlayers'];
    if (empty($teamName) || empty($teamId)) {
        header("Location: " . $baseURL . "admin/edit-team.php?error=emptyfields");
        exit();
    } else {
        $sql = "UPDATE teams SET teamName=? WHERE id=?;";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $teamName, $teamId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            header("Location: " . $baseURL . "admin/create-team.php?error=unknownerror");
            exit();
        }
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