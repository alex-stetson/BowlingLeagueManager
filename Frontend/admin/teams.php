<?php

require "includes/requireAuth.inc.php";
require "../includes/connection.inc.php";

$sql = "SELECT teams.teamName, GROUP_CONCAT(players.playerName) as teamMembers, teamMembers.teamId
FROM teams INNER JOIN teamMembers ON teams.id = teamMembers.teamId
INNER JOIN players ON teamMembers.playerEmail = players.email
GROUP BY teamMembers.teamId;";
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

    <title>Teams</title>

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
    <h2 style="float: left">Teams</h2>
    <a style="float: right" class="btn btn-default" href="/admin/create-team.php" role="button">Create Team</a>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Team Name</th>
            <th scope="col">Members</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $teamMembers = str_replace(",", ", ", $row['teamMembers']);
            echo '<tr>';
            echo '<td>' . $row['teamName'] . '</td>';
            echo '<td>' . $teamMembers . '</td>';
            echo '<td><a class="btn btn-default" href="/admin/edit-team.php?teamId=' . $row['teamId'] . '" role="button">Edit Team</a></td>';
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
