<?php
/**
 * No confundir con el procesos de la vista
 */
include_once 'PHP/util.php';
include_once 'PHP/bd.php';
include_once 'PHP/secciones.php';
$secciones = new secciones($BD);

session_destroy();
if (isset($_POST["correo"]) && isset($_POST["password"]) && ! $secciones->sesionIniciada()) {
    if ($_POST["correo"] != "" && $_POST["password"] != "") {
        if ($secciones->autentificar(filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL), filter_var($_POST["password"], FILTER_SANITIZE_STRING))) {
            $secciones->alerta("Login correcto!");
            $secciones->redireccionarJS("blog.php");
        } else {
            $secciones->alerta("El Password o usuario son incorrectos");
            $secciones->redireccionarJS("index.php");
            
            session_destroy();
        }
    }
}

