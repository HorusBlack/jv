<?php 
/**
 * Clase que recibe la clave de acceso para recuperaciòn de contraseñas
 */
include_once '../PHP/util.php';
include_once '../PHP/bd.php';
include_once '../PHP/secciones.php';

$secciones = new secciones($BD);
$mail='';
$access='';
$cuenta=array();

if(isset($_GET["access"])){
      if(($_GET["access"]!="")){
            $access=$_GET["access"];
            $md5=hash('MD5',$access);
            $cuenta=$secciones->verificarAcceso($md5);
            if($cuenta!=null){
                $valores=count($cuenta);
                if($valores>0){
                    if($cuenta[0]['ID']!=''){
                        $id=$cuenta[0]['ID'];
                        setcookie("keyacc", $id, time()+3600);
                        header("Location: recoveryAccess.php");
                    }else{
                        $secciones->alerta("Ocurrio un problema al consultar su informacion, intente de nuevo");
                        $secciones->redireccionarJS("../index.php");
                    }
                }else{
                    $secciones->alerta("No se pudo obtener su cuenta de acceso");
                    $secciones->redireccionarJS("../index.php");
                }
            }else{
                //cuenta esta saliendo null
                $secciones->alerta("Esta solicitud de restablecimiento ya a sido usada  por favor genere una nueva");
                $secciones->redireccionarJS("../recoveryPass.php");
            }
      }else{
        $secciones->alerta("Clave de recuperación incorrecta solicitela nuevamente");
       $secciones->redireccionarJS("../index.php");
      }
}else {
    $secciones->alerta("Clave de recuperación incorrecta");
}








