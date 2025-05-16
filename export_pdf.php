<?php
require_once 'db.php';
require('fpdf/fpdf.php'); // assure-toi que fpdf est bien installé

$query = $db->query("SELECT p.titre, p.description, p.date_creation, p.statut,
    CONCAT(et.prenom, ' ', et.nom) AS etudiant, enc.nom AS encadrant
    FROM projets p
    JOIN etudiants et ON p.etudiant_id = et.id
    JOIN encadrant enc ON p.encadrant_id = enc.id");

$projets = $query->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Liste des Projets ENSA',0,1,'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','',11);

foreach ($projets as $p) {
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(30, 8, 'Titre :');
    $pdf->SetFont('Arial','',11);
    $pdf->MultiCell(0, 8, $p['titre']);

    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(30, 8, 'Description :');
    $pdf->SetFont('Arial','',11);
    $pdf->MultiCell(0, 8, $p['description']);

    $pdf->Cell(30, 8, 'Date :');
    $pdf->Cell(0, 8, $p['date_creation'], 0, 1);

    $pdf->Cell(30, 8, 'Statut :');
    $pdf->Cell(0, 8, $p['statut'], 0, 1);

    $pdf->Cell(30, 8, 'Étudiant :');
    $pdf->Cell(0, 8, $p['etudiant'], 0, 1);

    $pdf->Cell(30, 8, 'Encadrant :');
    $pdf->Cell(0, 8, $p['encadrant'], 0, 1);

    $pdf->Ln(5);
}

$pdf->Output('D', 'tous_les_projets_ensa.pdf');
exit;
?>
