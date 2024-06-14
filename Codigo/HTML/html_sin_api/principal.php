<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenTech Solutions - Inicio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        .info {
            padding: 40px; 
            margin-bottom: -80px; /* Aquí se establece el margen inferior */ 
        }

        .info h2 {
            color: #2b7d2b;
            font-size: 28px;
            margin-bottom: 20px;
            grid-column: span 2;
        }

        main {
            padding: 40px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 20px;
        }

        main h2 {
            color: #2b7d2b;
            font-size: 28px;
            margin-bottom: 20px;
            grid-column: span 2;
        }

        .product {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
        }

        .product img {
            width: 100%;
            height: 200px; /* Altura fija para mantener la uniformidad */
            object-fit: cover; /* Ajusta la imagen para cubrir el tamaño especificado */
        }

        .product-info {
            padding: 20px;
            background-color: #fff;
        }

        .product-info h3 {
            margin-top: 0;
            color: #2b7d2b;
            font-size: 24px;
        }

        .product-info p {
            margin-bottom: 0;
        }

        .product-info p.description {
            margin-top: 10px;
        }

        footer {
            background-color: #2b7d2b;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        @media screen and (max-width: 768px) {
            main {
                padding: 20px;
                grid-template-columns: 1fr;
            }

            .product {
                /* Ajustes para mostrar los productos correctamente en una sola columna */
                margin-bottom: 20px; /* Espacio entre los productos */
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
            <li><a href="principal.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="web/nosotros.php"><i class="fas fa-users"></i> Sobre Nosotros</a></li>
            <li><a href="web/contacto.php"><i class="fas fa-envelope"></i> Contacto</a></li>
            <?php
            if (isset($_SESSION['email'])) {
                echo "<li><a href='web/sensores.php'><i class='fas fa-thermometer-half'></i> Sensores</a></li>";
                echo "<li><a href='web/grafico.php'><i class='fas fa-chart-line'></i> Gráficos</a></li>";
                echo "<li><a href='web/perfil.php'><i class='fas fa-user'></i> Perfil</a></li>";
                echo "<li><a href='cerrar-sesion.php'><i class='fas fa-sign-out-alt'></i> Cerrar sesión</a></li>";
            } else {
                echo "<li><a href='inicio_sesion.php'><i class='fas fa-sign-in-alt'></i> Iniciar sesión</a></li>";
            }
            ?>
        </ul>
    </div>
    <div class="info">
        <h2>Bienvenidos a GreenTech Solutions</h2>
        <p>Somos GreenTech Solutions, una empresa líder en innovación tecnológica aplicada a la agricultura. Nuestro compromiso es proporcionar soluciones avanzadas que ayuden a los agricultores a optimizar sus operaciones, aumentar la productividad y promover la sostenibilidad ambiental.</p>
        <p>En GreenTech Solutions, combinamos la experiencia en agronomía con la última tecnología para ofrecer productos y servicios de alta calidad que satisfagan las necesidades tanto del público general como del empresarial. Nuestro objetivo es contribuir al desarrollo de una agricultura más eficiente y sostenible.</p>
        <p>Con más de una década de experiencia en el sector, hemos desarrollado soluciones innovadoras que abarcan desde sistemas de monitoreo de cultivos hasta plataformas de gestión agrícola basadas en IoT. Nuestra pasión por la agricultura y el medio ambiente impulsa nuestro compromiso de ofrecer productos y servicios que marquen la diferencia.</p>
        <p>En GreenTech Solutions, nos enorgullece trabajar en estrecha colaboración con nuestros clientes y socios para desarrollar soluciones a medida que se adapten a sus necesidades específicas. Creemos en la importancia de la colaboración y la co-creación para impulsar la innovación y el crecimiento sostenible.</p>
        <p>Explora nuestros productos destacados a continuación y descubre cómo GreenTech Solutions puede ayudarte a llevar tu agricultura al siguiente nivel.</p>
    </div>
    <main>
        <h2>Nuestros productos</h2>
        <div class="product">
            <img src="../img/imagen_producto_general.jpg" alt="Producto para el público general">
            <div class="product-info">
                <h3>Producto para el Público General</h3>
                <p class="description">Nuestro producto estrella para el público general es una solución de monitoreo de cultivos fácil de usar y accesible para cualquier persona interesada en mejorar su huerto o jardín.</p>
            </div>
        </div>

        <div class="product">
            <img src="../img/imagen_producto_empresarial.jpg" alt="Producto para el público empresarial">
            <div class="product-info">
                <h3>Producto para el Público Empresarial</h3>
                <p class="description">Para empresas agrícolas y profesionales del sector, ofrecemos una plataforma completa de gestión de cultivos basada en tecnología IoT, diseñada para optimizar la producción y reducir los costos operativos.</p>
            </div>
        </div>
    </main>

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