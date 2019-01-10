<?php


require "includes/connection.inc.php";

$matchId = $_GET['matchId'];

if (empty($matchId)) {
    header("Location: /past-matches.php");
    exit();
}

$sql = "SELECT players.playerName, players.currentHandicap, teams.teamName, teamMembers.playerEmail, teamMembers.teamId,
matches.team1, matches.team2, matchScores.game1Score, matchScores.game2Score, matchScores.game3Score,
matches.matchTime, matches.matchLocation FROM
players INNER JOIN teamMembers ON players.email = teamMembers.playerEmail
INNER JOIN teams ON teamMembers.teamId = teams.id
INNER JOIN matches ON teams.id = matches.team1 OR teams.id = matches.team2
INNER JOIN matchScores ON teams.id = matchScores.teamId AND matches.id = matchScores.matchId AND players.email = matchScores.playerEmail
WHERE matches.id = ?;";
$scoresAvailable = false;
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $matchId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $team1Members = array();
    $team2Members = array();
    $team1Handicaps = array();
    $team2Handicaps = array();
    $team1Game1Scratch = 0;
    $team2Game1Scratch = 0;
    $team1Game2Scratch = 0;
    $team2Game2Scratch = 0;
    $team1Game3Scratch = 0;
    $team2Game3Scratch = 0;
    $team1Scores = array();
    $team2Scores = array();
    $team1Name = "";
    $team2Name = "";
    if ($row = mysqli_fetch_assoc($result)) {
        $scoresAvailable = true;
        $team1Id = $row['team1'];
        $team2Id = $row['team2'];
        $matchTime = (empty($row['matchTime']) ? '' : date('m/d/y h:i A', strtotime($row['matchTime'])));
        $matchLocation = $row['matchLocation'];
        mysqli_data_seek($result, 0);
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['teamId'] == $team1Id) {
                $team1Members[] = $row['playerName'];
                $team1Handicaps[] = $row['currentHandicap'];
                array_push($team1Scores, $row['game1Score'], $row['game2Score'], $row['game3Score']);
                $team1Game1Scratch += $row['game1Score'];
                $team1Game2Scratch += $row['game2Score'];
                $team1Game3Scratch += $row['game3Score'];
                $team1Name = $row['teamName'];
            } else {
                $team2Members[] = $row['playerName'];
                $team2Handicaps[] = $row['currentHandicap'];
                array_push($team2Scores, $row['game1Score'], $row['game2Score'], $row['game3Score']);
                $team2Game1Scratch += $row['game1Score'];
                $team2Game2Scratch += $row['game2Score'];
                $team2Game3Scratch += $row['game3Score'];
                $team2Name = $row['teamName'];
            }
        }
        $team1TotalHandicap = array_sum($team1Handicaps);
        $team2TotalHandicap = array_sum($team2Handicaps);
        $team1Game1Total = $team1Game1Scratch + $team1TotalHandicap;
        $team1Game2Total = $team1Game2Scratch + $team1TotalHandicap;
        $team1Game3Total = $team1Game3Scratch + $team1TotalHandicap;
        $team2Game1Total = $team2Game1Scratch + $team2TotalHandicap;
        $team2Game2Total = $team2Game2Scratch + $team2TotalHandicap;
        $team2Game3Total = $team2Game3Scratch + $team2TotalHandicap;
        $team1OverallTotal = $team1Game1Total + $team1Game2Total + $team1Game3Total;
        $team2OverallTotal = $team2Game1Total + $team2Game2Total + $team2Game3Total;
    } else {
        $sql = "SELECT matches.matchTime, matches.matchLocation, t1.teamName AS team1Name, t2.teamName AS team2Name
        FROM matches LEFT OUTER JOIN teams t1 ON t1.id = matches.team1
        LEFT OUTER JOIN teams t2 ON matches.team2 = t2.id
        WHERE matches.id = ?;";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $matchId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $team1Name = $row['team1Name'];
                $team2Name = $row['team2Name'];
                $matchLocation = $row['matchLocation'];
                $matchTime = $row['matchTime'];
            } else {
                header("Location: /past-matches.php");
                exit();
            }
        } else {
            header("Location: /past-matches.php");
            exit();
        }
    }
} else {
    header("Location: /past-matches.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <title>Match Details</title>

    <!-- Favicon -->
    <link href="assets/img/brand/favicon.png" rel="icon" type="image/png" />

    <!-- Fonts -->
    <link
      href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link
      href="assets/vendor/font-awesome/css/font-awesome.min.css"
      rel="stylesheet"
    />

    <!-- Argon CSS -->
    <link type="text/css" href="assets/css/argon.css" rel="stylesheet" />
  </head>

  <body>
    <?php
      include_once "includes/navbar.inc.php";
    ?>
    <main>
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <h1 class="h1 font-weight-bold mb-4">Match Details</h1>
          <h3 class="h3"><?php echo($team1Name.' vs '.$team2Name);?></h3>
          <small><?php echo $matchTime; ?></small>
          <br>
          <small><?php echo $matchLocation; ?></small>
          <hr>
          <?php if($scoresAvailable) { ?>
            <h4 class="h4"><?php echo $team1Name; ?></h4>
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">Player</th>
                    <th scope="col">Handicap</th>
                    <th scope="col">Game 1 Score</th>
                    <th scope="col">Game 2 Score</th>
                    <th scope="col">Game 3 Score</th>
                    <th scope="col" style="border-left: 2px solid">Total Scratch Score</th>
                    <th scope="col">Total Handicap</th>
                    <th scope="col">Total Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $scoreIndex = 0;
                    for($i=0;$i<count($team1Members);$i++) {
                        echo '<tr>';
                        echo '<th>'.$team1Members[$i].'</th>';
                        echo '<td>'.$team1Handicaps[$i].'</td>';
                        echo '<td>'.$team1Scores[$scoreIndex].'</td>';
                        echo '<td>'.$team1Scores[($scoreIndex+1)].'</td>';
                        echo '<td>'.$team1Scores[($scoreIndex+2)].'</td>';
                        $totalScratch = $team1Scores[$scoreIndex] + $team1Scores[($scoreIndex+1)] + $team1Scores[($scoreIndex+2)];
                        echo '<td style="border-left: 2px solid">'.$totalScratch.'</td>';
                        echo '<td>'.($team1Handicaps[$i] * 3).'</td>';
                        echo '<td>'.($totalScratch + ($team1Handicaps[$i] * 3)).'</td>';
                        echo '</tr>';
                        $scoreIndex += 3;
                    }
                    ?>
                    <tr style="border-top: 3px solid">
                    <th>Team Total (Scratch)</th>
                    <td></td>
                    <td><?php echo $team1Game1Scratch ;?></td>
                    <td><?php echo $team1Game2Scratch ;?></td>
                    <td><?php echo $team1Game3Scratch ;?></td>
                    <td style="border-left: 2px solid"></td>
                    <td></td>
                    <td></td>
                    </tr>
                    <tr>
                    <th>Team Handicap</th>
                    <td><?php echo $team1TotalHandicap; ?></td>
                    <td><?php echo $team1TotalHandicap; ?></td>
                    <td><?php echo $team1TotalHandicap; ?></td>
                    <td><?php echo $team1TotalHandicap; ?></td>
                    <td style="border-left: 2px solid"></td>
                    <td></td>
                    <td></td>
                    </tr>
                    <tr>
                    <th>Team Total (Overall)</th>
                    <td></td>
                    <?php echo (($team1Game1Total >= $team2Game1Total) ?
                        '<td class="text-success">'.$team1Game1Total.'</td>' :
                        '<td class="text-danger">'.$team1Game1Total.'</td>'); ?>
                    <?php echo (($team1Game2Total >= $team2Game2Total) ?
                        '<td class="text-success">'.$team1Game2Total.'</td>' :
                        '<td class="text-danger">'.$team1Game2Total.'</td>'); ?>
                    <?php echo (($team1Game3Total >= $team2Game3Total) ?
                        '<td class="text-success">'.$team1Game3Total.'</td>' :
                        '<td class="text-danger">'.$team1Game3Total.'</td>'); ?>
                    <td style="border-left: 2px solid"></td>
                    <td></td>
                    <?php echo (($team1OverallTotal >= $team2OverallTotal) ?
                        '<td class="text-success">'.$team1OverallTotal.'</td>' :
                        '<td class="text-danger">'.$team1OverallTotal.'</td>'); ?>
                    </tr>
                </tbody>
            </table>
            <hr>
            <h4 class="h4"><?php echo $team2Name; ?></h4>
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">Player</th>
                    <th scope="col">Handicap</th>
                    <th scope="col">Game 1 Score</th>
                    <th scope="col">Game 2 Score</th>
                    <th scope="col">Game 3 Score</th>
                    <th scope="col" style="border-left: 2px solid">Total Scratch Score</th>
                    <th scope="col">Total Handicap</th>
                    <th scope="col">Total Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $scoreIndex = 0;
                    for($i=0;$i<count($team2Members);$i++) {
                        echo '<tr>';
                        echo '<th>'.$team2Members[$i].'</th>';
                        echo '<td>'.$team2Handicaps[$i].'</td>';
                        echo '<td>'.$team2Scores[$scoreIndex].'</td>';
                        echo '<td>'.$team2Scores[($scoreIndex+1)].'</td>';
                        echo '<td>'.$team2Scores[($scoreIndex+2)].'</td>';
                        $totalScratch = $team2Scores[$scoreIndex] + $team2Scores[($scoreIndex+1)] + $team2Scores[($scoreIndex+2)];
                        echo '<td style="border-left: 2px solid">'.$totalScratch.'</td>';
                        echo '<td>'.($team2Handicaps[$i] * 3).'</td>';
                        echo '<td>'.($totalScratch + ($team2Handicaps[$i] * 3)).'</td>';
                        echo '</tr>';
                        $scoreIndex += 3;
                    }
                    ?>
                    <tr style="border-top: 3px solid">
                    <th>Team Total (Scratch)</th>
                    <td></td>
                    <td><?php echo $team2Game1Scratch ;?></td>
                    <td><?php echo $team2Game2Scratch ;?></td>
                    <td><?php echo $team2Game3Scratch ;?></td>
                    <td style="border-left: 2px solid"></td>
                    <td></td>
                    <td></td>
                    </tr>
                    <tr>
                    <th>Team Handicap</th>
                    <td><?php echo $team2TotalHandicap; ?></td>
                    <td><?php echo $team2TotalHandicap; ?></td>
                    <td><?php echo $team2TotalHandicap; ?></td>
                    <td><?php echo $team2TotalHandicap; ?></td>
                    <td style="border-left: 2px solid"></td>
                    <td></td>
                    <td></td>
                    </tr>
                    <tr>
                    <th>Team Total (Overall)</th>
                    <td></td>
                    <?php echo (($team2Game1Total >= $team1Game1Total) ?
                        '<td class="text-success">'.$team2Game1Total.'</td>' :
                        '<td class="text-danger">'.$team2Game1Total.'</td>'); ?>
                    <?php echo (($team2Game2Total >= $team1Game2Total) ?
                        '<td class="text-success">'.$team2Game2Total.'</td>' :
                        '<td class="text-danger">'.$team2Game2Total.'</td>'); ?>
                    <?php echo (($team2Game3Total >= $team1Game3Total) ?
                        '<td class="text-success">'.$team2Game3Total.'</td>' :
                        '<td class="text-danger">'.$team2Game3Total.'</td>'); ?>
                    <td style="border-left: 2px solid"></td>
                    <td></td>
                    <?php echo (($team2OverallTotal >= $team1OverallTotal) ?
                        '<td class="text-success">'.$team2OverallTotal.'</td>' :
                        '<td class="text-danger">'.$team2OverallTotal.'</td>'); ?>
                    </tr>
                </tbody>
            </table>
        <?php } else { ?>
            <h4 class="h4">Scores Currently Unavailable</h4>
        <?php } ?>
        </div>
      </div>
    </main>

    <!-- Core -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/bootstrap.min.js"></script>

    <!-- Theme JS -->
    <script src="assets/js/argon.js"></script>
  </body>
</html>