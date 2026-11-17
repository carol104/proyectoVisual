<?php

require_once "Models/model.php";
class MVControlller
{

  public function template()
  {

    include "Views/template.php";


  }

  public function enlacesPaginasController()
  {

    if (isset($_GET["action"])) {

      $enlacesController = $_GET["action"];

    } else {
      $enlacesController = "index.php";
    }

    //$modelo = new EnlacesPaginas();

    //$response = $modelo->enlacesPaginasModel($enlacesController);

    //Instanciacion implicita
    $response = EnlacesPaginas::enlacesPaginasModel($enlacesController);
    include $response;
  }


}


?>