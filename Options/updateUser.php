<?php
/**
 * Clase que recibe las nuevas contraseñas del usuario y las actualiza a su cuenta
 */
include_once '../PHP/util.php';
include_once '../PHP/bd.php';
include_once '../PHP/secciones.php';

$secciones = new secciones($BD);

$key=0;
$password='';
$passwordConfirm='';

if(isset($_POST["key"]) && isset($_POST["recoveryPass"]) && isset($_POST["confirmPass"])){
    if(($_POST["key"]!="") && ($_POST["recoveryPass"]!="") && ($_POST["confirmPass"]!="")){
        $key=$_POST["key"];
        $password=$_POST["recoveryPass"];
        $passwordConfirm=$_POST["confirmPass"];
        if($key>0){
            if($password==$passwordConfirm){
                $secciones->updatePasswordUser($key,$password);
                $secciones->alerta("Clave actualizada con éxito");
                $secciones->redireccionarJS("../index.php");
                
            }
        }else{
            $secciones->alerta("No se pudo actualizar actualizar su información en este momento. Intente más tarde por favor");
            $secciones->redireccionarJS("../recoveryPass.php");
        }
    }else{
            $secciones->alerta("Hubo un problema al recibir su información, por favor genere una nueva solicitud");
            $secciones->redireccionarJS("../recoveryPass.php");
    }
}else{
    $secciones->alerta("Esta solicitud ha caducado, intente de nuevo");
    $secciones->redireccionarJS("../recoveryPass.php");
}