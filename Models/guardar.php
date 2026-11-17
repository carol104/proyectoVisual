<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once __DIR__ . "/conexion.php";

$conexion = Conexion::conectar();

$cedula = isset($_POST['estCedula']) ? trim($_POST['estCedula']) : '';
$nombre = isset($_POST['estNombre']) ? trim($_POST['estNombre']) : '';
$apellido = isset($_POST['estApellido']) ? trim($_POST['estApellido']) : '';
$telefono = isset($_POST['estTelefono']) ? trim($_POST['estTelefono']) : '';
$direccion = isset($_POST['estDireccion']) ? trim($_POST['estDireccion']) : '';

// Validaciones
if (empty($cedula) || !preg_match('/^\d{10}$/', $cedula)) {
    echo json_encode(["errorMsg" => "La cédula debe tener exactamente 10 dígitos numéricos."]);
    exit();
}

if (empty($nombre) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) {
    echo json_encode(["errorMsg" => "El nombre solo puede contener letras y espacios."]);
    exit();
}

if (empty($apellido) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $apellido)) {
    echo json_encode(["errorMsg" => "El apellido solo puede contener letras y espacios."]);
    exit();
}

if (empty($telefono)) {
    $telefono = "0000000000";
} elseif (!preg_match('/^\d{10}$/', $telefono)) {
    echo json_encode(["errorMsg" => "El teléfono debe contener 10 dígitos numéricos."]);
    exit();
}

if (empty($direccion)) {
    $direccion = "SIN DIRECCION";
}

// Control de duplicados
$checkStmt = $conexion->prepare("SELECT COUNT(*) FROM ESTUDIANTES WHERE estCedula = ?");
$checkStmt->bind_param("s", $cedula);
$checkStmt->execute();
$checkStmt->bind_result($count);
$checkStmt->fetch();
$checkStmt->close();

if ($count > 0) {
    echo json_encode(["errorMsg" => "Ya existe un estudiante registrado con la cédula " . $cedula]);
    exit();
}

$stmt = $conexion->prepare("INSERT INTO ESTUDIANTES (estCedula, estNombre, estApellido, estTelefono, estDireccion) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $cedula, $nombre, $apellido, $telefono, $direccion);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Se ha agregado el estudiante con éxito."]);
} else {
    echo json_encode(["errorMsg" => "Error al guardar el estudiante: " . $conexion->error]);
}
$stmt->close();
?>
