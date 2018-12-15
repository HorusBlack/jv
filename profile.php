<?php
include_once 'PHP/util.php';
include_once 'PHP/bd.php';
include_once 'PHP/secciones.php';
$secciones = new secciones($BD);
$secciones->validarSesion();
$ide = filter_input(INPUT_GET, "ID", FILTER_SANITIZE_NUMBER_INT);
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
            echo $secciones->topBar();
            ?>
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
                        <?php
                        echo $secciones->migasPan();
                        ?>
                    </div>

				<div class="col-md-12" id="checkout">

					<div class="box">
						<form method="post" action="procesos.php">
							<h1>Información personal</h1>
							<p>
								¡Hola <strong><?php echo '' . $_SESSION["usuarioJDV"]["username_usuario"] ?></strong>!
								Asegurate de completar esta información para poder realizar
								compras y mantener tu perfil actualizado. Los campos marcados
								con (*) son obligatorios.
							</p>

                                <?php
                                echo $secciones->infoPer($ide);
                                ?>

                                <div class="box-footer">
								<div class="pull-left">
									<a href="index.php" class="btn btn-default"><i
										class="fa fa-chevron-left"></i>Volver a la página de inicio</a>
								</div>
								<div class="pull-right">
									<button type="submit" class="btn btn-primary">
										<i class="fas fa-save"></i> Guardar información
									</button>
								</div>
							</div>
						</form>
					</div>
					<!-- /.box -->

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
	<script type='text/javascript'>
            $(document).ready(function () {
                
                $('select[name="vendedor_info"]').on('change', function () {
                    var vendedor = $(this).val();
                    if (vendedor == "1") {
                        $('#tipovend_info').removeAttr('disabled');
                    } else {
                        $('#tipovend_info').attr('disabled', '');
                        $('#tipovend_info').attr('value', 'NULL');
                        $('#tipovend_info').val('');
                    }
                });
            });
        </script>

</body>

</html>