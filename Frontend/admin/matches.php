<?php

require "includes/requireAuth.inc.php";

require "../includes/connection.inc.php";

$sql = "SELECT matches.id, matches.matchTime, matches.matchLocation, t1.teamName AS team1Name, t2.teamName AS team2Name
FROM matches LEFT OUTER JOIN teams t1 ON t1.id = matches.team1
LEFT OUTER JOIN teams t2 ON matches.team2 = t2.id
ORDER BY matches.matchTime ASC;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
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

    <title>Matches</title>

    <!-- Favicon -->
    <link href="../assets/img/brand/favicon.png" rel="icon" type="image/png"/>

    <!-- Fonts -->
    <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
            rel="stylesheet"
    />

    <!-- Icons -->
    <link
            href="../assets/vendor/font-awesome/css/font-awesome.min.css"
            rel="stylesheet"
    />

    <!-- Argon CSS -->
    <link type="text/css" href="../assets/css/argon.css" rel="stylesheet"/>
</head>

<body>
<?php
include_once "../includes/navbar.inc.php";
?>
<main>
    <br>
    <h2 style="float: left">Matches</h2>
    <a style="float: right" class="btn btn-default" href="/admin/create-match.php" role="button">Create Match</a>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Match</th>
            <th scope="col">Time</th>
            <th scope="col">Location</th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['team1Name'] . ' vs ' . $row['team2Name'] . '</td>';
            echo '<td>' . (empty($row['matchTime']) ? '' : date('m/d/y h:i A', strtotime($row['matchTime']))) . '</td>';
            echo '<td>' . $row['matchLocation'] . '</td>';
            echo '<td><a class="btn btn-default" href="/admin/edit-match.php?matchId=' . $row['id'] . '" role="button">Edit Match</a></td>';
            echo '<td><a class="btn btn-default" href="/admin/match-scoresheet.php?matchId=' . $row['id'] . '" role="button">Generate Scoresheet</a></td>';
            echo '<td><a class="btn btn-default" href="/admin/score-entry.php?matchId=' . $row['id'] . '" role="button">Enter Scores</a></td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</main>

<!-- Core -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/popper/popper.min.js"></script>
<script src="../assets/vendor/bootstrap/bootstrap.min.js"></script>

<!-- Theme JS -->
<script src="../assets/js/argon.js"></script>
</body>
</html>
