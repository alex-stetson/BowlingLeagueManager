<?php

require "includes/requireAuth.inc.php";
requireAuth(["admin", "manager"]);

if (!isset($_GET['playerEmail'])) {
    header("Location: " . $baseURL . "admin/players.php");
    exit();
}

require "../includes/connection.inc.php";
require "../includes/config.inc.php";

$playerEmail = $_GET['playerEmail'];

if (empty($playerEmail)) {
    header("Location: " . $baseURL . "admin/players.php");
    exit();
} else {
    $sql = "SELECT * FROM players WHERE email=?;";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $playerEmail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        if (mysqli_num_rows($result) == 0) {
            header("Location: " . $baseURL . "admin/players.php");
            exit();
        } else {
            $row = mysqli_fetch_assoc($result);
            $playerEmail = $row['email'];
            $playerName = $row['playerName'];
        }
    } else {
        header("Location: " . $baseURL . "admin/players.php");
        exit();
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

    <title>Edit Player</title>

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
                            <h3>Edit Player</h3>
                            <?php
                            if (isset($_GET['error'])) {
                                if ($_GET['error'] == "emptyfields") {
                                    echo '<small class="text-danger">Please fill out all the fields</small>';
                                } else if ($_GET['error'] = "playerExists") {
                                    echo '<small class="text-danger">Player already exists</small>';
                                } else {
                                    echo '<small class="text-danger">Unknown error occured. Please try again</small>';
                                }
                            }
                            ?>
                        </div>
                        <form role="form" action="admin/includes/edit-player.inc.php" method="post">
                            <input type="hidden" name="playerEmail" value="<?php echo $playerEmail; ?>">
                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                          <span class="input-group-text"
                          ><i class="icon-user"></i
                              ></span>
                                    </div>
                                    <input
                                            class="form-control"
                                            placeholder="Name"
                                            type="text"
                                            name="playerName"
                                            value="<?php echo $playerName; ?>"
                                    />
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-default my-4" name="edit-player-submit">
                                    Edit Player
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

<script src="../assets/vendor/bootstrap/bootstrap.min.js"></script>

<!-- Theme JS -->
<script src="assets/js/argon.min.js"></script>
</body>
</html>
