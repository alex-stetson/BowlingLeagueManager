<?php

require "requireAuth.inc.php";
requireAuth(["admin"]);

require "../../includes/config.inc.php";

if (!$casEnabled) {
    exit("Cannot create CAS account - CAS login is disabled for this site");
}

if (isset($_POST['create-cas-account-submit'])) {

    require "../../includes/connection.inc.php";

    $userId = $_POST['userId'];
    $userRole = $_POST['userRole'];

    if (empty($userId) || empty($userRole)) {
        header("Location: " . $baseURL . "admin/create-account.php?error=emptyfields");
        exit();
    }

    $sql = "SELECT * FROM users WHERE username=?;";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            header("Location: " . $baseURL . "admin/create-account.php?error=userExists");
            exit();
        } else {
            $sql = "INSERT INTO users (`username`, `role`, `casUser`) VALUES (?, ?, 1);";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $userId, $userRole);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header("Location: " . $baseURL . "admin/create-account.php?success=casuseradded");
                exit();
            } else {
                header("Location: " . $baseURL . "admin/create-account.php?error=unknownerror");
                exit();
            }
        }
    } else {
        header("Location: " . $baseURL . "admin/create-account.php?error=unknownerror");
        exit();
    }
} else {
    header("Location: " . $baseURL);
    exit();
}

?>