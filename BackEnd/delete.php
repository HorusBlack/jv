<?php
include_once 'PHP/util.php';

include_once 'PHP/bd.php';

include_once 'PHP/secciones.php';

$secciones = new secciones($BD);

$secciones->validarSesion();

if (isset($_GET["table"]) && isset($_GET["ID"])) {
    
	//SE GENERA UN SIGLO INFINITO
    if ($_GET["table"] != "" && $_GET["ID"] != "") {
        
        if ($secciones->delete(filter_var($_GET["table"], FILTER_SANITIZE_STRING), 
                               filter_var($_GET["ID"], FILTER_SANITIZE_NUMBER_INT))) {
            
            $secciones->alerta("Eliminado Correctamente");

            $secciones->redireccionarJS($_GET["retorno"]);
            
        } else {
            
            $secciones->alerta("Algo salió mal, intentalo nuevamente");
            
            $secciones->redireccionarJS("sections.php");
        }
    } else {
        
        $secciones->alerta("Algo salió mal, intentalo nuevamente");
        
        $secciones->redireccionarJS("sections.php");
    }
} else {
    
    $secciones->alerta("Algo salió mal, intentalo nuevamente");
    
    $secciones->redireccionarJS("sections.php");
}

