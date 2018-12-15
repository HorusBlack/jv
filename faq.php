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
						<li>Preguntas Frecuentes</li>
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
								<li><a href="contact.php">Contacto</a></li>
								<li class="active"><a href="faq.php">Preguntas frecuentes</a></li>
								<li><a href="blog.php?pag=1">Blog</a></li>

							</ul>

						</div>
					</div>
                        
                        <?php
				echo $secciones->publicidad(2);		
                        ?>
                    </div>

				<div class="col-md-9">

					<div class="box" id="contact">
						<h1 class="subTitulos">Preguntas Frecuentes</h1>

						<p class="lead">¿Tienes alguna duda? ¿Tienes algún inconveniente
							con nuestros preductos?</p>
						<p>Aquí podrás encontrar preguntas frecuentes que quizá resuelvan
							sus dudas.</p>

						<hr>

						<div class="panel-group" id="accordion">

                                <?php
                                echo $secciones->preguntas();
                                ?>
                                <!-- /.panel -->

						</div>
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
