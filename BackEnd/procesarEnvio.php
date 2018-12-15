<?php
include_once './PHP/util.php';
include_once './PHP/bd.php';
include_once './PHP/secciones.php';

$secciones = new secciones($BD);




if(isset($_POST["paqueteria"]) && isset($_POST["guia"]) && isset($_POST["referencia"])){
     $paqueteria=filter_var($_POST["paqueteria"], FILTER_SANITIZE_STRING);
     $guia=filter_var($_POST["guia"], FILTER_SANITIZE_STRING);
     $referencia=filter_var($_POST["referencia"], FILTER_SANITIZE_STRING);
         $secciones->nuevaGuia($paqueteria, $guia, $referencia);
         $secciones->correoGuiaUser($paqueteria, $guia, $referencia);
         $secciones->redireccionarJS("ventas.php");
 }

?>
