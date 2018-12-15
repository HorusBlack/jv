<?php
    include_once 'PHP/util.php';
    include_once 'PHP/bd.php';
    include_once 'PHP/secciones.php';
    $s = new secciones($BD);
?>

<!DOCTYPE html>
<html lang="en">

    <?php
        echo $s->libreriasHead();
    ?>

    <body>

        <!-- *** TOPBAR ***
     _________________________________________________________ -->
        <div id="top">
            <?php
                echo $s->topBar();
            ?>
            <!-- *** MODAL DE INICIO DE SESION ***
     _________________________________________________________ -->
            <?php 
                echo $s->modalLogin();
            ?>

        </div>

        <!-- *** TOP BAR END *** -->

        <!-- *** NAVBAR ***
     _________________________________________________________ -->
        <?php 
            echo $s->menu();
        ?>
        
        <!-- /#navbar -->

        <!-- *** NAVBAR END *** -->

        <div id="all">

            <div id="content">
                <div class="container">

                    <div class="col-md-12">
                        <ul class="breadcrumb">

                            <li><a href="index.php">Inicio</a>
                            </li>
                            <li>Contacto</li>
                        </ul>

                    </div>

                    <div class="col-md-3">
                        <!-- *** PAGES MENU ***
     _________________________________________________________ -->
                        <div class="panel panel-default sidebar-menu">

                            <div class="panel-heading">
                                <h3 class="panel-title">Navegación</h3>
                            </div>

                            <div class="panel-body">
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active">
                                        <a href="contact.php">Contacto</a>
                                    </li>
                                    <li>
                                        <a href="faq.php">Preguntas frecuentes</a>
                                    </li>
                                    <li>
                                        <a href="blog.php?pag=1">Blog</a>
                                    </li>

                                </ul>

                            </div>
                        </div>

                        <!-- *** PAGES MENU END *** -->


                        <?php
                        echo $s->publicidad(4);
                        ?>
                    </div>

                    <div class="col-md-9">


                        <div class="box" id="contact">
                            <h1 class="subTitulos">Contáctanos</h1>

                            <p class="lead">¿Tienes alguna duda? ¿Tienes algún inconveniente con nuestros productos?</p>
                            <p>Sientete libre de contactarnos cuando quieras, nos interesa mucho tu opinion.</p>

                            <hr>
                            
                            <!--ROW DE INFORMACIÓN DE CONTACTO-->
                                <?php
                                    echo $s->infoContacto();
                                ?>
                            <!-- /.row -->

                            <hr>
                            <h2 class="subTitulos">¡Escribenos!</h2>

                            <form action="PHP/sendEmail.php" method="post">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="firstname">Nombre(s) *</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="lastname">Apellidos *</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Correo electrónico *</label>
                                            <input type="text" class="form-control" id="email" name="email">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="subject">Asunto *</label>
                                            <input type="text" class="form-control" id="subject" name="subject">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="message">Mensaje *</label>
                                            <textarea id="message" class="form-control" name="message"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <input name="submit" value="Enviar Mensaje" class="btn btn-primary" type="submit">

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>


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
                echo $s->footer();
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




        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=true"></script>

        <script>
            function initialize() {
                var mapOptions = {
                    zoom: 15,
                    center: new google.maps.LatLng(18.4631771, -97.3766953),
                    mapTypeId: google.maps.MapTypeId.ROAD,
                    scrollwheel: false
                }
                var map = new google.maps.Map(document.getElementById('map'),
                        mapOptions);

                var myLatLng = new google.maps.LatLng(18.4631771, -97.3766953);
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map
                });
            }

            google.maps.event.addDomListener(window, 'load', initialize);
        </script>


    </body>

</html>
