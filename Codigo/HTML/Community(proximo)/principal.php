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
                <div id="sensorValues">
                    <p id="humedad">Humedad: </p>
                    <p id="temperatura">Temperatura: </p>
                    <p id="humedadSuelo">Humedad del Suelo: </p>
                </div>
            </div>
            <div class="riego">
                <h3>Control de Riego</h3>
                <label for="tiempoRiego">Tiempo de Riego (segundos): </label>
                <input type="number" id="tiempoRiego" min="1" value="10">
                <button onclick="activarRiego()">Activar Riego</button>
            </div>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>