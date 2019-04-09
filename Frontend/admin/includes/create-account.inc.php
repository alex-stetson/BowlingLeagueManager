<?php

require "requireAuth.inc.php";
requireAuth(["admin"]);

require "../../includes/config.inc.php";

if (isset($_POST['create-account-submit'])) {

    require "../../includes/connection.inc.php";

    $userEmail = $_POST['userEmail'];
    $userRole = $_POST['userRole'];

    if (empty($userEmail) || empty($userRole)) {
        header("Location: " . $baseURL . "admin/create-account.php?error=emptyfields");
        exit();
    }

    $sql = "SELECT * FROM users WHERE username=?;";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $userEmail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            header("Location: " . $baseURL . "admin/create-account.php?error=userExists");
            exit();
        } else {
            $sql = "INSERT INTO users (`username`, `role`, `casUser`) VALUES (?, ?, 0);";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $userEmail, $userRole);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $selector = bin2hex(random_bytes(8));
                $token = random_bytes(32);

                $resetURL = $baseURL . "admin/reset-password.php?selector=" . $selector . "&validator=" . bin2hex($token);

                $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                $sql = "INSERT INTO pwdReset (`pwdResetEmail`, `pwdResetSelector`, `pwdResetToken`, `pwdResetExpiry`) VALUES
                (?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 DAY));";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sss", $userEmail, $selector, $hashedToken);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }

                $to = $userEmail;
                $subject = 'New Account Setup';
                $message = '<p>We received a new account request for your email. The link to set your password is below.
                It will only be valid for 24 hours after this email is sent.
                If you did not make this request, you can ignore this email.</p>';
                $message .= '<p>Here is your link: <br>';
                $message .= '<a href="' . $resetURL . '">' . $resetURL . '</a></p>';
                $headers = "Content-type: text/html\r\n";
                mail($to, $subject, $message, $headers);
                header("Location: " . $baseURL . "admin/create-account.php?success=useradded");
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