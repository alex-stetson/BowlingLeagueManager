<?php

require "includes/connection.inc.php";
$sql = "SELECT t1.id, t1.teamName, t1.totalPoints, COUNT(t2.totalPoints) AS Rank
FROM teams t1
JOIN teams t2 ON t1.totalPoints < t2.totalPoints OR (t1.totalPoints=t2.totalPoints and t1.id = t2.id)
GROUP BY t1.id, t1.totalPoints
ORDER BY t1.totalPoints DESC, t1.id DESC;";
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

    <title></title>

    <!-- Favicon -->
    <link href="assets/img/brand/favicon.png" rel="icon" type="image/png" />

    <!-- Fonts -->
    <link
      href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link href="assets/vendor/nucleo/css/nucleo.css" rel="stylesheet" />
    <link
      href="assets/vendor/font-awesome/css/font-awesome.min.css"
      rel="stylesheet"
    />

    <!-- Argon CSS -->
    <link type="text/css" href="assets/css/argon.css" rel="stylesheet" />
  </head>

  <body>
    <?php
      include_once "navbar.php";
    ?>
    <main>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Team Name</th>
                <th scope="col">Points</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<th scope="row">'.$row['Rank'].'</th>';
                    echo '<td>'.$row['teamName'].'</td>';
                    echo '<td>'.$row['totalPoints'].'</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {

        }
        ?>
    </main>

    <!-- Core -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/bootstrap.min.js"></script>

    <!-- Theme JS -->
    <script src="assets/js/argon.js"></script>
  </body>
</html>
