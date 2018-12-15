
<?php
// SECCIONES DEL BACKEND
/*
if(!isset($_SESSION)) 
{ 
    session_start(); 
}*/
session_start(); 
class secciones
{

    private $BD;

    public function __construct(BD $BD)
    {
        $this->BD = $BD;
    }

    public function BD()
    {
        return $this->BD;
    }

    // SCRIPTS PRESONALIZABLES
    public function alerta($mensaje)
    {
        echo "<script>alert('" . $mensaje . "');</script>";
    }

    public function redireccionarJS($pagina)
    {
        echo "<script>location.href='" . $pagina . "'</script>";
    }

    // SECCIONES DE SESIÓN DE USUARIO
    public function autentificarBCKND($usuario, $pass)
    {
        $user = $this->BD->ConsultaLibre("*", "usuarios_bcknd", "usuario_bcknd=? AND password_bcknd=?", array(
            $usuario,
            $pass
        ), 1);
        if ($user[0] != "") {
            $_SESSION["usuarioBCKND"] = $user[0];
            return true;
        } else {
            return false;
        }
    }

    public function sesionIniciada()
    {
        return (isset($_SESSION["usuarioBCKND"]));
    }

    public function validarSesion()
    {
        if (! $this->sesionIniciada()) {
            $this->alerta("Inicia sesión para poder acceder a esta página");
            $this->redireccionarJS("index.php");
        }
    }

    // SECCIONES PARA TODAS LAS PÁGINAS
    public function libreriasHead()
    {
        return '<head>

        <meta charset="utf-8">
        <meta name="robots" content="all,follow">
        <meta name="googlebot" content="index,follow,snippet,archive">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Obaju e-commerce template">
        <meta name="author" content="Ondrej Svestka | ondrejsvestka.cz">
        <meta name="keywords" content="">

        <title>
            Jade-v&#252;
        </title>

        <meta name="keywords" content="">

        <link href="http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet" type="text/css">

        <!-- styles -->
        <link href="../css/font-awesome.css" rel="stylesheet">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/animate.min.css" rel="stylesheet">
        <link href="../css/owl.carousel.css" rel="stylesheet">
        <link href="../css/owl.theme.css" rel="stylesheet">

        <!-- theme stylesheet -->
        <link href="../css/style.blue.css" rel="stylesheet" id="theme-stylesheet">

        <!-- your stylesheet with modifications -->
        <link href="../css/custom.css" rel="stylesheet">

        <script src="../js/respond.min.js"></script>
        <script src="./ck/ckeditor/ckeditor.js"></script>
        <script src="./ck/ckfinder/ckfinder.js"></script>
        <script src="./js/jscolor.js"></script>
        
        
        <link rel="shortcut icon" href="../favicon.png">



    </head>';
    }


    public function menu()
    {
        return '<div class="navbar navbar-default yamm" role="navigation" id="navbar">
            <div class="container">
                <div class="navbar-header">

                    <a class="navbar-brand home" href="sections.php" data-animate-hover="bounce">
                        <img src="../img/LOGOJV2.png" alt="Obaju logo" class="hidden-xs">
                        <img src="../img/LOGOJV2.png" alt="Obaju logo" class="visible-xs"><span class="sr-only">JADE-VÜ - go to homepage</span>
                    </a>
                </div>
                <!--/.navbar-header -->

                <div class="navbar-collapse collapse" id="navigation">

                    <ul class="nav navbar-nav navbar-left">
                        <li class="active"><a href="sections.php">Secciones</a>
                        </li>
                                                
                        <li class="yamm-fw">
                            <a href="responsable_edit.php?ID=' .secciones::perfilResponsable(). '">Perfil</a>
                        </li>
                        <!--
                        <li class="yamm-fw">
                            <a href="contact.php">Historial</a>
                                        -->
                        </li>
                      
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                                          
                        <li class="yamm-fw">
                            <a href="salir.php">Cerrar Sesion</a>
                        
                        </li>
                    </ul>

                </div>
                <!--/.nav-collapse -->

                <div class="navbar-buttons">

                    <div class="navbar-collapse collapse right" id="basket-overview">
                    <!--../../index.php-->    
                    <a href="../index.php" class="btn btn-primary navbar-btn"><i class="fa fa-shopping-cart"></i><span class="hidden-sm">Volver al sitio web</span></a>
                    </div>
                    <!--/.nav-collapse -->

                    

                </div>

                

            </div>
            <!-- /.container -->
        </div>';
    }

    public function footer()
    {
        return '
            <div id="copyright">
                <div class="container">
                    <div class="col-md-6">
                        <p class="pull-left"> © JADE-VÜ es una marca perteneciente a CUBO eSTUDIO 2018.</p>

                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>';
    }

    // SECCIONES GLOBALES
    public function migasPan($base = "", $linea = "", $categoria = "", $subcat = "", $producto = "", $noticia = "", $nuevo = "")
    {
        $i = "";
        if ($base != "") {
            $i .= '<li>Lineas de productos</li>';
        } else if ($linea != "" && $nuevo != 1) {
            $r = $this->BD->ConsultaWhereSimple("linea", "activo=? AND ID=?", array(
                1,
                $linea
            ));
            $i .= '<li><a href="lineas.php">Lineas de productos</a></li><li>' . $r[0]["nombre_linea"] . '</li>';
        } else if ($linea != "" && $nuevo == 1) {
            $i .= '<li><a href="lineas.php">Lineas de productos</a></li><li>Nueva línea de productos.</li>';
        } else if ($categoria != "" && $nuevo != 1) {
            $r = $this->BD->ConsultaWhereSimple("categoria", "activo=? AND ID=?", array(
                1,
                $categoria
            ));
            $j = $this->BD->ConsultaWhereSimple("linea", "activo=? AND ID=?", array(
                1,
                $r[0]["ID_linea"]
            ));
            $i .= '<li><a href="lineas.php">Lineas de productos</a></li><li><a href="categorias.php?ID=' . $j[0]["ID"] . '">' . $j[0]["nombre_linea"] . '</a></li><li>' . $r[0]["nombre_categoria"] . '</li>';
        } else if ($categoria != "" && $nuevo == 1) {
            $j = $this->BD->ConsultaWhereSimple("linea", "activo=? AND ID=?", array(
                1,
                $categoria
            ));
            $i .= '<li><a href="lineas.php">Lineas de productos</a></li><li><a href="categorias.php?ID=' . $j[0]["ID"] . '">' . $j[0]["nombre_linea"] . '</a></li><li>Nueva categoria</li>';
        } else if ($subcat != "" && $nuevo != 1) {
            $q = $this->BD->ConsultaWhereSimple("subcategoria", "activo=? AND ID=?", array(
                1,
                $subcat
            ));
            $r = $this->BD->ConsultaWhereSimple("categoria", "activo=? AND ID=?", array(
                1,
                $q[0]["ID_categoria"]
            ));
            $j = $this->BD->ConsultaWhereSimple("linea", "activo=? AND ID=?", array(
                1,
                $r[0]["ID_linea"]
            ));
            $i .= '<li><a href="lineas.php">Lineas de productos</a></li><li><a href="categorias.php?ID=' . $j[0]["ID"] . '">' . $j[0]["nombre_linea"] . '</a></li><li><a href="subcategorias.php?ID=' . $r[0]["ID"] . '">' . $r[0]["nombre_categoria"] . '</a></li><li>' . $q[0]["nombre_subcat"] . '</li>';
        } else if ($subcat != "" && $nuevo == 1) {
            $r = $this->BD->ConsultaWhereSimple("categoria", "activo=? AND ID=?", array(
                1,
                $subcat
            ));
            $j = $this->BD->ConsultaWhereSimple("linea", "activo=? AND ID=?", array(
                1,
                $r[0]["ID_linea"]
            ));
            $i .= '<li><a href="lineas.php">Lineas de productos</a></li><li><a href="categorias.php?ID=' . $j[0]["ID"] . '">' . $j[0]["nombre_linea"] . '</a></li><li><a href="subcategorias.php?ID=' . $r[0]["ID"] . '">' . $r[0]["nombre_categoria"] . '</a></li><li>Nueva Subcategoria</li>';
        } else if ($producto != "" && $nuevo != 1) {
            $t = $this->BD->ConsultaWhereSimple("producto", "activo=? AND ID=?", array(
                1,
                $producto
            ));
            $q = $this->BD->ConsultaWhereSimple("subcategoria", "activo=? AND ID=?", array(
                1,
                $t[0]["ID_subcat"]
            ));
            $r = $this->BD->ConsultaWhereSimple("categoria", "activo=? AND ID=?", array(
                1,
                $q[0]["ID_categoria"]
            ));
            $j = $this->BD->ConsultaWhereSimple("linea", "activo=? AND ID=?", array(
                1,
                $r[0]["ID_linea"]
            ));
            $i .= '<li><a href="lineas.php">Lineas de productos</a></li><li><a href="categorias.php?ID=' . $j[0]["ID"] . '">' . $j[0]["nombre_linea"] . '</a></li><li><a href="subcategorias.php?ID=' . $r[0]["ID"] . '">' . $r[0]["nombre_categoria"] . '</a></li><li><a href="productos.php?ID=' . $q[0]["ID"] . '">' . $q[0]["nombre_subcat"] . '</a></li><li>' . $t[0]["nombre_prod"] . '</li>';
        } else if ($producto != "" && $nuevo == 1) {
            $q = $this->BD->ConsultaWhereSimple("subcategoria", "activo=? AND ID=?", array(
                1,
                $producto
            ));
            $r = $this->BD->ConsultaWhereSimple("categoria", "activo=? AND ID=?", array(
                1,
                $q[0]["ID_categoria"]
            ));
            $j = $this->BD->ConsultaWhereSimple("linea", "activo=? AND ID=?", array(
                1,
                $r[0]["ID_linea"]
            ));
            $i .=  '<li>
                        <a href="lineas.php">Lineas de productos</a>
                    </li>
                    <li>
                        <a href="categorias.php?ID=' . $j[0]["ID"] . '">' . $j[0]["nombre_linea"] . '</a>
                    </li>
                    <li>
                        <a href="subcategorias.php?ID=' . $r[0]["ID"] . '">' . $r[0]["nombre_categoria"] . '</a>
                    </li>
                    <li>
                    <a href="productos.php?ID=' . $q[0]["ID"] . '">' . $q[0]["nombre_subcat"] . '</a>
                    </li>
                    <li>Producto nuevo</li>';
        }
        return '<ul class="breadcrumb">

                            <li><a href="sections.php">Inicio</a>
                            </li>
                            ' . $i . '
                        </ul>';
    }

    public function sections()
    {
        return '<div id="advantages">

                    
                        <div class="row">
                            <div class="col-md-4">
                                <div class="box same-height clickable">
                                    <div class="icon"><i class="fa fa-shopping-cart"></i>
                                    </div>

                                    <h3><a href="lineas.php">Productos</a></h3>
                                    <p>Productos y sus categorías a la venta en la tienda.</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="box same-height clickable">
                                    <div class="icon"><i class="fa fa-file-text"></i>
                                    </div>

                                    <h3><a href="entradasblog.php">Entradas del blog </a></h3>
                                    <p>Todas las noticias publicadas hasta el momento en el blog.</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="box same-height clickable">
                                    <div class="icon"><i class="fa fa-picture-o"></i>
                                    </div>

                                    <h3><a href="sliderprincipal.php">Slider principal</a></h3>
                                    <p>Imágenes que se visualizarán en el slider principal.</p>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="box same-height clickable">
                                    <div class="icon"><i class="fa fa-picture-o"></i>
                                    </div>

                                    <h3><a href="slidermarcas.php">Slider de marcas</a></h3>
                                    <p>Imágenes que se visualizarán en el slider de marcas.</p>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="box same-height clickable">
                                    <div class="icon"><i class="fa fa-question-circle"></i>
                                    </div>

                                    <h3><a href="preguntasfrec.php">Preguntas frecuentes</a></h3>
                                    <p>Preguntas frecuentes con su respectiva respuesta.</p>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="box same-height clickable">
                                    <div class="icon"><i class="fa fa-bookmark"></i>
                                    </div>

                                    <h3><a href="publicidad.php">Banners publicidad</a></h3>
                                    <p>Banners de publicidad que aparecerán aleatoriamente.</p>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                            <div class="box same-height clickable">
                                <div class="icon"><i class="fa fa-shopping-cart"></i>
                                </div>

                                <h3><a href="ventas.php">Ventas</a></h3>
                                <p>Visualización y Administración de los productos vendidos</p>
                            </div>
                        </div>
                            
                            <div class="col-md-4">
                                <div class="box same-height clickable">
                                    <div class="icon"><i class="fa fa-users"></i>
                                    </div>

                                    <h3><a href="usuarios.php">Usuarios</a></h3>
                                    <p>Gestión de los usuarios que se han registrado en el sitio web.</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="box same-height clickable">
                                    <div class="icon"><i class="fa fa-unlock-alt"></i>
                                    </div>

                                    <h3><a href="responsable.php">Responsables</a></h3>
                                    <p>Registra, modifica o elimina al personal autorizado para modificar la página web</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                            <div class="box same-height clickable">
                                <div class="icon"><i class="fa fa-info-circle"></i>
                                </div>

                                <h3><a href="infoEmpresa.php">Info. de la empresa</a></h3>
                                <p>Información de la empresa como dirección, teléfonos, etc.</p>
                            </div>
                        </div>

                        </div>
                        <!-- /.row -->

                </div>';
    }

    // SECCIONES DE CONSULTA DE REGISTROS
    public function blogListing()
    {
        $r = $this->BD->ConsultaWhereSimple("blog", "activo=? ORDER BY ID DESC", array(1));
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        foreach ($r as $c) {
            $i .= '<tr>
                                        <td>' . $c["Titulo_Noticia"] . '</td>
                                        <td><a href="blog_edit.php?ID=' . $c["ID"] . '"><i class="fa fa-pencil fa-2x"></i></a></td>
                                        <td><a href="delete.php?table=blog&ID=' . $c["ID"] . '&retorno='.$url.'"><i class="fa fa-ban fa-2x" ></i></a></td>
                                    </tr>';
        }
        return $i;
    }

    public function sliderpListing()
    {
        $r = $this->BD->ConsultaWhereSimple("slider", "activo=? ORDER BY orden DESC", array(1));
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        foreach ($r as $c) {
            $i .= '<tr>
                                        <td>' . $c["nombre"] . '</td>
                                        <td><a href="sliderp_edit.php?ID=' . $c["ID"] . '"><i class="fa fa-pencil fa-2x"></i></a></td>
                                        <td><a href="delete.php?table=slider&ID=' . $c["ID"] .'&retorno='.$url.'"><i class="fa fa-ban fa-2x" ></i></a></td>
                                    </tr>';
        }
        return $i;
    }

    public function preguntasListing()
    {
        $r = $this->BD->ConsultaWhereSimple("preguntas", "activo=? ORDER BY orden DESC", array(1));
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        foreach ($r as $c) {
            $i .= '<tr>
                                        <td>' . $c["pregunta"] . '</td>
                                        <td><a href="preguntas_edit.php?ID=' . $c["ID"] . '"><i class="fa fa-pencil fa-2x"></i></a></td>
                                        <td><a href="delete.php?table=preguntas&ID=' . $c["ID"].'&retorno='.$url.'"><i class="fa fa-ban fa-2x" ></i></a></td>
                                    </tr>';
        }
        return $i;
    }

    public function bannersListing()
    {
        $r = $this->BD->ConsultaWhereSimple("publicidad", "activo=? ORDER BY orden DESC", array(1));
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        foreach ($r as $c) {
            $i .= '<tr>
                                        <td>' . $c["nombre"] . '</td>
                                        <td><a href="publicidad_edit.php?ID=' . $c["ID"] . '"><i class="fa fa-pencil fa-2x"></i></a></td>
                                        <td><a href="delete.php?table=publicidad&ID=' . $c["ID"].'&retorno='.$url.'"><i class="fa fa-ban fa-2x" ></i></a></td>
                                    </tr>';
        }
        return $i;
    }

    public function empresaListing()
    {
        $r = $this->BD->ConsultaWhereSimple("infoempresa", "ID=?", array(
            1
        ));
        return '<tr>
                                        <td>' . $r[0]["nombre_empresa"] . '</td>
                                        <td><a href="infoEmpresa_edit.php"><i class="fa fa-pencil fa-2x"></i></a></td>
                                    </tr>';
    }

    public function usuariosListing()
    {
        $r = $this->BD->ConsultaWhereSimple("usuarios", "activo=? ORDER BY ID DESC", array(1));
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        foreach ($r as $c) {
            $i .= '<tr>
                                        <td>' . $c["username_usuario"] . '</td>
                                        <td><a href="usuarios_edit.php?ID=' . $c["ID"] . '"><i class="fa fa-pencil fa-2x"></i></a></td>
                                        <td><a href="delete.php?table=usuarios&ID=' . $c["ID"].'&retorno='.$url.'"><i class="fa fa-ban fa-2x" ></i></a></td>
                                    </tr>';
        }
        return $i;
    }

    public function responsablesListing()
    {
        $r = $this->BD->ConsultaWhereSimple("usuarios_bcknd", "activo=? ORDER BY ID DESC", array(1));
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        foreach ($r as $c) {
            $i .= '<tr>
                                        <td>' . $c["usuario_bcknd"] . '</td>
                                        <td><a href="responsable_edit.php?ID=' . $c["ID"] . '"><i class="fa fa-pencil fa-2x"></i></a></td>
                                        <td><a href="delete.php?table=usuarios_bcknd&ID=' . $c["ID"].'&retorno='.$url.'"><i class="fa fa-ban fa-2x" ></i></a></td>
                                    </tr>';
        }
        return $i;
    }

    public function ventasListing()
    {
        $r = $this->BD->ConsultaJoinVentas("u.nombres_usuario, u.apellidos_usuario, v.estatus, v.idEstatus, v.referencia, v.fecha ", " usuarios u ", " ventas v ", " u.ID=v.id_usuario "," v.fecha ", 20);
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        foreach ($r as $c) {
            $i .= '<tr>
                    <td align="left">' . $c["referencia"] . '</td>
                    <td align="left">' . $c["nombres_usuario"] . ' ' . $c["apellidos_usuario"] . '</td>
                    <td align="center"><a href="destinoEnvio.php?ref=' . $c["referencia"] . '" class="btn btn-primary"><i class="fa fa-map-marker"></i></a></td>
                    <td align="center"><a href="detalleCompra.php?ref=' . $c["referencia"] . '" class="btn btn-info"><i class="fa fa-shopping-cart"></i></a></td>
                    <td align="left">' . $c["estatus"] . '</td>
                    <td align="center"><a href="envioGuia.php?ref=' . $c["referencia"] . '" class="btn btn-success"><i class="fa fa-truck" aria-hidden="true"></i></a></td>
                    <td align="center">PROCESO</td>
                    </tr>';
        }
        /**
         * PENDIENTE REALIZAR GROUP BY CON LOS ELEMENTOS DE REFERENCIA
         */
        return $i;
    }

    public function comprasListing($referenciaCompra)
    /**
     * Pendiente generar la consulta
     */
    {
        $r = $this->BD->ConsultaJoinLibre("v.id ,v.referencia, p.nombre_prod, v.talla, v.piezas, p.fit_prod, p.precio_prod, p.img1_prod,v.fecha", "producto p", "ventas v", "p.ID=v.id_producto","v.referencia=".$referenciaCompra);
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        $idv=0;
        foreach ($r as $c) {
            $idv=$c["id"];
            $date = date_create($c["fecha"]);
            $i .= '<tr>
                    <td align="left">' . $c["referencia"] . '</td>
                    <td align="left">' . $c["nombre_prod"] . '</td>
                    <td align="left"><img src="' . $c["img1_prod"] . '" alt="'.$c["nombre_prod"].'" width="auto" height="175"></td>
                    <td align="left">' . $c["fit_prod"] . '</td>
                    <td align="left">' . $c["talla"] . '</td>
                    <td align="left">' . $c["piezas"] . '</td>
                    <td align="left">$' . $c["precio_prod"] . '</td>
                    <td align="left">' . date_format($date, 'd/m/Y H:i:s') . '</td>
                    </tr>';
        }
        /**
         * PENDIENTE REALIZAR GROUP BY CON LOS ELEMENTOS DE REFERENCIA
         */
        return $i;
    }
    public function prueba(){
        echo "hola mundo";
    }



    public function perfilResponsable()
    {
        $r = $this->BD->ConsultaWhereSimple("usuarios_bcknd", "activo=? ORDER BY ID DESC", array(1));
        $i = "";
        foreach ($r as $c) {
            //$i .="<a href='responsable_edit.php?ID=".$c['ID']."'></a>"; //Imprime 43 cuando deberia imprimir 4
            $i.=$c['ID'];
        }
        return $i;
    }

    public function slidermListing()
    {
        $r = $this->BD->ConsultaWhereSimple("marcas_slider", "activo=? ORDER BY orden DESC", array(1));
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        foreach ($r as $c) {
            $i .= '<tr>
                                        <td>' . $c["nombre_marca"] . '</td>
                                        <td><a href="sliderm_edit.php?ID=' . $c["ID"] . '"><i class="fa fa-pencil fa-2x"></i></a></td>
                                        <td><a href="delete.php?table=marcas_slider&ID=' . $c["ID"] . '&retorno='.$url.'"><i class="fa fa-ban fa-2x" ></i></a></td>
                                    </tr>';
        }
        return $i;
    }

    public function lineasListing()
    {
        $r = $this->BD->ConsultaWhereSimple("linea", "activo=? ORDER BY orden_linea DESC", array(1));
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        foreach ($r as $c) {
            $i .= '<tr>
                                        <td>' . $c["nombre_linea"] . '</td>
                                        <td><a href="categorias.php?ID=' . $c["ID"] . '"><i class="fa fa-folder fa-2x"></i></a></td>
                                        <td><a href="lineas_edit.php?ID=' . $c["ID"] . '"><i class="fa fa-pencil fa-2x"></i></a></td>
                                        <td><a href="delete.php?table=linea&ID=' . $c["ID"] .'&retorno='.$url.'"><i class="fa fa-ban fa-2x" ></i></a></td>
                                    </tr>';
        }
        return $i;
    }

    public function categoriasListing($ideLinea)
    {
        $r = $this->BD->ConsultaWhereSimple("categoria", "activo=? AND ID_linea=? ORDER BY orden_categoria DESC", array(
            1,
            $ideLinea
        ));
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        foreach ($r as $c) {
            $i .= '<tr>
                                        <td>' . $c["nombre_categoria"] . '</td>
                                        <td><a href="subcategorias.php?ID=' . $c["ID"] . '"><i class="fa fa-folder fa-2x"></i></a></td>
                                        <td><a href="categorias_edit.php?ID=' . $c["ID"] . '&IDLINEA=' . $c["ID_linea"] . '"><i class="fa fa-pencil fa-2x"></i></a></td>
                                        <td><a href="delete.php?table=categoria&ID=' . $c["ID"].'&retorno='.$url.'"><i class="fa fa-ban fa-2x" ></i></a></td>
                                    </tr>';
        }
        return $i;
    }

    public function subcategoriasListing($ideCategoria)
    {
        $r = $this->BD->ConsultaWhereSimple("subcategoria", "activo=? AND ID_categoria=? ORDER BY orden_subcat DESC", array(
            1,
            $ideCategoria
        ));
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        foreach ($r as $c) {
            $i .= '<tr>
                                        <td>' . $c["nombre_subcat"] . '</td>
                                        <td><a href="productos.php?ID=' . $c["ID"] . '"><i class="fa fa-folder fa-2x"></i></a></td>
                                        <td><a href="subcategorias_edit.php?ID=' . $c["ID"] . '&IDCATEGORIA=' . $c["ID_categoria"] . '"><i class="fa fa-pencil fa-2x"></i></a></td>
                                        <td><a href="delete.php?table=subcategoria&ID=' . $c["ID"] .'&retorno='.$url.'"><i class="fa fa-ban fa-2x" ></i></a></td>
                                    </tr>';
        }
        return $i;
    }

    public function productosListing($ideSubcat)
    {
        $r = $this->BD->ConsultaWhereSimple("producto", "activo=? AND ID_subcat=? ORDER BY orden_prod DESC", array(1,$ideSubcat));
        $i = "";
        $url =$_SERVER["REQUEST_URI"];
        foreach ($r as $c) {
            $i .= '<tr>
                       <td>' . $c["nombre_prod"] . '/' . $c["fit_prod"] . '/' . $c["lavado_prod"] . '</td>
                       <td><a href="productos_edit.php?ID=' . $c["ID"] . '&IDSUBCAT=' . $c["ID_subcat"] . '"><i class="fa fa-pencil fa-2x"></i></a></td>
                       <td><a href="delete.php?table=producto&ID=' . $c["ID"] .'&retorno='.$url.'"><i class="fa fa-ban fa-2x" ></i></a></td>
                    </tr>';
        }
        return $i;
    }

    public function productosEdit($ide, $ideSubcat)
    {
        $img1 = "'img1_prod'";
        $img2 = "'img2_prod'";
        $img3 = "'img3_prod'";
        $img4 = "'img4_prod'";
        $img5 = "'img5_prod'";
        if ($ide != "") {
            
            $r = $this->BD->ConsultaWhereSimple("producto", "ID=?", array(
                $ide
            ));
            switch ($r[0]["destacado_prod"]) {
                case 1:
                    $destacado = '
                        <select data-toggle="tooltip" title="Seleccione si el producto es destacado o no." class="form-control" name="destacado_prod">
                                    <option value="1" selected>Si</option>
                                    <option value="0">No</option>
                                </select>';
                    break;
                case 0:
                    $destacado = '
                        <select data-toggle="tooltip" title="Seleccione si el producto es destacado o no." class="form-control" name="destacado_prod">
                                    <option value="1">Si</option>
                                    <option value="0" selected>No</option>
                                </select>';
                    break;
            }
            switch ($r[0]["nuevo_prod"]) {
                case 1:
                    $nuevo = '
                        <select data-toggle="tooltip" title="Seleccione si el producto es nuevo o no." class="form-control" name="nuevo_prod">
                                    <option value="1" selected>Si</option>
                                    <option value="0">No</option>
                                </select>';
                    break;
                case 0:
                    $nuevo = '
                        <select data-toggle="tooltip" title="Seleccione si el producto es nuevo o no." class="form-control" name="nuevo_prod">
                                    <option value="1">Si</option>
                                    <option value="0" selected>No</option>
                                </select>';
                    break;
            }
            return '<div class="box" id="text-page">
            <h1>Editar producto: "' . $r[0]["nombre_prod"] . '" </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                <form action="procesos.php?IDPROD=' . $r[0]["ID"] . '&IDSUBCAT=' . $r[0]["ID_subcat"] . '" method="post" id="form_productos">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre<b> (Obligatorio)</b>*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre del producto." class="form-control" id="nombre_prod" name="nombre_prod" value="' . $r[0]["nombre_prod"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="lastname">Orden<b> (Obligatorio)</b>*</label>
                                        <select data-toggle="tooltip" title="Seleccione el orden de apareción del producto." class="form-control" id="orden_prod" name="orden_prod">
                                        '.secciones::llenarOrdenSelected($r[0]["orden_prod"]).'
                                        </select>
                                    </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Tallas y stock<b> (Obligatorio)</b>*</label>
                                            <input type="text" data-toggle="tooltip" title="Formato: 3:NUM,5:NUM,7:NUM,etc ó S:1,M:3,L:2,XL:1" class="form-control" id="talla_prod" name="talla_prod" value="' . $r[0]["talla_prod"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Fit</label>
                                            <select title="Seleccione el Fit del producto." class="form-control" name="fit_prod" id="fit_prod">
                                            '.secciones::llenarFitSelected($r[0]["fit_prod"]).'
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Lavado</label>
                                            <input type="text" data-toggle="tooltip" title="Ingrese el lavado del producto." class="form-control" id="lavado_prod" name="lavado_prod" value="' . $r[0]["lavado_prod"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Precio<b> (Obligatorio)</b>*</label>
                                            <input type="text" data-toggle="tooltip" title="Ingrese el precio base del producto, solo la cantidad." class="form-control" id="precio_prod" name="precio_prod" value="' . $r[0]["precio_prod"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Precio descuento</label>
                                            <input type="text" data-toggle="tooltip" title="Si el producto cuenta con descuento, ingrese el precio con el descuento aplicado." class="form-control" id="preciodesc_prod" name="preciodesc_prod" value="' . $r[0]["preciodesc_prod"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Imagen 1<b> (Obligatorio)</b>*</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen 1 del producto." class="form-control" id="img1_prod" name="img1_prod" value="'.$r[0]["img1_prod"].'">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer('. $img1.');"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Imagen 2<b> (Obligatorio)</b>*</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen 2 del producto." class="form-control" id="img2_prod" name="img2_prod" value="' . $r[0]["img2_prod"] . '">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer(' . $img2 . ');"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Imagen 3</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen 3 del producto (opcional)." class="form-control" id="img3_prod" name="img3_prod" value="' . $r[0]["img3_prod"] . '">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer(' . $img3 . ');"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Imagen 4</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen 4 del producto (opcional)." class="form-control" id="img4_prod" name="img4_prod" value="' . $r[0]["img4_prod"] . '">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer(' . $img4 . ');"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Imagen 5</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen 5 del producto (opcional)." class="form-control" id="img5_prod" name="img5_prod" value="' . $r[0]["img5_prod"] . '">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer(' . $img5 . ');"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Descripción<b> (Obligatorio)</b>*</label>
                                                <textarea data-toggle="tooltip" title="Descripcion del producto." name="descripcion_prod" id="descripcion_prod" rows="10" cols="80">
                                                    ' . $r[0]["descripcion_prod"] . '
                                                </textarea>
                                                <script>
                                                    window.onload = function(){
                                                    var editor = CKEDITOR.replace( "descripcion_prod" );
                                                    CKFinder.setupCKEditor(editor, "http://jade-vu.com/web/BackEnd/ck/ckfinder/");
                                                    }
                                                </script>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Color</label>
                                            <select data-toggle="tooltip" title="Color predominante del producto" class="form-control" id="color_prod" name="color_prod">
                                            '.secciones::llenarColoresSelected($r[0]["color_prod"]).'
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Color (Hexadecimal)</label>
                                            <input type="text" data-toggle="tooltip" title="Color del producto en formato hexadecimal (Usado para el filtro de color en la tienda) Selecciona un color parecido al que escribiste." class="form-control jscolor" id="hexa_prod" name="hexa_prod" value="' . $r[0]["hexa_prod"] . '" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">¿Producto destacado?<b> (Obligatorio)</b>*</label>
                                            ' . $destacado . '
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">¿Producto nuevo?<b> (Obligatorio)</b>*</label>
                                            ' . $nuevo . '
                                        </div>
                                    </div>
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" id="btnEnviar" value="2"><i class="fa fa-floppy-o"></i> Guardar</button>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>
                            </div>';
        } else {
            return '<div class="box" id="text-page">
            <h1>Nuevo producto </h1>

                        <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                            <form action="procesos.php?IDSUBCAT=' . $ideSubcat . '" id="form_productos" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre<b> (Obligatorio)</b>*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre del producto." data-toggle="tooltip" title="Nombre del producto" class="form-control" id="nombre_prod" name="nombre_prod" >
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden<b> (Obligatorio)</b>*</label>
                                            <select data-toggle="tooltip" title="Seleccione el orden de apareción del producto." class="form-control" id="orden_prod" name="orden_prod">
                                                '.secciones::llenarOrden().'
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Tallas y stock<b> (Obligatorio)</b>*</label>
                                            <input type="text" data-toggle="tooltip" title="Formato: 3:NUM,5:NUM,7:NUM,etc ó S:1,M:3,L:2,XL:1" class="form-control" id="talla_prod" name="talla_prod">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="lastname">Fit</label>
                                        <select data-toggle="tooltip" title="Seleccione el Fit del producto." class="form-control" name="fit_prod" id="fit_prod">
                                            '.secciones::llenarFit().'
                                      </select>
                                    </div>
                                </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Lavado</label>
                                            <input type="text" data-toggle="tooltip" title="Ingrese el lavado del producto." class="form-control" id="lavado_prod" name="lavado_prod">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Precio<b> (Obligatorio)</b>*</label>
                                            <input type="text" data-toggle="tooltip" title="Ingrese el precio base del producto, solo la cantidad" class="form-control" id="precio_prod" name="precio_prod">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Precio descuento</label>
                                            <input type="text" data-toggle="tooltip" title="Precio del producto con descuento (Solo si tiene), ingrese solo la cantidad." class="form-control" id="preciodesc_prod" name="preciodesc_prod">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Imagen 1<b> (Obligatorio)</b>*</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen 1 del producto." class="form-control" id="img1_prod" name="img1_prod">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer(' . $img1 . ');"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Imagen 2<b> (Obligatorio)</b>*</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen 2 del producto." class="form-control" id="img2_prod" name="img2_prod">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer(' . $img2 . ');"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Imagen 3</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen 3 del producto (opcional)." class="form-control" id="img3_prod" name="img3_prod">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer(' . $img3 . ');"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Imagen 4</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen 4 del producto (opcional)." class="form-control" id="img4_prod" name="img4_prod">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer(' . $img4 . ');"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Imagen 5</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen 5 del producto (opcional)." class="form-control" id="img5_prod" name="img5_prod">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer(' . $img5 . ');"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Descripción<b> (Obligatorio)</b>*</label>
                                                <textarea data-toggle="tooltip" title="Descripcion del producto." name="descripcion_prod" id="descripcion_prod" rows="10" cols="80">
                                                    
                                                </textarea>
                                                <script>
                                                    window.onload = function(){
                                                    var editor = CKEDITOR.replace( "descripcion_prod" );
                                                    CKFinder.setupCKEditor(editor, "http://jade-vu.com/web/BackEnd/ck/ckfinder/");
                                                    }
                                                </script>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Color predominante de la prenda</label>
                                            <select data-toggle="tooltip" title="Color predominante del producto" class="form-control" id="color_prod" name="color_prod">
                                            '.secciones::llenarColores().'
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Color (Hexadecimal)</label>
                                            <input type="text" data-toggle="tooltip" title="Color del producto en formato hexadecimal (Usado para el filtro de color en la tienda) Selecciona un color parecido al que escribiste." class="form-control jscolor" id="hexa_prod" name="hexa_prod" value="" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">¿Producto destacado?<b> (Obligatorio)</b>*</label>
                                            <select data-toggle="tooltip" title="Seleccione si el producto es destacado o no." class="form-control" name="destacado_prod">
                                                <option value="1">Si</option>
                                                <option value="0" selected>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">¿Producto nuevo?<b> (Obligatorio)</b>*</label>
                                            <select data-toggle="tooltip" title="Seleccione si el producto es nuevo o no." class="form-control" name="nuevo_prod">
                                                <option value="1" selected>Si</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" id="btnEnviar" value="1"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>
                            </div>';
        }
    }

    public function subcategoriasEdit($ide, $ideCategoria)
    {
        if ($ide != "") {
            $r = $this->BD->ConsultaWhereSimple("subcategoria", "ID=?", array(
                $ide
            ));
            return '<div class="box" id="text-page">
            <h1>Editar subcategoria de productos: "' . $r[0]["nombre_subcat"] . '" </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                <form action="procesos.php?IDSUBCAT=' . $r[0]["ID"] . '&IDCATEGORIA=' . $r[0]["ID_categoria"] . '" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre de la subcategoria." class="form-control" id="nombre_subcat" name="nombre_subcat" value="' . $r[0]["nombre_subcat"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden de la subcategoría, mientras mayor sea el número mayor prioridad tendrá." class="form-control" id="orden_subcat" name="orden_subcat" value="' . $r[0]["orden_subcat"] . '">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="2"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>
                            </div>';
        } else {
            return '<div class="box" id="text-page"><h1>Nueva subcategoria de productos </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                <form action="procesos.php?IDCATEGORIA=' . $ideCategoria . '" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre de la subcategoria." class="form-control" id="nombre_subcat" name="nombre_subcat">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden de la subcategoría, mientras mayor sea el número mayor prioridad tendrá." class="form-control" id="orden_subcat" name="orden_subcat">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="1"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>
                            </div>';
        }
    }

    public function categoriasEdit($ide, $ideLinea)
    {
        if ($ide != "") {
            $r = $this->BD->ConsultaWhereSimple("categoria", "ID=?", array(
                $ide
            ));
            return '<div class="box" id="text-page">
            <h1>Editar categoria de productos: "' . $r[0]["nombre_categoria"] . '" </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                <form action="procesos.php?IDCATEGORIA=' . $r[0]["ID"] . '&IDLINEA=' . $r[0]["ID_linea"] . '" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre de la categoría." class="form-control" id="nombre_categoria" name="nombre_categoria" value="' . $r[0]["nombre_categoria"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden de la categoría, mientras mayor sea el número mayor prioridad tendrá." class="form-control" id="orden_categoria" name="orden_categoria" value="' . $r[0]["orden_categoria"] . '">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="2"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>
                            </div>';
        } else {
            return '<div class="box" id="text-page"><h1>Nueva categoria de productos </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                <form action="procesos.php?IDLINEA=' . $ideLinea . '" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre de la categoría." class="form-control" id="nombre_categoria" name="nombre_categoria">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden de la categoría, mientras mayor sea el número mayor prioridad tendrá." class="form-control" id="orden_categoria" name="orden_categoria">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="1"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>
                            </div>';
        }
    }

    public function lineasEdit($ide)
    {
        if ($ide != "") {
            $r = $this->BD->ConsultaWhereSimple("linea", "ID=?", array(
                $ide
            ));
            return '<div class="box" id="text-page">
            <h1>Editar linea de productos: "' . $r[0]["nombre_linea"] . '" </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                <form action="procesos.php?IDLINEA=' . $r[0]["ID"] . '" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre de la línea de productos." class="form-control" id="nombre_linea" name="nombre_linea" value="' . $r[0]["nombre_linea"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden de la línea de productos, mientras mayor sea el número mayor prioridad tendrá." class="form-control" id="orden_linea" name="orden_linea" value="' . $r[0]["orden_linea"] . '">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="2"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>
                            </div>';
        } else {
            return '<div class="box" id="text-page"><h1>Nueva linea de productos </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                <form action="procesos.php" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre de la línea de productos." class="form-control" id="nombre_linea" name="nombre_linea">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden de la línea de productos, mientras mayor sea el número mayor prioridad tendrá." class="form-control" id="orden_linea" name="orden_linea">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="1"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>
                            </div>';
        }
    }

    public function slidermEdit($ide)
    {
        if ($ide != "") {
            $r = $this->BD->ConsultaWhereSimple("marcas_slider", "ID=?", array(
                $ide
            ));
            return '<h1>Editar imagen de slider marcas: "' . $r[0]["nombre_marca"] . '" </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                <form action="procesos.php?IDSLIDERM=' . $r[0]["ID"] . '" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre de la imagen de slider." class="form-control" id="nombre_sliderm" name="nombre_sliderm" value="' . $r[0]["nombre_marca"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden de la imagen de slider, mientras mayor sea el número mayor prioridad tendrá." class="form-control" id="orden_sliderm" name="orden_sliderm" value="' . $r[0]["orden"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Descripcion</label>
                                            <input type="text" data-toggle="tooltip" title="Descripción del slider (opcional)." class="form-control" id="descripcion_sliderm" name="descripcion_sliderm" value="' . $r[0]["descripcion"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Imagen*</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen del slider." class="form-control" id="imagen_sliderm" name="imagen_sliderm" value="' . $r[0]["imagen"] . '">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer();"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Enlace</label>
                                            <input type="text" data-toggle="tooltip" title="Enlace hacia otra página al dar click sobre la imagen (opcional)." class="form-control" id="enlace_sliderm" name="enlace_sliderm" value="' . $r[0]["enlace"] . '">
                                            
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="2"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>';
        } else {
            return '<h1>Nueva imagen de slider marcas </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                <form action="procesos.php" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre de la imagen de slider." class="form-control" id="nombre_sliderm" name="nombre_sliderm">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden de la imagen de slider, mientras mayor sea el número mayor prioridad tendrá." class="form-control" id="orden_sliderm" name="orden_sliderm">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Descripcion</label>
                                            <input type="text" data-toggle="tooltip" title="Descripción del slider (opcional)." class="form-control" id="descripcion_sliderm" name="descripcion_sliderm">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Imagen*</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen del slider." class="form-control" id="imagen_sliderm" name="imagen_sliderm">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer();"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Enlace</label>
                                            <input type="text" data-toggle="tooltip" title="Enlace hacia otra página al dar click sobre la imagen (opcional)." class="form-control" id="enlace_sliderm" name="enlace_sliderm">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="1"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>';
        }
    }

    public function usuariosEdit($ide)
    {
        if ($ide != "") {
            
            $r = $this->BD->ConsultaWhereSimple("usuarios", "ID=?", array($ide));
            print_r($r[0]["vendedor_usuario"]);
            switch ($r[0]["vendedor_usuario"]) {
                case 1:
                    $vendedor = '
                        <select data-toggle="tooltip" title="Define si el usuario es vendedor." class="form-control" name="vendedor_usuario">
                                    <option value="1" selected>Si</option>
                                    <option value="0">No</option>
                                </select>';
                    break;
                case 0:
                    $vendedor = '
                        <select data-toggle="tooltip" title="Define si el usuario es vendedor." class="form-control" name="vendedor_usuario">
                                    <option value="1">Si</option>
                                    <option value="0" selected>No</option>
                                </select>';
                    break;
            }
            return '<h1>Editar usuario: "' . $r[0]["username_usuario"] . '" </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>

                            
                            <form action="procesos.php?IDUSER=' . $r[0]["ID"] . '" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre de Usuario*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre con el que se identificarán a los usuarios." class="form-control" id="username_usuario" name="username_usuario" value="' . $r[0]["username_usuario"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Nombre(s)*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre(s) real(es) del usuario registrado." class="form-control" id="nombres_usuario" name="nombres_usuario" value="' . $r[0]["nombres_usuario"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Apellidos*</label>
                                            <input type="text" data-toggle="tooltip" title="Apellidos del usuario registrado." class="form-control" id="apellidos_usuario" name="apellidos_usuario" value="' . $r[0]["apellidos_usuario"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Correo*</label>
                                            <input type="email" data-toggle="tooltip" title="Correo de contacto del usuario." class="form-control" id="correo__usuario" name="correo_usuario" value="' . $r[0]["correo_usuario"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Contraseña*</label>
                                            <input type="text" data-toggle="tooltip" title="Contraseña del usuario." class="form-control" id="password_usuario" name="password_usuario" value="' . $r[0]["password_usuario"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Dirección</label>
                                            <input type="text" data-toggle="tooltip" title="Dirección de contacto del usuario." class="form-control" id="direccion_usuario" name="direccion_usuario" value="' . $r[0]["direccion_usuario"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Ciudad</label>
                                            <input type="text" data-toggle="tooltip" title="Ciudad" class="form-control" id="ciudad_usuario" name="ciudad_usuario" value="' . $r[0]["ciudad_usuario"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Estado</label>
                                            <input type="text" data-toggle="tooltip" title="Estado" class="form-control" id="estado_usuario" name="estado_usuario" value="' . $r[0]["estado_usuario"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Pais</label>
                                            <input type="text" data-toggle="tooltip" title="País (Por defecto aparecerá México ya que solo contamos con envíos dentro de la república Mexicana)." class="form-control" id="pais_usuario" name="pais_usuario" value="' . $r[0]["pais_usuario"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Codigo postal</label>
                                            <input type="text" data-toggle="tooltip" title="Código Postal." class="form-control" id="codpost_usuario" name="codpost_usuario" value="' . $r[0]["codpost_usuario"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Teléfono</label>
                                            <input type="text" data-toggle="tooltip" title="Teléfono de contacto del usuario." class="form-control" id="telefono_usuario" name="telefono_usuario" value="' . $r[0]["telefono_usuario"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">¿Vendedor?*</label>
                                            ' . $vendedor . '
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Tipo de vendedor</label>
                                            <input type="text" data-toggle="tooltip" title="Describe qué tipo de vendedor es el usuario." class="form-control" id="tipovendedor_usuario" name="tipovendedor_usuario" value="' . $r[0]["tipovendedor_usuario"] . '">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="2"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>';
        } else {
            return '<h1>Nuevo usuario</h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>

                            
                            <form action="procesos.php" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre de Usuario*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre con el que se identificarán a los usuarios." class="form-control" id="username_usuario" name="username_usuario">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Nombre(s)*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre(s) real(es) del usuario registrado." class="form-control" id="nombres_usuario" name="nombres_usuario">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Apellidos*</label>
                                            <input type="text" data-toggle="tooltip" title="Apellidos del usuario registrado." class="form-control" id="apellidos_usuario" name="apellidos_usuario">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Correo*</label>
                                            <input type="email" data-toggle="tooltip" title="Correo de contacto del usuario." class="form-control" id="correo__usuario" name="correo_usuario">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Contraseña*</label>
                                            <input type="text" data-toggle="tooltip" title="Contraseña del usuario." class="form-control" id="password_usuario" name="password_usuario">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Dirección</label>
                                            <input type="text" data-toggle="tooltip" title="Dirección de contacto del usuario." class="form-control" id="direccion_usuario" name="direccion_usuario">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Ciudad</label>
                                            <input type="text" data-toggle="tooltip" title="Ciudad" class="form-control" id="ciudad_usuario" name="ciudad_usuario">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Estado</label>
                                            <input type="text" data-toggle="tooltip" title="Estado" class="form-control" id="estado_usuario" name="estado_usuario">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Pais</label>
                                            <input type="text" data-toggle="tooltip" title="País (Por defecto aparecerá México ya que solo contamos con envíos dentro de la república Mexicana)." class="form-control" id="pais_usuario" name="pais_usuario">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Codigo postal</label>
                                            <input type="text" data-toggle="tooltip" title="Código Postal." class="form-control" id="codpost_usuario" name="codpost_usuario">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Teléfono</label>
                                            <input type="text" data-toggle="tooltip" title="Teléfono de contacto del usuario." class="form-control" id="telefono_usuario" name="telefono_usuario">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">¿Vendedor?*</label>
                                            <select data-toggle="tooltip" title="Define si el usuario es vendedor." class="form-control" name="vendedor_usuario">
                                                <option value="1">Si</option>
                                                <option value="0" selected>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Tipo de vendedor</label>
                                            <input type="text" data-toggle="tooltip" title="Describe qué tipo de vendedor es el usuario." class="form-control" id="tipovendedor_usuario" name="tipovendedor_usuario">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="1"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>';
        }
    }

    public function responsablesEdit($ide)
    {
        if ($ide != "") {
            
            $resultado = $this->BD->ConsultaWhereSimple("usuarios_bcknd", "ID=?", array($ide));
    
            return '<h1>Editar usuario: "' . $resultado[0]["usuario_bcknd"] . '" </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>

                            
                            <form action="procesos.php?IDUSER=' . $resultado[0]["ID"] . '" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre de Usuario*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre con el que se identificarán a los usuarios." class="form-control" id="usuario_bcknd" name="usuario_bcknd" value="' . $resultado[0]["usuario_bcknd"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Nombre(s)*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre(s) real(es) del usuario registrado." class="form-control" id="nombres_bcknd" name="nombres_bcknd" value="' . $resultado[0]["nombres_bcknd"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Apellidos*</label>
                                            <input type="text" data-toggle="tooltip" title="Apellidos del usuario registrado." class="form-control" id="apellidos_bcknd" name="apellidos_bcknd" value="' . $resultado[0]["apellidos_bcknd"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Contraseña*</label>
                                            <input type="text" data-toggle="tooltip" title="Contraseña del usuario." class="form-control" id="password_bcknd" name="password_bcknd" value="' . $resultado[0]["password_bcknd"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Nivel de Autorización</label>
                                            <input type="text" data-toggle="tooltip" title="Nivel de privilegios a dar." class="form-control" id="privilegios_bcknd" name="privilegios_bcknd" value="' . $resultado[0]["privilegios_bcknd"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="2"><i class="fa fa-floppy-o"></i> Guardar</button>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>';
        } else {
            return '<h1>Nuevo usuario</h1>

                            <p class="lead">Asegúrese de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                           
                        <form action="procesos.php" method="post">
                            <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="firstname">Nombre de Usuario*</label>
                                    <input type="text" data-toggle="tooltip" title="Nombre con el que se identificarán a los usuarios." class="form-control" id="usuario_bcknd" name="usuario_bcknd">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="lastname">Nombre(s)*</label>
                                    <input type="text" data-toggle="tooltip" title="Nombre(s) real(es) del usuario registrado." class="form-control" id="nombres_bcknd" name="nombres_bcknd">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="email">Apellidos*</label>
                                    <input type="text" data-toggle="tooltip" title="Apellidos del usuario registrado." class="form-control" id="apellidos_bcknd" name="apellidos_bcknd">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="subject">Contraseña*</label>
                                    <input type="text" data-toggle="tooltip" title="Contraseña del usuario." class="form-control" id="password_bcknd" name="password_bcknd">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="subject">Nivel de Autorización</label>
                                    <input type="text" data-toggle="tooltip" title="Nivel de privilegios a dar." class="form-control" id="privilegios_bcknd" name="privilegios_bcknd">
                                </div>
                            </div>
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary" name="accion" value="1"><i class="fa fa-floppy-o"></i> Guardar</button>
                            </div>
                        </div>
                    <!-- /.row -->
                            </form>';
        }
    }

    public function empresaEdit()
    {
        $r = $this->BD->ConsultaWhereSimple("infoempresa", "ID=?", array(
            1
        ));
        return '<h1>Editar información de la empresa: "' . $r[0]["nombre_empresa"] . '" </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>

                            
                            <form action="procesos.php" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre de la empresa*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre de la empresa." class="form-control" id="nombre_empresa" name="nombre_empresa" value="' . $r[0]["nombre_empresa"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Dirección*</label>
                                            <input type="text" data-toggle="tooltip" title="Dirección." class="form-control" id="direccion_empresa" name="direccion_empresa" value="' . $r[0]["direccion_empresa"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Codigo postal*</label>
                                            <input type="text" data-toggle="tooltip" title="Código Postal." class="form-control" id="cp_empresa" name="cp_empresa" value="' . $r[0]["cp_empresa"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Teléfono*</label>
                                            <input type="text" data-toggle="tooltip" title="Teléfono de contacto." class="form-control" id="telefono_empresa" name="telefono_empresa" value="' . $r[0]["telefono_empresa"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Correo electrónico*</label>
                                            <input type="text"data-toggle="tooltip" title="Correo electrónico de contacto." class="form-control" id="correo_empresa" name="correo_empresa" value="' . $r[0]["correo_empresa"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Facebook</label>
                                            <input type="text" data-toggle="tooltip" title="Enlace hacia la página de Facebook." class="form-control" id="fb_empresa" name="fb_empresa" value="' . $r[0]["facebook_empresa"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Instagram</label>
                                            <input type="text" data-toggle="tooltip" title="Enlace hacia la página de Instagram." class="form-control" id="insta_empresa" name="insta_empresa" value="' . $r[0]["instagram_empresa"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Twitter</label>
                                            <input type="text" data-toggle="tooltip" title="Enlace hacia la página de Twitter." class="form-control" id="twitter_empresa" name="twitter_empresa" value="' . $r[0]["twitter_empresa"] . '">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="2"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>';
    }

    public function bannersEdit($ide)
    {
        if ($ide != "") {
            $r = $this->BD->ConsultaWhereSimple("publicidad", "ID=?", array(
                $ide
            ));
            return '<h1>Editar banner publicitario: "' . $r[0]["nombre"] . '" </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>

                            
                            <form action="procesos.php?IDBANNER=' . $r[0]["ID"] . '" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre del banner publicitario." class="form-control" id="nombre_banner" name="nombre_banner" value="' . $r[0]["nombre"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden del banner publicitario (Entre mayor sea el número, mayor prioridad tendrá)." class="form-control" id="orden_banner" name="orden_banner" value="' . $r[0]["orden"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Imagen*</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen del banner publicitario." class="form-control" id="imagen_banner" name="imagen_banner" value="' . $r[0]["imagen"] . '">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer();"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Enlace</label>
                                            <input type="text" data-toggle="tooltip" title="Enlace hacia otra página al dar click en el banner publicitario." class="form-control" id="enlace_banner" name="enlace_banner" value="' . $r[0]["enlace"] . '">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="2"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>';
        } else {
            return '<h1>Nuevo banner publicitario</h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>

                            
                            <form action="procesos.php" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre del banner publicitario." class="form-control" id="nombre_banner" name="nombre_banner">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden del banner publicitario (Entre mayor sea el número, mayor prioridad tendrá)." class="form-control" id="orden_banner" name="orden_banner">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Imagen*</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen del banner publicitario." class="form-control" id="imagen_banner" name="imagen_banner">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer();"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Enlace</label>
                                            <input type="text" data-toggle="tooltip" title="Enlace hacia otra página al dar click en el banner publicitario." class="form-control" id="enlace_banner" name="enlace_banner">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="1"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>';
        }
    }

    public function preguntasEdit($ide)
    {
        if ($ide != "") {
            $r = $this->BD->ConsultaWhereSimple("preguntas", "ID=?", array(
                $ide
            ));
            return '<h1>Editar pregunta: "' . $r[0]["pregunta"] . '" </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un (*). Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>

                            
                            <form action="procesos.php?IDPREG=' . $r[0]["ID"] . '" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Pregunta*</label>
                                            <input type="text" data-toggle="tooltip" title="Pregunta frecuente (Recuerda usar los signos de interrogación ¿?)." class="form-control" id="pregunta_preguntas" name="pregunta_preguntas" value="' . $r[0]["pregunta"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Respuesta*</label>
                                            
                                            <textarea data-toggle="tooltip" title="Respuesta." name="respuesta_preguntas" id="respuesta_preguntas" rows="10" cols="80">
                                            ' . $r[0]["respuesta"] . '
                                            </textarea>
                                            <script>
                                                // Replace the <textarea id="editor1"> with a CKEditor
                                                // instance, using default configuration.
                                                window.onload = function(){
                                                    var editor = CKEDITOR.replace( "respuesta_preguntas");
                                                    CKFinder.setupCKEditor(editor, {basePath: "http://jade-vu.com/web/BackEnd/ck/ckfinder/"});
                                                }
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden de la pregunta frecuente (Mientras mayor sea el número, más prioridad tendra)." class="form-control" id="orden_preguntas" name="orden_preguntas" value="' . $r[0]["orden"] . '">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="2"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>';
        } else {
            return '<h1>Nueva pregunta </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un (*). Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>

                            
                            <form action="procesos.php" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Pregunta*</label>
                                            <input type="text" data-toggle="tooltip" title="Pregunta frecuente (Recuerda usar los signos de interrogación ¿?)." class="form-control" id="pregunta_preguntas" name="pregunta_preguntas">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Respuesta*</label>
                                            <textarea data-toggle="tooltip" title="Respuesta." name="respuesta_preguntas" id="respuesta_preguntas" rows="10" cols="80">
                                                
                                            </textarea>
                                            <script>
                                                // Replace the <textarea id="editor1"> with a CKEditor
                                                // instance, using default configuration.
                                                window.onload = function(){
                                                    var editor = CKEDITOR.replace( "respuesta_preguntas" );
                                                    CKFinder.setupCKEditor(editor, "http://jade-vu.com/web/BackEnd/ck/ckfinder/");
                                                }
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden de la pregunta frecuente (Mientras mayor sea el número, más prioridad tendra)." class="form-control" id="orden_preguntas" name="orden_preguntas">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="1"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>';
        }
    }

    public function sliderpEdit($ide)
    {
        if ($ide != "") {
            
            $r = $this->BD->ConsultaWhereSimple("slider", "ID=?", array(
                $ide
            ));
            return '<h1>Editar imagen de slider principal: "' . $r[0]["nombre"] . '" </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                <form action="procesos.php?IDSLIDERP=' . $r[0]["ID"] . '" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre del slider principal." class="form-control" id="nombre_sliderp" name="nombre_sliderp" value="' . $r[0]["nombre"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden del slider principal (Entre mayor sea el número, más prioridad tendrá)." class="form-control" id="orden_sliderp" name="orden_sliderp" value="' . $r[0]["orden"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Imagen*</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen del slider principal." class="form-control" id="imagen_sliderp" name="imagen_sliderp" value="' . $r[0]["imagen"] . '">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer();"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Enlace</label>
                                            <input type="text" data-toggle="tooltip" title="Enlace hacia otra página al hacer click." class="form-control" id="enlace_sliderp" name="enlace_sliderp" value="' . $r[0]["enlace"] . '">
                                            
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="2"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>';
        } else {
            return '<h1>Nueva imagen de slider principal </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>
                <form action="procesos.php" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Nombre*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre del slider principal." class="form-control" id="nombre_sliderp" name="nombre_sliderp">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Orden*</label>
                                            <input type="text" data-toggle="tooltip" title="Orden del slider principal (Entre mayor sea el número, más prioridad tendrá)." class="form-control" id="orden_sliderp" name="orden_sliderp">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Imagen*</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen del slider principal." class="form-control" id="imagen_sliderp" name="imagen_sliderp">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer();"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Enlace</label>
                                            <input type="text" data-toggle="tooltip" title="Enlace hacia otra página al hacer click." class="form-control" id="enlace_sliderp" name="enlace_sliderp">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="1"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>';
        }
    }

    public function blogEdit($ide)
    {
        if ($ide != "") {
            $r = $this->BD->ConsultaWhereSimple("blog", "ID=?", array(
                $ide
            ));
            return '<div class="box" id="text-page">
                            <h1>Editar entrada de blog: "' . $r[0]["Titulo_Noticia"] . '" </h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>

                            
                            <form action="procesos.php?IDBLOG=' . $ide . '" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Título de la noticia*</label>
                                            <input type="text" data-toggle="tooltip" title="Título de la noticia." class="form-control" id="titulo_noticia" name="titulo_noticia" value="' . $r[0]["Titulo_Noticia"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Autor*</label>
                                            <input type="text" data-toggle="tooltip" title="Quién redacto la noticia." class="form-control" id="autor_noticia" name="autor_noticia" value="' . $r[0]["Autor"] . '">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Imagen de encabezado*</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen de encabezado referente a la noticia." class="form-control" id="imagen_noticia" name="imagen_noticia" value="' . $r[0]["Encabezado_img"] . '">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer();"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Cuerpo*</label>
                                                <textarea data-toggle="tooltip" title="Cuerpo de la noticia." name="cuerpo" id="cuerpo" rows="10" cols="80">
                                                    ' . $r[0]["Cuerpo"] . '
                                                </textarea>
                                                <script>
                                                    // Replace the <textarea id="editor1"> with a CKEditor
                                                    // instance, using default configuration.
                                                    window.onload = function(){
                                                    var editor = CKEDITOR.replace( "cuerpo" );
                                                    CKFinder.setupCKEditor(editor, "http://jade-vu.com/web/BackEnd/ck/ckfinder/");
                                                    }
                                                </script>
                                        </div>
                                    </div>
                                    

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="2"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>
                            
        </div>';
        } else {
            return '<div class="box" id="text-page">
                            <h1>Nueva entrada de blog</h1>

                            <p class="lead">Asegúrate de llenar los campos obligatorios marcados con un *. Al finalizar los cambios guardalos dando click en el botón "Guardar"</p>

                            
                            <form action="procesos.php" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Título de la noticia*</label>
                                            <input type="text" data-toggle="tooltip" title="Título de la noticia." class="form-control" id="titulo_noticia" name="titulo_noticia">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="lastname">Autor*</label>
                                            <input type="text" data-toggle="tooltip" title="Quién redacto la noticia." class="form-control" id="autor_noticia" name="autor_noticia">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Imagen de encabezado*</label>
                                            <input type="text" data-toggle="tooltip" title="Imagen de encabezado referente a la noticia." class="form-control" id="imagen_noticia" name="imagen_noticia">
                                            <button type="button" class="btn btn-primary" value="Browse Server" onclick="BrowseServer();"><i class="fa fa-cloud-upload "></i> Subir/Buscar</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="subject">Cuerpo*</label>
                                            <textarea data-toggle="tooltip" title="Cuerpo de la noticia." name="cuerpo" id="cuerpo" rows="10" cols="80">
                                            </textarea>
                                                <script>
                                                    // Replace the <textarea id="editor1"> with a CKEditor
                                                    // instance, using default configuration.
                                                    window.onload = function(){
                                                    var editor = CKEDITOR.replace( "cuerpo" );
                                                    CKFinder.setupCKEditor(editor, "http://jade-vu.com/web/BackEnd/ck/ckfinder/");
                                                    }
                                                </script>
                                        </div>
                                    </div>
                                    

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary" name="accion" value="1"><i class="fa fa-floppy-o"></i> Guardar</button>

                                    </div>
                                </div>
                                <!-- /.row -->
                            </form>
                            
        </div>';
        }
    }
    /**
     * METODOS REFERENTES A LAS VENTAS
     */

    public function usuarioDestino($referencia)
    {
        if ($referencia != "") {
            //Checar que campos se van a mostrar y tambien deshabilitar los campos para que solo se muestre la información
            //De ser necesario se pondra una opción para que se rediriga al campo para editar al usuario
            $venta=$this->BD->ConsultaWhereSimple("ventas", "referencia=?", array($referencia));
            $r = $this->BD->ConsultaWhereSimple("usuarios", "ID=?", array($venta[0]["id_usuario"]));
  
            return '<h3>Cliente: "' . $r[0]["nombres_usuario"] . '.' . $r[0]["apellidos_usuario"] . '" </h3>

                            <p class="lead">Para cambiar la información del usuario diríjase al <a href="usuarios.php">administrador de usuarios</a></p>
                            
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="lastname">Nombre(s)*</label>
                                            <input type="text" data-toggle="tooltip" title="Nombre(s) real(es) del usuario registrado." class="form-control" id="nombres_usuario" name="nombres_usuario" value="' . $r[0]["nombres_usuario"] . '" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Apellidos*</label>
                                            <input type="text" data-toggle="tooltip" title="Apellidos del usuario registrado." class="form-control" id="apellidos_usuario" name="apellidos_usuario" value="' . $r[0]["apellidos_usuario"] . '" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="subject">Dirección</label>
                                            <input type="text" data-toggle="tooltip" title="Dirección de contacto del usuario." class="form-control" id="direccion_usuario" name="direccion_usuario" value="' . $r[0]["direccion_usuario"] . '" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="subject">Ciudad</label>
                                            <input type="text" data-toggle="tooltip" title="Ciudad" class="form-control" id="ciudad_usuario" name="ciudad_usuario" value="' . $r[0]["ciudad_usuario"] . '" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="subject">Estado</label>
                                            <input type="text" data-toggle="tooltip" title="Estado" class="form-control" id="estado_usuario" name="estado_usuario" value="' . $r[0]["estado_usuario"] . '" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="subject">Pais</label>
                                            <input type="text" data-toggle="tooltip" title="País (Por defecto aparecerá México ya que solo contamos con envíos dentro de la república Mexicana)." class="form-control" id="pais_usuario" name="pais_usuario" value="' . $r[0]["pais_usuario"] . '" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="subject">Codigo postal</label>
                                            <input type="text" data-toggle="tooltip" title="Código Postal." class="form-control" id="codpost_usuario" name="codpost_usuario" value="' . $r[0]["codpost_usuario"] . '" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="subject">Teléfono</label>
                                            <input type="text" data-toggle="tooltip" title="Teléfono de contacto del usuario." class="form-control" id="telefono_usuario" name="telefono_usuario" value="' . $r[0]["telefono_usuario"] . '" disabled>
                                        </div>
                                    </div>

                                </div>';
                            
        } 
    }

    // SECCIONES DE INSERCIÓN O ACTUALIZACIÓN EN FORMULARIOS
    /**
     * SE ESTA GENERANDO UN ERROR EN EL ORDEN EN QUE SE GUARDAN LOS ARCHIVOS
     */
    public function productosNew($orden, $nombre, $fit, $precio, $img1, $img2, $descripcion, $destacado, $nuevo, $idSubcat, $color = "", $hexa = "", $img3 = "", $img4 = "", $img5 = "", $talla = "", $preciodec = "", $lavado = "")
    {
        $insert = $this->BD->InsertLibre("producto", "ID_subcat, orden_prod, nombre_prod, talla_prod, fit_prod, lavado_prod, precio_prod, preciodesc_prod, img1_prod, img2_prod, img3_prod ,img4_prod ,img5_prod,
                                         descripcion_prod, color_prod, hexa_prod, destacado_prod, nuevo_prod", "'" . $idSubcat . "','" . $orden . "','" . $nombre . "','" . $talla . "','" . $fit . 
                                         "','" . $lavado . "','" . $precio . "','" . $preciodec . "','" . $img1 . "','" . $img2 . "','" . $img3 . "','" . $img4 . "','" . $img5 . "','" . $descripcion . "','" . $color . "','" . 
                                         $hexa . "','" . $destacado . "','" . $nuevo . "'");
        return $insert;
    }

    public function productosUpdate($orden, $nombre, $fit, $precio, $img1, $img2, $descripcion, $destacado, $nuevo, $ide, $color = "", $hexa = "", $img3 = "", $img4 = "", $img5 = "", $talla = "", 
                                    $preciodec = "", $lavado = "")
    {
        $update = $this->BD->UpdateLibre("producto", "orden_prod=?, nombre_prod=?, talla_prod=?, fit_prod=?, lavado_prod=?, precio_prod=?, preciodesc_prod=?, 
                                        img1_prod=?, img2_prod=?, img3_prod=?,img4_prod=?,img5_prod=?, descripcion_prod=?, color_prod=?, hexa_prod=?, destacado_prod=?, nuevo_prod=?"
                                        ,array($orden,$nombre,$talla,$fit,$lavado,$precio,$preciodec,$img1,$img2,$img3,$img4,$img5,$descripcion,$color,$hexa,$destacado,$nuevo,$ide), "ID=?");
        return $update;
    }

    public function subcategoriasNew($nombre, $orden, $ideSubcat)
    {
        $insert = $this->BD->InsertLibre("subcategoria", "ID_categoria, nombre_subcat, orden_subcat ", "'" . $ideSubcat . "','" . $nombre . "','" . $orden . "'");
        return $insert;
    }

    public function subcategoriasUpdate($nombre, $orden, $ide)
    {
        $update = $this->BD->UpdateLibre("subcategoria", "nombre_subcat=?, orden_subcat=?", array(
            $nombre,
            $orden,
            $ide
        ), "ID=?");
        return $update;
    }

    public function categoriasNew($nombre, $orden, $ideLinea)
    {
        $insert = $this->BD->InsertLibre("categoria", "ID_linea, nombre_categoria, orden_categoria ", "'" . $ideLinea . "','" . $nombre . "','" . $orden . "'");
        return $insert;
    }

    public function categoriasUpdate($nombre, $orden, $ide)
    {
        $update = $this->BD->UpdateLibre("categoria", "nombre_categoria=?, orden_categoria=?", array(
            $nombre,
            $orden,
            $ide
        ), "ID=?");
        return $update;
    }

    public function lineasNew($nombre, $orden)
    {
        $insert = $this->BD->InsertLibre("linea", "nombre_linea, orden_linea", "'" . $nombre . "','" . $orden . "'");
        return $insert;
    }

    public function lineasUpdate($nombre, $orden, $ide)
    {
        $update = $this->BD->UpdateLibre("linea", "nombre_linea=?, orden_linea=?", array(
            $nombre,
            $orden,
            $ide
        ), "ID=?");
        return $update;
    }

    public function slidermNew($nombre, $orden, $imagen, $descripcion = "", $enlace = "")
    {
        $insert = $this->BD->InsertLibre("marcas_slider", "nombre_marca, orden, descripcion, imagen, enlace", "'" . $nombre . "','" . $orden . "','" . $descripcion . "','" . $imagen . "','" . $enlace . "'");
        return $insert;
    }

    public function slidermUpdate($nombre, $orden, $ide, $imagen, $descripcion = "", $enlace = "")
    {
        $update = $this->BD->UpdateLibre("marcas_slider", "nombre_marca=?, orden=?, descripcion=?, imagen=?, enlace=?", array(
            $nombre,
            $orden,
            $descripcion,
            $imagen,
            $enlace,
            $ide
        ), "ID=?");
        return $update;
    }

    public function usuariosUpdate($username, $nombres, $apellidos, $correo, $password, $vendedor, $ide, $direccion = "", $ciudad = "", $estado = "", 
                                   $pais = "", $codpost = "", $telefono = "", $tipovendedor = "")
    {
        $update = $this->BD->UpdateLibre("usuarios", "username_usuario=?, nombres_usuario=?, apellidos_usuario=?, correo_usuario=?, password_usuario=?, 
                                          direccion_usuario=?, ciudad_usuario=?, estado_usuario=?, pais_usuario=?, codpost_usuario=?, telefono_usuario=?, 
                                          vendedor_usuario=?, tipovendedor_usuario=?", array(
            $username,$nombres,$apellidos,$correo,$password,$direccion,$ciudad,$estado,$pais,$codpost,$telefono,$vendedor,$tipovendedor,$ide), "ID=?");
        return $update;
    }

    public function usuariosNew($username, $nombres, $apellidos, $correo, $password, $vendedor, $direccion = "", $ciudad = "", $estado = "", $pais = "", 
                                $codpost = "", $telefono = "", $tipovendedor = "")
    {
        $insert = $this->BD->InsertLibre("usuarios", "username_usuario, nombres_usuario, apellidos_usuario, correo_usuario, password_usuario, direccion_usuario,
                                         ciudad_usuario, estado_usuario, pais_usuario, codpost_usuario, telefono_usuario, vendedor_usuario, tipovendedor_usuario", 
                                         "'" . $username . "','" . $nombres . "','" . $apellidos . "','" . $correo . "','" . $password . "','" . $direccion . "',
                                         '" . $ciudad . "','" . $estado . "','" . $pais . "','" . $codpost . "','" . $telefono . "','" . $vendedor . "',
                                         '" . $tipovendedor . "'");
        return $insert;
    }
    /**
     * TERMINAR DE CHECAR ESTOS METODOS
     */
    public function responsableUpdate($username, $nombres, $apellidos, $password, $privilegios,$ide)
    {
        $update = $this->BD->UpdateLibre("usuarios_bcknd", "usuario_bcknd=?, nombres_bcknd=?, apellidos_bcknd=?, password_bcknd=?, privilegios_bcknd=?,activo=?", 
        array($username,$nombres,$apellidos,$password,$privilegios,1,$ide), "ID=?");
        return $update;
    }
    
    public function responsableNew($username, $nombres, $apellidos, $password, $privilegios)
    {
        $insert = $this->BD->InsertLibre("usuarios_bcknd", "usuario_bcknd, nombres_bcknd, apellidos_bcknd, password_bcknd,
                                         privilegios_bcknd", "'" . $username . "','" . $nombres . "','" . $apellidos . "','" . $password . 
                                         "','" . $privilegios . "'");
        return $insert;
    }

    public function empresaUpdate($nombre, $direccion, $cp, $telefono, $correo, $facebook = "", $instagram = "", $twitter = "")
    {
        $update = $this->BD->UpdateLibre("infoempresa", "nombre_empresa=?, direccion_empresa=?, cp_empresa=?, telefono_empresa=?, correo_empresa=?, facebook_empresa=?, instagram_empresa=?, twitter_empresa=?", array(
            $nombre,
            $direccion,
            $cp,
            $telefono,
            $correo,
            $facebook,
            $instagram,
            $twitter
        ), "ID=1");
        return $update;
    }

    public function bannersNew($nombre, $orden, $imagen, $enlace)
    {
        $insert = $this->BD->InsertLibre("publicidad", "nombre, orden, imagen, enlace", "'" . $nombre . "','" . $orden . "','" . $imagen . "','" . $enlace . "'");
        return $insert;
    }

    public function bannersUpdate($nombre, $orden, $imagen, $enlace, $ide)
    {
        $update = $this->BD->UpdateLibre("publicidad", "nombre=?, orden=?, imagen=?, enlace=?", array(
            $nombre,
            $orden,
            $imagen,
            $enlace,
            $ide
        ), "ID=?");
        return $update;
    }

    public function preguntasNew($pregunta, $respuesta, $orden)
    {
        $insert = $this->BD->InsertLibre("preguntas", "pregunta, respuesta, orden", "'" . $pregunta . "','" . $respuesta . "','" . $orden . "'");
        return $insert;
    }

    public function preguntasUpdate($pregunta, $respuesta, $orden, $ide)
    {
        $update = $this->BD->UpdateLibre("preguntas", "pregunta=?, respuesta=?, orden=?", array(
            $pregunta,
            $respuesta,
            $orden,
            $ide
        ), "ID=?");
        return $update;
    }

    public function sliderpNew($nombre, $orden, $imagen, $enlace)
    {
        $insert = $this->BD->InsertLibre("slider", "nombre, orden, imagen, enlace", "'" . $nombre . "','" . $orden . "','" . $imagen . "','" . $enlace . "'");
        return $insert;
    }

    public function sliderpUpdate($nombre, $orden, $imagen, $enlace, $ide)
    {
        $update = $this->BD->UpdateLibre("slider", "nombre=?, orden=?, imagen=?, enlace=?", array(
            $nombre,
            $orden,
            $imagen,
            $enlace,
            $ide
        ), "ID=?");
        return $update;
    }

    public function blogNew($titulo, $autor, $img, $cuerpo)
    {
        $insert = $this->BD->InsertLibre("blog", "Titulo_Noticia, Autor, Encabezado_img, Cuerpo, Fecha", "'" . $titulo . "','" . $autor . "','" . $img . "','" . $cuerpo . "',CURDATE()");
        return $insert;
    }

    public function blogUpdate($titulo, $autor, $img, $cuerpo, $ide)
    {
        $update = $this->BD->UpdateLibre("blog", "Titulo_Noticia=?, Autor=?, Encabezado_img=?, Cuerpo=?", array(
            $titulo,
            $autor,
            $img,
            $cuerpo,
            $ide
        ), "ID=?");
        return $update;
    }

    public function delete($tabla, $ide)
    {
        $update = $this->BD->UpdateLibre($tabla, "activo=?", array(0,$ide), "ID=?");
        return $update;
    }

    public function llenarOrden(){
        $valor='';
        for($i=1;$i<=20;$i++){
            $valor.= '<option value="'.$i.'">'.$i.'</option>';
        }
        return $valor;
    }

    public function llenarOrdenSelected($registro){
        $valor='';
        for($i=1;$i<=20;$i++){
            if($registro==$i){
                $valor.= '<option value="'.$i.'" selected>'.$i.'</option>';
            }else{
                $valor.= '<option value="'.$i.'">'.$i.'</option>';
            }
            
        }
        return $valor;
    }
    
      public function llenarFit(){
        $valor='';
        $fit=array("Skinny","Slim","Bootcut","Capry","Shomt","Skirt","Slim Jacket","Regular Jacket","Relax","Loose");
        $cantidad=count($fit);
        for($i=0;$i<$cantidad;$i++){
        $valor.= '<option value="'.$fit[$i].'">'.$fit[$i].'</option>';
     }
     return $valor;
    }

    public static function llenarFitSelected($registro){
        $valor='';
        $fit=array("Skinny","Slim","Bootcut","Capry","Shomt","Skirt","Slim Jacket","Regular Jacket","Relax","Loose");
        $cantidad=count($fit);
        for($i=0;$i<$cantidad;$i++){
            if($registro==$fit[$i]){
                $valor.= '<option value="'.$fit[$i].'" selected>'.$fit[$i].'</option>';
            }else{
                $valor.= '<option value="'.$fit[$i].'">'.$fit[$i].'</option>';
            }
     }
     return $valor;
    }

      public static function llenarColores(){
        $valor='';
        $colores=array("Amarillo","Azul","Azul","Azul turquesa","Azul marino","Arena","Beige",
                       "Blanco","Cyan","Gris","Magenta","Naranja","Negro","Rojo","Rosa","Verde");
        $cantidad=count($colores);
        for($i=0;$i<$cantidad;$i++){
            $valor.= '<option value="'.$colores[$i].'">'.$colores[$i].'</option>';
     }
     return $valor;
    }

    public static function llenarColoresSelected($registro){
        $valor='';
        $colores=array("Amarillo","Azul","Azul","Azul turquesa","Azul marino","Arena","Beige",
                       "Blanco","Cyan","Gris","Magenta","Naranja","Negro","Rojo","Rosa","Verde");
        $cantidad=count($colores);
        for($i=0;$i<$cantidad;$i++){
            if($registro==$colores[$i]){
                $valor.= '<option value="'.$colores[$i].'" selected>'.$colores[$i].'</option>';
            }else{
                $valor.= '<option value="'.$colores[$i].'">'.$colores[$i].'</option>';
            }
            
     }
     return $valor;
    }
}