<?php

session_start();

include_once 'PHP/util.php';

include_once 'PHP/bd.php';

include_once 'PHP/secciones.php';

$secciones = new secciones($BD);
$secciones->validarSesion();
//Preguntar
session_destroy();

//unset($_SESSION["usuarioJDV"]);

$secciones->redireccionarJS("index.php");