<?php

require "includes/config.inc.php";
require "includes/connection.inc.php";

$sql = "SELECT DISTINCT(ms.playerEmail), players.playerName, groupedms.maxScore FROM matchScores ms 
INNER JOIN (SELECT playerEmail, GREATEST(MAX(game1Score), MAX(game2Score), MAX(game3Score)) AS maxScore, MAX(matchId) AS maxID FROM matchScores AS msMax WHERE msMax.isBlind = 0 GROUP BY playerEmail) groupedms ON ms.playerEmail = groupedms.playerEmail AND GREATEST(ms.game1Score, ms.game2Score, ms.game3Score) = groupedms.maxScore 
LEFT OUTER JOIN players ON ms.playerEmail = players.email
ORDER BY maxScore DESC;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_execute($stmt);
    $highScratchGame = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    exit();
}

$sql = "SELECT DISTINCT(ms.playerEmail), players.playerName, groupedms.maxScore FROM matchScores ms 
INNER JOIN (SELECT playerEmail, MAX(game1Score + game2Score + game3Score) AS maxScore, MAX(matchId) AS maxID FROM matchScores AS msMax WHERE msMax.isBlind = 0 GROUP BY playerEmail) groupedms ON ms.playerEmail = groupedms.playerEmail 
LEFT OUTER JOIN players ON ms.playerEmail = players.email
ORDER BY maxScore DESC;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_execute($stmt);
    $highScratchSeries = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    exit();
}

$sql = "SELECT DISTINCT(ms.playerEmail), players.playerName, groupedms.maxScore FROM matchScores ms 
INNER JOIN (SELECT playerEmail, GREATEST(MAX(game1Score + handicap), MAX(game2Score + handicap), MAX(game3Score + handicap)) AS maxScore, MAX(matchId) AS maxID FROM matchScores AS msMax WHERE msMax.isBlind = 0 GROUP BY playerEmail) groupedms ON ms.playerEmail = groupedms.playerEmail
LEFT OUTER JOIN players ON ms.playerEmail = players.email
ORDER BY maxScore DESC;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_execute($stmt);
    $highHandicapGame = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    exit();
}

$sql = "SELECT DISTINCT(ms.playerEmail), players.playerName, groupedms.maxScore FROM matchScores ms 
INNER JOIN (SELECT playerEmail, MAX(handicap + game1Score + game2Score + game3Score) AS maxScore, MAX(matchId) AS maxID FROM matchScores AS msMax WHERE msMax.isBlind = 0 GROUP BY playerEmail) groupedms ON ms.playerEmail = groupedms.playerEmail 
LEFT OUTER JOIN players ON ms.playerEmail = players.email
ORDER BY maxScore DESC;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_execute($stmt);
    $highHandicapSeries = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <title>Statistics</title>

    <base href="<?php echo $baseURL; ?>">

    <!-- Favicon -->
    <link href="assets/img/brand/favicon.png" rel="icon" type="image/png"/>

    <!-- Fonts -->
    <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
            rel="stylesheet"
    />

    <!-- Icons -->
    <link href="assets/vendor/icomoon/icomoon.min.css" rel="stylesheet"/>

    <!-- Argon CSS -->
    <link type="text/css" href="assets/css/argon.min.css" rel="stylesheet"/>
</head>

<body>
<?php
include_once "includes/navbar.inc.php";
?>
<main>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <h1 class="h1 font-weight-bold mb-4">Statistics</h1>

            <div class="nav-wrapper">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-text" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-scratch-game-tab" data-toggle="tab"
                           href="#tabs-scratch-game" role="tab" aria-controls="tabs-scratch-game" aria-selected="true">Scratch
                            Game</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-scratch-series-tab" data-toggle="tab"
                           href="#tabs-scratch-series" role="tab" aria-controls="tabs-scratch-series"
                           aria-selected="false">Scratch Series</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-handicap-game-tab" data-toggle="tab"
                           href="#tabs-handicap-game" role="tab" aria-controls="tabs-handicap-game"
                           aria-selected="false">Handicap Game</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-handicap-series-tab" data-toggle="tab"
                           href="#tabs-handicap-series" role="tab" aria-controls="tabs-handicap-series"
                           aria-selected="false">Handicap Series</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tabs-scratch-game" role="tabpanel"
                     aria-labelledby="tabs-scratch-game-tab">
                    <h3 class="h3 mb-4">Highest Scratch Game</h3>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Player Name</th>
                            <th scope="col">Score</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $rank = 0;
                        $increment = 1;
                        $prevScore = PHP_INT_MAX;
                        while ($row = mysqli_fetch_assoc($highScratchGame)) {
                            if ($row['maxScore'] < $prevScore) {
                                $rank += $increment;
                                $increment = 1;
                                $prevScore = $row['maxScore'];
                            } else {
                                $increment += 1;
                            }
                            echo '<tr>';
                            echo '<th scope="row">' . $rank . '</th>';
                            echo '<td>' . $row['playerName'] . '</td>';
                            echo '<td>' . $row['maxScore'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tabs-scratch-series" role="tabpanel"
                     aria-labelledby="tabs-scratch-series-tab">
                    <h3 class="h3 mb-4">Highest Scratch Series</h3>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Player Name</th>
                            <th scope="col">Score</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $rank = 0;
                        $increment = 1;
                        $prevScore = PHP_INT_MAX;
                        while ($row = mysqli_fetch_assoc($highScratchSeries)) {
                            if ($row['maxScore'] < $prevScore) {
                                $rank += $increment;
                                $increment = 1;
                                $prevScore = $row['maxScore'];
                            } else {
                                $increment += 1;
                            }
                            echo '<tr>';
                            echo '<th scope="row">' . $rank . '</th>';
                            echo '<td>' . $row['playerName'] . '</td>';
                            echo '<td>' . $row['maxScore'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tabs-handicap-game" role="tabpanel"
                     aria-labelledby="tabs-handicap-game-tab">
                    <h3 class="h3 mb-4">Highest Handicap Game</h3>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Player Name</th>
                            <th scope="col">Score</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $rank = 0;
                        $increment = 1;
                        $prevScore = PHP_INT_MAX;
                        while ($row = mysqli_fetch_assoc($highHandicapGame)) {
                            if ($row['maxScore'] < $prevScore) {
                                $rank += $increment;
                                $increment = 1;
                                $prevScore = $row['maxScore'];
                            } else {
                                $increment += 1;
                            }
                            echo '<tr>';
                            echo '<th scope="row">' . $rank . '</th>';
                            echo '<td>' . $row['playerName'] . '</td>';
                            echo '<td>' . $row['maxScore'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tabs-handicap-series" role="tabpanel"
                     aria-labelledby="tabs-handicap-series-tab">
                    <h3 class="h3 mb-4">Highest Handicap Series</h3>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Player Name</th>
                            <th scope="col">Score</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $rank = 0;
                        $increment = 1;
                        $prevScore = PHP_INT_MAX;
                        while ($row = mysqli_fetch_assoc($highHandicapSeries)) {
                            if ($row['maxScore'] < $prevScore) {
                                $rank += $increment;
                                $increment = 1;
                                $prevScore = $row['maxScore'];
                            } else {
                                $increment += 1;
                            }
                            echo '<tr>';
                            echo '<th scope="row">' . $rank . '</th>';
                            echo '<td>' . $row['playerName'] . '</td>';
                            echo '<td>' . $row['maxScore'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Core -->
<script src="assets/vendor/jquery/jquery.min.js"></script>

<script src="assets/vendor/bootstrap/bootstrap.min.js"></script>

<!-- Theme JS -->
<script src="assets/js/argon.min.js"></script>
</body>
</html>
