<?php

require "includes/requireAuth.inc.php";
require "../includes/config.inc.php";

if (!isset($_GET['matchId'])) {
    header("Location: " . $baseURL . "admin/matches.php");
    exit();
}

require "../includes/connection.inc.php";

$matchId = $_GET['matchId'];

$sql = "SELECT players.playerName, players.currentHandicap, teams.teamName, teamMembers.playerEmail, teamMembers.teamId, matches.team1, matches.team2, matches.matchLocation, matches.matchTime
FROM players
INNER JOIN teamMembers ON players.email = teamMembers.playerEmail
INNER JOIN teams ON teamMembers.teamId = teams.id
INNER JOIN matches ON teams.id = matches.team1 OR teams.id = matches.team2
WHERE matches.id = ?;";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $matchId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $team1Members = array();
    $team2Members = array();
    $team1Handicaps = array();
    $team2Handicaps = array();
    $team1Id = 0;
    $team2Id = 0;
    $team1Name = "";
    $team2Name = "";
    $matchLocation = "";
    $matchTime = "";
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['teamId'] == $row['team1']) {
            $team1Members[] = $row['playerName'];
            $team1Handicaps[] = $row['currentHandicap'];
            $team1Name = $row['teamName'];
            $team1Id = $row['team1'];
        } else {
            $team2Members[] = $row['playerName'];
            $team2Handicaps[] = $row['currentHandicap'];
            $team2Name = $row['teamName'];
            $team2Id = $row['team2'];
        }
        $matchLocation = $row['matchLocation'];
        $matchTime = $row['matchTime'];
    }
    if ($team1Id == 0 && $team2Id == 0) {
        header("Location: " . $baseURL . "admin/matches.php");
        exit();
    }
} else {
    header("Location: " . $baseURL . "admin/matches.php");
    exit();
}

require('../assets/fpdf/fpdf.php');

class PDF extends FPDF
{
    function setTeam1Name($team1Name)
    {
        $this->team1Name = $team1Name;
    }

    function setTeam2Name($team2Name)
    {
        $this->team2Name = $team2Name;
    }

    function setDateTime($dateTime)
    {
        $this->dateTime = (empty($dateTime) ? '' : date('m/d/y h:i A', strtotime($dateTime)));
    }

    function setLocation($location)
    {
        $this->location = $location;
    }

    // Page header
    function Header()
    {
        // Logo
        $this->Image('../assets/img/brand/bclogo.png', 10, 6, 30);
        // Arial bold 18
        $this->SetFont('Arial', 'B', 18);
        // Title
        $this->Cell(0, 8, $this->team1Name, 0, 0, 'C');
        $this->Ln();
        $this->Cell(0, 8, "vs", 0, 0, 'C');
        $this->Ln();
        $this->Cell(0, 8, $this->team2Name, 0, 0, 'C');
        $this->Ln(10);
        $this->SetFont('Times', '', 14);
        $this->Cell(0, 7, $this->dateTime, 0, 0, 'C');
        $this->Ln();
        $this->Cell(0, 7, $this->location, 0, 0, 'C');
        // Logo
        $this->Image('../assets/img/brand/trlogo.png', 170, 6, 30);
        // Line break
        $this->Ln(15);
    }

    function DataTable($names, $handicaps)
    {
        // Colors, line width and bold font
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        // Header
        $header = array("Name", "Handicap", "Game 1", "Game 2", "Game3");
        $w = array(63, 28, 34, 34, 34);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', false);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = false;
        for ($i = 0; $i < count($names); $i++) {
            $this->Cell($w[0], 8, $names[$i], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 8, $handicaps[$i], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 8, "", 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 8, "", 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 8, "", 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->setTeam1Name($team1Name);
$pdf->setTeam2Name($team2Name);
$pdf->setDateTime($matchTime);
$pdf->setLocation($matchLocation);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 18);
$pdf->Cell(40, 10, $team1Name);
$pdf->Ln();
$pdf->DataTable($team1Members, $team1Handicaps);
$pdf->Ln(10);
$pdf->Cell(40, 10, $team2Name);
$pdf->Ln();
$pdf->DataTable($team2Members, $team2Handicaps);
$pdf->Ln(15);
$pdf->Cell(0, 8, $team1Name . " Signature: _________________________________", 0, 0, 'C');
$pdf->Ln(22);
$pdf->Cell(0, 8, $team2Name . " Signature: _________________________________", 0, 0, 'C');
$pdf->Output('I', 'Scoresheet.pdf');

?>