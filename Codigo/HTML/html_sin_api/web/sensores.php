<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../principal.php");
    exit();
}

include_once "../bbdd/bbdd.php";

// Variables para los filtros
$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
$fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
$id_ard = isset($_POST['id_ard']) ? $_POST['id_ard'] : '';

// Consulta SQL con filtros
$query = "SELECT * FROM datos_sensores WHERE 1=1";
$params = [];

if (!empty($fecha_inicio)) {
    $query .= " AND fecha_hora >= :fecha_inicio";
    $params[':fecha_inicio'] = $fecha_inicio;
}
if (!empty($fecha_fin)) {
    $query .= " AND fecha_hora <= :fecha_fin";
    $params[':fecha_fin'] = $fecha_fin;
}
if (!empty($id_ard)) {
    $query .= " AND id_ard = :id_ard";
    $params[':id_ard'] = $id_ard;
}

$query .= " ORDER BY fecha_hora DESC"; // Ordenar por fecha y hora en orden descendente

$stmt = $conn->prepare($query);
$stmt->execute($params);
$datos_sensores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Datos de Sensores</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f9f0;
            color: #333;
        }
        header {
            background-color: #2b7d2b;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
        }
        .toggle-btn {
            position: fixed;
            top: 20px; /* Distancia desde arriba */
            left: 20px; /* Distancia desde la izquierda */
            background-color: transparent;
            border: none;
            cursor: pointer;
            z-index: 1001; /* Asegura que esté por encima del menú */
        }
        .toggle-btn span {
            display: block;
            width: 24px;
            height: 3px;
            background-color: #fff;
            margin-bottom: 5px;
        }
        .menu-container {
            position: fixed;
            top: 0;
            left: -200px; /* Oculta el menú al principio */
            width: 200px;
            height: 100%;
            background-color: #3aa03a;
            padding-top: 20px;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: left 0.3s ease; /* Agrega una transición suave al abrir/cerrar el menú */
        }
        #menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        #menu li {
            margin-bottom: 10px;
        }
        #menu li a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
        }
        #menu li a:hover {
            background-color: #2b7d2b;
        }
        main {
            padding: 20px;
            text-align: center;
        }
        .sensor-info {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        .sensor-info h2 {
            margin-top: 0;
            font-size: 24px;
            color: #2b7d2b; /* Verde oscuro */
        }
        .sensor-info p {
            font-size: 20px;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        #control-section {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 40px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        #control-section label {
            font-size: 18px;
            display: block;
            margin-bottom: 10px;
        }
        #control-section input[type="number"] {
            width: 60px;
            font-size: 18px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        #control-section button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 30px;
            font-size: 18px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            transition-duration: 0.4s;
            cursor: pointer;
            border-radius: 8px;
        }
        #control-section button:hover {
            background-color: #45a049;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            font-size: 18px;
            margin-right: 10px;
        }
        input[type="date"],
        input[type="number"] {
            padding: 8px;
            font-size: 16px;
            margin-right: 10px;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        footer {
            background-color: #2b7d2b;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        #sensor-data {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            grid-gap: 20px; /* Espacio entre los elementos */
        }

        .sensor-info {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
</head>
<body>
    <header>
        <h1>GreenTech Solutions - Sensores</h1>
    </header>
    <button class="toggle-btn" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <div class="menu-container" id="menu-container">
        <ul id="menu">
            <br>
            <br>
            <li><a href="../principal.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="nosotros.php"><i class="fas fa-users"></i> Sobre Nosotros</a></li>
            <li><a href="contacto.php"><i class="fas fa-envelope"></i> Contacto</a></li>
            <?php
            if (isset($_SESSION['email'])) {
                echo "<li><a href='sensores.php'><i class='fas fa-thermometer-half'></i> Sensores</a></li>";
                echo "<li><a href='grafico.php'><i class='fas fa-chart-line'></i> Gráficos</a></li>";
                echo "<li><a href='perfil.php'><i class='fas fa-user'></i> Perfil</a></li>";
                echo "<li><a href='../cerrar-sesion.php'><i class='fas fa-sign-out-alt'></i> Cerrar sesión</a></li>";
            } else {
                echo "<li><a href='../inicio_sesion.php'><i class='fas fa-sign-in-alt'></i> Iniciar sesión</a></li>";
            }
            ?>
        </ul>
    </div>
    <main>
    <h2><i class="fas fa-wifi"></i> Sensores en tiempo real</h2>
        <div id="sensor-data" class="sensor-container">
            <div class="sensor-info">
                <h2><i class="fas fa-temperature-high"></i> Temperatura</h2>
                <p id="temperatura">--</p>
            </div>
            <div class="sensor-info">
                <h2><i class="fas fa-tint"></i> Humedad</h2>
                <p id="humedad">--</p>
            </div>
            <div class="sensor-info">
                <h2><i class="fas fa-water"></i> Humedad del Suelo</h2>
                <p id="humedad-suelo">--</p>
            </div>
        </div>
        <div id="control-section">
            <h2><i class="fas fa-water"></i> Control de Riego (Proximamente)</h2>
            <label for="tiempo-riego">Duración del Riego (segundos):</label>
            <input type="number" id="tiempo-riego" min="1" value="10">
            <button onclick="enviarTiempoRiego()">Activar Riego</button>
        </div>
        
        <h2><i class="fas fa-list"></i> Registro</h2>
        <form method="post">
            <label for="fecha_inicio">Fecha Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
            <label for="fecha_fin">Fecha Fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">
            <label for="id_ard">ID Arduino:</label>
            <input type="number" id="id_ard" name="id_ard" value="<?= htmlspecialchars($id_ard) ?>">
            <button type="submit"><i class="fas fa-filter"></i> Filtrar</button>
        </form>
        <div style="height: 400px; overflow-y: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Temperatura</th>
                        <th>Humedad</th>
                        <th>Humedad Suelo</th>
                        <th>Fecha y Hora</th>
                        <th>ID Arduino</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos_sensores as $dato): ?>
                        <tr>
                            <td><?= htmlspecialchars($dato['id']) ?></td>
                            <td><?= htmlspecialchars($dato['temperatura']) ?></td>
                            <td><?= htmlspecialchars($dato['humedad']) ?></td>
                            <td><?= htmlspecialchars($dato['humedad_suelo']) ?></td>
                            <td><?= htmlspecialchars($dato['fecha_hora']) ?></td>
                            <td><?= htmlspecialchars($dato['id_ard']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        <p>© 2024 GreenTech Solutions - Todos los derechos reservados</p>
    </footer>
    <script>
        window.addEventListener('mouseover', initLandbot, { once: true });
        window.addEventListener('touchstart', initLandbot, { once: true });
        var myLandbot;
        function initLandbot() {
        if (!myLandbot) {
            var s = document.createElement('script');s.type = 'text/javascript';s.async = true;
            s.addEventListener('load', function() {
            var myLandbot = new Landbot.Livechat({
                configUrl: 'https://storage.googleapis.com/landbot.online/v3/H-2517666-A6OV8IA612SWVVF0/index.json',
            });
            });
            s.src = 'https://cdn.landbot.io/landbot-3/landbot-3.0.0.js';
            var x = document.getElementsByTagName('script')[0];
            x.parentNode.insertBefore(s, x);
        }
        }

        var client = new Paho.MQTT.Client("wss://jarduino.ddns.net:8883/mqtt", "web_" + parseInt(Math.random() * 100, 10));

        client.onMessageArrived = function (message) {
            var payload = message.payloadString;
            var temperature = extractValue(payload, "Temperatura:");
            var humidity = extractValue(payload, "Humedad:");
            var soilHumidity = extractValue(payload, "Humedad del suelo:");

            document.getElementById("temperatura").innerText = "Temperatura: " + temperature;
            document.getElementById("humedad").innerText = "Humedad: " + humidity;
            document.getElementById("humedad-suelo").innerText = "Humedad del Suelo: " + soilHumidity;
        };

        function extractValue(payload, key) {
            var startIndex = payload.indexOf(key);
            if (startIndex === -1) return "--";

            var valueStartIndex = startIndex + key.length;
            var endIndex = payload.indexOf(",", valueStartIndex);
            if (endIndex === -1) endIndex = payload.length;

            return payload.substring(valueStartIndex, endIndex);
        }

        client.connect({
            onSuccess: function () {
                console.log("Conectado al servidor MQTT");
                client.subscribe("sensores");
            }
        });

        function toggleMenu() {
            var menuContainer = document.getElementById("menu-container");
            if (menuContainer.style.left === "0px") {
                menuContainer.style.left = "-200px"; // Oculta el menú si ya está visible
            } else {
                menuContainer.style.left = "0px"; // Muestra el menú
            }
        }

        function enviarTiempoRiego() {
            var tiempoRiego = document.getElementById("tiempo-riego").value;
            if (tiempoRiego.trim() === "") {
                alert("Por favor, ingresa la duración del riego.");
                return;
            }
            tiempoRiego = parseInt(tiempoRiego);
            if (client.isConnected()) {
                client.send("riego", tiempoRiego.toString());
                console.log("Tiempo de riego enviado: " + tiempoRiego + " segundos.");
            } else {
                console.log("El cliente MQTT no está conectado.");
            }
        }
    </script>
</body>
</html>
