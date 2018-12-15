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
            echo $secciones->topBar();
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
						<li>Registrarse</li>
					</ul>

				</div>

				<div class="col-md-12">
					<div class="box">
						<h1>Nueva cuenta</h1>

						<p class="lead">¿No eres un cliente registrado aún?</p>
						<p>
							Registrate para poder tener acceso a las opciones de </strong>compra</strong>
							y </strong>carrito de compras.</strong> ¡Además de recibir
							noticias sobre descuentos y ofertas especiales!
						</p>
						<p class="text-muted">Los campos marcados con (*) son
							obligatorios.</p>

						<hr>

						<form action="procesos.php" method="post">
							<div class="form-group">
								<label for="name">Nombre de usuario*</label> <input type="text"
									class="form-control" name="username_reg">
							</div>
							<div class="form-group">
								<label for="name">Nombre(s)*</label> <input type="text"
									class="form-control" name="nombres_reg">
							</div>
							<div class="form-group">
								<label for="name">Apellidos*</label> <input type="text"
									class="form-control" name="apellidos_reg">
							</div>
							<div class="form-group">
								<label for="email">Correo electrónico*</label> <input
									type="text" class="form-control" name="correo_reg">
							</div>
							<div class="form-group">
								<label for="password">Contraseña*</label> <input type="password"
									class="form-control" name="password_reg">
							</div>
							<div class="form-group">
								<label for="password">Repite tu contraseña*</label> <input
									type="password" class="form-control" name="password2_reg">
							</div>
							<div class="row">
                   				 <div class="col-md-12">
                    				<iframe src="http://docs.google.com/gview?url=http://www.jade-vu.com/web/Assets/AvisoPrivacidadJade018.pdf&embedded=true" style="width:100%; height:300px;" frameborder="0"></iframe>
                    			</div>
							</div>
							<div class="form-group">
								<input type="checkbox" name="checkbox" id="checkbox_id" value="value" checked>
								<label for="checkbox_id">He leído y acepto los términos y condiciones de uso</label>
							</div>
							<div class="text-center">
								<button type="submit" class="btn btn-primary" id="btnRegistro">
									<i class="fa fa-user-md"></i> Registrarse
								</button>
							</div>

						</form>
							
						<div class="text-center">
							<br>
							<p class="text-center text-muted">
								¿Ya tienes una cuenta?<br> ¡<a href="#" data-toggle="modal"
									data-target="#login-modal"><strong>Inicia sesión ahora</strong></a>!
							</p>
						</div>
					</div>
				</div>

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
	<script>
	$(function() {
    $('#checkbox_id').click(function() {
        if ($(this).is(':checked')) {
			$('#btnRegistro').removeAttr('disabled');
        } else {
		    $('#btnRegistro').attr('disabled', 'disabled');
			
        }
    });
});
	</script>
</body>

</html>
