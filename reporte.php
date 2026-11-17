<?php
require_once "fpdf186/fpdf.php";
require_once "Models/conexion.php";

function fpdf_str($s) {
    return mb_convert_encoding($s, 'ISO-8859-1', 'UTF-8');
}

$conexion = Conexion::conectar();
$sql = "SELECT estCedula, estNombre, estApellido, estTelefono, estDireccion FROM ESTUDIANTES";
$result = $conexion->query($sql);

$pdf = new FPDF();
$pdf->AddPage();

// Encabezado
$pdf->SetFont("Arial", "B", 16);
$pdf->Cell(190, 10, fpdf_str("Reporte General de Estudiantes"), 0, 1, "C");
$pdf->Ln(5);

// Títulos de las columnas
$pdf->SetFont("Arial", "B", 12);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(30, 10, fpdf_str("Cédula"), 1, 0, 'C', true);
$pdf->Cell(40, 10, fpdf_str("Nombre"), 1, 0, 'C', true);
$pdf->Cell(40, 10, fpdf_str("Apellido"), 1, 0, 'C', true);
$pdf->Cell(35, 10, fpdf_str("Teléfono"), 1, 0, 'C', true);
$pdf->Cell(45, 10, fpdf_str("Dirección"), 1, 1, 'C', true);

// Datos
$pdf->SetFont("Arial", "", 11);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(30, 9, fpdf_str($row['estCedula']), 1);
        $pdf->Cell(40, 9, fpdf_str($row['estNombre']), 1);
        $pdf->Cell(40, 9, fpdf_str($row['estApellido']), 1);
        $pdf->Cell(35, 9, fpdf_str($row['estTelefono']), 1);
        $pdf->Cell(45, 9, fpdf_str($row['estDireccion']), 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(190, 10, fpdf_str("No hay estudiantes registrados."), 1, 1, 'C');
}

$pdf->Output();
?>
