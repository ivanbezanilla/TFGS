<?php

include_once "bbdd/bbdd.php"; 

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
    $_SESSION['id'] = $row['id'];
    
    // Verificar si el usuario es admin
    if ($email === 'admin@admin.es') {
        // Redirigir al admin
        header("Location: web/admin.php");
    } else {
        // Redirigir a la página principal
        header("Location: principal.php");
    }
    exit();
} else {
    // Usuario o contraseña incorrectos
    session_start();
    $_SESSION['error'] = "Usuario o contraseña incorrectos.";
    header("Location: inicio_sesion.php");
}

// Cerrar conexión
$conn = null;
?>