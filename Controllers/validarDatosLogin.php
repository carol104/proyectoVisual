<?php
session_start();
require_once __DIR__ . "/../Models/conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = isset($_POST['user']) ? trim($_POST['user']) : '';
    $contr = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($usuario) || empty($contr)) {
        $_SESSION['error'] = "Por favor ingrese usuario y contraseña.";
        header("Location: ../login.php");
        exit();
    }

    try {
        $con = Conexion::conectar();
        $sqlSelect = "SELECT nombre, clave FROM usuarios WHERE nombre=? AND clave=?";
        $sentencia = $con->prepare($sqlSelect);
        $sentencia->bind_param("ss", $usuario, $contr);
        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $_SESSION['usuario'] = $usuario; 
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['error'] = "Usuario o contraseña no válidos o no existen.";
            header("Location: ../login.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error en el servidor: " . $e->getMessage();
        header("Location: ../login.php");
        exit();
    }
}
?>