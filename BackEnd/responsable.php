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
						<h1>Responsables</h1>

						<p class="lead">
							Aquí podrás editar la información de los usuarios responsables del
                                   sitio web, eliminar y editar <br> la información de un usuario. Seleccione el icono 
                                   <i class="fa fa-pencil fa-lg"></i>. <br>Para eliminar un usuario de
							click en el ícono <i class="fa fa-ban fa-lg"></i>.
						</p>

						<table class="table table-striped">
							<thead>
								<a href="responsable_edit.php?ID="><i
									class="fa fa-plus-circle fa-2x"></i></a> Agregar nuevo
								<tr>
									<th>Nombre</th>
									<th>Editar</th>
									<th>Eliminar</th>
								</tr>
							</thead>
							<tbody>
                                    <?php
                                    echo $secciones->responsablesListing();
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