<?php

require "includes/requireAuth.inc.php";
requireAuth(["admin", "manager", "scorer"]);
require "../includes/config.inc.php";
require "../includes/connection.inc.php";

$casUser = false;
$sql = "SELECT * FROM users WHERE userId=?;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $_SESSION['userId']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['casUser'] == 1) {
            $casUser = true;
        }
    }
} else {
    header("Location: " . $baseURL);
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

    <title>My Account</title>

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
                            <h3>Change Password</h3>
                            <?php
                            if ($casUser) {
                                echo '<small>Please change your password through your CAS provider</small>';
                            }
                            if (isset($_GET['success']) && $_GET['success'] == "password-changed") {
                                echo '<small class="text-success">Password changed!</small>';
                            } else if (isset($_GET['error'])) {
                                if ($_GET['error'] == "emptyfields") {
                                    echo '<small class="text-danger">Please fill out all the fields</small>';
                                } else if ($_GET['error'] == "password-mismatch") {
                                    echo '<small class="text-danger">New passwords didn\'t match</small>';
                                } else if ($_GET['error'] == "incorrect-password") {
                                    echo '<small class="text-danger">Incorrect current password</small>';
                                } else {
                                    echo '<small class="text-danger">Unknown error occurred. Please try again</small>';
                                }
                            }
                            ?>
                        </div>
                        <form role="form" action="admin/includes/change-password.inc.php" method="post">
                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                          <span class="input-group-text"
                          ><i class="icon-lock"></i
                              ></span>
                                    </div>
                                    <input
                                            class="form-control"
                                            placeholder="Current Password"
                                            type="password"
                                            name="currentPassword"
                                        <?php echo($casUser ? 'disabled' : null); ?>
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                          <span class="input-group-text"
                          ><i class="icon-lock"></i
                              ></span>
                                    </div>
                                    <input
                                            class="form-control"
                                            placeholder="New Password"
                                            type="password"
                                            name="newPassword"
                                        <?php echo($casUser ? 'disabled' : null); ?>
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                          <span class="input-group-text"
                          ><i class="icon-lock"></i
                              ></span>
                                    </div>
                                    <input
                                            class="form-control"
                                            placeholder="Confirm New Password"
                                            type="password"
                                            name="newPasswordConfirm"
                                        <?php echo($casUser ? 'disabled' : null); ?>
                                    />
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-default my-4"
                                        name="change-password-submit" <?php echo($casUser ? 'disabled' : null); ?>>
                                    Change Password
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
