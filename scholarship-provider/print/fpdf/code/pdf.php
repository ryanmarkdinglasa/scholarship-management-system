<?php
require('../fpdf.php');

class PDF extends FPDF {
  function Header() {
    // Logo
    $this->Image('logo.png', 10, 6, 30);
    // Arial bold 15
    $this->SetFont('Arial', 'B', 15);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30, 10, 'Inputs', 1, 0, 'C');
    // Line break
    $this->Ln(20);
  }

  function Footer() {
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial', 'I', 8);
    // Page number
    $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
  }
}
if(isset($_POST['submit'])){
  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',16);
  $pdf->Cell(40,10,'Name');
  $pdf->Cell(40,10,'Age');
  $pdf->Cell(40,10,'City');
  $pdf->Ln();
  
  $pdf->SetFont('Arial','',12);
  $pdf->Cell(40,10,$_POST['name1']);
  $pdf->Cell(40,10,$_POST['age1']);
  $pdf->Cell(40,10,$_POST['city1']);
  $pdf->Ln();
  
  $pdf->Cell(40,10,$_POST['name2']);
  $pdf->Cell(40,10,$_POST['age2']);
  $pdf->Cell(40,10,$_POST['city2']);
  $pdf->Ln();
  
  $pdf->Cell(40,10,$_POST['name3']);
  $pdf->Cell(40,10,$_POST['age3']);
  $pdf->Cell(40,10,$_POST['city3']);

  $pdf->Output('example.pdf', 'F');
}
?>
