<?php

if (isset($_POST['login-submit'])) {

    require "connection.inc.php";

    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    if (empty($email) || empty($password)) {
        header("Location: /login.php?error=emptyfields");
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE email=?;";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row['pass'])) {
                    session_start();
                    $_SESSION['userEmail'] = $row['email'];
                    header("Location: /index.php");
                    exit();
                } else {
                    header("Location: /login.php?error=incorrectcreds&loginEmail=".$email);
                    exit();
                }
            } else {
                header("Location: /login.php?error=incorrectcreds&loginEmail=".$email);
                exit();
            }
        } else {
            header("Location: /login.php?error=incorrectcreds&loginEmail=".$email);
        exit();
        }
    }
} else {
    header("Location: /index.php");
    exit();
}

?>