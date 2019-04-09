<?php

require "requireAuth.inc.php";
requireAuth(["admin", "manager", "scorer"]);
require "../../includes/config.inc.php";

if (isset($_POST['change-password-submit'])) {

    require "../../includes/connection.inc.php";

    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $newPasswordConfirm = $_POST['newPasswordConfirm'];

    if (empty($currentPassword) || empty($newPassword) || empty($newPasswordConfirm)) {
        header("Location: " . $baseURL . "admin/account.php?error=emptyfields");
        exit();
    }

    if ($newPassword !== $newPasswordConfirm) {
        header("Location: " . $baseURL . "admin/account.php?error=password-mismatch");
        exit();
    }

    $sql = "SELECT * FROM users WHERE userId=? AND casUser=0;";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['userId']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($currentPassword, $row['password'])) {
                $sql = "UPDATE users SET password=? WHERE userId=?;";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $_SESSION['userId']);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    header("Location: " . $baseURL . "admin/account.php?success=password-changed");
                    exit();
                } else {
                    header("Location: " . $baseURL . "admin/account.php?error=unknown");
                    exit();
                }
            } else {
                header("Location: " . $baseURL . "admin/account.php?error=incorrect-password");
                exit();
            }
        } else {
            header("Location: " . $baseURL . "admin/account.php?error=unknown");
            exit();
        }
    } else {
        header("Location: " . $baseURL . "admin/account.php?error=unknown");
        exit();
    }
} else {
    header("Location: " . $baseURL);
    exit();
}

?>