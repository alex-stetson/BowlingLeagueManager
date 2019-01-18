<?php

require "includes/requireAuth.inc.php";

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

$playerDropdownContent = NULL;
while ($row = mysqli_fetch_assoc($result)) {
    $playerDropdownContent .= '<option value="' . $row['email'] . '">' . $row['playerName'] . '</option>';
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

    <title>Create Team</title>

    <base href="<?php echo $baseURL; ?>">

    <!-- Favicon -->
    <link href="assets/img/brand/favicon.png" rel="icon" type="image/png"/>

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
    <link type="text/css" href="assets/css/argon.css" rel="stylesheet"/>

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
                            <h3>Create Team</h3>
                        </div>
                        <form role="form" action="admin/includes/create-team.inc.php" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Team Name" name="teamName"/>
                            </div>
                            <div class="form-group mb-3">
                                <label for="teamPlayers">Players</label>
                                <select id="teamPlayers" name="teamPlayers[]" class="form-control select2-multiple"
                                        multiple="multiple">
                                    <?php echo $playerDropdownContent; ?>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-default my-4" name="create-team-submit">
                                    Create Team
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
<script src="assets/vendor/popper/popper.min.js"></script>
<script src="assets/vendor/bootstrap/bootstrap.min.js"></script>

<!-- Theme JS -->
<script src="assets/js/argon.js"></script>

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2-multiple').select2({maximumSelectionLength: 4});
    });
</script>
</body>
</html>
