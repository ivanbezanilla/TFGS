<?php
session_start(); // Iniciar la sesión
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - GreenTech Solutions</title>
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
        <h1>GreenTech Solutions - Contacto</h1>
        <p>¿Necesitas ayuda o tienes alguna pregunta? ¡Estamos aquí para ayudarte!</p>
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
        <div id="contact-form">
            <h2>Contáctanos</h2>
            <p>Estamos aquí para ayudarte con cualquier pregunta o inquietud que puedas tener. Por favor, completa el formulario a continuación y te responderemos lo antes posible.</p>
            <form action="enviar.php" method="post">
                <label for="asunto">Asunto:</label>
                <input type="text" id="asunto" name="asunto" required>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" required></textarea>
                <button type="submit">Enviar</button>
            </form>
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
                openModal("¡Éxito!", "¡El mensaje ha sido enviado correctamente!");
                <?php unset($_SESSION['mensaje_enviado']); ?>
            <?php elseif(isset($_SESSION['mensaje_error'])): ?>
                openModal("Error", "Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo más tarde.");
                <?php unset($_SESSION['mensaje_error']); ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>
