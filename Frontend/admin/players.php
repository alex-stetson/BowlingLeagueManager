<?php

require "includes/requireAuth.inc.php";

require "../includes/connection.inc.php";

$sql = "SELECT * FROM players;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    exit();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <title>Players</title>

    <!-- Favicon -->
    <link href="../assets/img/brand/favicon.png" rel="icon" type="image/png" />

    <!-- Fonts -->
    <link
      href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link
      href="../assets/vendor/font-awesome/css/font-awesome.min.css"
      rel="stylesheet"
    />

    <!-- Argon CSS -->
    <link type="text/css" href="../assets/css/argon.css" rel="stylesheet" />
  </head>

  <body>
    <?php
      include_once "../includes/navbar.inc.php";
    ?>
    <main>
        <br>
        <h2 style="float: left">Players</h2>
        <a style="float: right" class="btn btn-default" href="/admin/add-player.php" role="button">Add Player</a>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">Email</th>
                <th scope="col">Name</th>
                <th scope="col">Current Handicap</th>
                <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>'.$row['email'].'</td>';
                    echo '<td>'.$row['playerName'].'</td>';
                    echo '<td>'.$row['currentHandicap'].'</td>';
                    echo '<td><a class="btn btn-default" href="/admin/edit-player.php?playerEmail='.$row['email'].'" role="button">Edit Player</a></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </main>

    <!-- Core -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/popper/popper.min.js"></script>
    <script src="../assets/vendor/bootstrap/bootstrap.min.js"></script>

    <!-- Theme JS -->
    <script src="../assets/js/argon.js"></script>
  </body>
</html>
