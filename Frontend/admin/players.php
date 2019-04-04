<?php

require "includes/requireAuth.inc.php";
requireAuth(["admin", "manager", "scorer"]);

require "../includes/connection.inc.php";
require "../includes/config.inc.php";

$sql = "SELECT * FROM players;";
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

    <title>Players</title>

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
    <h2 style="float: left">Players</h2>
    <a style="float: right" class="btn btn-default" href="admin/add-player.php" role="button">Add Player</a>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Email</th>
            <th scope="col">Name</th>
            <th scope="col">Current Handicap</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['playerName'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['currentHandicap'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td><a class="btn btn-default" href="admin/edit-player.php?playerEmail=' . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . '" role="button">Edit Player</a></td>';
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
