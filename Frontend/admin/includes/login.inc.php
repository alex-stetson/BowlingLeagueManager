<?php

require "../../includes/config.inc.php";

function rateLimit($email, $link)
{
    $remoteIp = $_SERVER['REMOTE_ADDR'];
    $timeFrame = 300;
    $sql = "SELECT * FROM failedLogins WHERE ipAddr = INET6_ATON(?) AND attemptedAt > DATE_SUB(NOW(), INTERVAL ? MINUTE) ORDER BY attemptedAt DESC;";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "si", $remoteIp, $timeFrame);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        $numAttempts = mysqli_num_rows($result);
        if ($numAttempts >= 20) {
            if ($row = mysqli_fetch_assoc($result)) {
                $nextLoginTime = (int)date('U', strtotime($row['attemptedAt'])) + 1800;
                if (time() < $nextLoginTime) {
                    header("Location: " . $baseURL . "admin/login.php?error=lockout&lockoutTime=" . ($nextLoginTime - time()) . "&loginEmail=" . $email);
                    exit();
                }
            } else {
                header("Location: " . $baseURL . "admin/login.php?error=unknownerror&loginEmail=" . $email);
                exit();
            }
        } else if ($numAttempts >= 10) {
            if ($row = mysqli_fetch_assoc($result)) {
                $nextLoginTime = (int)date('U', strtotime($row['attemptedAt'])) + 900;
                if (time() < $nextLoginTime) {
                    header("Location: " . $baseURL . "admin/login.php?error=lockout&lockoutTime=" . ($nextLoginTime - time()) . "&loginEmail=" . $email);
                    exit();
                }
            } else {
                header("Location: " . $baseURL . "admin/login.php?error=unknownerror&loginEmail=" . $email);
                exit();
            }
        } else if ($numAttempts >= 5) {
            if ($row = mysqli_fetch_assoc($result)) {
                $nextLoginTime = (int)date('U', strtotime($row['attemptedAt'])) + 300;
                if (time() < $nextLoginTime) {
                    header("Location: " . $baseURL . "admin/login.php?error=lockout&lockoutTime=" . ($nextLoginTime - time()) . "&loginEmail=" . $email);
                    exit();
                }
            } else {
                header("Location: " . $baseURL . "admin/login.php?error=unknownerror&loginEmail=" . $email);
                exit();
            }
        }
    } else {
        header("Location: " . $baseURL . "admin/login.php?error=unknownerror&loginEmail=" . $email);
        exit();
    }
}

function cleanEntries($link)
{
    $timeFrame = 300;
    $sql = "DELETE FROM failedLogins WHERE attemptedAt < DATE_SUB(NOW(), INTERVAL ? MINUTE);";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $timeFrame);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

function clearCurrIP($link) {
    $remoteIp = $_SERVER['REMOTE_ADDR'];
    $sql = "DELETE FROM failedLogins WHERE ipAddr = INET6_ATON(?);";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $remoteIp);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

function addFailedLogin($link)
{
    $remoteIp = $_SERVER['REMOTE_ADDR'];
    $sql = "INSERT INTO failedLogins (`ipAddr`, `attemptedAt`) VALUES (INET6_ATON(?), NOW());";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $remoteIp);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

if (isset($_POST['login-submit'])) {

    require "../../includes/connection.inc.php";

    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    if (empty($email) || empty($password)) {
        header("Location: " . $baseURL . "admin/login.php?error=emptyfields");
        exit();
    }

    cleanEntries($link);
    rateLimit($email, $link);

    $sql = "SELECT * FROM users WHERE email=?;";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['pass'])) {
                clearCurrIP($link);
                session_start();
                $_SESSION['userID'] = $row['email'];
                header("Location: " . $baseURL);
                exit();
            } else {
                addFailedLogin($link);
                header("Location: " . $baseURL . "admin/login.php?error=incorrectcreds&loginEmail=" . $email);
                exit();
            }
        } else {
            addFailedLogin($link);
            header("Location: " . $baseURL . "admin/login.php?error=incorrectcreds&loginEmail=" . $email);
            exit();
        }
    } else {
        header("Location: " . $baseURL . "admin/login.php?error=unknownerror&loginEmail=" . $email);
        exit();
    }
} else {
    header("Location: " . $baseURL);
    exit();
}

?>