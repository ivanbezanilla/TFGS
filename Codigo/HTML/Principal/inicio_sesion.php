<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - GreenTech Solutions</title>
    <style>
        /* Estilos CSS para la página */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f9f0; /* Color de fondo ecológico */
            color: #333; /* Color de texto principal */
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Sombra ligera */
        }
        h1 {
            text-align: center;
            color: #2b7d2b; /* Verde oscuro */
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #2b7d2b; /* Verde oscuro */
            color: #fff;
            border: none;
            padding: 15px 0;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #238c23; /* Verde más oscuro al pasar el mouse */
        }
        .message {
            text-align: center;
            margin-top: 20px;
            color: #ff6347; /* Rojo coral */
        }
        .company-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #2b7d2b; /* Verde oscuro */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Iniciar Sesión</h1>
        <p class="company-title">Bienvenido a GreenTech Solutions</p>
        <!-- Formulario de inicio de sesión -->
        <form action="login.php" method="POST">
            <label for="email">Correo Electrónico:</label>
            <input type="text" id="email" name="email" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Iniciar Sesión">
        </form>
        <!-- Mostrar mensaje de error -->
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo "<p class='message'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']); // Limpiar el mensaje de error
        }
        ?>
    </div>
</body>
</html>
