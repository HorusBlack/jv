<?php
include_once './PHP/util.php';
include_once './PHP/bd.php';
include_once './PHP/secciones.php';

$secciones = new secciones($BD);
$secciones->validarSesion();
/**
 * Checar los credenciales primero
 */
?>

<!DOCTYPE html>
<html lang="en">

    <?php
    echo $secciones->libreriasHead();
    ?>
    <body>

	<!-- *** TOPBAR ***
     _________________________________________________________ -->

	<!-- *** TOP BAR END *** -->

	<!-- *** NAVBAR ***
     _________________________________________________________ -->
        <?php
        echo $secciones->menu();
        ?>
        <!-- /#navbar -->

	<!-- *** NAVBAR END *** -->

	<div id="all">

		<div id="content">
			<div class="container">
				<div class="col-md-12">
                        <?php
                        echo $secciones->migasPan();
                        ?>
                    </div>

				<div class="col-md-12">

					<div class="box" id="text-page">
						<h1>Ventas</h1>

						<p class="lead">
                                          Sección que permite informar sobre las compras realizadas por los clientes así como
							el estado actual de las mismas. También podra generar el proceso correspondiente.<br> Para consultar la información de un usuario. Seleccione el icono<i class="fa fa-pencil fa-lg"></i>. <br>Para eliminar un usuario de
							click en el ícono <i class="fa fa-ban fa-lg"></i>.
						</p>

						<table class="table table-striped">
							<thead>
								
								<tr>
									<th>Referencia</th>
									<th>Comprador</th>
                                                      <th align="center">Destino de envio</th>
                                                      <th align="center">Procesar la compra</th>
                                                      <th align="center">Estatus</th>
                                                      <th align="center">Envio de No. de Guía</th>
                                                      <th align="center">Número de guía</th>
								</tr>
							</thead>
							<tbody>
                                    <?php
							echo $secciones->ventasListing();
                                    ?>
                                </tbody>
						</table>
							

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
            echo $secciones->footer();
            ?>
            <!-- *** COPYRIGHT END *** -->



	</div>
	<!-- /#all -->

	<!-- *** SCRIPTS TO INCLUDE ***
     _________________________________________________________ -->
	<script src="../js/jquery-1.11.0.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/jquery.cookie.js"></script>
	<script src="../js/waypoints.min.js"></script>
	<script src="../js/modernizr.js"></script>
	<script src="../js/bootstrap-hover-dropdown.js"></script>
	<script src="../js/owl.carousel.min.js"></script>
	<script src="../js/front.js"></script>

</body>

</html>