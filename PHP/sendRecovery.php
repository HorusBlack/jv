<?php
include_once 'util.php';
include_once 'bd.php';
include_once 'secciones.php';
$secciones = new secciones($BD);

$id=0;
$nombre='';
$apellido='';
$md5="";
$to_email='soporte@jade-vu.com';
$face="https://www.facebook.com/jadevu.jeans?fb_dtsg_ag=AdwboBDG5hcLJvoCvf3wILBq2BZkCQBK2e-6MRKLHqEyKg%3AAdx0xR0iRzb_ruBZsUzhVUQ5D6RtOdp_3KNicKGXdmEKrg";

if (isset($_POST["email"])) {
        $cuenta=array();
        $cuenta= $secciones->buscarCuenta($_POST["email"]);
        $valores=count($cuenta);

    if ($valores>0) {
        $destinatario = isset($_POST["email"]) ? filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL) : '';
        $id=$cuenta[0]['ID'];
        $nombre=$cuenta[0]['nombres_usuario'];
        $apellido=$cuenta[0]['apellidos_usuario'];
        $correo=$cuenta[0]['correo_usuario'];
        $titulo = "Solicitud de cambio de contraseña";
        $referencia=''.time();

        $mail_message_register='<p>Estimado/a <strong>'.$nombre.' '.$apellido.'</strong></p>'. "\n";
        $mail_message_register.='<p>Recientemente solicitaste el <strong>restablecimiento de contraseña</strong></p>'. "\n";
        $mail_message_register.='<p>Para completar el proceso <strong>haga click</strong> en el enlace de abajo</p>'. "\n";
        $mail_message_register.='<p><a href="http://www.jade-vu.com/web/Options/catchParameters.php?access='.$referencia.'">Restablecer ahora</a></p>'. "\n";
        $mail_message_register.='<p>Por su seguridad cuenta con <strong>una hora </strong>para poder actualizar su contraseña. En otro caso debera solicitar nuevamente el cambio</p>'. "\n";
        $mail_message_register.='<p>Si no realizaste esta modificaci&oacute;n o si crees que alguien ha accedido a tu cuenta sin autorizaci&oacute;n, ignore este mensaje.</p>'. "\n";
        $mail_message_register.='<h5>Facebook: <a href="'.$web.'">
        Jade-VÜ Glam Jeans</a> 
         o al correo <a href="mailto:'.$to_email.'">'.$to_email.'</a> </h5>'. "\n";


        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers .= "From: Jade-VÜ Glam Jeans <".$to_email.">\r\n";
        $headers .= "Reply-To: ".$to_email."\r\n";
        $headers .= "Return-path: ".$to_email."\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion(); 

        mail($destinatario, $titulo, $mail_message_register,$headers);
        $secciones->updatePasswordUser($id,$referencia);

        $secciones->alerta("Solicitud enviada. Revise su bandeja de entrada");
        $secciones->redireccionarJS("../index.php");
    }else{
        $secciones->alerta("No existe una cuenta asociada al correo que uso, verifique");
        $secciones->redireccionarJS("../recoveryPass.php");
    }
} else {
    $secciones->alerta("La solicitud no a podido generarse \n por favor intentelo de nuevo");
}

?>