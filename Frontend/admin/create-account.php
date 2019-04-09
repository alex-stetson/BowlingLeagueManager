<?php

require "includes/requireAuth.inc.php";
requireAuth(["admin"]);

require "../includes/connection.inc.php";
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

    <title>Create Account</title>

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
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <h1 class="h1 font-weight-bold mb-4">Create Account</h1>
            <?php
            if (isset($_GET['success'])) {
                if ($_GET['success'] == "casuseradded") {
                    echo '<small class="text-success">CAS User Added!</small>';
                } else if ($_GET['success'] == "useradded") {
                    echo '<small class="text-success">Account activation email sent! They will have 
                    24 hours to click on the link in the email and finish setting up their account.</small>';
                }
            } else if (isset($_GET['error'])) {
                if ($_GET['error'] == "emptyfields") {
                    echo '<small class="text-danger">Please fill out all the fields</small>';
                } else if ($_GET['error'] = "userExists") {
                    echo '<small class="text-danger">User already exists</small>';
                } else {
                    echo '<small class="text-danger">Unknown error occurred. Please try again</small>';
                }
            }
            ?>
            <?php if ($casEnabled) { ?>
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-regular-account-tab" data-toggle="tab"
                               href="#tabs-regular-account" role="tab" aria-controls="tabs-regular-account"
                               aria-selected="true">Regular Account</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-cas-account-tab" data-toggle="tab"
                               href="#tabs-cas-account" role="tab" aria-controls="tabs-cas-account"
                               aria-selected="false">CAS Account</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tabs-regular-account" role="tabpanel"
                         aria-labelledby="tabs-regular-account-tab">
                        <div class="container pt-lg-md">
                            <div class="row justify-content-center">
                                <div class="col-lg-5">
                                    <div class="card bg-secondary shadow border-0">
                                        <div class="card-body px-lg-5 py-lg-5">
                                            <div class="text-center text-muted mb-4">
                                                <h3>Add Regular User</h3>
                                            </div>
                                            <form role="form" action="admin/includes/create-account.inc.php"
                                                  method="post">
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
                                                                name="userEmail"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <small>User Role</small>
                                                    <select class="form-control" name="userRole">
                                                        <option value="scorer">Scorer</option>
                                                        <option value="manager">Manager</option>
                                                        <option value="admin">Admin</option>
                                                    </select>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-default my-4"
                                                            name="create-account-submit">
                                                        Send Activation Email
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs-cas-account" role="tabpanel"
                         aria-labelledby="tabs-cas-account-tab">
                        <div class="container pt-lg-md">
                            <div class="row justify-content-center">
                                <div class="col-lg-5">
                                    <div class="card bg-secondary shadow border-0">
                                        <div class="card-body px-lg-5 py-lg-5">
                                            <div class="text-center text-muted mb-4">
                                                <h3>Add CAS User</h3>
                                            </div>
                                            <form role="form" action="admin/includes/create-cas-account.inc.php"
                                                  method="post">
                                                <div class="form-group mb-3">
                                                    <div class="input-group input-group-alternative">
                                                        <div class="input-group-prepend">
                          <span class="input-group-text"
                          ><i class="icon-envelope"></i
                              ></span>
                                                        </div>
                                                        <input
                                                                class="form-control"
                                                                placeholder="User ID"
                                                                type="text"
                                                                name="userId"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <small>User Role</small>
                                                    <select class="form-control" name="userRole">
                                                        <option value="scorer">Scorer</option>
                                                        <option value="manager">Manager</option>
                                                        <option value="admin">Admin</option>
                                                    </select>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-default my-4"
                                                            name="create-cas-account-submit">
                                                        Add CAS User
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="container pt-lg-md">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card bg-secondary shadow border-0">
                                <div class="card-body px-lg-5 py-lg-5">
                                    <div class="text-center text-muted mb-4">
                                        <h3>Add User</h3>
                                    </div>
                                    <form role="form" action="admin/includes/create-account.inc.php" method="post">
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
                                                        name="userEmail"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <small>User Role</small>
                                            <select class="form-control" name="userRole">
                                                <option value="scorer">Scorer</option>
                                                <option value="manager">Manager</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-default my-4"
                                                    name="create-account-submit">
                                                Send Activation Email
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
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
