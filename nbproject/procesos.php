<?php

include_once 'PHP/util.php';
include_once 'PHP/bd.php';
include_once 'PHP/secciones.php';
$s = new secciones($BD);

if (isset($_POST["correo"]) && isset($_POST["password"]) && !$s->sesionIniciada()) {
    if ($_POST["correo"] <> "" && $_POST["password"] <> "") {
        if ($s->autentificar(filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL),  filter_var($_POST["password"], FILTER_SANITIZE_STRING))) {
            print_r(filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL));
            $s->alerta("Login correcto!");
            $s->alerta($s->autentificar(filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL),  filter_var($_POST["password"], FILTER_SANITIZE_STRING)));
            $s->redireccionarJS("blog.php");
            
        } else {
            print_r(filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL));
            $s->alerta("El Password o usuario son incorrectos");
            $s->redireccionarJS("index.php");
        }
    }
}else{
    print_r(filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL));
            $s->alerta("El Password o usuario son incorrectos");
            $s->redireccionarJS("index.php");
            session_destroy();
}


