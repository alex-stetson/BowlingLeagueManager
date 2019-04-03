<?php

require "../../includes/config.inc.php";

if (isset($_POST['reset-password-submit'])) {

    require "../../includes/connection.inc.php";

    $selector = $_POST['resetSelector'];
    $validator = $_POST['resetValidator'];
    $password = $_POST['resetPassword'];
    $passwordConfirm = $_POST['resetPasswordConfirm'];

    if (empty($selector) || empty($validator)) {
        header("Location: " . $baseURL . "admin/forgot-password.php?error=resubmit");
        exit();
    }

    if (empty($password) || empty($passwordConfirm)) {
        header("Location: " . $baseURL . "admin/reset-password.php?error=emptyfields&selector=" . $selector . "&validator=" . $validator);
        exit();
    }

    if ($password !== $passwordConfirm) {
        header("Location: " . $baseURL . "admin/reset-password.php?error=password-mismatch&selector=" . $selector . "&validator=" . $validator);
        exit();
    }

    $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpiry >= NOW();";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $selector);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $tokenBin = hex2bin($validator);
            if (password_verify($tokenBin, $row['pwdResetToken'])) {
                $email = $row['pwdResetEmail'];
                $sql = "SELECT * from users WHERE email=?;";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    mysqli_stmt_close($stmt);
                    if ($row = mysqli_fetch_assoc($result)) {
                        $sql = "UPDATE users SET pass=? WHERE email=?;";
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                            mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $email);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            mysqli_stmt_close($stmt);

                            $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?;";
                            if ($stmt = mysqli_prepare($link, $sql)) {
                                mysqli_stmt_bind_param($stmt, "s", $email);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);
                            }
                            header("Location: " . $baseURL . "admin/reset-password.php?success");
                            exit();
                        } else {
                            header("Location: " . $baseURL . "admin/reset-password.php?error=unknown&selector=" . $selector . "&validator=" . $validator);
                            exit();
                        }
                    } else {
                        header("Location: " . $baseURL . "admin/reset-password.php?error=unknown&selector=" . $selector . "&validator=" . $validator);
                        exit();
                    }
                } else {
                    header("Location: " . $baseURL . "admin/reset-password.php?error=unknown&selector=" . $selector . "&validator=" . $validator);
                    exit();
                }
            } else {
                header("Location: " . $baseURL . "admin/forgot-password.php?error=resubmit");
                exit();
            }
        } else {
            header("Location: " . $baseURL . "admin/forgot-password.php?error=resubmit");
            exit();
        }
    } else {
        header("Location: " . $baseURL . "admin/reset-password.php?error=unknown&selector=" . $selector . "&validator=" . $validator);
        exit();
    }
} else {
    header("Location: " . $baseURL);
    exit();
}

?>