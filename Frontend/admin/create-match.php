<?php

require "includes/requireAuth.inc.php";
requireAuth(["admin", "manager"]);

require "../includes/connection.inc.php";
require "../includes/config.inc.php";

$sql = "SELECT * FROM teams;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    exit();
}

$teamDropdownContent = '<option value="NULL" selected>Choose...</option>';
while ($row = mysqli_fetch_assoc($result)) {
    $teamDropdownContent .= '<option value="' . $row['id'] . '">' . htmlspecialchars($row['teamName'], ENT_QUOTES, 'UTF-8') . '</option>';
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

    <title>Create Match</title>

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
    <div class="container pt-lg-md">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <h3>Create Match</h3>
                        </div>
                        <form role="form" action="admin/includes/create-match.inc.php" method="post">
                            <div class="form-group mb-3">
                                <label for="team1">Team 1</label>
                                <select id="team1" name="team1" class="form-control">
                                    <?php echo $teamDropdownContent; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="team2">Team 2</label>
                                <select id="team2" name="team2" class="form-control">
                                    <?php echo $teamDropdownContent; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Location" name="matchLocation"/>
                            </div>
                            <div class="form-group">
                                <input type="datetime-local" class="form-control" name="matchTime"/>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-default my-4" name="create-match-submit">
                                    Create Match
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
