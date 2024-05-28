<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado, de lo contrario redirigir al inicio de sesión
if (!isset($_SESSION['email'])) {
    header("Location: principal.php");
    exit();
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
            main {
                padding: 10px;
            }
            #contact-form {
                max-width: 100%;
            }
        }
        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            text-align: center;
        }
        .modal-content h2 {
            color: #2b7d2b;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>GreenTech Solutions - Perfil</h1>
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
            <li><a href="principal.php">Inicio</a></li>
            <li><a href="nosotros.php">Sobre Nosotros</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <?php
            if (isset($_SESSION['email'])) {
                echo "<li><a href='sensores.php'>Sensores</a></li>";
                echo "<li><a href='grafico.php'>Gráficos</a></li>";
                echo "<li><a href='perfil.php'>Perfil</a></li>";
                echo "<li><a href='../cerrar-sesion.php'>Cerrar sesión</a></li>";
            } else {
                echo "<li><a href='../inicio_sesion.php'>Iniciar sesión</a></li>";
            }
            ?>
        </ul>
    </div>
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

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modal-title"></h2>
            <p id="modal-message"></p>
        </div>
    </div>
    
    <script>
        // Función para mostrar u ocultar el menú desplegable
        function toggleMenu() {
            var menuContainer = document.getElementById("menu-container");
            if (menuContainer.style.left === "0px") {
                menuContainer.style.left = "-200px"; // Oculta el menú si ya está visible
            } else {
                menuContainer.style.left = "0px"; // Muestra el menú
            }
        }

        // Función para abrir el modal
        function openModal(title, message) {
            document.getElementById('modal-title').innerText = title;
            document.getElementById('modal-message').innerText = message;
            document.getElementById('myModal').style.display = "block";
        }

        // Función para cerrar el modal
        function closeModal() {
            document.getElementById('myModal').style.display = "none";
        }

        // Mostrar el mensaje modal si hay un mensaje en la sesión
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(isset($_SESSION['mensaje_enviado'])): ?>
                openModal("¡Éxito!", "¡El perfil ha sido actualizado correctamente!");
                <?php unset($_SESSION['mensaje_enviado']); ?>
            <?php elseif(isset($_SESSION['mensaje_error'])): ?>
                openModal("Error", "Hubo un error al actualizar el perfil. Por favor, inténtalo de nuevo más tarde.");
                <?php unset($_SESSION['mensaje_error']); ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>
