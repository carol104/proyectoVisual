<?php
session_start();
$response = [
    "sesionValida" => isset($_SESSION['usuario']), // Valida si existe un usuario
    "usuario" => $_SESSION['usuario'] ?? null
];

header('Content-Type: application/json');
echo json_encode($response);
?>
