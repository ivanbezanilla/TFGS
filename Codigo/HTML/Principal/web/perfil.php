<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado, de lo contrario redirigir al inicio de sesión
if (!isset($_SESSION['email'])) {
    header("Location: ../inicio_sesion.html");
    exit();
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

// Obtener el ID del usuario de la sesión
$id = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - GreenTech Solutions</title>
    <style>
        /* Estilos CSS aquí */
        /* Puedes agregar tus estilos personalizados aquí */
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
        .profile-info {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2); /* Sombra para resaltar */
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        .profile-info h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #2b7d2b; /* Verde oscuro */
        }
        .profile-info label {
            font-size: 20px;
            display: block;
            margin-bottom: 10px;
            color: #2b7d2b; /* Verde oscuro */
        }
        .profile-info input[type="text"],
        .profile-info input[type="email"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .profile-info button {
            background-color: #4CAF50; /* Color de fondo para botones */
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
            border-radius: 8px; /* Bordes redondeados */
        }
        .profile-info button:hover {
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
            #contact-form {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>GreenTech Solutions - Perfil</h1>
    </header>
    <nav>
        <button onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <ul id="menu">
            <li><a href="principal.php">Inicio</a></li>
            <li><a href="#">Perfil</a></li>
            <li><a href="contacto.php">Contacto</a></li>
        </ul>
        <!-- Botón para cerrar sesión -->
        <form method="post" style="display: inline;">
            <button id="cerrar-sesion" type="submit" name="cerrar_sesion">Cerrar Sesión</button>
        </form>
    </nav>
    <main>
        <div class="profile-info">
            <h2>Perfil</h2>
            <?php
            // Incluir el archivo de conexión a la base de datos
            require_once '../bbdd/bbdd.php';
            
            // Consulta SQL para obtener los datos del usuario
            $sql = "SELECT * FROM users WHERE id = :id_usuario";

            // Preparar la declaración
            $stmt = $conn->prepare($sql);

            // Vincular el parámetro
            $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
            
            // Ejecutar la consulta
            $stmt->execute();

            // Obtener la fila del resultado como un objeto
            $usuario = $stmt->fetch(PDO::FETCH_OBJ);

            // Mostrar el formulario con los datos del usuario
            echo "<form action='actualizar_perfil.php' method='post'>";
            echo "<input type='hidden' name='id_usuario' value='" . $usuario->id . "'>";
            echo "<label for='nombre'>Nombre:</label>";
            echo "<input type='text' id='nombre' name='nombre' value='" . $usuario->nombre . "' required>";
            echo "<label for='apellido'>Apellido:</label>";
            echo "<input type='text' id='apellido' name='apellido' value='" . $usuario->apellidos . "' required>";
            echo "<label for='email'>Correo Electrónico:</label>";
            echo "<input type='email' id='email' name='email' value='" . $usuario->email . "' required>";
            echo "<button type='submit'>Actualizar</button>";
            echo "</form>";
            ?>
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
