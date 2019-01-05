<?php

require "requireAuth.inc.php";

if (isset($_POST['add-player-submit'])) {

    require "../../includes/connection.inc.php";

    $email = $_POST['playerEmail'];
    $playerName = $_POST['playerName'];

    if (empty($email) || empty($playerName)) {
        header("Location: /admin/add-player.php?error=emptyfields");
        exit();
    } else {
        $sql = "SELECT * FROM players WHERE email=?;";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            if (mysqli_num_rows($result) != 0) {
                header("Location: /admin/add-player.php?error=playerExists");
                exit();
            } else {
                $sql = "INSERT INTO players (`email`, `playerName`) VALUES (?, ?);";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ss", $email, $playerName);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    header("Location: /admin/add-player.php?success=true");
                    exit();
                } else {
                    header("Location: /admin/add-player.php?error=unknownerror");
                    exit();
                }
            }
        } else {
            header("Location: /admin/add-player.php?error=unknownerror");
            exit();
        }
    }
} else {
    header("Location: /");
    exit();
}

?>