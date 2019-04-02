<?php

require "includes/requireAuth.inc.php";
requireAuth(["admin", "manager"]);

require "../includes/connection.inc.php";
require "../includes/config.inc.php";

$teamId = $_GET['teamId'];
$teamName = NULL;

if (empty($teamId)) {
    header("Location: " . $baseURL . "admin/teams.php");
    exit();
}

# Get current team information
$sql = "SELECT * FROM teams WHERE id=?;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $teamId);
    mysqli_stmt_execute($stmt);
    $teamResult = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    exit();
}
if ($row = mysqli_fetch_assoc($teamResult)) {
    $teamName = $row['teamName'];
} else {
    header("Location: " . $baseURL . "admin/teams.php");
    exit();
}

# Get current team members
$sql = "SELECT teamMembers.playerEmail, players.playerName
FROM players
INNER JOIN teamMembers ON players.email = teamMembers.playerEmail
WHERE teamMembers.teamId = ?;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $teamId);
    mysqli_stmt_execute($stmt);
    $teamMembersResult = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    exit();
}
$teamMemberEmails = array();
$teamMemberNames = array();
while ($row = mysqli_fetch_assoc($teamMembersResult)) {
    $teamMemberEmails[] = $row['playerEmail'];
    $teamMemberNames[] = $row['playerName'];
}

# Get all players
$sql = "SELECT * FROM players;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    exit();
}
$playerDropdownContent = NULL;
while ($row = mysqli_fetch_assoc($result)) {
    if (!in_array($row['email'], $teamMemberEmails)) {
        $playerDropdownContent .= '<option value="' . $row['email'] . '">' . $row['playerName'] . '</option>';
    }
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

    <title>Edit Team</title>

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

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
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
                            <h3>Edit Team</h3>
                        </div>
                        <form role="form" action="admin/includes/edit-team.inc.php" method="post">
                            <input type="hidden" name="teamId" value="<?php echo $teamId; ?>">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Team Name" name="teamName"
                                       value="<?php echo $teamName; ?>"/>
                            </div>
                            <div class="form-group">
                                <small>Current Players: <?php echo(implode(", ", $teamMemberNames)); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="teamPlayers">Additional Players</label>
                                <select id="teamPlayers" name="teamPlayers[]" class="form-control select2-multiple"
                                        multiple="multiple">
                                    <?php echo $playerDropdownContent; ?>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-default my-4" name="edit-team-submit">
                                    Edit Team
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

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
    let selectLimit = <?php echo(4 - count($teamMemberEmails)); ?>;
    $(document).ready(function () {
        $(".select2-multiple").select2({maximumSelectionLength: selectLimit});
        if (selectLimit === 0) {
            $(".select2-multiple").prop("disabled", true);
        }
    });
</script>
</body>
</html>
