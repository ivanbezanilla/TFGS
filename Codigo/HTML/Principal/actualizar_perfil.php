<?php
require_once 'bbdd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $id_usuario = $_POST["id_usuario"];

    try {
        $query = "UPDATE users SET nombre = :nombre, apellidos = :apellido, email = :email WHERE id = :id_usuario";
        $statement = $conn->prepare($query);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':apellido', $apellido);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':id_usuario', $id_usuario);
        $statement->execute();
        echo "Perfil actualizado correctamente.";
    } catch (PDOException $e) {
        echo "Error al actualizar el perfil: " . $e->getMessage();
    }
} else {
    echo "Acceso denegado.";
}
?>

