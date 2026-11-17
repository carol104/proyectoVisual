<?php
require_once "Controllers/middleware.php"; 
?>
<html>

<head>
  <meta charset="UTF-8" />
  <title>UTA</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="css/estilo.css" />
  <link rel="stylesheet" type="text/css" href="jquery/themes/default/easyui.css" />
  <link rel="stylesheet" type="text/css" href="jquery/themes/icon.css" />
  <link rel="stylesheet" type="text/css" href="jquery/themes/color.css" />
  <!-- <link
    rel="stylesheet"
    type="text/css"
    href="jquery/demo/demo.css"
  /> -->
  <script type="text/javascript" src="jquery/jquery.min.js"></script>
  <script type="text/javascript" src="jquery/jquery.easyui.min.js"></script>
</head>

<body>
  <header>
    <img src="images/bannerUTA.png" width="100%" alt="80%" />
  </header>

  <nav>
    <ul>
      <li><a href="index.php?action=home">Inicio</a></li>
      <li><a href="index.php?action=about">Nosotros</a></li>
      <li><a href="index.php?action=services">Servicios</a></li>
      <li><a href="index.php?action=contact">Contactanos</a></li>
      <?php if ($isLoggedIn): ?>
        <li><a href="Controllers/logout.php">Cerrar Sesión</a></li>
      <?php else: ?>
        <li><a href="login.php">Iniciar Sesión</a></li>
      <?php endif; ?>
    </ul>
  </nav>
  <section>

    <?php

    require_once "Controllers/controller.php";


    $mvc = new MVControlller();

    $mvc->enlacesPaginasController();
    ?>
  </section>

  <footer>
    <h4>Derechos Reservados @cuartoSoftware</h4>
  </footer>

</body>

</html>