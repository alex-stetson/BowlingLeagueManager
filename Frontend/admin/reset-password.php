<?php

require "../includes/config.inc.php";

$selector = $_GET['selector'];
$validator = $_GET['validator'];

if (!(isset($_GET['success']) || (isset($_GET['error']) && $_GET['error'] == "resubmit"))) {
    if (empty($selector) || empty($validator)) {
        header("Location: " . $baseURL . "admin/forgot-password.php?error=resubmit");
        exit();
    }

    if (!ctype_xdigit($selector) || !ctype_xdigit($validator)) {
        header("Location: " . $baseURL . "admin/forgot-password.php?error=resubmit");
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

    <title>Reset Password</title>

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
<main>
    <section class="section section-shaped section-lg">
        <div class="shape shape-style-1 bg-gradient-default" style="position: fixed">
            <span></span> <span></span> <span></span> <span></span> <span></span>
            <span></span> <span></span> <span></span>
        </div>
        <div class="container pt-lg-md">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card bg-secondary shadow border-0">
                        <div class="card-body px-lg-5 py-lg-5">
                            <div class="text-center text-muted mb-4">
                                <h5>Reset Password</h5>
                                <?php
                                if (isset($_GET['error'])) {
                                    if ($_GET['error'] == "emptyfields") {
                                        echo '<small class="text-danger">Please fill out all the fields</small>';
                                    } else if ($_GET['error'] == "password-mismatch") {
                                        echo '<small class="text-danger">Entered passwords did not match</small>';
                                    } else if ($_GET['error'] == "resubmit") {
                                        echo '<small class="text-danger">Invalid reset token. Please re-submit your password reset request.</small>';
                                    } else {
                                        echo '<small class="text-danger">Unknown error occurred. Please try again</small>';
                                    }
                                } else if (isset($_GET['success'])) {
                                    echo '<small class="text-success">Password reset successfully!</small>';
                                }
                                ?>
                            </div>
                            <form role="form" action="admin/includes/reset-password.inc.php" method="post">
                                <div class="form-group">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <input type="hidden" name="resetSelector" value="<?php echo $selector; ?>"/>
                                            <input type="hidden" name="resetValidator"
                                                   value="<?php echo $validator; ?>"/>
                                            <span class="input-group-text"
                                            ><i class="icon-lock"></i
                                                ></span>
                                        </div>
                                        <input
                                                class="form-control"
                                                placeholder="Password"
                                                type="password"
                                                name="resetPassword"
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
                                                placeholder="Confirm Password"
                                                type="password"
                                                name="resetPasswordConfirm"
                                        />
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-default my-4" name="reset-password-submit">
                                        Submit
                                    </button>
                                </div>
                                <div class="text-center">
                                    <a href="admin/login.php">
                                        <small>Back to Login</small>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Core -->
<script src="assets/vendor/jquery/jquery.min.js"></script>

<script src="assets/vendor/bootstrap/bootstrap.min.js"></script>

<!-- Theme JS -->
<script src="assets/js/argon.min.js"></script>
</body>
</html>
