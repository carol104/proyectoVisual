<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Comprobar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Crear una variable global para indicar el estado de la sesión
    $isLoggedIn = false;
} else {
    $isLoggedIn = true;
}
