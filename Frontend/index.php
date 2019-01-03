<?php

require "includes/connection.inc.php";
$sql = "SELECT matches.matchTime, t1.teamName AS team1Name, t2.teamName AS team2Name
FROM teams t1 LEFT OUTER JOIN matches ON t1.id = matches.team1
LEFT OUTER JOIN teams t2 ON matches.team2 = t2.id
WHERE matches.matchTime >= DATE_SUB(NOW(), INTERVAL 2 HOUR) ORDER BY matches.matchTime ASC;";
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

    <title>Home</title>

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
      <div class="row justify-content-center mt-md">
        <div class="col-lg-12">
          <h1 class="h1 font-weight-bold mb-4">Upcoming Matches</h1>
                <?php
                $currTime = NULL;
                if ($row = mysqli_fetch_assoc($result)) {
                  echo '<table class="table">';
                  echo '<tbody>';
                  $currTime = $row['matchTime'];
                  echo '<h3 style="font-weight:bold">'.date('m/d/y h:i A', strtotime($currTime)).'</h3>';
                  echo '<tr>';
                  echo '<td>'.$row['team1Name'].' vs '.$row['team2Name'].'</td>';
                  echo '<td>'.$row['matchTime'].'</td>';
                  echo '</tr>';
                  while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['matchTime'] != $currTime) {
                      $currTime = $row['matchTime'];
                      echo '</tbody>';
                      echo '</table>';
                      echo '<h3 style="font-weight:bold">'.date('m/d/y h:i A', strtotime($currTime)).'</h3>';
                      echo '<table class="table">';
                      echo '<tbody>';
                    }
                    echo '<tr>';
                    echo '<td>'.$row['team1Name'].' vs '.$row['team2Name'].'</td>';
                    echo '<td>'.$row['matchTime'].'</td>';
                    echo '</tr>';
                  }
                  echo '</tbody>';
                  echo '</table>';
                } else {
                  echo '<h2>No Upcoming Matches</h2>';
                }
                ?>
        </div>
      </div>
    </main>

    <!-- Core -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/bootstrap.min.js"></script>

    <!-- Theme JS -->
    <script src="assets/js/argon.js"></script>
  </body>
</html>
