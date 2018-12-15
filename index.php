<?php
include_once './PHP/util.php';
include_once './PHP/bd.php';
include_once './PHP/secciones.php';

$secciones = new secciones($BD);
$slider = $secciones->sliderBig();
$sliderMarcas=$secciones->sliderMarcas();
?>

<!DOCTYPE html>
<html lang="en">
    <?php
    echo $secciones->libreriasHead();
    ?>

    <body>
   <!--
    <div class="modal fade modal-size" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
           <div id="modalVideo" class="modal-body">
			<iframe width="100%" height="350" src="https://www.youtube.com/embed/TEd1W4hT638?autoplay=1&mute=1" frameborder="1" allow="encrypted-media" allowfullscreen></iframe>	
	 </div>
	 <div id="modalBtn" class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Ocultar</button>
        </div>
      </div>
   </div>
</div>
	-->
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
	
	<!--DIV ALL ALOJA TODO EL CUERPO DE LA PÁGINA HASTA EL FOOTER ECLIPSE-->
	<div id="all">

		<!--CUERPO DE LA PÁGINA-->
		<div id="content">

			<!--SLIDER PRINCIPAL-->
			<!-- <div class="container">
                     <div class="col-md-12">
                         <div id="main-slider">
                <?php
                // echo $secciones->sliderPrincipal();
                ?>
                         </div>
                <!-- /#main-slider -->
			<!--</div>
            </div>-->

			<!--/SLIDER PRINCIPAL-->

			<div id="bootstrap-touch-slider"
				class="carousel bs-slider fade  control-round indicators-line"
				data-ride="carousel" data-pause="hover" data-interval="false"
				style="margin-bottom: 30px;">

				<!-- Indicators -->
				<div class="center-block">
					<ol class="carousel-indicators">
                            <?php
                            echo $slider["botones"];
                            ?>
                        </ol>
				</div>



				<!-- Wrapper For Slides -->
				<div class="carousel-inner" role="listbox">

                        <?php
                        echo $slider["items"];
                        ?>


                    </div>
				<!-- End of Wrapper For Slides -->

				<!-- Left Control -->
				<a class="left carousel-control" href="#bootstrap-touch-slider"
					role="button" data-slide="prev"> <span class="fa fa-angle-left"
					aria-hidden="true"></span> <span class="sr-only">Previous</span>
				</a>

				<!-- Right Control -->
				<a class="right carousel-control" href="#bootstrap-touch-slider"
					role="button" data-slide="next"> <span class="fa fa-angle-right"
					aria-hidden="true"></span> <span class="sr-only">Next</span>
				</a>

			</div>
			<!-- End  bootstrap-touch-slider Slider -->

			<!-- *** ADVANTAGES HOMEPAGE ***
     _________________________________________________________ -->
                <?php
                //echo $secciones->ventajas();
                ?>
                <!-- /#advantages -->

			<!-- *** ADVANTAGES END *** -->

			<!-- *** HOT PRODUCT SLIDESHOW ***
     _________________________________________________________ -->
			<div id="hot">

				<div class="box">
					<div class="container">
						<div class="col-md-12">
							<h2>PRODUCTOS DESTACADOS</h2>
						</div>
					</div>
				</div>

                    <?php
                    echo $secciones->prodDestacados();
                    ?>
                    <!-- /.container -->

			</div>
			<!-- /#hot -->

			<!-- *** HOT END *** -->

			<!-- *** GET INSPIRED ***
     _________________________________________________________ -->
			
   				<div class="container" data-animate="fadeInUpBig">
					<div class="col-md-12">
						<div class="box slideshow">
							<h3>Conoce nuestras Marcas</h3>
							<p class="lead">Descubre las marcas asosiadas que						podrás encontrar y lo que tienen para ti.</p>
						</div>
					</div>
					<!--
					<div id="get-inspired" class="owl-carousel owl-theme">				  
					  <?php
						  //echo $sliderMarcas["items"];
                                ?>
                            	 </div>
					     -->
				</div>
				
				<div class="container">
	     				<div class="row justify-content-md-center">
                  			<div class="col col-md-2">			
                  			</div>
                  			<div id="get-inspired" class="col col-md-8 owl-carousel owl-theme">
						<?php
							  echo $sliderMarcas["items"];
                                	?>
                  			</div>
                  			<div class="col col-md-2">
                  			</div>
            			</div>
			      </div>
			<!-- *** GET INSPIRED END *** -->

			<!-- *** BLOG HOMEPAGE ***
     _________________________________________________________ -->

			<div id="slay" class="marginContainer box text-center" data-animate="fadeInUp">
				<div class="container">
					<div class="col-md-12">
						<h3 class="text-uppercase">Visita nuestro blog</h3>

						<p class="lead">
							¿Quieres enterarte de las ofertas y promociones que publicamos? <a
								href="blog.php">¡Visita nuestro blog!</a>
						</p>
					</div>
				</div>
			</div>

			<!--2 ÚLTIMAS NOTICIAS-->
			<div class="container">

				<div class="col-md-12" data-animate="fadeInUp">

					<div id="blog-homepage" class="row">
                            <?php
                            echo $secciones->ultimasNoticias()?>
                        </div>
					<!-- /#blog-homepage -->
				</div>
			</div>
			<!-- /.container -->

			<!-- *** BLOG HOMEPAGE END *** -->

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
	<script src="slider/bootstrap-touch-slider.js"></script>
	
	<script type="text/javascript">
            $('#bootstrap-touch-slider').bsTouchSlider();
      </script>

	<script>
      $(document).ready(function()
      {
         $("#mostrarmodal").modal("show");
      });
    </script>

	<!--
		Las categorias en la parte de las bermudas tiene un error de responsividad

	 -->
</body>

</html>