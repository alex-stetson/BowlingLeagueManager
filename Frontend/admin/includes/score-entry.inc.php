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

    $p1Email = $_POST['p1Email'];
    $p1Handicap = $_POST['p1Handicap'];
    $p1g1 = $_POST['p1g1'];
    $p1g2 = $_POST['p1g2'];
    $p1g3 = $_POST['p1g3'];
    $p1Blind = (int)!empty($_POST['p1Blind']);

    $p2Email = $_POST['p2Email'];
    $p2Handicap = $_POST['p2Handicap'];
    $p2g1 = $_POST['p2g1'];
    $p2g2 = $_POST['p2g2'];
    $p2g3 = $_POST['p2g3'];
    $p2Blind = (int)!empty($_POST['p2Blind']);

    $p3Email = $_POST['p3Email'];
    $p3Handicap = $_POST['p3Handicap'];
    $p3g1 = $_POST['p3g1'];
    $p3g2 = $_POST['p3g2'];
    $p3g3 = $_POST['p3g3'];
    $p3Blind = (int)!empty($_POST['p3Blind']);

    $p4Email = $_POST['p4Email'];
    $p4Handicap = $_POST['p4Handicap'];
    $p4g1 = $_POST['p4g1'];
    $p4g2 = $_POST['p4g2'];
    $p4g3 = $_POST['p4g3'];
    $p4Blind = (int)!empty($_POST['p4Blind']);

    $p5Email = $_POST['p5Email'];
    $p5Handicap = $_POST['p5Handicap'];
    $p5g1 = $_POST['p5g1'];
    $p5g2 = $_POST['p5g2'];
    $p5g3 = $_POST['p5g3'];
    $p5Blind = (int)!empty($_POST['p5Blind']);

    $p6Email = $_POST['p6Email'];
    $p6Handicap = $_POST['p6Handicap'];
    $p6g1 = $_POST['p6g1'];
    $p6g2 = $_POST['p6g2'];
    $p6g3 = $_POST['p6g3'];
    $p6Blind = (int)!empty($_POST['p6Blind']);

    $p7Email = $_POST['p7Email'];
    $p7Handicap = $_POST['p7Handicap'];
    $p7g1 = $_POST['p7g1'];
    $p7g2 = $_POST['p7g2'];
    $p7g3 = $_POST['p7g3'];
    $p7Blind = (int)!empty($_POST['p7Blind']);

    $p8Email = $_POST['p8Email'];
    $p8Handicap = $_POST['p8Handicap'];
    $p8g1 = $_POST['p8g1'];
    $p8g2 = $_POST['p8g2'];
    $p8g3 = $_POST['p8g3'];
    $p8Blind = (int)!empty($_POST['p8Blind']);


    if (empty($p1Email) || empty($p2Email) || empty($p3Email) || empty($p4Email) || empty($p5Email) || empty($p6Email) || empty($p7Email) || empty($p8Email)) {
        header("Location: " . $baseURL . "score-entry.php?matchId=" . $matchId);
        exit();
    } else {
        # Insert matchScore Data
        $sql = "INSERT INTO matchScores (`matchId`, `teamId`, `playerEmail`, `handicap`, `game1Score`, `game2Score`, `game3Score`, `isBlind`) VALUES
        (?, ?, ?, ?, ?, ?, ?, ?),
        (?, ?, ?, ?, ?, ?, ?, ?),
        (?, ?, ?, ?, ?, ?, ?, ?),
        (?, ?, ?, ?, ?, ?, ?, ?),
        (?, ?, ?, ?, ?, ?, ?, ?),
        (?, ?, ?, ?, ?, ?, ?, ?),
        (?, ?, ?, ?, ?, ?, ?, ?),
        (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        handicap = VALUES(handicap),
        game1Score = VALUES(game1Score),
        game2Score = VALUES(game2Score),
        game3Score = VALUES(game3Score),
        isBlind = VALUES(isBlind);";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "iisiiiiiiisiiiiiiisiiiiiiisiiiiiiisiiiiiiisiiiiiiisiiiiiiisiiiii",
                $matchId, $team1Id, $p1Email, $p1Handicap, $p1g1, $p1g2, $p1g3, $p1Blind,
                $matchId, $team1Id, $p2Email, $p2Handicap, $p2g1, $p2g2, $p2g3, $p2Blind,
                $matchId, $team1Id, $p3Email, $p3Handicap, $p3g1, $p3g2, $p3g3, $p3Blind,
                $matchId, $team1Id, $p4Email, $p4Handicap, $p4g1, $p4g2, $p4g3, $p4Blind,
                $matchId, $team2Id, $p5Email, $p5Handicap, $p5g1, $p5g2, $p5g3, $p5Blind,
                $matchId, $team2Id, $p6Email, $p6Handicap, $p6g1, $p6g2, $p6g3, $p6Blind,
                $matchId, $team2Id, $p7Email, $p7Handicap, $p7g1, $p7g2, $p7g3, $p7Blind,
                $matchId, $team2Id, $p8Email, $p8Handicap, $p8g1, $p8g2, $p8g3, $p8Blind);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            exit();
        }
        if (empty($_POST['shouldCountPoints'])) {
            # Calculate team points for match
            $t1Handicap = $p1Handicap + $p2Handicap + $p3Handicap + $p4Handicap;
            $t2Handicap = $p5Handicap + $p6Handicap + $p7Handicap + $p8Handicap;
            $t1g1 = $t1Handicap + $p1g1 + $p2g1 + $p3g1 + $p4g1;
            $t2g1 = $t2Handicap + $p5g1 + $p6g1 + $p7g1 + $p8g1;
            $t1g2 = $t1Handicap + $p1g2 + $p2g2 + $p3g2 + $p4g2;
            $t2g2 = $t2Handicap + $p5g2 + $p6g2 + $p7g2 + $p8g2;
            $t1g3 = $t1Handicap + $p1g3 + $p2g3 + $p3g3 + $p4g3;
            $t2g3 = $t2Handicap + $p5g3 + $p6g3 + $p7g3 + $p8g3;
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
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $p1Email, $p1Email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            exit();
        }
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $p2Email, $p2Email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            exit();
        }
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $p3Email, $p3Email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            exit();
        }
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $p4Email, $p4Email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            exit();
        }
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $p5Email, $p5Email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            exit();
        }
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $p6Email, $p6Email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            exit();
        }
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $p7Email, $p7Email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            exit();
        }
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $p8Email, $p8Email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            exit();
        }
        header("Location: " . $baseURL . "admin/matches.php");
    }
} else {
    header("Location: " . $baseURL . "admin/matches.php");
    exit();
}

?>