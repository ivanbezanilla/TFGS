<?php
// Verifica si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario
    $asunto = $_POST['asunto'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $mensaje = $_POST['mensaje'];

    // Configura el destinatario del correo
    $destinatario = "ibezanillal01@educantabria.es";
    
    // Construye el cuerpo del correo
    $cuerpo = "Asunto: $asunto\n";
    $cuerpo .= "Nombre: $nombre\n";
    $cuerpo .= "Email: $email\n\n";
    $cuerpo .= "Mensaje:\n$mensaje";

    // Envía el correo electrónico
    $enviado = mail($destinatario, $asunto, $cuerpo);

    // Verifica si el correo se envió correctamente
    if ($enviado) {
        // Redirecciona de vuelta a la página de contacto con un mensaje de éxito
        header("Location: contacto.html?enviado=true");
        exit();
    } else {
        // Redirecciona de vuelta a la página de contacto con un mensaje de error
        header("Location: contacto.html?enviado=false");
        exit();
    }
} else {
    // Si el formulario no se ha enviado por el método POST, redirige a la página de contacto
    header("Location: contacto.html");
    exit();
}
?>
