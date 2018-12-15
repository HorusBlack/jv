<?php
include_once 'PHP/util.php';
include_once 'PHP/bd.php';
include_once 'PHP/secciones.php';
$secciones = new secciones($BD);
if (isset($_GET["pag"])) {
    if ($_GET["pag"] != "") {
        $pag = filter_input(INPUT_GET, "pag", FILTER_SANITIZE_NUMBER_INT);
    } else {
        $pag = 1;
    }
} else {
    $pag = 1;
}
list ($posts, $paginador) = $secciones->cuerpoPostPreviewPag($pag, 2);
?>
<!-- 
EL PAGINADOR FUNCIONA
 -->

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

				<div class="col-sm-12">
                        <?php
                        echo $secciones->migasPan("", "", 1, "");
                        ?>
                    </div>

				<!-- *** LEFT COLUMN ***
                         _________________________________________________________ -->

				<div class="col-md-3">
					<!-- *** BLOG MENU ***
     _________________________________________________________ -->

					<!-- /.col-md-3 -->

					<!-- *** BLOG MENU END *** -->
					<div class="panel panel-default sidebar-menu">

						<div class="panel-heading">
							<h3 class="panel-title">Navegación</h3>
						</div>

						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
								<li><a href="contact.php">Contacto</a></li>
								<li><a href="faq.php">Preguntas frecuentes</a></li>
								<li class="active"><a href="blog.php?pag=1">Blog</a></li>

							</ul>

						</div>
					</div>
                        
                        <?php
                        echo $secciones->publicidad(4);
                        ?>
                    </div>

				<div class="col-sm-9" id="blog-listing">


					<div class="box">

						<h1 class="subTitulos">Noticias</h1>
						<p>Mantente al tanto de promociones, ofertas y nuevas colecciones
							de ropa con las últimas noticias publicadas solo en nuestro blog.
						</p>
					</div>

                        <?php
                        echo $posts;
                        echo $paginador;
                        ?>

                    </div>
				<!-- /.col-md-9 -->

				<!-- *** LEFT COLUMN END *** -->

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