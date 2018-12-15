<?php
include_once 'util.php';
include_once 'bd.php';
include_once 'secciones.php';
$secciones = new secciones($BD);
/**
 * EL correo parese que se manda pero no se recibe checar eso
 * Cambiar el diseño del exito del envio
 */
if (isset($_POST["submit"])) {
    if ($_POST["firstname"] != "" && $_POST["lastname"] != "" && $_POST["email"] != "" && $_POST["subject"] != "" && $_POST["message"] != "") {
        $recipient = "horus_black@live.com";
        $subject = filter_input(INPUT_POST, "subject", FILTER_SANITIZE_STRING);
        $nombre = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_STRING);
        $apellido = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_STRING);
        $sender = $nombre . $apellido;
        $senderEmail = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $message = filter_input(INPUT_POST, "message", FILTER_SANITIZE_STRING);
        
        $mailBody = "Nombre: $sender\nCorreo: $senderEmail\n\n$message";
        
        mail($recipient, $subject, $mailBody, "From: $sender <$senderEmail>");
        $secciones->alerta("Mensaje enviado");
        $secciones->redireccionarJS("../contact.php");
    } else {
        $secciones->alerta("No se ha enviado el mensaje. Recuerda llenar los campos marcados con (*)");
        $secciones->redireccionarJS("../contact.php");
    }
} else {
    $secciones->alerta("No se ha enviado el mensaje");
    $secciones->redireccionarJS("../contact.php");
}

