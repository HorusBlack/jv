<?php 
/**
 * Vista que muestra el formulario que necesita el usuario para mandar su contraseña nueva
 */
include_once '../PHP/util.php';
include_once '../PHP/bd.php';
include_once '../PHP/secciones.php';

$secciones = new secciones($BD);
$key=0;

$cuenta=array();
if (isset($_COOKIE['keyacc'])) {
	$key=$_COOKIE['keyacc'];
}else{
	  $secciones->alerta("No hay ningún proceso activo");
	 $secciones->redireccionarJS("../index.php");
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
						<li>Estado</li>
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

					<div class="col-md-9">


                              	<div class="box text-center" id="contact">
                                    	<h1 class="subTitulos">Cambio de contraseña</h1>
                                    
						<?php 

						$email='';
							if($key!=''){
								$cuenta=$secciones->buscarCorreo($key);
								$key=$cuenta[0]["ID"];
								$email=$cuenta[0]["correo_usuario"];
									echo "<p><strong>Cuenta de acceso: ".$email."</strong></p>";
									echo "<form action='updateUser.php' method='POST' id='form_updatePass'>
											<div class='row'>
												    <div class='col-md-12'>
													  <div class='form-group'>
													  <input type='hidden' id='key' name='key' value='".$key."'>
													 	<label for='text'>Nueva contraseña*</label>
														<input type='password' class='form-control' id='recoveryPass' name='recoveryPass'>
														<label for='text'>Vuelva a escribirla*</label>
														<input type='password' class='form-control' id='confirmPass' name='confirmPass'>
													    </div>
												   </div>
											</div>
														<input type='submit' value='Actualizar contraseña' class='btn btn-primary'>
										</form>";
								 //unset ($_COOKIE ["keyacc"]);
								}else{
									echo '<p>No se puedo generar el cambio de contraseña</p>';
								}
						
                                    ?>
                              </div>
                        </div>
			</div>
		</div>
	</div>
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