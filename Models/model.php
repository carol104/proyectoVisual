<?php

// //El include recarga una vez, el include_once carga solo una vez.
// include_once "./conexion.php";

// $conexion = new Conexion();

// $conexion->conectar();

class EnlacesPaginas
{

  public static function enlacesPaginasModel($enlacesModel)
  {

    if ($enlacesModel == 'about' || $enlacesModel == 'services' || $enlacesModel == "contact") {

      $module = "Views/" . $enlacesModel . '.php';


    } else {

      $module = "Views/home.php";
    }

    return $module;
  }


}





?>