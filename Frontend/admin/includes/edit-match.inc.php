<?php

require "requireAuth.inc.php";

if (isset($_POST['edit-match-submit'])) {

    require "../../includes/connection.inc.php";

    $matchId = $_POST['matchId'];
    $matchTime = $_POST['matchTime'];
    $matchLocation = $_POST['matchLocation'];
    if (empty($matchId)) {
        header("Location: /admin/edit-match.php?error=emptyfields");
        exit();
    } else {
        if (!empty($matchTime)) {
            $matchTime = date ("Y-m-d H:i:s", strtotime($matchTime));
        } else {
            $matchTime = NULL;
        }
        $sql = "UPDATE matches SET matchLocation=?, matchTime=? WHERE id=?;";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssi", $matchLocation, $matchTime, $matchId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            header("Location: /admin/edit-match.php?error=unknownerror");
            exit();
        }
    }
    header("Location: /admin/matches.php");
    exit();
} else {
    header("Location: /");
    exit();
}

?>