<?php

require "includes/requireAuth.inc.php";
requireAuth(["admin", "manager", "scorer"]);
require "../includes/connection.inc.php";
require "../includes/config.inc.php";

$sql = "SELECT teams.id, teams.teamName, GROUP_CONCAT( players.playerName ) AS teamMembers
FROM teams
LEFT OUTER JOIN teamMembers ON teams.id = teamMembers.teamId
LEFT OUTER JOIN players ON teamMembers.playerEmail = players.email
GROUP BY teams.id;";
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

    <base href="<?php echo htmlspecialchars($baseURL, ENT_QUOTES, 'UTF-8'); ?>">

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
    <br>
    <h2 style="float: left">Teams</h2>
    <a style="float: right" class="btn btn-default" href="admin/create-team.php" role="button">Create Team</a>
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
            echo '<td>' . htmlspecialchars($row['teamName'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($teamMembers, ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td><a class="btn btn-default" href="admin/edit-team.php?teamId=' . $row['id'] . '" role="button">Edit Team</a></td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</main>

<!-- Core -->
<script src="assets/vendor/jquery/jquery.min.js"></script>

<script src="assets/vendor/bootstrap/bootstrap.min.js"></script>

<!-- Theme JS -->
<script src="assets/js/argon.min.js"></script>
</body>
</html>
