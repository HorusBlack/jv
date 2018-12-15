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

                            <li><a href="index.php">Inicio</a></li>
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
                            <h1 class="subTitulos">Recupera tu cuenta</h1>

                            <p class="lead">Podemos ayudarlo a restablecer su contraseña y la información de seguridad.
                                 Primero, ingrese su correo electrónico y a continuación revise su bandeja de entrada.</p>
                            <hr>
                            <hr>
                            <h2 class="subTitulos">Correo electrónico</h2>

                            <form action="PHP/sendRecovery.php" method="post" id="form_recovery">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Correo electrónico *</label>
                                            <input type="text" class="form-control" id="email" name="email">
                                            <label for="text" hidden="hidden">hola</label>
                                        </div>
                                    </div>
                                  
                                    <div class="col-sm-12 text-center">
                                        <input name="submit" value="Enviar Solicitud" class="btn btn-primary" type="submit">
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

    </body>

</html>
