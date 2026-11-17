<?php

//El include carga fisicamente el archivo, el requiere carga la clase

require_once "Controllers/controller.php";


$mvc = new MVControlller();

$mvc->template();

?>