<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "asir205";
$password = "Alisal2023";
$dbname = "sistema_riego";
 
try {
    // Crear conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Establecer modo de error a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    // Obtener datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];
 
    // Consulta preparada para verificar las credenciales
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
 
    // Verificar si se encontró algún registro
    if ($stmt->rowCount() > 0) {
        session_start();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['email'] = $row['email'];
        // Inicio de sesión exitoso, redireccionar a la página principal
        header("Location: principal.php");
        exit();
    } else {
        // Usuario o contraseña incorrectos
        echo "Usuario o contraseña incorrectos.";
    }
} catch(PDOException $e) {
    // Manejo de excepciones
    echo "Error de conexión: " . $e->getMessage();
}
 
// Cerrar conexión
$conn = null;
?>