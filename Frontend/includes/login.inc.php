<?php

if (isset($_POST['login-submit'])) {

    require "connection.inc.php";

    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    if (empty($email) || empty($password)) {
        header("Location: /login.php?error=emptyfield");
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE email=?;";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_get_result($stmt);
            mysqli_stmt_close($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row['pass'])) {
                    session_start();
                    $_SESSION['userEmail'] = $row['email'];
                } else {
                    header("Location: /login.php?error=incorrectcreds");
                    exit();
                }
            } else {
                header("Location: /login.php?error=incorrectcreds");
                exit();
            }
        } else {
            header("Location: /login.php?error=unexpectederror");
        exit();
        }
    }
} else {
    header("Location: /index.php");
    exit();
}

?>