<?php
header('Content-Type: application/json; charset=utf-8');

include_once __DIR__ . "/conexion.php";

$conexion = Conexion::conectar();

$sql = "SELECT estCedula, estNombre, estApellido, estTelefono, estDireccion FROM ESTUDIANTES";

$datos = $conexion->query($sql);

$resultado = array();

if ($datos && $datos->num_rows > 0) {
  while ($row = $datos->fetch_assoc()) {
    $resultado[] = $row;
  }
}

echo json_encode($resultado);
?>
