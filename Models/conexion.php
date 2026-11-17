<?php

class Conexion
{
  public static function conectar()
  {
    $serverName = "localhost";
    $userName = "root";
    $password = "";
    $db = "cuarto";

    $conn = @mysqli_connect($serverName, $userName, $password, $db);

    if (!$conn) {
      // Si la conexión falla, registrar o terminar con mensaje claro
      die(json_encode([
        "success" => false,
        "errorMsg" => "Error de conexión a la base de datos: " . mysqli_connect_error()
      ]));
    }

    mysqli_set_charset($conn, "utf8mb4");
    return $conn;
  }
}
?>
