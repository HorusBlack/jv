<?php

session_start();

include_once 'PHP/util.php';

include_once 'PHP/bd.php';

include_once 'PHP/secciones.php';

$secciones = new secciones($BD);
$secciones->validarSesion();

//unset($_SESSION["usuarioBCKND"]);
session_destroy();
$secciones->alerta("Adios :)");


$secciones->redireccionarJS("index.php");