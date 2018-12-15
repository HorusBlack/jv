<?php
include_once 'PHP/util.php';
include_once 'PHP/bd.php';
include_once 'PHP/secciones.php';

/*
 *
 */

$secciones = new secciones($BD);
$preorden="";
$precolor="";
$categoria = filter_input(INPUT_GET, "cat", FILTER_SANITIZE_NUMBER_INT);
if (isset($_GET["pag"])) {
    if ($_GET["pag"] != "") {
        $pag = filter_input(INPUT_GET, "pag", FILTER_SANITIZE_NUMBER_INT);
    } else {
        $pag = 1;
    }
} else {
    $pag = 1;
}
if (isset($_GET["color"])) {
    $precolor = $_GET["color"];
}
if (isset($_GET["order"])) {
    $preorden = $_GET["order"];
    if ($_GET["order"] == 1) {
        if (isset($_GET["color"])) {
            $precolor = $_GET["color"];
            list ($productos, $paginador, $cantidad) = $secciones->productosCategoria($categoria, $pag, 9, "precio_prod ASC", filter_var($_GET["order"], FILTER_SANITIZE_NUMBER_INT), filter_var($_GET["color"], FILTER_SANITIZE_STRING));
        } else {
            list ($productos, $paginador, $cantidad) = $secciones->productosCategoria($categoria, $pag, 9, "precio_prod ASC", filter_var($_GET["order"], FILTER_SANITIZE_NUMBER_INT));
        }
    } else if ($_GET["order"] == 2) {
        if (isset($_GET["color"])) {
            $precolor = $_GET["color"];
            list ($productos, $paginador, $cantidad) = $secciones->productosCategoria($categoria, $pag, 9, "precio_prod DESC", filter_var($_GET["order"], FILTER_SANITIZE_NUMBER_INT), filter_var($_GET["color"], FILTER_SANITIZE_STRING));
        } else {
            list ($productos, $paginador, $cantidad) = $secciones->productosCategoria($categoria, $pag, 9, "precio_prod DESC", filter_var($_GET["order"], FILTER_SANITIZE_NUMBER_INT));
        }
    } else if ($_GET["orden"] == 0) {
        if (isset($_GET["color"])) {
            $precolor = $_GET["color"];
            list ($productos, $paginador, $cantidad) = $secciones->productosCategoria($categoria, $pag, 9, "orden_prod DESC", filter_var($_GET["order"], FILTER_SANITIZE_NUMBER_INT), filter_var($_GET["color"], FILTER_SANITIZE_STRING));
        } else {
            list ($productos, $paginador, $cantidad) = $secciones->productosCategoria($categoria, $pag, 9, "orden_prod DESC", filter_var($_GET["order"], FILTER_SANITIZE_NUMBER_INT));
        }
    } else {
        if (isset($_GET["color"])) {
            $precolor = $_GET["color"];
            list ($productos, $paginador, $cantidad) = $secciones->productosCategoria($categoria, $pag, 9, "orden_prod DESC", "", filter_var($_GET["color"], FILTER_SANITIZE_STRING));
        } else {
            list ($productos, $paginador, $cantidad) = $secciones->productosCategoria($categoria, $pag, 9, "orden_prod DESC");
        }
    }
} else {
    if (isset($_GET["color"])) {
        $precolor = $_GET["color"];
        list ($productos, $paginador, $cantidad) = $secciones->productosCategoria($categoria, $pag, 9, "orden_prod DESC", 0, filter_var($_GET["color"], FILTER_SANITIZE_STRING));
    } else {
        list ($productos, $paginador, $cantidad) = $secciones->productosCategoria($categoria, $pag, 9, "orden_prod DESC", 0);
    }
}
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
                        <?php
                        echo $secciones->migasPan($categoria, "", "");
                        ?>
                    </div>

				<div class="col-md-3">
					<!-- *** MENUS AND FILTERS ***
     _________________________________________________________ -->
                        <?php
                        echo $secciones->menuLatCat();
                        ?>

                        <?php
                        /**
                         * El Metodo menuColor no esta completo, pag y preorden no tienen una funcion especifica
                         */
                        //echo $secciones->menuColor2($categoria, $pag, $preorden);
                        echo $secciones->menuColor2($categoria, $pag, $pag);
                        ?>

                        <!-- *** MENUS AND FILTERS END *** -->

					    <?php
                         echo $secciones->publicidad(4);
                        ?>
				</div>

				<div class="col-md-9">
                        <?php
                        echo $secciones->headerSubcar($categoria);
                        ?>

                        <div class="box info-bar">
                        <!--ABRIR CON EL ECLIPSE PARA VER ERRORES-->
						<div class="row">
							<div class="col-sm-12 col-md-4 products-showing">
                                    <?php
                                    if ($cantidad == 0) {
                                        echo "Productos próximamente.";
                                    } else {
                                        echo "Mostrando <strong>" . $cantidad . "</strong> producto(s)";
                                    }
                                    ?>

                                </div>
                                        <!--Bermudas me da una idea de como poner los articulos-->
							<div class="col-sm-12 col-md-8  products-number-sort">
								<div class="row">
									<form class="form-inline">
										<div class="col-md-6 col-sm-6"></div>
										<div class="col-md-6 col-sm-6">
                                                <?php
                                                //3802831
                                                /**
                                                 * El metodo orderBy tampoco esta completos
                                                 */
                                                //echo $secciones->orderBy($categoria, $pag, $preorden, $precolor);
                                                echo $secciones->orderBy($categoria, $pag, $preorden, $precolor);
                                                ?>
                                            </div>
									</form>
								</div>
							</div>
						</div>
					</div>

					<div class="row products">
						<div class="filtr-container">
                                <?php
                                echo $productos;
                                ?>
                                <!-- /.col-md-4 -->
						</div>
					</div>
					<!-- /.products -->

                        <?php
                        if ($productos != "") {
                            echo $paginador;
                        }
                        ?>
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
    <script data-require="jquery@*" data-semver="3.0.0" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.js"></script>
	<script src="js/verificacion.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.cookie.js"></script>
	<script src="js/waypoints.min.js"></script>
	<script src="js/modernizr.js"></script>
	<script src="js/bootstrap-hover-dropdown.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<script src="js/front.js"></script>
	<script src="js/jquery.filterizr.js"></script>
	<script>

            var filterizd = $('.filtr-container').filterizr({});
            filterizd.filterizr('setOptions', {
            animationDuration: .5, //in seconds
                    filter: 'all', //Initial filter
                    delay: 0, //Transition delay in ms
                    delayMode: 'progressive', //'progressive' or 'alternate'
                    easing: 'ease-out',
                    filterOutCss: {//Filtering out animation
                    opacity: 0,
                            transform: ''
                    },
                    filterInCss: {//Filtering in animation
                    opacity: 1,
                            transform: ''
                    },
                    layout: 'sameSize', //See layouts
                    selector: '.filtr-container',
                    setupControls: false
            })
        </script>

</body>

</html>
