<?php
session_start(); // Asegúrate de iniciar la sesión en el archivo actualizar_perfil.php

require_once '../bbdd/bbdd.php';

// Verificar si se ha enviado una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $id_usuario = $_POST["id_usuario"];

    // Preparar y la consulta SQL para actualizar el perfil del usuario
    $query = "UPDATE users SET nombre = :nombre, apellidos = :apellido, email = :email WHERE id = :id_usuario";
    $statement = $conn->prepare($query);
    $statement->bindParam(':nombre', $nombre);
    $statement->bindParam(':apellido', $apellido);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':id_usuario', $id_usuario);

    try {
        $statement->execute();
        $_SESSION['mensaje_enviado'] = true;
    } catch (Exception $e) {
        $_SESSION['mensaje_error'] = true;
    }

    // Redirigir a la página principal después de actualizar el perfil
    header("Location: perfil.php");
    exit();
}
?>
