<?php

require "includes/requireAuth.inc.php";
require "../includes/config.inc.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <title>Add Player</title>

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
                            <h3>Add Player</h3>
                            <?php
                            if (isset($_GET['success']) && $_GET['success'] == "true") {
                                echo '<small class="text-success">Player Added!</small>';
                            } else if (isset($_GET['error'])) {
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
                        <form role="form" action="admin/includes/add-player.inc.php" method="post">
                            <div class="form-group mb-3">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                          <span class="input-group-text"
                          ><i class="icon-envelope"></i
                              ></span>
                                    </div>
                                    <input
                                            class="form-control"
                                            placeholder="Email"
                                            type="email"
                                            name="playerEmail"
                                    />
                                </div>
                            </div>
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
                                    />
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-default my-4" name="add-player-submit">
                                    Add Player
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
