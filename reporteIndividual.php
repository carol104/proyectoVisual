<?php
require_once 'fpdf186/fpdf.php';
require_once 'Models/conexion.php';

function fpdf_str($s) {
    return mb_convert_encoding($s, 'ISO-8859-1', 'UTF-8');
}

$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : (isset($_GET['cedula']) ? trim($_GET['cedula']) : '');

if (empty($cedula)) {
    die("Cédula no especificada para generar el reporte.");
}

$conexion = Conexion::conectar();
$stmt = $conexion->prepare("SELECT estCedula, estNombre, estApellido, estTelefono, estDireccion FROM ESTUDIANTES WHERE estCedula = ?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', "B", 16);
$pdf->Cell(190, 10, fpdf_str("Reporte Individual de Estudiante"), 0, 1, "C");
$pdf->Ln(5);

$pdf->SetFont('Arial', "B", 12);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(30, 10, fpdf_str("Cédula"), 1, 0, 'C', true);
$pdf->Cell(40, 10, fpdf_str("Nombre"), 1, 0, 'C', true);
$pdf->Cell(40, 10, fpdf_str("Apellido"), 1, 0, 'C', true);
$pdf->Cell(35, 10, fpdf_str("Teléfono"), 1, 0, 'C', true);
$pdf->Cell(45, 10, fpdf_str("Dirección"), 1, 1, 'C', true);

$pdf->SetFont('Arial', "", 11);
$found = false;
while ($fila = $result->fetch_object()) {
    $found = true;
    $pdf->Cell(30, 9, fpdf_str($fila->estCedula), 1);
    $pdf->Cell(40, 9, fpdf_str($fila->estNombre), 1);
    $pdf->Cell(40, 9, fpdf_str($fila->estApellido), 1);
    $pdf->Cell(35, 9, fpdf_str($fila->estTelefono), 1);
    $pdf->Cell(45, 9, fpdf_str($fila->estDireccion), 1);
    $pdf->Ln();
}

if (!$found) {
    $pdf->Cell(190, 10, fpdf_str("No se encontró ningún estudiante con la cédula: " . $cedula), 1, 1, 'C');
}

$stmt->close();
$pdf->Output();
?>
