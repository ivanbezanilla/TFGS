<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.es') {
    header("Location: principal.php");
    exit();
}

include_once "../bbdd/bbdd.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    if ($accion === 'crear_usuario') {
        $nuevo_email = $_POST['nuevo_email'];
        $nuevo_password = password_hash($_POST['nuevo_password'], PASSWORD_DEFAULT);
        $nuevo_nombre = $_POST['nuevo_nombre'];
        $nuevo_apellidos = $_POST['nuevo_apellidos'];

        $stmt = $conn->prepare("INSERT INTO users (email, password, nombre, apellidos) VALUES (:email, :password, :nombre, :apellidos)");
        $stmt->bindParam(':email', $nuevo_email);
        $stmt->bindParam(':password', $nuevo_password);
        $stmt->bindParam(':nombre', $nuevo_nombre);
        $stmt->bindParam(':apellidos', $nuevo_apellidos);
        $stmt->execute();
    } elseif ($accion === 'eliminar_usuario') {
        $id_usuario = $_POST['id_usuario'];

        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id_usuario);
        $stmt->execute();
    } elseif ($accion === 'editar_usuario') {
        $id_usuario = $_POST['id_usuario'];
        $email_usuario = $_POST['email_usuario'];
        $nombre_usuario = $_POST['nombre_usuario'];
        $apellidos_usuario = $_POST['apellidos_usuario'];

        $stmt = $conn->prepare("UPDATE users SET email = :email, nombre = :nombre, apellidos = :apellidos WHERE id = :id");
        $stmt->bindParam(':email', $email_usuario);
        $stmt->bindParam(':nombre', $nombre_usuario);
        $stmt->bindParam(':apellidos', $apellidos_usuario);
        $stmt->bindParam(':id', $id_usuario);
        $stmt->execute();
    } elseif ($accion === 'crear_arduino') {
        $nueva_ip = $_POST['nueva_ip'];

        $stmt = $conn->prepare("INSERT INTO arduino (ip) VALUES (:ip)");
        $stmt->bindParam(':ip', $nueva_ip);
        $stmt->execute();
    } elseif ($accion === 'eliminar_arduino') {
        $id_arduino = $_POST['id_arduino'];

        $stmt = $conn->prepare("DELETE FROM arduino WHERE id = :id");
        $stmt->bindParam(':id', $id_arduino);
        $stmt->execute();
    } elseif ($accion === 'editar_arduino') {
        $id_arduino = $_POST['id_arduino'];
        $ip_arduino = $_POST['ip_arduino'];

        $stmt = $conn->prepare("UPDATE arduino SET ip = :ip WHERE id = :id");
        $stmt->bindParam(':ip', $ip_arduino);
        $stmt->bindParam(':id', $id_arduino);
        $stmt->execute();
    }
}

$usuarios = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
$dispositivos = $conn->query("SELECT * FROM arduino")->fetchAll(PDO::FETCH_ASSOC);

// Función para cerrar la sesión
function cerrarSesion() {
    // Destruir todas las variables de sesión
    session_destroy();

    // Redirigir al usuario a la página de inicio de sesión
    header("Location: principal.php");
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
    <title>Admin - GreenTech Solutions</title>
    <style>
        /* Estilos CSS para la página */
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
        nav button:hover ul {
            display: block;
        }
        main {
            padding: 20px;
            text-align: center;
        }
        #control-section {
            text-align: center;
            margin-top: 20px;
        }
        #control-section label {
            font-size: 18px;
            display: block;
            margin-bottom: 10px;
        }
        #control-section input[type="number"] {
            width: 60px;
            font-size: 18px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        #control-section button {
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
        #control-section button:hover {
            background-color: #45a049;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        .actions {
            display: flex;
            justify-content: space-around;
        }
        .actions form {
            display: inline;
        }
        .actions input[type="text"],
        .actions input[type="email"] {
            width: 150px;
        }
        @media screen and (max-width: 768px) {
            header {
                padding: 10px 0;
            }
            nav {
                text-align: center;
            }
            nav button {
                margin-right: 0;
            }
            main {
                padding: 10px;
            }
            .sensor-info {
                margin-bottom: 10px;
            }
            #control-section {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin - Monitorización y Control de Sensores</h1>
    </header>
    <nav>
        <form method="post" style="display: inline;">
            <button id="cerrar-sesion" type="submit" name="cerrar_sesion">Cerrar Sesión</button>
        </form>
    </nav>
    <main>
        <h2>Usuarios</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['apellidos']) ?></td>
                        <td class="actions">
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="accion" value="eliminar_usuario">
                                <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id']) ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="accion" value="editar_usuario">
                                <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id']) ?>">
                                <input type="text" name="email_usuario" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                                <input type="text" name="nombre_usuario" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                                <input type="text" name="apellidos_usuario" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>
                                <button type="submit">Editar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Crear Usuario</h3>
        <form method="post">
            <input type="hidden" name="accion" value="crear_usuario">
            <input type="email" name="nuevo_email" placeholder="Email" required>
            <input type="password" name="nuevo_password" placeholder="Contraseña" required>
            <input type="text" name="nuevo_nombre" placeholder="Nombre" required>
            <input type="text" name="nuevo_apellidos" placeholder="Apellidos" required>
            <button type="submit">Crear Usuario</button>
        </form>

        <h2>Dispositivos Arduino</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>IP</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dispositivos as $dispositivo): ?>
                    <tr>
                        <td><?= htmlspecialchars($dispositivo['id']) ?></td>
                        <td><?= htmlspecialchars($dispositivo['ip']) ?></td>
                        <td class="actions">
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="accion" value="eliminar_arduino">
                                <input type="hidden" name="id_arduino" value="<?= htmlspecialchars($dispositivo['id']) ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="accion" value="editar_arduino">
                                <input type="hidden" name="id_arduino" value="<?= htmlspecialchars($dispositivo['id']) ?>">
                                <input type="text" name="ip_arduino" value="<?= htmlspecialchars($dispositivo['ip']) ?>" required>
                                <button type="submit">Editar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Crear Dispositivo Arduino</h3>
        <form method="post">
            <input type="hidden" name="accion" value="crear_arduino">
            <input type="text" name="nueva_ip" placeholder="IP" required>
            <button type="submit">Crear Dispositivo</button>
        </form>
    </main>
    <footer>
        <p>© 2024 GreenTech Solutions - Todos los derechos reservados</p>
    </footer>
</body>
</html>
