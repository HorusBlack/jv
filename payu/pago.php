<?php
include_once '../PHP/util.php';
include_once '../PHP/bd.php';
include_once '../PHP/secciones.php';

$vueltas=0;
class pago
{

    private $BD;

    public function __construct(BD $BD)
    {
        $this->BD = $BD;
    }

    public function BD()
    {
        return $this->BD;
    }

    public function calularTotal($carrito){
        $total = 0;
        $total_r=0;
        $envioNormal=99;
        $envioSobrePeso=115;
        $numeroArticulos=0;
        if (isset($carrito)) {
            foreach ($carrito as $contenido) {
                $prod = $this->BD->ConsultaWhereSimple("producto", "activo=? AND ID=?", array(1,$contenido[0]));
                ($prod[0]["preciodesc_prod"] > 0 ? ($total = $total + $prod[0]["preciodesc_prod"] * $contenido[1]) : ($total = $total + $prod[0]["precio_prod"] * $contenido[1]));

            }
            $numCarrito=(count($carrito));
            for ($i=0; $i<$numCarrito; $i++) {
    			$numPiezas=$carrito[$i][1];
                $numeroArticulos+=$numPiezas;
             }
            if($numeroArticulos<=5){
                $total_r=round($total+($total * 0.16)+$envioNormal,0,PHP_ROUND_HALF_UP);
            }else{
                $total_r=round($total+($total * 0.16)+$envioSobrePeso,0,PHP_ROUND_HALF_UP);
            }
        }
        return $total_r;
    }

    public function crearDescripcion($carrito)
    {
        $descripcion = '';
        foreach ($carrito as $prod) {
            $r = $this->BD->ConsultaLibre("nombre_prod", "producto", "ID=?", array(
                $prod[0]
            ), "1");
        }
    }

    public function nuevaVenta($id_cliente,$id_producto,$piezas,$talla, $referencia){
        $estatus="PAGO PENDIENTE";
        $guia="No Disponible";
        $registro = $this->BD->InsertLibrereg("ventas", " id_usuario, id_producto, piezas, talla, estatus, referencia, numGuia",
                                              "'" . $id_cliente . "','" . $id_producto . "','" . $piezas . "','" . $talla . "'
                                              ,'" . $estatus . "','" . $referencia . "','" . $guia . "'");
    }

    public function paymentform($carrito, $usuario)
    {
        
        static $destino="https://checkout.payulatam.com/ppp-web-gateway-payu/";

        static $apiKeyMerchanId="Pm6aANFCvERZUEBW2nEoKXzjyN~760982~";
        
        static $merchanId=760982;

        static $acountId=767544;
        
        static $currency="MXN";

        static $isoPais="MX";

        static $tax=0;

        static $taxReturnBase=0;
        
        static $paginaRespuesta="http://jade-vu.com/web/payu/resultBuy.php";
        
        static $paginaConfirmacion="http://jade-vu.com/web/payu/confirmation.php";
        
        $total=0;
        $total= round($this->calularTotal($carrito),0,PHP_ROUND_HALF_UP);
        
        $referencia=''.time();
        
        $md5="";
        $md5=hash('MD5',"$apiKeyMerchanId"."$referencia".'~'."$total".'~MXN');
        
        $nombreCompleto=$_SESSION["usuarioJDV"]['nombres_usuario'].' '.$_SESSION["usuarioJDV"]['apellidos_usuario'];
        $nombreCompleto=strtoupper($nombreCompleto);
        
        $direccion=$_SESSION["usuarioJDV"]['direccion_usuario'].', '.$_SESSION["usuarioJDV"]['codpost_usuario'];
        $direccion=strtoupper($direccion);
        
        $ciudad=$_SESSION["usuarioJDV"]['ciudad_usuario'];
        $ciudad=strtoupper($ciudad);
        
        $correo=$_SESSION["usuarioJDV"]['correo_usuario'];
        
        $telefono=$_SESSION["usuarioJDV"]['telefono_usuario'];

        $idProducto=0;
		$numPiezas=0;
		$tallaSeleccionada="";
		$numCarrito=(count($_SESSION["carritoJDV"]));
		$id_cliente=$_SESSION["usuarioJDV"]["ID"];
        $id_cliente+=0;
        
        if ($total != null) {
            
            for ($i=0; $i<$numCarrito; $i++) {
    			$idProducto=$_SESSION["carritoJDV"][$i][0];
    			$numPiezas=$_SESSION["carritoJDV"][$i][1];
    			$tallaSeleccionada=$_SESSION["carritoJDV"][$i][2];
                echo $this->nuevaVenta($id_cliente, $idProducto, $numPiezas, $tallaSeleccionada, $referencia);
             }

            return '<form method="post" action="'.$destino.'">
                <input name="merchantId"    type="hidden"  value="'.$merchanId.'">
                <input name="accountId"     type="hidden"  value="'.$acountId.'">
                <input name="description"   type="hidden"  value="Compra Jade-vu: '.$referencia.'">
                <input name="referenceCode" type="hidden"  value="'.$referencia.'">
                <input name="amount"        type="hidden"  value="'.$total.'">
                <input name="tax"           type="hidden"  value="'.$tax.'">
                <input name="taxReturnBase" type="hidden"  value="'.$taxReturnBase.'">
                <input name="currency"      type="hidden"  value="'.$currency.'">
                <input name="signature"     type="hidden"  value="'.$md5.'">
                <input name="buyerFullName" type="hidden"  value="'.$nombreCompleto.'" >
                <input name="shippingAddress" type="hidden"  value="'.$direccion.'" >
                <input name="shippingCity"    type="hidden"  value="'.$ciudad.'" >
                <input name="shippingCountry" type="hidden"  value="'.$isoPais.'">
                <input name="buyerEmail"    type="hidden"  value="'.$correo.'" >
                <input name="telephone"    type="hidden"  value="'.$telefono.'" >
                <input name="responseUrl"   type="hidden"  value="'.$paginaRespuesta.'">
                <input name="confirmationUrl"    type="hidden"  value="'.$paginaConfirmacion.'" >
                <button type="submit" class="btn btn-primary">Pagar<i class="fa fa-chevron-right"></i></button>
                </form>';
                          
        } else {
            return 'Hubo un problema al procesar su pago, intentelo de nuevo más tarde.';
        }
    }
    }
