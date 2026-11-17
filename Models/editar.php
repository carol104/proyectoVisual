<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once __DIR__ . "/conexion.php";

$conexion = Conexion::conectar();

$cedula = isset($_POST['estCedula']) ? trim($_POST['estCedula']) : (isset($_GET['cedula']) ? trim($_GET['cedula']) : '');
$nombre = isset($_POST['estNombre']) ? trim($_POST['estNombre']) : '';
$apellido = isset($_POST['estApellido']) ? trim($_POST['estApellido']) : '';
$telefono = isset($_POST['estTelefono']) ? trim($_POST['estTelefono']) : '';
$direccion = isset($_POST['estDireccion']) ? trim($_POST['estDireccion']) : '';

if (empty($cedula)) {
    echo json_encode(["success" => false, "errorMsg" => "Cédula no proporcionada."]);
    exit();
}

if (empty($nombre) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) {
    echo json_encode(["success" => false, "errorMsg" => "El nombre solo puede contener letras y espacios."]);
    exit();
}

if (empty($apellido) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $apellido)) {
    echo json_encode(["success" => false, "errorMsg" => "El apellido solo puede contener letras y espacios."]);
    exit();
}

if (empty($telefono)) {
    $telefono = "0000000000";
} elseif (!preg_match('/^\d{10}$/', $telefono)) {
    echo json_encode(["success" => false, "errorMsg" => "El teléfono debe contener 10 dígitos numéricos."]);
    exit();
}

if (empty($direccion)) {
    $direccion = "SIN DIRECCION";
}

$stmt = $conexion->prepare("UPDATE ESTUDIANTES SET estNombre=?, estApellido=?, estTelefono=?, estDireccion=? WHERE estCedula=?");
$stmt->bind_param("sssss", $nombre, $apellido, $telefono, $direccion, $cedula);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Estudiante actualizado con éxito"]);
} else {
    echo json_encode(["success" => false, "errorMsg" => "Error al actualizar estudiante: " . $conexion->error]);
}
$stmt->close();
?>
