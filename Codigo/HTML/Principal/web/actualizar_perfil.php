<?php
require_once '../bbdd/bbdd.php';

// Verificar si se ha enviado una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $id_usuario = $_POST["id_usuario"];

    try {
        // Preparar y ejecutar la consulta SQL para actualizar el perfil del usuario
        $query = "UPDATE users SET nombre = :nombre, apellidos = :apellido, email = :email WHERE id = :id_usuario";
        $statement = $conn->prepare($query);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':apellido', $apellido);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':id_usuario', $id_usuario);
        $statement->execute();
        $mensaje = "Perfil actualizado correctamente.";
    } catch (PDOException $e) {
        $mensaje = "Error al actualizar el perfil: " . $e->getMessage();
    }
} else {
    // Si no se ha enviado una solicitud POST, mostrar un mensaje de acceso denegado
    $mensaje = "Acceso denegado.";
}

// Función para cerrar la sesión
function cerrarSesion() {
    // Destruir todas las variables de sesión
    session_destroy();

    // Redirigir al usuario a la página de inicio de sesión
    header("Location: ../inicio_sesion.html");
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
    <title>Actualizar Perfil - GreenTech Solutions</title>
    <style>
        /* Estilos CSS aquí */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f9f0; /* Color de fondo ecológico */
        }
        header {
            background-color: #2b7d2b; /* Verde oscuro */
            color: #fff;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2); /* Sombra para resaltar */
        }
        nav {
            position: relative; /* Ajustar posición relativa para el menú desplegable */
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
            margin-right: 20px; /* Espacio entre el botón de menú y el de cerrar sesión */
            vertical-align: middle; /* Alinear verticalmente con el botón de cerrar sesión */
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
            position: absolute; /* Ajustar posición absoluta */
            top: 50px; /* Alinear con la parte inferior del botón de menú */
            right: 10px;
            background-color: #3aa03a; /* Verde claro */
            border-radius: 5px;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2); /* Sombra para resaltar */
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
            background-color: #2b7d2b; /* Verde oscuro */
        }
        nav button:hover ul {
            display: block;
        }
        main {
            padding: 20px;
            text-align: left;
        }
        .message {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2); /* Sombra para resaltar */
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        .message h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #2b7d2b; /* Verde oscuro */
        }
        .message p {
            font-size: 18px;
        }
        .button-container {
            margin-top: 20px;
        }
        .button-container a {
            background-color: #4CAF50; /* Color de fondo para botones */
            border: none;
            color: white;
            padding: 10px 30px;
            font-size: 18px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition-duration: 0.4s;
            cursor: pointer;
            border-radius: 8px; /* Bordes redondeados */
        }
        .button-container a:hover {
            background-color: #45a049; /* Cambio de color al pasar el ratón */
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
            background-color: #2b7d2b; /* Verde oscuro */
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
        }
    </style>
</head>
<body>
    <header>
        <h1>GreenTech Solutions - Actualizar Perfil</h1>
    </header>
    <nav>
        <button onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <ul id="menu">
            <li><a href="principal.php">Inicio</a></li>
            <li><a href="perfil.php">Perfil</a></li>
            <li><a href="contacto.php">Contacto</a></li>
        </ul>
        <!-- Botón para cerrar sesión -->
        <form method="post" style="display: inline;">
            <button id="cerrar-sesion" type="submit" name="cerrar_sesion">Cerrar Sesión</button>
        </form>
    </nav>
    <main>
        <div class="message">
            <h2>Actualizar Perfil</h2>
            <p><?php echo $mensaje; ?></p>
            <div class="button-container">
                <a href="perfil.php">Volver</a>
            </div>
        </div>
    </main>
    <footer>
        <p>© 2024 GreenTech Solutions - Todos los derechos reservados</p>
    </footer>
    <script>
        // Función para mostrar u ocultar el menú desplegable
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
