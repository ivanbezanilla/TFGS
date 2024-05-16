<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $asunto = $_POST['asunto'];
    $nombre = $_POST['nombre'];
    $mensaje = $_POST['mensaje'];

    // Configuración del servidor SMTP de Outlook
    $smtpHost = 'smtp-mail.outlook.com'; // Servidor SMTP de Outlook
    $smtpPort = 587; // Puerto SMTP
    $smtpUsuario = 'pruebaaodoo@outlook.es'; // Tu dirección de correo de Outlook
    $smtpPassword = 'Alisal2023'; // Tu contraseña de Outlook

    // Configurar el correo electrónico
    $destinatario = "pruebaaodoo@outlook.es"; // Dirección de correo del destinatario
    $remite = "From: $nombre <$smtpUsuario>"; // Remitente

    // Formatear el cuerpo del correo
    $cuerpoCorreo = "Asunto: $asunto\n";
    $cuerpoCorreo .= "Nombre: $nombre\n";
    $cuerpoCorreo .= "Mensaje:\n$mensaje";

    // Configurar opciones adicionales para la función mail
    $opciones = "-f$smtpUsuario";

    // Configurar la conexión SMTP
    $smtpConexion = sprintf('tls://%s:%d', $smtpHost, $smtpPort);

    // Intentar enviar el correo
    if (mail($destinatario, $asunto, $cuerpoCorreo, $remite, $opciones)) {
        // Si se envió correctamente, redirigir de vuelta a la página de contacto
        header("Location: contacto.php?enviado=1");
    } else {
        // Si hubo un error al enviar el correo, redirigir con un mensaje de error
        header("Location: contacto.php?enviado=0");
    }
} else {
    // Si se intenta acceder a este script directamente sin enviar el formulario, redirigir a la página de contacto
    header("Location: contacto.php");
}
?>
