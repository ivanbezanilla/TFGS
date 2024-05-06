<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "asir205";
$password = "Alisal2023";
$dbname = "sistema_riego";
$conn = new mysqli($servername, $username, $password, $dbname);
 
// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
 
// Consulta SQL para obtener el último registro para cada id_ard
$sql = "SELECT ds.* FROM datos_sensores ds
        JOIN (
            SELECT id_ard, MAX(id) AS max_id
            FROM datos_sensores
            GROUP BY id_ard
) ultimos ON ds.id_ard = ultimos.id_ard AND ds.id = ultimos.max_id ORDER BY id_ard ASC";
 
$result = $conn->query($sql);
 
if ($result->num_rows > 0) {
    // Datos encontrados, devolver resultados como JSON
    $output = array();
    while($row = $result->fetch_assoc()) {
        $output[] = $row;
    }
    echo json_encode($output);
} else {
    // No se encontraron datos
    echo "0 results";
}
$conn->close();
?>