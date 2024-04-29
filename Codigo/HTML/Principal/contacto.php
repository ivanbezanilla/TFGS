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
    <title>Contacto - GreenTech Solutions</title>
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
        #contact-form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            text-align: left;
        }
        #contact-form h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #2b7d2b; /* Verde oscuro */
        }
        #contact-form p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        #contact-form label {
            font-size: 18px;
            display: block;
            margin-bottom: 10px;
            color: #2b7d2b; /* Verde oscuro */
        }
        #contact-form input[type="text"],
        #contact-form input[type="email"],
        #contact-form textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        #contact-form textarea {
            height: 150px;
        }
        #contact-form button {
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
        #contact-form button:hover {
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
            #contact-form {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>GreenTech Solutions - Contacto</h1>
        <p>¿Necesitas ayuda o tienes alguna pregunta? ¡Estamos aquí para ayudarte!</p>
    </header>
    <nav>
        <button onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <ul id="menu">
            <li><a href="index.html">Inicio</a></li>
            <li><a href="perfil.php">Perfil</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
        <button onclick="cerrarSesion()" id="cerrar-sesion">Cerrar Sesión</button>
    </nav>
    <main>
        <div id="contact-form">
            <h2>Contáctanos</h2>
            <p>Estamos aquí para ayudarte con cualquier pregunta o inquietud que puedas tener. Por favor, completa el formulario a continuación y te responderemos lo antes posible.</p>
            <form action="enviar.php" method="post">
                <label for="asunto">Asunto:</label>
                <input type="text" id="asunto" name="asunto" required>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" required></textarea>
                <button type="submit">Enviar</button>
            </form>
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

        // Función para cerrar sesión
        function cerrarSesion() {
            // Aquí iría el código para cerrar la sesión del usuario
            console.log("Sesión cerrada");
            // Redirigir al usuario a la página de inicio de sesión, por ejemplo:
            window.location.href = "inicio_sesion.html";
        }
    </script>
</body>
</html>
