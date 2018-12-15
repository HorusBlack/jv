<?php
include_once '../PHP/util.php';
include_once '../PHP/bd.php';
include_once '../PHP/secciones.php';
include_once 'pago.php';

$secciones = new secciones($BD);
$pago = new pago($BD);

if (!session_id()) {
    session_start();
}

/**
 * PÁGINA QUE SE ME MOSTRARA AL USUARIO ANTES DE PROCEDER A LA COMPRA FINAL
 */
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
						<li>Datos de entrega</li>
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

					<div class="box" id="contact">
						<h1 class="subTitulos">Datos de entrega</h1>
                            <!--Aqui empieza-->

						<hr>

                <div class="row">
                    <div class="col-md-12">
						<?php
				    echo '<br>';
				    
                            echo '<b>Destinatario</b><br>';
                            echo $_SESSION["usuarioJDV"]['nombres_usuario'].' '.$_SESSION["usuarioJDV"]['apellidos_usuario']."<br>";
                            echo '<br><b>Dirección</b><br>';
                            echo $_SESSION["usuarioJDV"]['direccion_usuario'].', '.$_SESSION["usuarioJDV"]['ciudad_usuario'].', '.$_SESSION["usuarioJDV"]['estado_usuario'].', '.$_SESSION["usuarioJDV"]['codpost_usuario']."<br>";
                            echo '<br><b>Teléfono</b><br>';
                            echo $_SESSION["usuarioJDV"]['telefono_usuario'].'<br>';
                            echo '<br><b>Paquetería</b><br>';
                            echo '<img src="../img/estafeta.png" alt="Estafeta" width="auto" height="50px">';
                            echo '<br><br>';
                            echo $pago->paymentform($_SESSION["carritoJDV"], $_SESSION["usuarioJDV"])."<br>".'<a href="../profile.php?ID=1" class="btn btn-default"><i class="fas fa-sync-alt"></i>Actualizar Datos</a>';
                        ?>

                    </div>
                </div>
            
            <!-- / .container -->

						<!-- /.panel-group -->

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
