<?php
include_once 'PHP/util.php';
include_once 'PHP/bd.php';
include_once 'PHP/secciones.php';
$secciones = new secciones($BD);
$post = filter_input(INPUT_GET, "post", FILTER_SANITIZE_NUMBER_INT);
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

				<div class="col-sm-12">

                        <?php
                        echo $secciones->migasPan("", "", "", $post);
                        ?>
                    </div>

				<div class="col-sm-9" id="blog-post">


					<div class="box">

                            <?php
                            echo $secciones->cuerpoPostFull($post);
                            ?>
                            <!-- /#post-content -->

						<!--COMENTARIOS Y FORMULARIO PENDIENTES... PENSANDO SI IMPLEMENTARLOS O NO-->
						<!-- /#comments -->

						<!--<div id="comment-form" data-animate="fadeInUp">

                                <h4>Leave comment</h4>

                                <form>
                                    <div class="row">

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="name">Name <span class="required">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="name">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">Email <span class="required">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="email">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="comment">Comment <span class="required">*</span>
                                                </label>
                                                <textarea class="form-control" id="comment" rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <button class="btn btn-primary"><i class="fa fa-comment-o"></i> Post comment</button>
                                        </div>
                                    </div>

                                </form>

                            </div>-->
						<!-- /#comment-form -->

					</div>
					<!-- /.box -->
				</div>
				<!-- /#blog-post -->

				<div class="col-md-3">
					<!-- *** BLOG MENU ***
     _________________________________________________________ -->

					<!-- /.col-md-3 -->

					<!-- *** BLOG MENU END *** -->

                        <?php
                        echo $secciones->publicidad(3);
                        ?>
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
    <script data-require="jquery@*" data-semver="3.0.0" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.js"></script>
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