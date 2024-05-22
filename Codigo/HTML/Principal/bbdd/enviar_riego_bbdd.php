<?php

require('/var/www/html/bbdd/phpMQTT.php'); // Asegúrate de que el archivo phpMQTT.php esté en el mismo directorio o ajusta la ruta según sea necesario
require('/var/www/html/bbdd/bbdd.php'); // Incluye el archivo con la conexión a la base de datos

$host = 'localhost';            // Cambia si es necesario
$port = 1883;                   // Cambia si es necesario
$client_id = 'phpMQTT-subscriber'; // Asegúrate de que este sea único para conectarse al servidor; podrías usar uniqid()

$mqtt = new Bluerhinos\phpMQTT($host, $port, $client_id);
if(!$mqtt->connect(true, NULL, NULL, NULL)) { // No se necesitan credenciales para suscribirse
	exit(1);
}

$mqtt->debug = true;

$topics['riego'] = array('qos' => 0, 'function' => 'procRiegoMsg'); // Cambia 'riego' al tema al que desees suscribirte para recibir los tiempos de riego
$mqtt->subscribe($topics, 0);

while($mqtt->proc()) {

}

$mqtt->close();

function procRiegoMsg($topic, $msg){
    global $conn; // Utiliza la conexión a la base de datos definida en bbdd.php

    echo 'Mensaje Recibido: ' . date('r') . "\n";
    echo "Tema: {$topic}\n\n";
    echo "\t$msg\n\n";

    // Parsear el mensaje para obtener el tiempo de riego
    $tiempo_riego = intval($msg);

    // Obtener el id_ard basado en el id_user
    $id_user = 1; // Establece el ID del usuario según sea necesario

    $sql_ard = "SELECT id FROM arduino WHERE id_user = ?";
    $stmt_ard = $conn->prepare($sql_ard);
    $stmt_ard->bindValue(1, $id_user, PDO::PARAM_INT);
    $stmt_ard->execute();
    $row_ard = $stmt_ard->fetch(PDO::FETCH_ASSOC);

    if ($row_ard) {
        $id_ard = $row_ard['id'];
    } else {
        echo "No se encontró un Arduino asociado al usuario con ID $id_user.\n";
        return;
    }

    // Insertar los datos en la tabla registros_riego
    $fecha_hora = date("Y-m-d H:i:s");

    $sql = "INSERT INTO registros_riego (id_user, id_ard, fecha_hora, duracion_segundos) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $id_user, PDO::PARAM_INT);
    $stmt->bindValue(2, $id_ard, PDO::PARAM_INT);
    $stmt->bindValue(3, $fecha_hora, PDO::PARAM_STR);
    $stmt->bindValue(4, $tiempo_riego, PDO::PARAM_INT);
    $stmt->execute();
}

?>
