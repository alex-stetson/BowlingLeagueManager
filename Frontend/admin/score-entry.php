<?php

require "includes/requireAuth.inc.php";
require "../includes/config.inc.php";

if (!isset($_GET['matchId'])) {
    header("Location: " . $baseURL . "admin/matches.php");
    exit();
}

require "../includes/connection.inc.php";

$matchId = $_GET['matchId'];

if (empty($matchId)) {
    header("Location: " . $baseURL . "admin/matches.php");
    exit();
}


$sql = "SELECT players.playerName, players.currentHandicap, teams.teamName, teamMembers.playerEmail, teamMembers.teamId, matches.team1, matches.team2, matches.matchLocation, matches.matchTime
FROM players
INNER JOIN teamMembers ON players.email = teamMembers.playerEmail
INNER JOIN teams ON teamMembers.teamId = teams.id
INNER JOIN matches ON teams.id = matches.team1 OR teams.id = matches.team2
WHERE matches.id = ?;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $matchId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $team1Emails = array();
    $team2Emails = array();
    $team1Names = array();
    $team2Names = array();
    $team1Handicaps = array();
    $team2Handicaps = array();
    $team1Id = 0;
    $team2Id = 0;
    $team1Name = "";
    $team2Name = "";
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['teamId'] == $row['team1']) {
            $team1Emails[] = $row['playerEmail'];
            $team1Names[] = $row['playerName'];
            $team1Handicaps[] = $row['currentHandicap'];
            $team1Name = $row['teamName'];
            $team1Id = $row['team1'];
        } else {
            $team2Emails[] = $row['playerEmail'];
            $team2Names[] = $row['playerName'];
            $team2Handicaps[] = $row['currentHandicap'];
            $team2Name = $row['teamName'];
            $team2Id = $row['team2'];
        }
        $matchLocation = $row['matchLocation'];
        $matchTime = $row['matchTime'];
    }
    if ($team1Id == 0 && $team2Id == 0) {
        header("Location: " . $baseURL . "admin/matches.php");
        exit();
    }
} else {
    header("Location: " . $baseURL . "admin/matches.php");
    exit();
}

$sql = "SELECT * FROM matchScores WHERE matchId = ?;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $matchId);
    mysqli_stmt_execute($stmt);
    $matchScoresResult = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $rowCount = mysqli_num_rows($matchScoresResult);
    $wasScored = false;
    if ($rowCount > 0) {
        $wasScored = true;
        $oldHandicapsArr = [];
        $oldGame1ScoreArr = [];
        $oldGame2ScoreArr = [];
        $oldGame3ScoreArr = [];
        while ($row = mysqli_fetch_assoc($matchScoresResult)) {
            $oldHandicapsArr[$row['playerEmail']] = $row['handicap'];
            $oldGame1ScoreArr[$row['playerEmail']] = $row['game1Score'];
            $oldGame2ScoreArr[$row['playerEmail']] = $row['game2Score'];
            $oldGame3ScoreArr[$row['playerEmail']] = $row['game3Score'];
        }
    }
} else {
    header("Location: " . $baseURL . "admin/matches.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <title>Score Entry</title>

    <base href="<?php echo $baseURL; ?>">

    <!-- Favicon -->
    <link href="assets/img/brand/favicon.png" rel="icon" type="image/png"/>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet"/>

    <!-- Icons -->
    <link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>

    <!-- Argon CSS -->
    <link type="text/css" href="assets/css/argon.min.css" rel="stylesheet"/>
</head>

<body>
<?php
include_once "../includes/navbar.inc.php";
?>
<div class="row justify-content-center mt-md">
    <div class="col-lg-12">
        <h1 class="h1 font-weight-bold">Score Entry</h1>
        <h4><?php echo $team1Name . " vs " . $team2Name; ?></h4>
        <h5><?php echo(empty($matchTime) ? '' : date('m/d/y h:i A', strtotime($matchTime))); ?></h5>
        <h5><?php echo $matchLocation ?></h5>
        <?php echo(($wasScored) ?
            '<small class="text-success">Scored</small>' :
            '<small class="text-danger">Unscored</small>'); ?>
        <form role="form" action="admin/includes/score-entry.inc.php" method="post">
            <input type="hidden" name="matchId" value="<?php echo $matchId; ?>">
            <input type="hidden" name="team1Id" value="<?php echo $team1Id; ?>">
            <input type="hidden" name="team2Id" value="<?php echo $team2Id; ?>">
            <div class="row">
                <div class="col-lg-6">
                    <?php echo '<h2 class="h3 font-weight-bold mb-4">' . $team1Name . '</h2>'; ?>
                    <hr>
                    <hr>
                    <?php for ($i = 0; $i < count($team1Emails); $i++) { ?>
                        <div class="row">
                            <div class="col-lg-4">
                                <?php echo $team1Names[$i] . "<br> (" . $team1Emails[$i] . ")"; ?>
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" id="<?php echo "t1Blinds_" . $i; ?>"
                                           name="t1Blinds[]" type="checkbox" value="<?php $i; ?>">
                                    <label class="custom-control-label" for="<?php echo "t1Blinds_" . $i; ?>">
                                        <span>Blind</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <input type="hidden" name="t1Emails[]" value="<?php echo $team1Emails[$i]; ?>">
                                    <input type="number" placeholder="Handicap" name="t1Handicaps[]"
                                           class="form-control"
                                           value="<?php echo($wasScored ? $oldHandicapsArr[$team1Emails[$i]] : $team1Handicaps[$i]); ?>"/>
                                    <input type="number" placeholder="Game 1 Score" name="t1g1[]" class="form-control"
                                           min="0"
                                           max="300"
                                           value="<?php echo($wasScored ? $oldGame1ScoreArr[$team1Emails[$i]] : ""); ?>"/>
                                    <input type="number" placeholder="Game 2 Score" name="t1g2[]" class="form-control"
                                           min="0"
                                           max="300"
                                           value="<?php echo($wasScored ? $oldGame2ScoreArr[$team1Emails[$i]] : ""); ?>"/>
                                    <input type="number" placeholder="Game 3 Score" name="t1g3[]" class="form-control"
                                           min="0"
                                           max="300"
                                           value="<?php echo($wasScored ? $oldGame3ScoreArr[$team1Emails[$i]] : ""); ?>"/>
                                </div>
                            </div>
                        </div>
                        <hr>
                    <?php } ?>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <?php echo '<h2 class="h3 font-weight-bold mb-4">' . $team2Name . '</h2>'; ?>
                    <hr>
                    <hr>
                    <?php for ($i = 0; $i < count($team2Emails); $i++) { ?>
                        <div class="row">
                            <div class="col-lg-4">
                                <?php echo $team2Names[$i] . "<br> (" . $team2Emails[$i] . ")"; ?>
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" id="<?php echo "t2Blinds_" . $i; ?>"
                                           name="t2Blinds[]" type="checkbox" value="<?php $i; ?>">
                                    <label class="custom-control-label" for="<?php echo "t2Blinds_" . $i; ?>">
                                        <span>Blind</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <input type="hidden" name="t2Emails[]" value="<?php echo $team2Emails[$i]; ?>">
                                    <input type="number" placeholder="Handicap" name="t2Handicaps[]"
                                           class="form-control"
                                           value="<?php echo($wasScored ? $oldHandicapsArr[$team2Emails[$i]] : $team2Handicaps[$i]); ?>"/>
                                    <input type="number" placeholder="Game 1 Score" name="t2g1[]" class="form-control"
                                           min="0"
                                           max="300"
                                           value="<?php echo($wasScored ? $oldGame1ScoreArr[$team2Emails[$i]] : ""); ?>"/>
                                    <input type="number" placeholder="Game 2 Score" name="t2g2[]" class="form-control"
                                           min="0"
                                           max="300"
                                           value="<?php echo($wasScored ? $oldGame2ScoreArr[$team2Emails[$i]] : ""); ?>"/>
                                    <input type="number" placeholder="Game 3 Score" name="t2g3[]" class="form-control"
                                           min="0"
                                           max="300"
                                           value="<?php echo($wasScored ? $oldGame3ScoreArr[$team2Emails[$i]] : ""); ?>"/>
                                </div>
                            </div>
                        </div>
                        <hr>
                    <?php } ?>
                </div>
            </div>
            <div class="text-center">
                <div class="custom-control custom-control-alternative custom-checkbox">
                    <input class="custom-control-input" id="shouldCountPoints" name="shouldCountPoints" type="checkbox">
                    <label class="custom-control-label" for="shouldCountPoints">
                        <span>Check this box if this match should not affect team standings (i.e. match is only for handicaps)</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-default my-4" name="submit-scores">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Core -->
<script src="assets/vendor/jquery/jquery.min.js"></script>

<script src="assets/vendor/bootstrap/bootstrap.min.js"></script>

<!-- Theme JS -->
<script src="assets/js/argon.min.js"></script>
</body>

</html>