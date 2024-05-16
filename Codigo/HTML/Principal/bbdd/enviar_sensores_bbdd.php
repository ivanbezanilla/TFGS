<?php

require('/var/www/html/bbdd/phpMQTT.php'); // Asegúrate de que el archivo phpMQTT.php esté en el mismo directorio o ajusta la ruta según sea necesario
require('/var/www/html/bbdd/bbdd.php'); // Incluye el archivo con la conexión a la base de datos

$host = 'localhost';            // Cambia si es necesario
$port = 1883;                   // Cambia si es necesario
$client_id = uniqid(); // Asegúrate de que este sea único para conectarse al servidor; podrías usar uniqid()

$mqtt = new Bluerhinos\phpMQTT($host, $port, $client_id);
if(!$mqtt->connect(true, NULL, NULL, NULL)) { // No se necesitan credenciales para suscribirse
	exit(1);
}

$mqtt->debug = true;

$topics['sensores'] = array('qos' => 0, 'function' => 'procMsg'); // Cambia 'sensores' al tema al que desees suscribirte y ajusta la función de procesamiento de mensajes si es necesario
$mqtt->subscribe($topics, 0);

while($mqtt->proc()) {

}

$mqtt->close();

function procMsg($topic, $msg){
    global $conn; // Utiliza la conexión a la base de datos definida en bbdd.php

    echo 'Mensaje Recibido: ' . date('r') . "\n";
    echo "Tema: {$topic}\n\n";
    echo "\t$msg\n\n";

    // Parsear el mensaje para obtener los valores de temperatura, humedad y humedad del suelo
    $temperatura = extractValue($msg, "Temperatura:");
    $humedad = extractValue($msg, "Humedad:");
    $humedad_suelo = extractValue($msg, "Humedad del suelo:");
    $id_ard = extractValue($msg, "id_ard:");

    // Insertar los datos en la base de datos
    $fecha_hora = date("Y-m-d H:i:s");

    $sql = "INSERT INTO datos_sensores (temperatura, humedad, humedad_suelo, fecha_hora, id_ard) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $temperatura, PDO::PARAM_STR);
    $stmt->bindValue(2, $humedad, PDO::PARAM_STR);
    $stmt->bindValue(3, $humedad_suelo, PDO::PARAM_STR);
    $stmt->bindValue(4, $fecha_hora, PDO::PARAM_STR);
    $stmt->bindValue(5, $id_ard, PDO::PARAM_INT);
    $stmt->execute();

}

// Función para extraer el valor de una cadena
function extractValue($msg, $key) {
    $startIndex = strpos($msg, $key);
    if ($startIndex === false) return null;

    $valueStartIndex = $startIndex + strlen($key);
    $endIndex = strpos($msg, ",", $valueStartIndex);
    if ($endIndex === false) $endIndex = strlen($msg);

    return substr($msg, $valueStartIndex, $endIndex - $valueStartIndex);
}

?>