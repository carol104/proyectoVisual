<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once __DIR__ . "/conexion.php";

$conexion = Conexion::conectar();

$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : (isset($_GET['cedula']) ? trim($_GET['cedula']) : '');

if (empty($cedula)) {
    echo json_encode(["success" => false, "errorMsg" => "Cédula no proporcionada."]);
    exit();
}

$stmt = $conexion->prepare("DELETE FROM ESTUDIANTES WHERE estCedula = ?");
$stmt->bind_param("s", $cedula);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Estudiante eliminado con éxito"]);
} else {
    echo json_encode(["success" => false, "errorMsg" => "Error al eliminar el estudiante: " . $conexion->error]);
}
$stmt->close();
?>
