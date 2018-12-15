<?php
include_once '../PHP/util.php';
include_once '../PHP/bd.php';
include_once '../PHP/secciones.php';
include_once 'pago.php';

$secciones = new secciones($BD);
$pago = new pago($BD);

if (!session_id()) {
    session_start();
}
/*
Manejarlo con estos parametros, si funciona agregar mas
*/ 
$ApiKey = "Pm6aANFCvERZUEBW2nEoKXzjyN";
$merchant_id=0;
$referenceCode='';
$reference_pol='';
$TX_VALUE=0;
$currency='';
$transactionId='';
$transactionState=0;
$firma='';
$extra1='';
$lapPaymentMethod='';
if(isset($_REQUEST['merchantId']) && isset($_REQUEST['referenceCode']) && isset($_REQUEST['TX_VALUE']) && isset($_REQUEST['currency']) && isset($_REQUEST['transactionState']) && isset($_REQUEST['processingDate']) && isset($_REQUEST['signature'])){
      if($_REQUEST['merchantId']!='' && $_REQUEST['referenceCode']!='' && $_REQUEST['TX_VALUE']!='' && $_REQUEST['currency']!='' && $_REQUEST['transactionState']!='' && $_REQUEST['processingDate']!='' && $_REQUEST['signature']!=''){
            //id de la tienda
            $merchant_id = $_REQUEST['merchantId'];
            //referencia del pedido
            $referenceCode = $_REQUEST['referenceCode'];
            //monto total de la transacción
            $TX_VALUE = $_REQUEST['TX_VALUE'];
            //Tipo de moneda
            $currency = $_REQUEST['currency'];
            //Id de transaccion
            $transactionId = $_REQUEST['transactionId'];
            //Estado de la transacción
            $transactionState = $_REQUEST['transactionState'];
            //Firma de respuesta
            $firma = $_REQUEST['signature'];
            //Descripcion de la venta
            $extra1 = $_REQUEST['description'];
            //Numero transaccion payu
            $reference_pol = $_REQUEST['reference_pol'];
            //Forma de pago
            $lapPaymentMethod = $_REQUEST['lapPaymentMethod'];
            
            $New_value = number_format($TX_VALUE, 1, '.', '');
            $firma_cadena = "$ApiKey~$merchant_id~$referenceCode~$New_value~$currency~$transactionState";
            $firmacreada = hash('MD5',$firma_cadena);
      }
}
?>

<!DOCTYPE html>
<html lang="en">

    <?php
    echo $secciones->libreriasHeadPayu();
    ?>

    <body>

	<!-- *** TOPBAR ***
     _________________________________________________________ -->
	<div id="top">
            <?php
            echo $secciones->topBarPayu();
            ?>
            <!-- *** MODAL DE INICIO DE SESION ***
     _________________________________________________________ -->
            <?php
            echo $secciones->modalLoginPayu();
            ?>

        </div>

	<!-- *** TOP BAR END *** -->

	<!-- *** NAVBAR ***
     _________________________________________________________ -->
        <?php
        echo $secciones->menuPayu();
        ?>
        <!-- /#navbar -->

	<!-- *** NAVBAR END *** -->

	<div id="all">

		<div id="content">
			<div class="container">

				<div class="col-md-12">
					<ul class="breadcrumb">
						<li><a href="../index.php">Inicio</a></li>
						<li>Resultado de la transferencia</li>
					</ul>

				</div>

				<div class="col-md-3">
					<!-- *** PAGES MENU ***
     _________________________________________________________ -->


					<!-- *** PAGES MENU END *** -->

					<div class="panel panel-default sidebar-menu">

						<div class="panel-heading">
							<h3 class="panel-title">Navegación</h3>
						</div>

						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
								<li><a href="../contact.php">Contacto</a></li>
								<li class="active"><a href="../aviso.php">Términos y Condiciones</a></li>
								<li><a href="../blog.php?pag=1">Blog</a></li>
							</ul>

						</div>
					</div>

                    </div>

				<div class="col-md-9">

					<div class="box" id="contact">
						<h1 class="subTitulos">Resultado de la compra</h1>
                            <!--Aqui empieza-->

						<hr>

                <div class="row">
                    <div class="col-md-12">
						<?php
                                          
                                          if ($_REQUEST['transactionState'] == 4 ) {
	                                    $estadoTx = "Transacción aprobada";
                                          }

                                          else if ($_REQUEST['transactionState'] == 6 ) {
	                                    $estadoTx = "Transacción rechazada";
                                          }

                                          else if ($_REQUEST['transactionState'] == 104 ) {
	                                    $estadoTx = "Error";
                                          }

                                          else if ($_REQUEST['transactionState'] == 7 ) {
	                                    $estadoTx = "Transacción pendiente";
                                          }

                                          else {
	                                    $estadoTx=$_REQUEST['mensaje'];
                                          }

                                          if (strtoupper($firma) == strtoupper($firmacreada)) {
                                                echo "<h2>Resumen Transacción</h2>
								<table>
									<tr>
										<td>Estado de la transaccion</td>
										<td>".$estadoTx."</td>
									</tr>
									<tr>
									<tr>
										<td>ID de la transaccion</td>
										<td>".$transactionId."</td>
									</tr>
									<tr>
										<td>Referencia de la venta</td>
										<td>".$reference_pol."</td>
									</tr>
									<tr>
										<td>Referencia de la transaccion</td>
										<td>".$referenceCode."</td>
									</tr>
									<tr>
									<tr>
										<td>Valor total</td>
										<td>$".number_format($TX_VALUE)."</td>
									</tr>
									<tr>
										<td>Moneda</td>
										<td>".$currency."</td>
									</tr>
									<tr>
										<td>Descripción</td>
										<td>".$extra1."</td>
									</tr>
									<tr>
										<td>Entidad:</td>
										<td>".$lapPaymentMethod."</td>
									</tr>
							</table>";
                                    }else{
						            	echo "<h2>Hubo un problema al generar la firma digital</h2>";
						}
                                          
                                    ?>
						
                    </div>
                </div>
            
            <!-- / .container -->

						<!-- /.panel-group -->

					</div>

				</div>
				<!-- /.col-md-9 -->
                
			</div>
			<!-- /.container -->
		</div>
		<!-- /#content -->

		<!-- *** FOOTER ***
     _________________________________________________________ -->
            <?php
            echo $secciones->footerPayu();
            ?>
            <!-- /#footer -->

		<!-- *** FOOTER END *** -->

		<!-- *** COPYRIGHT ***
     _________________________________________________________ -->

		<!-- *** COPYRIGHT END *** -->

	</div>
	<!-- /#all -->

	<!-- *** SCRIPTS TO INCLUDE ***
     _________________________________________________________ -->
	<script type="text/javascript" src="../js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="../js/verificacion.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../js/jquery.cookie.js"></script>
	<script type="text/javascript" src="../js/waypoints.min.js"></script>
	<script type="text/javascript" src="../js/modernizr.js"></script>
	<script type="text/javascript" src="../js/bootstrap-hover-dropdown.js"></script>
	<script type="text/javascript" src="../js/owl.carousel.min.js"></script>
	<script type="text/javascript" src="../js/front.js"></script>
	
</body>

</html>
