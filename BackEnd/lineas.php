<?php
include_once './PHP/util.php';
include_once './PHP/bd.php';
include_once './PHP/secciones.php';

$secciones = new secciones($BD);
$linea = filter_input(INPUT_GET, "ID", FILTER_SANITIZE_NUMBER_INT);
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
                        echo $secciones->migasPan(1, "", "", "", "", "");
                        ?>
                    </div>

				<div class="col-md-12">

					<div class="box" id="text-page">
						<h1>Lineas de productos</h1>

						<p class="lead">
							Aquí podrás agregar nuevas líneas de productos o editar las ya
							existentes. Para editar una linea de producto de click en el
							icono <i class="fa fa-pencil fa-lg"></i>. Para eliminar una linea
							de producto da click en el ícono <i class="fa fa-ban fa-lg"></i></br>Para
							acceder a una linea de productos de click en el ícono <i
								class="fa fa-folder fa-lg"></i>
						</p>
						<p>
							<strong>ADVERTENCIA:</strong> Si eliminas una linea de productos
							estarás eliminando también todas sus categorías, sub-categorías y
							productos.
						</p>

						<table class="table table-striped">
							<thead>
								<a href="lineas_edit.php?ID="><i class="fa fa-plus-circle fa-2x"></i></a>
								Agregar nuevo
								<tr>
									<th>Nombre</th>
									<th>Acceder</th>
									<th>Editar</th>
									<th>Eliminar</th>
								</tr>
							</thead>
							<tbody>
							<?php
                                    echo $secciones->lineasListing();
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