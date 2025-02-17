<?php

require "../includes/config.inc.php";

session_start();
if (isset($_SESSION['userId']) && $_SESSION['userId'] != '') {
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

    <title>Login</title>

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
                                <h5>Sign In</h5>
                                <?php if ($casEnabled) { ?>
                                    <form action="<?php echo htmlspecialchars($baseURL, ENT_QUOTES, 'UTF-8') . 'admin/cas-login.php' ?>">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-default my-4">
                                                Sign in with CAS
                                            </button>
                                        </div>
                                    </form>
                                <?php } ?>
                                <?php
                                if (isset($_GET['error'])) {
                                    if ($_GET['error'] == "emptyfields") {
                                        echo '<small class="text-danger">Please fill out all the fields</small>';
                                    } else if ($_GET['error'] == "lockout") {
                                        $lockoutTime = (is_numeric($_GET['lockoutTime']) ? ceil($_GET['lockoutTime'] / 60) : "a few");
                                        echo '<small class="text-danger">Too many login attempts.
                        Please wait ' . $lockoutTime . ' minute(s) and try again.</small>';
                                    } else if ($_GET['error'] == "incorrectcreds") {
                                        echo '<small class="text-danger">Incorrect Email or Password</small>';
                                    } else if ($_GET['error'] == "unauthorizedCASUser") {
                                        echo '<small class="text-danger">You are unauthorized to sign in to this site via CAS</small>';
                                    } else {
                                        echo '<small class="text-danger">Unknown error occurred. Please try again</small>';
                                    }
                                }
                                ?>
                            </div>
                            <form role="form" action="admin/includes/login.inc.php" method="post">
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
                                                name="loginEmail"
                                            <?php
                                            if (isset($_GET['loginEmail'])) {
                                                echo 'value="' . $_GET['loginEmail'] . '"';
                                            }
                                            ?>
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
                                                placeholder="Password"
                                                type="password"
                                                name="loginPassword"
                                        />
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a href="admin/forgot-password.php">
                                        <small>Forgot Password</small>
                                    </a>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-default my-4" name="login-submit">
                                        Sign in
                                    </button>
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
