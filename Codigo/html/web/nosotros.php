<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenTech Solutions - Sobre Nosotros</title>
    <style>
        /* Estilos CSS para la página */
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        header {
            background-color: #2b7d2b;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
        }

        .toggle-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: transparent;
            border: none;
            cursor: pointer;
            z-index: 1001;
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
            left: -200px;
            width: 200px;
            height: 100%;
            background-color: #3aa03a;
            padding-top: 20px;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: left 0.3s ease;
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
            padding: 12px 20px;
            transition: background-color 0.3s;
        }

        #menu li a:hover {
            background-color: #2b7d2b;
        }

        main {
            padding: 40px;
        }

        main h2 {
            color: #2b7d2b;
            font-size: 28px;
            margin-bottom: 20px;
        }

        main h3 {
            color: #2b7d2b;
            font-size: 22px;
            margin-top: 30px;
        }

        main p {
            margin-bottom: 20px;
            line-height: 1.6;
        }

        main ul {
            margin-bottom: 30px;
        }

        main li {
            margin-bottom: 10px;
        }

        #map-container {
            height: 400px;
            width: 100%;
        }
        iframe {
            border: 0;
            width: 100%;
            height: 100%;
        }

        footer {
            background-color: #2b7d2b;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        @media screen and (max-width: 768px) {
            header {
                padding: 15px 0;
            }
            main {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>GreenTech Solutions</h1>
        <p>Transformando la Agricultura con Tecnología Innovadora</p>
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
        <h2>Sobre Nosotros</h2>
        <p>En GreenTech Solutions, estamos comprometidos con la creación de soluciones innovadoras que revolucionan la agricultura y promueven la sostenibilidad ambiental. Nuestro enfoque se centra en la integración de tecnología de punta con prácticas agrícolas tradicionales para mejorar la eficiencia, la productividad y el cuidado del medio ambiente.</p>
        <p>Nuestra historia comenzó hace más de una década, cuando un equipo de ingenieros apasionados por la agricultura y la tecnología se unió con el objetivo de abordar los desafíos que enfrentan los agricultores en todo el mundo. Desde entonces, hemos estado desarrollando soluciones que van desde sistemas de riego inteligentes hasta plataformas de monitoreo de cultivos basadas en IoT.</p>
        <p>En GreenTech Solutions, creemos en la importancia de la innovación constante. Nos esforzamos por estar a la vanguardia de la tecnología agrícola, colaborando con expertos en diversas disciplinas para ofrecer soluciones que sean eficientes, confiables y sostenibles. Nuestro equipo está formado por ingenieros, agrónomos, científicos de datos y desarrolladores de software, todos trabajando juntos para impulsar el cambio y hacer del mundo un lugar mejor.</p>
        <h3>Nuestra Misión</h3>
        <p>Nuestra misión es proporcionar a los agricultores las herramientas y los conocimientos necesarios para optimizar sus operaciones, aumentar la productividad y reducir su impacto ambiental. Creemos en un futuro donde la tecnología y la agricultura trabajen en armonía para alimentar a una población en crecimiento de manera sostenible y responsable.</p>
        <h3>Nuestros Valores</h3>
        <ul>
            <li><strong>Innovación:</strong> Buscamos constantemente nuevas formas de mejorar y desarrollar soluciones más eficientes.</li>
            <li><strong>Calidad:</strong> Nos comprometemos a ofrecer productos y servicios de la más alta calidad a nuestros clientes.</li>
            <li><strong>Sostenibilidad:</strong> Nos preocupamos por el impacto ambiental de nuestras actividades y trabajamos para promover prácticas agrícolas sostenibles.</li>
            <li><strong>Colaboración:</strong> Creemos en el poder de la colaboración y trabajamos en estrecha colaboración con nuestros clientes y socios para lograr objetivos comunes.</li>
            <li><strong>Ética:</strong> Actuamos con integridad y honestidad en todas nuestras relaciones comerciales y profesionales.</li>
        </ul>
        <div id="map-container">
            <h3>Donde encontrarnos</h3>
            <p>Visítanos en nuestra oficina principal.</p>
            <!-- Mapa de Google Maps incrustado -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11599.410556643617!2d-3.8545302469467777!3d43.38010518367432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd49368abb617121%3A0x6c43539994967b2c!2s39692%20Lia%C3%B1o%2C%20Cantabria!5e0!3m2!1ses!2ses!4v1716809374219!5m2!1ses!2ses" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </main>
    <br>
    <br>
    <br>
    <br>
    <footer>
        <p>© 2024 GreenTech Solutions - Todos los derechos reservados</p>
    </footer>
    <script>
        function toggleMenu() {
            var menuContainer = document.getElementById("menu-container");
            if (menuContainer.style.left === "0px") {
                menuContainer.style.left = "-200px"; // Oculta el menú si ya está visible
            } else {
                menuContainer.style.left = "0px"; // Muestra el menú
            }
        }
    </script>
</body>
</html>
