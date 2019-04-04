<?php

require "../../includes/config.inc.php";

if (isset($_POST['forgot-password-submit'])) {

    require "../../includes/connection.inc.php";

    $email = $_POST['forgotPasswordEmail'];

    if (empty($email)) {
        header("Location: " . $baseURL . "admin/forgot-password.php?error=emptyfields");
        exit();
    }

    $sql = "SELECT * FROM users WHERE username=? AND casUser=0;";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            // user exists, send email
            $selector = bin2hex(random_bytes(8));
            $token = random_bytes(32);

            $resetURL = $baseURL . "admin/reset-password.php?selector=" . $selector . "&validator=" . bin2hex($token);

            $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?;";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }

            $hashedToken = password_hash($token, PASSWORD_DEFAULT);
            $sql = "INSERT INTO pwdReset (`pwdResetEmail`, `pwdResetSelector`, `pwdResetToken`, `pwdResetExpiry`) VALUES
            (?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR));";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sss", $email, $selector, $hashedToken);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }

            $to = $email;
            $subject = 'Password Reset Email';
            $message = '<p>We received a password reset request for your email. The link to reset your password is below.
            It will only be valid for 60 minutes after this email is sent.
            If you did not make this request, you can ignore this email.</p>';
            $message .= '<p>Here is your password reset link: <br>';
            $message .= '<a href="' . $resetURL . '">' . $resetURL . '</a></p>';
            $headers = "Content-type: text/html\r\n";
            mail($to, $subject, $message, $headers);
            header("Location: " . $baseURL . "admin/forgot-password.php?success");
            exit();
        } else {
            // user does not exist - return;
            header("Location: " . $baseURL . "admin/forgot-password.php?success");
            exit();
        }
    } else {
        header("Location: " . $baseURL . "admin/forgot-password.php?error=unknownerror");
        exit();
    }
} else {
    header("Location: " . $baseURL);
    exit();
}

?>