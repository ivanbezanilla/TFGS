<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../inicio_sesion.php");
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

$stmt = $conn->prepare($query);
$stmt->execute($params);
$datos_sensores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función para cerrar la sesión
function cerrarSesion() {
    // Destruir todas las variables de sesión
    session_destroy();

    // Redirigir al usuario a la página de inicio de sesión
    header("Location: ../inicio_sesion.php");
    exit();
}

// Si se presiona el botón de "Cerrar Sesión", llamar a la función para cerrar la sesión
if (isset($_POST['cerrar_sesion'])) {
    cerrarSesion();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Datos de Sensores</title>
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
        nav {
            position: relative;
            background-color: #3aa03a;
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
            color: #fff;
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
        #cerrar-sesion {
            background-color: #2b7d2b; /* Verde oscuro */
            color: #fff; /* Texto blanco */
            font-weight: bold;
            border: 2px solid #2b7d2b; /* Borde verde oscuro */
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px; /* Borde ligeramente redondeado */
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }
        #cerrar-sesion:hover {
            background-color: #4CAF50; /* Verde al pasar el mouse */
        }
        footer {
            background-color: #2b7d2b;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Registro de Datos de Sensores</h1>
    </header>
    <nav>
        <form method="post" style="display: inline;">
            <button id="cerrar-sesion" type="submit" name="cerrar_sesion">Cerrar Sesión</button>
        </form>
        <button onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <ul id="menu">
            <li><a href="principal.php">Inicio</a></li>
            <li><a href="perfil.php">Perfil</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li><a href="registro.php">Sensores</a></li>
        </ul>
    </nav>
    <main>
        <form method="post">
            <label for="fecha_inicio">Fecha Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
            <label for="fecha_fin">Fecha Fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">
            <label for="id_ard">ID Arduino:</label>
            <input type="number" id="id_ard" name="id_ard" value="<?= htmlspecialchars($id_ard) ?>">
            <button type="submit">Filtrar</button>
        </form>
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
    </main>
    <footer>
        <p>© 2024 GreenTech Solutions - Todos los derechos reservados</p>
    </footer>
    <script>
        function toggleMenu() {
            var menu = document.getElementById("menu");
            if (menu.style.display === "block") {
                menu.style.display = "none";
            } else {
                menu.style.display = "block";
            }
        }
    </script>
</body>
</html>
