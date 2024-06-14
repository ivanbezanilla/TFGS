<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../principal.php");
    exit();
}

include_once "../bbdd/bbdd.php";

// Variable para el filtro por id_ard
$id_ard = isset($_POST['id_ard']) ? $_POST['id_ard'] : '';

// Variable para el filtro por mes
$mes = isset($_POST['mes']) ? $_POST['mes'] : '';

// Consulta SQL base sin filtros
$query = "SELECT * FROM datos_sensores WHERE 1=1";
$params = [];

// Agregar filtro por id_ard si se ha especificado
if (!empty($id_ard)) {
    $query .= " AND id_ard = :id_ard";
    $params[':id_ard'] = $id_ard;
}

// Agregar filtro por mes si se ha especificado
if (!empty($mes)) {
    $query .= " AND MONTH(fecha_hora) = :mes";
    $params[':mes'] = $mes;
}

// Preparar y ejecutar la consulta SQL
$stmt = $conn->prepare($query);
$stmt->execute($params);
$datos_sensores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener fechas y datos para los gráficos
$fechas = [];
$temperaturas = [];
$humedades = [];
$humedades_suelo = [];

foreach ($datos_sensores as $dato) {
    $fechas[] = date('Y-m-d H:i:s', strtotime($dato['fecha_hora']));
    $temperaturas[] = $dato['temperatura'];
    $humedades[] = $dato['humedad'];
    $humedades_suelo[] = $dato['humedad_suelo'];
}

// Función para limpiar y convertir las temperaturas
function limpiarTemperaturas($temperaturas)
{
    foreach ($temperaturas as &$temp) {
        // Elimina el texto " C" y convierte a número
        $temp = (float) str_replace(" C", "", $temp);
    }
    return $temperaturas;
}

// Función para limpiar y convertir las humedades
function limpiarHumedades($humedades)
{
    foreach ($humedades as &$humedad) {
        // Elimina el texto " %" y convierte a número
        $humedad = (float) str_replace(" %", "", $humedad);
    }
    return $humedades;
}

// Función para limpiar y convertir las humedades_suelo
function limpiarHumedadesSuelo($humedades_suelo)
{
    foreach ($humedades_suelo as &$humedad_suelo) {
        // Elimina el texto " %" y convierte a número
        $humedad_suelo = (float) str_replace(" %", "", $humedad_suelo);
    }
    return $humedades_suelo;
}

// Limpia y convierte los datos
$temperaturas_limpias = limpiarTemperaturas($temperaturas);
$humedades_limpias = limpiarHumedades($humedades);
$humedades_suelo_limpias = limpiarHumedadesSuelo($humedades_suelo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico de Datos de Sensores</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        form {
            margin-bottom: 20px;
        }
        form label {
            font-size: 18px;
            margin-right: 10px;
        }
        form input[type="number"] {
            padding: 5px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        form button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        form button:hover {
            background-color: #45a049;
        }
        .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Centrar los gráficos */
            margin-top: 20px;
        }

        /* Estilos para los gráficos individuales */
        canvas {
            max-width: 90%; /* Ancho máximo de los gráficos */
            margin-bottom: 20px; /* Margen inferior para separar los gráficos */
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            height: 100px;
        }

        .chart-container canvas {
            max-width: 80%; /* Ancho máximo de los gráficos */
            height: 300px; /* Modifica la altura según sea necesario */
            margin-bottom: 20px; /* Margen inferior para separar los gráficos */
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
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
            main {
                padding: 10px;
            }
            canvas {
                max-width: 100%; /* Asegurar que los gráficos se ajusten al ancho de la pantalla en dispositivos móviles */
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>GreenTech Solutions - Gráfico de Datos</h1>
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
        <form method="post">
            <label for="id_ard">ID Arduino:</label>
            <input type="number" id="id_ard" name="id_ard" value="<?= htmlspecialchars($id_ard) ?>">
            <label for="mes">Mes:</label>
            <select name="mes" id="mes">
                <option value="">Todos</option>
                <option value="01">Enero</option>
                <option value="02">Febrero</option>
                <option value="03">Marzo</option>
                <option value="04">Abril</option>
                <option value="05">Mayo</option>
                <option value="06">Junio</option>
                <option value="07">Julio</option>
                <option value="08">Agosto</option>
                <option value="09">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>
        <div class="chart-container">
            <canvas id="graficoTemperatura"></canvas>
            <canvas id="graficoHumedad"></canvas>
            <canvas id="graficoHumedadSuelo"></canvas>
        </div>
    </main>
    <footer>
        <p>© 2024 GreenTech Solutions - Todos los derechos reservados</p>
    </footer>
    <!-- Script de Chart.js y el adaptador date-fns para manejar fechas -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script>
        // Función para mostrar/ocultar el menú de navegación
        function toggleMenu() {
            var menuContainer = document.getElementById("menu-container");
            if (menuContainer.style.left === "0px") {
                menuContainer.style.left = "-200px"; // Oculta el menú si ya está visible
            } else {
                menuContainer.style.left = "0px"; // Muestra el menú
            }
        }

        // Configuración del gráfico de Temperatura
        const ctxTemperatura = document.getElementById('graficoTemperatura').getContext('2d');
        const graficoTemperatura = new Chart(ctxTemperatura, {
            type: 'line',
            data: {
                labels: <?= json_encode($fechas) ?>,
                datasets: [{
                    label: 'Temperatura',
                    data: <?= json_encode($temperaturas_limpias) ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'yyyy-MM-dd HH:mm:ss'
                        },
                        title: {
                            display: true,
                            text: 'Fecha y Hora'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Temperatura (°C)'
                        }
                    }
                }
            }
        });

        // Configuración del gráfico de Humedad
        const ctxHumedad = document.getElementById('graficoHumedad').getContext('2d');
        const graficoHumedad = new Chart(ctxHumedad, {
            type: 'line',
            data: {
                labels: <?= json_encode($fechas) ?>,
                datasets: [{
                    label: 'Humedad',
                    data: <?= json_encode($humedades_limpias) ?>,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'yyyy-MM-dd HH:mm:ss'
                        },
                        title: {
                            display: true,
                            text: 'Fecha y Hora'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Humedad (%)'
                        }
                    }
                }
            }
        });

        // Configuración del gráfico de Humedad del Suelo
        const ctxHumedadSuelo = document.getElementById('graficoHumedadSuelo').getContext('2d');
        const graficoHumedadSuelo = new Chart(ctxHumedadSuelo, {
            type: 'line',
            data: {
                labels: <?= json_encode($fechas) ?>,
                datasets: [{
                    label: 'Humedad del Suelo',
                    data: <?= json_encode($humedades_suelo_limpias) ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'yyyy-MM-dd HH:mm:ss'
                        },
                        title: {
                            display: true,
                            text: 'Fecha y Hora'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Humedad del Suelo (%)'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
