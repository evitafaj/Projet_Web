<?php
require('fpdf/fpdf.php');
include('includes/db_connect.php');

$user_id = $_POST['user_id'];

// Récupérer les informations utilisateur
$sql_user = "SELECT * FROM users WHERE id='$user_id'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

// Récupérer les formations et projets
$sql_education = "SELECT * FROM education WHERE user_id='$user_id' ORDER BY end_date DESC";
$result_education = $conn->query($sql_education);

$sql_projects = "SELECT * FROM projects WHERE user_id='$user_id'";
$result_projects = $conn->query($sql_projects);

// Générer le PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(0, 10, 'CV de ' . $user['first_name'] . ' ' . $user['last_name'], 0, 1, 'C');

// Ajouter les formations
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Formations', 0, 1);
$pdf->SetFont('Arial', '', 12);
while ($education = $result_education->fetch_assoc()) {
    $pdf->Cell(0, 10, $education['degree'] . ' à ' . $education['institution'] . ' (' . $education['start_date'] . ' - ' . $education['end_date'] . ')', 0, 1);
}

// Ajouter les projets
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Projets', 0, 1);
$pdf->SetFont('Arial', '', 12);
while ($project = $result_projects->fetch_assoc()) {
    $pdf->Cell(0, 10, $project['title'] . ': ' . $project['description'], 0, 1);
}

// Sauvegarder ou afficher le PDF
$pdf->Output('D', 'CV.pdf'); // Téléchargement direct
?>
