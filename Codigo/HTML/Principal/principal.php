<?php
// Verificar si el usuario está autenticado, de lo contrario redirigir al inicio de sesión
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: iniciar_sesion.html");
    exit();
}

$id = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitorización y Control de Sensores - GreenTech Solutions</title>
    <style>
        /* Estilos CSS para la página */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f9f0; /* Color de fondo ecológico */
            color: #333; /* Color de texto principal */
        }
        header {
            background-color: #2b7d2b; /* Verde oscuro */
            color: #fff;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2); /* Sombra para resaltar */
        }
        nav {
            position: relative;
            background-color: #3aa03a; /* Verde claro */
            padding: 10px;
            text-align: right;
        }
        nav button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            display: inline-block;
            padding: 5px;
            margin-right: 20px;
            vertical-align: middle;
        }
        nav button span {
            display: block;
            width: 24px;
            height: 3px;
            background-color: #fff;
            margin-bottom: 5px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            position: absolute;
            top: 50px;
            right: 10px;
            background-color: #3aa03a;
            border-radius: 5px;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
            display: none;
        }
        nav ul li {
            padding: 10px 20px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }
        nav ul li a:hover {
            background-color: #2b7d2b;
        }
        nav button:hover ul {
            display: block;
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
        #cerrar-sesion {
            background-color: #fff;
            color: #000;
            font-weight: bold;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #cerrar-sesion:hover {
            background-color: #ddd;
        }
        footer {
            background-color: #2b7d2b;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        @media screen and (max-width: 768px) {
            /* Estilos para dispositivos móviles y tablets */
            header {
                padding: 10px 0;
            }
            nav {
                text-align: center;
            }
            nav button {
                margin-right: 0;
            }
            nav ul {
                position: static;
                display: block;
                background-color: transparent;
                box-shadow: none;
                margin-top: 10px;
            }
            nav ul li {
                display: inline-block;
                margin: 0 10px;
            }
            main {
                padding: 10px;
            }
            .sensor-info {
                margin-bottom: 10px;
            }
            #control-section {
                margin-top: 10px;
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.2/mqttws31.js" type="text/javascript"></script>
</head>
<body>
    <header>
        <h1>Monitorización y Control de Sensores</h1>
        <p>¡Bienvenido a GreenTech Solutions!</p>
        <p>Somos una empresa dedicada a desarrollar soluciones tecnológicas para el monitoreo y control de sensores en entornos agrícolas y ambientales.</p>
    </header>
    <nav>
        <button onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <ul id="menu">
            <li><a href="#">Inicio</a></li>
            <li><a href="perfil.php">Perfil</a></li>
            <li><a href="contacto.php">Contacto</a></li>
        </ul>
        <button onclick="cerrarSesion()" id="cerrar-sesion">Cerrar Sesión</button>
    </nav>
    <main>
        <div id="sensor-data">
            <div class="sensor-info">
                <h2>Temperatura</h2>
                <p id="temperatura">--</p>
            </div>
            <div class="sensor-info">
                <h2>Humedad</h2>
                <p id="humedad">--</p>
            </div>
            <div class="sensor-info">
                <h2>Humedad del Suelo</h2>
                <p id="humedad-suelo">--</p>
            </div>
        </div>
        <div id="control-section">
            <h2>Control de Riego</h2>
            <label for="tiempo-riego">Duración del Riego (segundos):</label>
            <input type="number" id="tiempo-riego" min="1" value="10">
            <button onclick="enviarTiempoRiego()">Activar Riego</button>
        </div>
    </main>
    <footer>
        <p>© 2024 GreenTech Solutions - Todos los derechos reservados</p>
    </footer>
    <script>
        var client = new Paho.MQTT.Client("54.163.155.226", 9001, "web_" + parseInt(Math.random() * 100, 10));

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
            var menu = document.getElementById("menu");
            if (menu.style.display === "block") {
                menu.style.display = "none";
            } else {
                menu.style.display = "block";
            }
        }

        function cerrarSesion() {
            // Aquí iría el código para cerrar la sesión del usuario
            console.log("Sesión cerrada");
            // Redirigir al usuario a la página de inicio de sesión, por ejemplo:
            window.location.href = "inicio_sesion.html";
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
