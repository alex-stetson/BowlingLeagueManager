<?php

require "requireAuth.inc.php";
requireAuth(["admin", "manager"]);
require "../../includes/config.inc.php";

if (isset($_POST['create-match-submit'])) {

    require "../../includes/connection.inc.php";

    $team1 = $_POST['team1'];
    $team2 = $_POST['team2'];
    $matchTime = $_POST['matchTime'];
    $matchLocation = $_POST['matchLocation'];
    if (empty($team1) || empty($team2)) {
        header("Location: " . $baseURL . "admin/create-match.php?error=emptyfields");
        exit();
    } else if ($team1 == "NULL" || $team2 == "NULL") {
        header("Location: " . $baseURL . "admin/create-match.php?error=emptyfields");
        exit();
    } else if ($team1 == $team2) {
        header("Location: " . $baseURL . "admin/create-match.php?error=sameteam");
        exit();
    } else {
        if (empty($matchTime)) {
            if (empty($matchLocation)) {
                $sql = "INSERT INTO matches (`team1`, `team2`) VALUES (?, ?);";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ss", $team1, $team2);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    header("Location: " . $baseURL . "admin/matches.php");
                    exit();
                } else {
                    header("Location: " . $baseURL . "admin/create-match.php?error=unknownerror");
                    exit();
                }
            } else {
                $sql = "INSERT INTO matches (`team1`, `team2`, `matchLocation`) VALUES (?, ?, ?);";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sss", $team1, $team2, $matchLocation);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    header("Location: " . $baseURL . "admin/matches.php");
                    exit();
                } else {
                    header("Location: " . $baseURL . "admin/create-match.php?error=unknownerror");
                    exit();
                }
            }
        } else {
            if (empty($matchLocation)) {
                $matchTime = date("Y-m-d H:i:s", strtotime($matchTime));
                $sql = "INSERT INTO matches (`team1`, `team2`, `matchTime`) VALUES (?, ?, ?);";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sss", $team1, $team2, $matchTime);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    header("Location: " . $baseURL . "admin/matches.php");
                    exit();
                } else {
                    header("Location: " . $baseURL . "admin/create-match.php?error=unknownerror");
                    exit();
                }
            } else {
                $matchTime = date("Y-m-d H:i:s", strtotime($matchTime));
                $sql = "INSERT INTO matches (`team1`, `team2`, `matchTime`, `matchLocation`) VALUES (?, ?, ?, ?);";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ssss", $team1, $team2, $matchTime, $matchLocation);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    header("Location: " . $baseURL . "admin/matches.php");
                    exit();
                } else {
                    header("Location: " . $baseURL . "admin/create-match.php?error=unknownerror");
                    exit();
                }
            }
        }
    }
} else {
    header("Location: " . $baseURL);
    exit();
}

?>