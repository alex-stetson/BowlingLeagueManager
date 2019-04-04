<?php

require "includes/config.inc.php";
require "includes/connection.inc.php";

$sql = "SELECT matches.id, matches.matchTime, matches.matchLocation, t1.teamName AS team1Name, t2.teamName AS team2Name
FROM matches LEFT OUTER JOIN teams t1 ON t1.id = matches.team1
LEFT OUTER JOIN teams t2 ON matches.team2 = t2.id
WHERE matches.matchTime < DATE_SUB(NOW(), INTERVAL 2 HOUR) ORDER BY matches.matchTime DESC;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <title>Past Matches</title>

    <base href="<?php echo htmlspecialchars($baseURL, ENT_QUOTES, 'UTF-8'); ?>">

    <!-- Favicon -->
    <link href="assets/img/brand/favicon.png" rel="icon" type="image/png"/>

    <!-- Fonts -->
    <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
            rel="stylesheet"
    />

    <!-- Icons -->
    <link href="assets/vendor/icomoon/icomoon.min.css" rel="stylesheet"/>

    <!-- Argon CSS -->
    <link type="text/css" href="assets/css/argon.min.css" rel="stylesheet"/>
</head>

<body>
<?php
include_once "includes/navbar.inc.php";
?>
<main>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <h1 class="h1 font-weight-bold mb-4" style="float: left">Past Matches</h1>
            <input class="form-control"
                   placeholder="Team Name"
                   type="text"
                   id="teamNameSearchInput"
                   onkeyup="teamNameSearch()"
                   style="width: 30%; float: right; margin-top: 0.5rem"/>
            <?php
            $currTime = NULL;
            if ($row = mysqli_fetch_assoc($result)) {
                echo '<table class="table" data-name="matchTable">';
                echo '<tbody>';
                $currTime = $row['matchTime'];
                echo '<h3 style="font-weight:bold; clear: both;" class="dateHeading">' . htmlspecialchars(date('m/d/y h:i A', strtotime($currTime)), ENT_QUOTES, 'UTF-8') . '</h3>';
                echo '<tr>';
                echo '<td style="width: 50%;">' . htmlspecialchars($row['team1Name'], ENT_QUOTES, 'UTF-8') . ' vs ' . htmlspecialchars($row['team2Name'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars(date('m/d/y h:i A', strtotime($row['matchTime'])), ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($row['matchLocation'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '</tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['matchTime'] != $currTime) {
                        $currTime = $row['matchTime'];
                        echo '</tbody>';
                        echo '</table>';
                        echo '<h3 style="font-weight:bold; clear: both;" class="dateHeading">' . htmlspecialchars(date('m/d/y h:i A', strtotime($currTime)), ENT_QUOTES, 'UTF-8') . '</h3>';
                        echo '<table class="table" data-name="matchTable">';
                        echo '<tbody>';
                    }
                    echo '<tr>';
                    echo '<td style="width: 50%;">' . htmlspecialchars($row['team1Name'], ENT_QUOTES, 'UTF-8') . ' vs ' . htmlspecialchars($row['team2Name'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . htmlspecialchars(date('m/d/y h:i A', strtotime($row['matchTime'])), ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . htmlspecialchars($row['matchLocation'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<h2 style="clear:both">No Matches</h2>';
            }
            ?>
        </div>
    </div>
</main>

<!-- Core -->
<script src="assets/vendor/jquery/jquery.min.js"></script>

<script src="assets/vendor/bootstrap/bootstrap.min.js"></script>

<!-- Theme JS -->
<script src="assets/js/argon.min.js"></script>
<script type="text/javascript">
    function teamNameSearch() {
        let input, filter, tr, td, i, matchTables;
        matchTables = document.querySelectorAll("table[data-name=matchTable]");
        input = document.getElementById("teamNameSearchInput");
        filter = input.value.toLowerCase();
        matchTables.forEach(function (table) {
            tr = table.getElementsByTagName("tr");
            let hiddenCount = 0;
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    if (td.innerHTML.toLowerCase().indexOf(filter) > -1) {
                        $(tr[i]).show();
                    } else {
                        $(tr[i]).hide();
                        hiddenCount++;
                    }
                }
            }
            if (hiddenCount === tr.length) {
                $(table).prev('.dateHeading').hide();
                $(table).hide();
            } else {
                $(table).prev('.dateHeading').show();
                $(table).show();
            }
        });
    }
</script>
</body>
</html>
