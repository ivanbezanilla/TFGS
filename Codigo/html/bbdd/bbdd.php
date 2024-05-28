<?php
$host = "jarduino-db.cpc2sac0ayaa.us-east-1.rds.amazonaws.com";
$contraseña = "Alisal2023";
$usuario = "asir205";
$nombre_base_de_datos = "sistema_riego";

try {
    $conn = new PDO('mysql:host=' . $host . ';dbname=' . $nombre_base_de_datos, $usuario, $contraseña);
    // Establecer el modo de error PDO en excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Ocurrió algo con la base de datos: " . $e->getMessage();
}
?>
