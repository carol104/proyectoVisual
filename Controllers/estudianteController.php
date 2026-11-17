<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../Models/conexion.php";

/**
 * Función de validación de cédula ecuatoriana (Algoritmo Módulo 10)
 */
function validarCedulaEcuatoriana($cedula) {
    $cedula = trim($cedula);
    if (!preg_match('/^\d{10}$/', $cedula)) {
        return false;
    }
    $provincia = intval(substr($cedula, 0, 2));
    if (($provincia < 1 || $provincia > 24) && $provincia !== 30) {
        return false;
    }
    $tercerDigito = intval($cedula[2]);
    if ($tercerDigito >= 6) {
        return false;
    }
    $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
    $suma = 0;
    for ($i = 0; $i < 9; $i++) {
        $valor = intval($cedula[$i]) * $coeficientes[$i];
        if ($valor >= 10) {
            $valor -= 9;
        }
        $suma += $valor;
    }
    $digitoVerificador = (10 - ($suma % 10)) % 10;
    return $digitoVerificador === intval($cedula[9]);
}

/**
 * Función para verificar autenticación de sesión
 */
function verificarSesion() {
    if (!isset($_SESSION['usuario'])) {
        echo json_encode([
            "success" => false,
            "errorMsg" => "Acceso no autorizado. Debe iniciar sesión."
        ]);
        exit();
    }
}

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : 'acceder');
$conexion = Conexion::conectar();

switch ($action) {
    case 'acceder':
    case 'listar':
        $sql = "SELECT estCedula, estNombre, estApellido, estTelefono, estDireccion FROM ESTUDIANTES";
        $datos = $conexion->query($sql);
        $resultado = array();
        if ($datos && $datos->num_rows > 0) {
            while ($row = $datos->fetch_assoc()) {
                $resultado[] = $row;
            }
        }
        echo json_encode($resultado);
        break;

    case 'guardar':
        verificarSesion();

        $cedula = isset($_POST['estCedula']) ? trim($_POST['estCedula']) : '';
        $nombre = isset($_POST['estNombre']) ? trim($_POST['estNombre']) : '';
        $apellido = isset($_POST['estApellido']) ? trim($_POST['estApellido']) : '';
        $telefono = isset($_POST['estTelefono']) ? trim($_POST['estTelefono']) : '';
        $direccion = isset($_POST['estDireccion']) ? trim($_POST['estDireccion']) : '';

        // Controles de validación
        if (empty($cedula)) {
            echo json_encode(["errorMsg" => "El campo Cédula es obligatorio."]);
            exit();
        }
        if (!preg_match('/^\d{10}$/', $cedula)) {
            echo json_encode(["errorMsg" => "La cédula debe contener exactamente 10 dígitos numéricos."]);
            exit();
        }
        if (!validarCedulaEcuatoriana($cedula)) {
            echo json_encode(["errorMsg" => "La cédula ingresada no es válida (cédula ecuatoriana inválida)."]);
            exit();
        }
        if (empty($nombre)) {
            echo json_encode(["errorMsg" => "El campo Nombre es obligatorio."]);
            exit();
        }
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) {
            echo json_encode(["errorMsg" => "El Nombre solo puede contener letras y espacios."]);
            exit();
        }
        if (empty($apellido)) {
            echo json_encode(["errorMsg" => "El campo Apellido es obligatorio."]);
            exit();
        }
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $apellido)) {
            echo json_encode(["errorMsg" => "El Apellido solo puede contener letras y espacios."]);
            exit();
        }
        if (empty($telefono)) {
            $telefono = "0000000000";
        } elseif (!preg_match('/^\d{10}$/', $telefono)) {
            echo json_encode(["errorMsg" => "El teléfono debe contener exactamente 10 dígitos numéricos."]);
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

        // Inserción segura con sentencia preparada
        $insertStmt = $conexion->prepare("INSERT INTO ESTUDIANTES (estCedula, estNombre, estApellido, estTelefono, estDireccion) VALUES (?, ?, ?, ?, ?)");
        $insertStmt->bind_param("sssss", $cedula, $nombre, $apellido, $telefono, $direccion);

        if ($insertStmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Estudiante registrado con éxito."
            ]);
        } else {
            echo json_encode([
                "errorMsg" => "Error al guardar estudiante: " . $conexion->error
            ]);
        }
        $insertStmt->close();
        break;

    case 'editar':
        verificarSesion();

        $cedula = isset($_POST['estCedula']) && !empty($_POST['estCedula']) ? trim($_POST['estCedula']) : (isset($_GET['estCedula']) ? trim($_GET['estCedula']) : (isset($_GET['cedula']) ? trim($_GET['cedula']) : ''));
        $nombre = isset($_POST['estNombre']) ? trim($_POST['estNombre']) : '';
        $apellido = isset($_POST['estApellido']) ? trim($_POST['estApellido']) : '';
        $telefono = isset($_POST['estTelefono']) ? trim($_POST['estTelefono']) : '';
        $direccion = isset($_POST['estDireccion']) ? trim($_POST['estDireccion']) : '';

        if (empty($cedula)) {
            echo json_encode(["errorMsg" => "Cédula no especificada para la edición."]);
            exit();
        }
        if (empty($nombre)) {
            echo json_encode(["errorMsg" => "El campo Nombre es obligatorio."]);
            exit();
        }
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) {
            echo json_encode(["errorMsg" => "El Nombre solo puede contener letras y espacios."]);
            exit();
        }
        if (empty($apellido)) {
            echo json_encode(["errorMsg" => "El campo Apellido es obligatorio."]);
            exit();
        }
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $apellido)) {
            echo json_encode(["errorMsg" => "El Apellido solo puede contener letras y espacios."]);
            exit();
        }
        if (empty($telefono)) {
            $telefono = "0000000000";
        } elseif (!preg_match('/^\d{10}$/', $telefono)) {
            echo json_encode(["errorMsg" => "El teléfono debe contener exactamente 10 dígitos numéricos."]);
            exit();
        }
        if (empty($direccion)) {
            $direccion = "SIN DIRECCION";
        }

        // Actualización segura
        $updateStmt = $conexion->prepare("UPDATE ESTUDIANTES SET estNombre=?, estApellido=?, estTelefono=?, estDireccion=? WHERE estCedula=?");
        $updateStmt->bind_param("sssss", $nombre, $apellido, $telefono, $direccion, $cedula);

        if ($updateStmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Estudiante actualizado con éxito."
            ]);
        } else {
            echo json_encode([
                "errorMsg" => "Error al actualizar estudiante: " . $conexion->error
            ]);
        }
        $updateStmt->close();
        break;

    case 'borrar':
        verificarSesion();

        $cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : (isset($_GET['cedula']) ? trim($_GET['cedula']) : '');

        if (empty($cedula)) {
            echo json_encode([
                "success" => false,
                "errorMsg" => "Cédula no especificada para la eliminación."
            ]);
            exit();
        }

        $deleteStmt = $conexion->prepare("DELETE FROM ESTUDIANTES WHERE estCedula = ?");
        $deleteStmt->bind_param("s", $cedula);

        if ($deleteStmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Estudiante eliminado con éxito."
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "errorMsg" => "Error al eliminar el estudiante: " . $conexion->error
            ]);
        }
        $deleteStmt->close();
        break;

    default:
        echo json_encode([
            "errorMsg" => "Acción no reconocida."
        ]);
        break;
}
