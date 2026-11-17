<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UTA</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red; margin-bottom: 15px; font-weight: bold;"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <form action="Controllers/validarDatosLogin.php" method="post">
            <input type="text" name="user" placeholder="Usuario" required autocomplete="username">
            <input type="password" name="password" placeholder="Contraseña" required autocomplete="current-password">
            <button type="submit">Iniciar sesión</button>
        </form>
        <p style="margin-top: 15px;"><a href="index.php" style="color: #666; text-decoration: none;">&larr; Volver al inicio</a></p>
    </div>
</body>
</html>
