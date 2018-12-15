<?php
include_once '../PHP/util.php';
include_once '../PHP/bd.php';
include_once '../PHP/secciones.php';


$secciones = new secciones($BD);


$merchant_id=0;
$reference_sale='';
$value=0;
$currency='';
$state_pol='';
$sign='';
$md5="";
static $apiKey="Pm6aANFCvERZUEBW2nEoKXzjyN";
$update=false;

if(isset($_POST["merchant_id"]) && isset($_POST["reference_sale"]) && isset($_POST["value"]) && isset($_POST["currency"]) 
 && isset($_POST["state_pol"]) && isset($_POST["sign"])){
     $merchant_id=filter_var($_POST["merchant_id"], FILTER_SANITIZE_NUMBER_INT);
     $reference_sale=filter_var($_POST["reference_sale"], FILTER_SANITIZE_STRING);
     $value=filter_var($_POST["value"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
     $currency=filter_var($_POST["currency"], FILTER_SANITIZE_STRING);
     $state_pol=filter_var($_POST["state_pol"], FILTER_SANITIZE_NUMBER_INT);
     $sign=filter_var($_POST["sign"], FILTER_SANITIZE_STRING);
     $valor_formato=number_format($value, 1);
    
     if ($state_pol==4) {
        $md5=hash('MD5', "$apiKey".'~'."$merchant_id".'~'."$reference_sale".'~'."$valor_formato".'~'."$currency".'~'."$state_pol");

        if ($md5==$sign) {
            do{
                $update=$secciones->updateEstatusCompra("PAGADO",$reference_sale,4);
                $secciones->updateCompraBeta($reference_sale);
            }while($update==false || $update==null);
            if($update){
                $secciones->sendMailEstatus($state_pol,$reference_sale,$value);
                
            }
        }
     } elseif ($state_pol==5) {
        $md5=hash('MD5', "$apiKey".'~'."$merchant_id".'~'."$reference_sale".'~'."$valor_formato".'~'."$currency".'~'."$state_pol");
        if ($md5==$sign) {
            do{
                $update=$secciones->updateEstatusCompra("CANCELADO",$reference_sale,5);
            }while($update==false || $update==null);
            if($update){
                $secciones->sendMailEstatus($state_pol,$reference_sale,$value);
            }
        }
     } elseif ($state_pol==6) {
        $md5=hash('MD5', "$apiKey".'~'."$merchant_id".'~'."$reference_sale".'~'."$valor_formato".'~'."$currency".'~'."$state_pol");
        
        if ($md5==$sign) {
            do{
                $update=$secciones->updateEstatusCompra("RECHAZADO",$reference_sale,6);
            }while($update==false || $update==null);
            if($update){
                $secciones->sendMailEstatus($state_pol,$reference_sale,$value);
            }
        }else{
            $secciones->alerta("el hash no es igual");
        }
     }
     
 }

?>
