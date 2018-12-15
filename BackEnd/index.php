<?php
include_once './PHP/util.php';
include_once './PHP/bd.php';
include_once './PHP/secciones.php';
$secciones = new secciones($BD);
?>

<!DOCTYPE html>
<html lang="en">
    <?php
    echo $secciones->libreriasHead();
    ?>
    <body>

	<!-- *** TOP BAR END *** -->

	<!-- *** NAVBAR ***
        
         /#navbar -->

	<!-- *** NAVBAR END *** -->

	<div id="all" style="margin: 10% 0 10% 0;">

		<div id="content">
			<div class="container">
				<div class="col-md-1"></div>
				<div class="col-md-10">
					<div class="box">
						<h1>Iniciar Sesión</h1>

						<p class="lead">Inicia Sesión para poder acceder al adminitrador
							de contenido</p>
						<hr>

						<form action="procesos.php" method="post" id="form_session_bkn">
							<div class="form-group">
								<label for="email">Usuario</label> 
								<input type="text" class="form-control" id="usuario" name="usuario">
							</div>
							<div class="form-group">
								<label for="password">Contraseña</label> 
								<input type="password" class="form-control" id="password" name="password">
							</div>
							<div class="text-center">
								<button type="submit" class="btn btn-primary">
									<i class="fa fa-sign-in"></i> Iniciar Sesión
								</button>
							</div>
						</form>
					</div>
					<hr>
					<div class="col-md-6">
						<p class="pull-left">© JADE-V&#252; es una marca perteneciente a CDDMU
							eSTUDIO 2018.</p>

					</div>
					<div class="col-md-6">

				</div>
				<div class="col-md-1"></div>

			</div>
			<!-- /.container -->
		</div>
		<!-- /#content -->


		<!-- *** FOOTER ***
     _________________________________________________________ -->

		<!-- /#footer -->

		<!-- *** FOOTER END *** -->


		<!-- *** COPYRIGHT ***
     _________________________________________________________ -->

		<!-- *** COPYRIGHT END *** -->

	</div>
	<!-- /#all -->

	<!-- *** SCRIPTS TO INCLUDE ***
     _________________________________________________________ -->
	 
	<script src="../js/jquery-1.11.0.min.js"></script>
	<script src="js/verificacion.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/jquery.cookie.js"></script>
	<script src="../js/waypoints.min.js"></script>
	<script src="../js/modernizr.js"></script>
	<script src="../js/bootstrap-hover-dropdown.js"></script>
	<script src="../js/owl.carousel.min.js"></script>
	<script src="../js/front.js"></script>

</body>

</html>
