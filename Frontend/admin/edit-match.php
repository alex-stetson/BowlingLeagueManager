<?php

require "includes/requireAuth.inc.php";
requireAuth(["admin", "manager"]);

require "../includes/connection.inc.php";
require "../includes/config.inc.php";

$matchId = $_GET['matchId'];

if (empty($matchId)) {
    header("Location: " . $baseURL . "admin/matches.php");
    exit();
}

$sql = "SELECT matches.matchTime, matches.matchLocation, t1.teamName AS team1Name, t2.teamName AS team2Name
FROM matches LEFT OUTER JOIN teams t1 ON t1.id = matches.team1
LEFT OUTER JOIN teams t2 ON matches.team2 = t2.id
WHERE matches.id=?;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $matchId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    exit();
}

if ($row = mysqli_fetch_assoc($result)) {
    $team1Name = $row['team1Name'];
    $team2Name = $row['team2Name'];
    $matchLocation = $row['matchLocation'];
    $matchTime = $row['matchTime'];
} else {
    header("Location: " . $baseURL . "admin/matches.php");
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

    <title>Edit Match</title>

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
include_once "../includes/navbar.inc.php";
?>
<main>
    <div class="container pt-lg-md">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <h3>Edit Match</h3>
                        </div>
                        <form role="form" action="admin/includes/edit-match.inc.php" method="post">
                            <input type="hidden" name="matchId" value="<?php echo $matchId; ?>">
                            <div class="form-group mb-3">
                                <small>Team 1: <?php echo($team1Name); ?></small>
                            </div>
                            <div class="form-group">
                                <small>Team 2: <?php echo($team2Name); ?></small>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Location" name="matchLocation"
                                       value="<?php echo $matchLocation; ?>"/>
                            </div>
                            <div class="form-group">
                                <input type="datetime-local" class="form-control" name="matchTime"
                                       value="<?php echo((empty($row['matchTime']) ? '' : date("Y-m-d\TH:i:s", strtotime($matchTime)))) ?>"/>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-default my-4" name="edit-match-submit">
                                    Edit Match
                                </button>
                            </div>
                        </form>
                    </div>
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
