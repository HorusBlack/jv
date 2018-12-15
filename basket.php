<?php
include_once 'PHP/util.php';
include_once 'PHP/bd.php';
include_once 'PHP/secciones.php';
$secciones = new secciones($BD);
?>

<!DOCTYPE html>
<html lang="en">

    <?php
    echo $secciones->libreriasHead();
    ?>
    <body>

	<!-- *** TOPBAR ***
     _________________________________________________________ -->
	<div id="top">
            <?php
            echo $secciones->topbar();
            ?>
            <!-- *** MODAL DE INICIO DE SESION ***
     _________________________________________________________ -->
            <?php
            echo $secciones->modalLogin();
            ?>

        </div>

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
					<ul class="breadcrumb">

						<li><a href="index.php">Inicio</a></li>
						<li>Carrito de compras</li>
					</ul>

					<div class="col-md-9" id="basket">

						<div class="box">

                            <?php
                            echo $secciones->productosCarrito();
                            ?>

                        </div>
						<!-- /.box -->


						<div class="row same-height-row">
							<div class="col-md-3 col-sm-6">
								<div class="box same-height">
									<h3 class="subTitulos">Te podrían interesar estos productos.</h3>
								</div>
							</div>

                            <?php
                            // 3 PRODUCTOS DESTACADOS RANDOM
                            echo $secciones->productosInteres();
                            ?>

                        </div>


					</div>
					<!-- /.col-md-9 -->

					<div class="col-md-3">
						<!--AQUI VA EL RESUMEN DE COMPRA-->
                        <?php
                        echo $secciones->resumenCompra();
                        ?>

                    </div>
					<!-- /.col-md-3 -->

				</div>
				<!-- /.container -->
			</div>
			<!-- /#content -->

			<!-- *** FOOTER ***
     _________________________________________________________ -->
            <?php
            echo $secciones->footer();
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
		<script src="js/jquery-1.11.0.min.js"></script>
		<script src="js/verificacion.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.cookie.js"></script>
		<script src="js/waypoints.min.js"></script>
		<script src="js/modernizr.js"></script>
		<script src="js/bootstrap-hover-dropdown.js"></script>
		<script src="js/owl.carousel.min.js"></script>
		<script src="js/front.js"></script>

</body>

</html>
