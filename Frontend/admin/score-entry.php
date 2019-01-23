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
    $team1Members = array();
    $team2Members = array();
    $team1Handicaps = array();
    $team2Handicaps = array();
    $team1Id = 0;
    $team2Id = 0;
    $team1Name = "";
    $team2Name = "";
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['teamId'] == $row['team1']) {
            $team1Members[] = $row['playerEmail'];
            $team1Members[] = $row['playerName'];
            $team1Handicaps[] = $row['currentHandicap'];
            $team1Name = $row['teamName'];
            $team1Id = $row['team1'];
        } else {
            $team2Members[] = $row['playerEmail'];
            $team2Members[] = $row['playerName'];
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
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo $team1Members[1] . "<br> (" . $team1Members[0] . ")"; ?>
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                <input class="custom-control-input" id="p1Blind" name="p1Blind" type="checkbox">
                                <label class="custom-control-label" for="p1Blind">
                                    <span>Blind</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <input type="hidden" name="p1Email" value="<?php echo $team1Members[0]; ?>">
                                <input type="number" placeholder="Handicap" name="p1Handicap" class="form-control"
                                       value="<?php echo $team1Handicaps[0]; ?>"/>
                                <input type="number" placeholder="Game 1 Score" name="p1g1" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 2 Score" name="p1g2" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 3 Score" name="p1g3" class="form-control" min="0"
                                       max="300"/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo $team1Members[3] . "<br> (" . $team1Members[2] . ")"; ?>
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                <input class="custom-control-input" id="p2Blind" name="p2Blind" type="checkbox">
                                <label class="custom-control-label" for="p2Blind">
                                    <span>Blind</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <input type="hidden" name="p2Email" value="<?php echo $team1Members[2]; ?>">
                                <input type="number" placeholder="Handicap" name="p2Handicap" class="form-control"
                                       value="<?php echo $team1Handicaps[1]; ?>"/>
                                <input type="number" placeholder="Game 1 Score" name="p2g1" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 2 Score" name="p2g2" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 3 Score" name="p2g3" class="form-control" min="0"
                                       max="300"/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo $team1Members[5] . "<br> (" . $team1Members[4] . ")"; ?>
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                <input class="custom-control-input" id="p3Blind" name="p3Blind" type="checkbox">
                                <label class="custom-control-label" for="p3Blind">
                                    <span>Blind</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <input type="hidden" name="p3Email" value="<?php echo $team1Members[4]; ?>">
                                <input type="number" placeholder="Handicap" name="p3Handicap" class="form-control"
                                       value="<?php echo $team1Handicaps[2]; ?>"/>
                                <input type="number" placeholder="Game 1 Score" name="p3g1" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 2 Score" name="p3g2" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 3 Score" name="p3g3" class="form-control" min="0"
                                       max="300"/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo $team1Members[7] . "<br> (" . $team1Members[6] . ")"; ?>
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                <input class="custom-control-input" id="p4Blind" name="p4Blind" type="checkbox">
                                <label class="custom-control-label" for="p4Blind">
                                    <span>Blind</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <input type="hidden" name="p4Email" value="<?php echo $team1Members[6]; ?>">
                                <input type="number" placeholder="Handicap" name="p4Handicap" class="form-control"
                                       value="<?php echo $team1Handicaps[3]; ?>"/>
                                <input type="number" placeholder="Game 1 Score" name="p4g1" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 2 Score" name="p4g2" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 3 Score" name="p4g3" class="form-control" min="0"
                                       max="300"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <?php echo '<h2 class="h3 font-weight-bold mb-4">' . $team2Name . '</h2>'; ?>
                    <hr>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo $team2Members[1] . "<br> (" . $team2Members[0] . ")"; ?>
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                <input class="custom-control-input" id="p5Blind" name="p5Blind" type="checkbox">
                                <label class="custom-control-label" for="p5Blind">
                                    <span>Blind</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <input type="hidden" name="p5Email" value="<?php echo $team2Members[0]; ?>">
                                <input type="number" placeholder="Handicap" name="p5Handicap" class="form-control"
                                       value="<?php echo $team2Handicaps[0]; ?>"/>
                                <input type="number" placeholder="Game 1 Score" name="p5g1" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 2 Score" name="p5g2" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 3 Score" name="p5g3" class="form-control" min="0"
                                       max="300"/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo $team2Members[3] . "<br> (" . $team2Members[2] . ")"; ?>
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                <input class="custom-control-input" id="p6Blind" name="p6Blind" type="checkbox">
                                <label class="custom-control-label" for="p6Blind">
                                    <span>Blind</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <input type="hidden" name="p6Email" value="<?php echo $team2Members[2]; ?>">
                                <input type="number" placeholder="Handicap" name="p6Handicap" class="form-control"
                                       value="<?php echo $team2Handicaps[1]; ?>"/>
                                <input type="number" placeholder="Game 1 Score" name="p6g1" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 2 Score" name="p6g2" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 3 Score" name="p6g3" class="form-control" min="0"
                                       max="300"/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo $team2Members[5] . "<br> (" . $team2Members[4] . ")"; ?>
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                <input class="custom-control-input" id="p7Blind" name="p7Blind" type="checkbox">
                                <label class="custom-control-label" for="p7Blind">
                                    <span>Blind</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <input type="hidden" name="p7Email" value="<?php echo $team2Members[4]; ?>">
                                <input type="number" placeholder="Handicap" name="p7Handicap" class="form-control"
                                       value="<?php echo $team2Handicaps[2]; ?>"/>
                                <input type="number" placeholder="Game 1 Score" name="p7g1" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 2 Score" name="p7g2" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 3 Score" name="p7g3" class="form-control" min="0"
                                       max="300"/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <?php echo $team2Members[7] . "<br> (" . $team2Members[6] . ")"; ?>
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                <input class="custom-control-input" id="p8Blind" name="p8Blind" type="checkbox">
                                <label class="custom-control-label" for="p8Blind">
                                    <span>Blind</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <input type="hidden" name="p8Email" value="<?php echo $team2Members[6]; ?>">
                                <input type="number" placeholder="Handicap" name="p8Handicap" class="form-control"
                                       value="<?php echo $team2Handicaps[3]; ?>"/>
                                <input type="number" placeholder="Game 1 Score" name="p8g1" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 2 Score" name="p8g2" class="form-control" min="0"
                                       max="300"/>
                                <input type="number" placeholder="Game 3 Score" name="p8g3" class="form-control" min="0"
                                       max="300"/>
                            </div>
                        </div>
                    </div>
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