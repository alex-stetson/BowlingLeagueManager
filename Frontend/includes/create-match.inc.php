<?php

session_start();
if (!isset($_SESSION['userEmail']) || $_SESSION['userEmail'] == '') {
    header("Location: /login.php");
    exit();
}

if (isset($_POST['create-match-submit'])) {

    require "connection.inc.php";

    $team1 = $_POST['team1'];
    $team2 = $_POST['team2'];
    $matchTime = $_POST['matchTime'];
    if (empty($team1) || empty($team2)) {
        header("Location: /create-match.php?error=emptyfields");
        exit();
    } else if ($team1 == "NULL" || $team2 == "NULL") {
        header("Location: /create-match.php?error=emptyfields");
        exit();
    } else if ($team1 == $team2) {
        header("Location: /create-match.php?error=sameteam");
        exit();
    } else {
        if (empty($matchTime)) {
            $sql = "INSERT INTO matches (`team1`, `team2`) VALUES (?, ?);";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $team1, $team2);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header("Location: /matches.php");
                exit();
            } else {
                header("Location: /create-match.php?error=unknownerror");
                exit();
            }
        } else {
            $matchTime = date ("Y-m-d H:i:s", strtotime($matchTime));
            $sql = "INSERT INTO matches (`team1`, `team2`, `matchTime`) VALUES (?, ?, ?);";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sss", $team1, $team2, $matchTime);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header("Location: /matches.php");
                exit();
            } else {
                header("Location: /create-match.php?error=unknownerror");
                exit();
            }
        }
    }
} else {
    header("Location: /");
    exit();
}

?>