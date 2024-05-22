<?php
// Verificar si se ha enviado el formulario de cerrar sesión
if(isset($_POST['cerrarsesion'])) {
    // Iniciar sesión si no está iniciada
    session_start();
    
    // Destruir todas las variables de sesión
    session_unset();
    
    // Destruir la sesión
    session_destroy();
    
    // Redireccionar al inicio de sesión
    header("Location: index.html");
    exit();
}
 
// Verificar si el usuario está autenticado, de lo contrario redirigir al inicio de sesión
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Riego Automatizado</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Nombre de la Empresa</h1>
            <form action="" method="post">
                <input type="submit" name="cerrarsesion" value="Cerrar sesión">
            </form>
        </div>
    </header>
    <main>
    <div class="container">
        <h2>Sistema de Riego Automatizado</h2>
        <div class="sensor-data">
            <h3>Datos de Sensores</h3>
            <div id="sensorValues"></div> <!-- Cambiado para mostrar los valores de los sensores aquí -->
        </div>
        <div class="riego">
            <h3>Control de Riego</h3>
            <label for="tiempoRiego">Tiempo de Riego (segundos): </label>
            <input type="number" id="tiempoRiego" min="1" value="10">
            <button onclick="activarRiego()">Activar Riego</button>
        </div>
    </div>
    <script>
        // Función para cargar los parámetros de los Arduinos desde la base de datos
        function cargarParametros() {
            const parametrosArduino = document.getElementById('parametrosArduino');
            const sensorValues = document.getElementById('sensorValues'); // Nuevo elemento para mostrar los valores de los sensores
            
            // Realizar una solicitud AJAX al servidor PHP
            const xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Procesar la respuesta y mostrar los parámetros en la página web
                    const parametros = JSON.parse(this.responseText);
                    parametros.forEach(arduino => {
                        const elemento = document.createElement('div');
                        elemento.innerHTML = `<p><b>Arduino: ${arduino.id_ard}</b></p>
                            <p>Humedad del suelo: ${arduino.humedad_suelo}</p>
                            <p>Temperatura: ${arduino.temperatura}</p>
                            <p>Humedad: ${arduino.humedad}</p>`;
                        sensorValues.appendChild(elemento); // Añadir el elemento al área de "Datos de Sensores"
                    });
                }
            };
            xhttp.open("GET", "obtener_parametros.php", true);
            xhttp.send();
        }
        
        // Cargar los parámetros al cargar la página
        window.onload = cargarParametros;
    </script>
    </main>
    <script src="script.js"></script>
</body>
</html>