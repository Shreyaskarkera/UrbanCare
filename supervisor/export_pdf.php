<?php
require '../vendor/autoload.php'; // Include TCPDF (if using Composer)
require '../connection.php'; // Include DB connection

use TCPDF;

$conn = db_connect();
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Access Denied");
}

$supervisor_id = $_SESSION['user_id'];

// Fetch assigned place
$query = "SELECT place_id FROM supervisor_map WHERE supervisor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $supervisor_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("No place assigned to this supervisor.");
}

$place_id = $row['place_id'];

// Fetch complaints
$query = "SELECT id, title, complaint_status, created_at FROM complaints WHERE place_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $place_id);
$stmt->execute();
$result = $stmt->get_result();

$pdf = new TCPDF();
$pdf->SetTitle('Supervisor Report');
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Title
$pdf->Cell(190, 10, "Supervisor Complaint Report", 0, 1, 'C');

// Table Header
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 10, "ID", 1);
$pdf->Cell(70, 10, "Title", 1);
$pdf->Cell(40, 10, "Status", 1);
$pdf->Cell(60, 10, "Date", 1);
$pdf->Ln();

// Table Data
$pdf->SetFont('helvetica', '', 10);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(20, 10, $row['id'], 1);
    $pdf->Cell(70, 10, $row['title'], 1);
    $pdf->Cell(40, 10, $row['complaint_status'], 1);
    $pdf->Cell(60, 10, $row['created_at'], 1);
    $pdf->Ln();
}

$pdf->Output('Supervisor_Report.pdf', 'D'); // Forces download
?>
