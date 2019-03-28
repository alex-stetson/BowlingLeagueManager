<?php

require "requireAuth.inc.php";
require "../../includes/config.inc.php";

if (isset($_POST['submit-scores'])) {

    require "../../includes/connection.inc.php";

    $matchId = $_POST['matchId'];
    $team1Id = $_POST['team1Id'];
    $team2Id = $_POST['team2Id'];
    if (empty($matchId)) {
        header("Location: " . $baseURL . "admin/matches.php");
        exit();
    } else if (empty($team1Id) || empty($team2Id)) {
        header("Location: " . $baseURL . "score-entry.php?matchId=" . $matchId);
        exit();
    }

    $t1Emails = $_POST['t1Emails'];
    $t1Handicaps = $_POST['t1Handicaps'];
    $t1g1 = $_POST['t1g1'];
    $t1g2 = $_POST['t1g2'];
    $t1g3 = $_POST['t1g3'];
    $t1Blinds = $_POST['t1Blinds'];

    $t2Emails = $_POST['t2Emails'];
    $t2Handicaps = $_POST['t2Handicaps'];
    $t2g1 = $_POST['t2g1'];
    $t2g2 = $_POST['t2g2'];
    $t2g3 = $_POST['t2g3'];
    $t1Blinds = $_POST['t2Blinds'];

    if (empty($t1Emails) || empty($t2Emails)) {
        header("Location: " . $baseURL . "score-entry.php?matchId=" . $matchId);
        exit();
    } else {
        # Insert matchScore Data
        $sql = "INSERT INTO matchScores (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`, `isBlind`) VALUES
        (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        handicap = VALUES(handicap),
        game1Score = VALUES(game1Score),
        game2Score = VALUES(game2Score),
        game3Score = VALUES(game3Score),
        isBlind = VALUES(isBlind);";
        for ($i = 0; $i < count($t1Emails); $i++) {
            $currBlind = in_array($i, $t1Blinds);
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "iisiiiii",
                    $matchId, $team1Id, $t1Emails[$i], $t1Handicaps[$i], $t1g1[$i], $t1g2[$i], $t1g3[$i], $currBlind);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                exit();
            }
        }
        for ($i = 0; $i < count($t2Emails); $i++) {
            $currBlind = in_array($i, $t2Blinds);
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "iisiiiii",
                    $matchId, $team2Id, $t2Emails[$i], $t2Handicaps[$i], $t2g1[$i], $t2g2[$i], $t2g3[$i], $currBlind);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                exit();
            }
        }

        if (empty($_POST['shouldCountPoints'])) {
            # Calculate team points for match
            $t1Handicap = array_sum($t1Handicaps);
            $t2Handicap = array_sum($t2Handicaps);
            $t1g1 = $t1Handicap + array_sum($t1g1);
            $t2g1 = $t2Handicap + array_sum($t2g1);
            $t1g2 = $t1Handicap + array_sum($t1g2);
            $t2g2 = $t2Handicap + array_sum($t2g2);
            $t1g3 = $t1Handicap + array_sum($t1g3);
            $t2g3 = $t2Handicap + array_sum($t2g3);
            $t1Total = $t1g1 + $t1g2 + $t1g3;
            $t2Total = $t2g1 + $t2g2 + $t2g3;

            $team1Points = 0;
            $team2Points = 0;
            if ($t1g1 > $t2g1) {
                $team1Points += 2;
            } else if ($t1g1 < $t2g1) {
                $team2Points += 2;
            } else {
                $team1Points += 1;
                $team2Points += 1;
            }
            if ($t1g2 > $t2g2) {
                $team1Points += 2;
            } else if ($t1g2 < $t2g2) {
                $team2Points += 2;
            } else {
                $team1Points += 1;
                $team2Points += 1;
            }
            if ($t1g3 > $t2g3) {
                $team1Points += 2;
            } else if ($t1g3 < $t2g3) {
                $team2Points += 2;
            } else {
                $team1Points += 1;
                $team2Points += 1;
            }
            if ($t1Total > $t2Total) {
                $team1Points += 2;
            } else if ($t1Total < $t2Total) {
                $team2Points += 2;
            } else {
                $team1Points += 1;
                $team2Points += 1;
            }
            $sql = "UPDATE matches SET team1Points=?, team2Points=? WHERE id=?;";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "iii", $team1Points, $team2Points, $matchId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                exit();
            }
        } else {
            $sql = "UPDATE matches SET team1Points=0, team2Points=0 WHERE id=?;";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $matchId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                exit();
            }
        }
        # Update team total points
        $sql = "UPDATE teams SET totalPoints=(SELECT SUM(IF(team1=?, team1Points, team2Points)) AS totalPts FROM matches WHERE team1=? OR team2=?) WHERE id=?;";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "iiii", $team1Id, $team1Id, $team1Id, $team1Id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            exit();
        }
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "iiii", $team2Id, $team2Id, $team2Id, $team2Id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            exit();
        }
        # Update player handicaps
        $sql = "UPDATE players SET currentHandicap=(GREATEST(ROUND(0.9*(200-(SELECT (AVG(`game1Score`)+AVG(`game2Score`)+AVG(`game3Score`))/3 as Average FROM matchScores where playerEmail=? AND isBlind=0)), 0), 0)) WHERE email=?;";
        for ($i = 0; $i < count($t1Emails); $i++) {
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $t1Emails[$i], $t1Emails[$i]);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                exit();
            }
        }
        for ($i = 0; $i < count($t2Emails); $i++) {
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $t2Emails[$i], $t2Emails[$i]);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                exit();
            }
        }
        header("Location: " . $baseURL . "admin/matches.php");
    }
} else {
    header("Location: " . $baseURL . "admin/matches.php");
    exit();
}

?>