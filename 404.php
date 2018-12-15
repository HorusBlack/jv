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


					<div class="row" id="error-page">
						<div class="col-sm-6 col-sm-offset-3">
							<div class="box">

								<p class="text-center">
									<img src="img/logo.png" alt="Obaju template">
								</p>

								<h3>¡Oops! - Esta página no está disponible disculpa las
									molestias</h3>
								<h4 class="text-muted">Error 404 - Page not found</h4>

								<p class="text-center">Para volver a la tienda dirígete a la
									página principal</p>

								<p class="buttons">
									<a href="index.php" class="btn btn-primary"><i
										class="fa fa-home"></i> Ir a página principal</a>
								</p>
							</div>
						</div>
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