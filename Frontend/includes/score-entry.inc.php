<?php

session_start();
if (!isset($_SESSION['userEmail']) || $_SESSION['userEmail'] == '') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit-scores'])) {

    require "connection.inc.php";

    $matchId = $_POST['matchId'];
    if (empty($matchId)) {
        header("Location: /score-entry-list.php");
        exit();
    }

    $p1Email = $_POST['p1Email'];
    $p1Handicap = $_POST['p1Handicap'];
    $p1g1 = $_POST['p1g1'];
    $p1g2 = $_POST['p1g2'];
    $p1g3 = $_POST['p1g3'];

    $p2Email = $_POST['p2Email'];
    $p2Handicap = $_POST['p2Handicap'];
    $p2g1 = $_POST['p2g1'];
    $p2g2 = $_POST['p2g2'];
    $p2g3 = $_POST['p2g3'];

    $p3Email = $_POST['p3Email'];
    $p3Handicap = $_POST['p3Handicap'];
    $p3g1 = $_POST['p3g1'];
    $p3g2 = $_POST['p3g2'];
    $p3g3 = $_POST['p3g3'];

    $p4Email = $_POST['p4Email'];
    $p4Handicap = $_POST['p4Handicap'];
    $p4g1 = $_POST['p4g1'];
    $p4g2 = $_POST['p4g2'];
    $p4g3 = $_POST['p4g3'];

    $p5Email = $_POST['p5Email'];
    $p5Handicap = $_POST['p5Handicap'];
    $p5g1 = $_POST['p5g1'];
    $p5g2 = $_POST['p5g2'];
    $p5g3 = $_POST['p5g3'];

    $p6Email = $_POST['p6Email'];
    $p6Handicap = $_POST['p6Handicap'];
    $p6g1 = $_POST['p6g1'];
    $p6g2 = $_POST['p6g2'];
    $p6g3 = $_POST['p6g3'];

    $p7Email = $_POST['p7Email'];
    $p7Handicap = $_POST['p7Handicap'];
    $p7g1 = $_POST['p7g1'];
    $p7g2 = $_POST['p7g2'];
    $p7g3 = $_POST['p7g3'];

    $p8Email = $_POST['p8Email'];
    $p8Handicap = $_POST['p8Handicap'];
    $p8g1 = $_POST['p8g1'];
    $p8g2 = $_POST['p8g2'];
    $p8g3 = $_POST['p8g3'];


    if (empty($p1Email) || empty($p1Handicap)) {
        header("Location: /score-entry.php?matchId=".$matchId);
        exit();
    } else {
        $sql = "INSERT INTO matchScores (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`) VALUES
        (?, (SELECT teamMembers.teamId from teamMembers WHERE teamMembers.playerEmail=?), ?, ?, ?, ?, ?),
        (?, (SELECT teamMembers.teamId from teamMembers WHERE teamMembers.playerEmail=?), ?, ?, ?, ?, ?),
        (?, (SELECT teamMembers.teamId from teamMembers WHERE teamMembers.playerEmail=?), ?, ?, ?, ?, ?),
        (?, (SELECT teamMembers.teamId from teamMembers WHERE teamMembers.playerEmail=?), ?, ?, ?, ?, ?),
        (?, (SELECT teamMembers.teamId from teamMembers WHERE teamMembers.playerEmail=?), ?, ?, ?, ?, ?),
        (?, (SELECT teamMembers.teamId from teamMembers WHERE teamMembers.playerEmail=?), ?, ?, ?, ?, ?),
        (?, (SELECT teamMembers.teamId from teamMembers WHERE teamMembers.playerEmail=?), ?, ?, ?, ?, ?),
        (?, (SELECT teamMembers.teamId from teamMembers WHERE teamMembers.playerEmail=?), ?, ?, ?, ?, ?);";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "issiiiiissiiiiissiiiiissiiiiissiiiiissiiiiissiiiiissiiii",
            $matchId, $p1Email, $p1Email, $p1Handicap, $p1g1, $p1g2, $p1g3,
            $matchId, $p2Email, $p2Email, $p2Handicap, $p2g1, $p2g2, $p2g3,
            $matchId, $p3Email, $p3Email, $p3Handicap, $p3g1, $p3g2, $p3g3,
            $matchId, $p4Email, $p4Email, $p4Handicap, $p4g1, $p4g2, $p4g3,
            $matchId, $p5Email, $p5Email, $p5Handicap, $p5g1, $p5g2, $p5g3,
            $matchId, $p6Email, $p6Email, $p6Handicap, $p6g1, $p6g2, $p6g3,
            $matchId, $p7Email, $p7Email, $p7Handicap, $p7g1, $p7g2, $p7g3,
            $matchId, $p8Email, $p8Email, $p8Handicap, $p8g1, $p8g2, $p8g3);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            exit();
        }
        header("Location: /index.php");
    }
} else {
    header("Location: /score-entry-list.php");
    exit();
}

?>