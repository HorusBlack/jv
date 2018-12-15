<?php
include_once './PHP/util.php';
include_once './PHP/bd.php';
include_once './PHP/secciones.php';

$secciones = new secciones($BD);
$secciones->validarSesion();
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
						<h1>Bienvenido al administrador de contenido</h1>

						<p class="lead">Aquí podrás administrar el contenido que se
							muestra en el sitio web de JADE-VÜ dividido por secciones.</p>

                            <?php
                            echo $secciones->sections();
                            ?>

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