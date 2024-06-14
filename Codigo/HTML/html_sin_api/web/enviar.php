<?php
session_start(); // Iniciar la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recopilar datos del formulario
    $asunto = $_POST['asunto'];
    $nombre = $_POST['nombre'];
    $mensaje = $_POST['mensaje'];

    // Configurar la biblioteca cliente de SendGrid
    require 'sendgrid/vendor/autoload.php';
    $sendgrid = new \SendGrid('API_SEND');

    // Configurar el correo electrónico
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("ibezanillal01@educantabria.es", "$nombre");
    $email->setSubject($asunto);
    $email->addTo("ibezanillal01@educantabria.es", "Destinatario");
    $email->addContent("text/plain", $mensaje);

    // Enviar el correo electrónico utilizando SendGrid API
    try {
        $response = $sendgrid->send($email);
        $_SESSION['mensaje_enviado'] = true;
    } catch (Exception $e) {
        $_SESSION['mensaje_error'] = true;
    }
}

// Redirigir a la página principal después de enviar el formulario
header("Location: contacto.php");
exit();
?>
