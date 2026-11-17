<?php
$a = 3;
echo gettype($a);
echo ("<br>");
echo gettype($a);

$colores = array("rojo", "amarillo", "verde");
echo ("<br>");
echo $colores[0];

//un objetov en php se define como clave valor
//No se debe emplear un = sino un =>
$frutas = (object) [
  "fruta1" => ["nombre" => "pera", "color" => "verde", "tamaño" => "mediana"],
  "fruta2" => "sandia"
];
echo ("<br>");
// Acceder al arreglo dentro de fruta1
echo $frutas->fruta1['nombre'];  // salida: pera
echo ("<br>");
echo $frutas->fruta1['color'];   // salida: verde
echo ("<br>");
echo $frutas->fruta1['tamaño'];  // salida: median
echo ("<br>");
echo $frutas->fruta2;  // salida: median


//definir un arrray en forma de objeto

$verduras = array("verdura1" => "tomate", "verdura2" => "pimiento");

echo ("<br>");
echo $verduras["verdura1"];  // salida: median

echo ("<br>");
//para base de datos el array, el objeto y el array hecho objetvo


function saludo()
{

  echo "Hola como estas";
}

saludo();

echo ("<br>");
function despedida($nombre)
{

  echo "Hasta luego " . $nombre;

}

despedida("Juan");

echo "<br>";


function sumar($numero1, $numero2)
{

  return $numero1 + $numero2;

}


echo sumar(5, 3);


function mayorEdad($edad)
{


  if ($edad >= 18) {

    echo "Es mayor de edad";

  } else {

    echo "Es menor de edad";
  }

}

echo "<br>";
mayorEdad(19);

echo "<br>";
for ($i = 1; $i <= 10; $i++) {
  echo $i;
  echo "<br>";
}

//while, do while

$dia = "lunes";

switch ($dia) {
  case "lunes":
    # code...
    echo "Hoy estudio";
    break;

  case "martes":
    echo "hoy es martes";
  default:
    echo "no se que dia es";
    break;
}

?>