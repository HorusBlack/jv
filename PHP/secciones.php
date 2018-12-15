<?php
/**
 * Sesion_start permite el uso de la variable superglobal $_SESSION la cual permite que los datos que reciba y almacene sean usados por
 * otros archivos que se encuentren dentro de su alcance
 */
/**
 * Correccion para evitar el error
 */
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

class secciones
{

    // BD es una instancia de la BD
    private $BD;
    public function __construct(BD $BD)
    {
        $this->BD = $BD;
    }

    public function BD()
    {
        return $this->BD;
    }

    public function sesionCarrito()
    {
        $_SESSION["carritoJDV"] = [];
    }

   /**
    * Metodo que recorre las tallas de los productos, y retorna el el numero de productos
    *de una determinada talla
    */
    public function consultarStock($prod, $talla)
    {
        $AregTallas = explode(',', trim($prod[0]["talla_prod"]));
        foreach ($AregTallas as $vuelta) {
            $tall = explode(':', trim($vuelta));
            if ($tall[0] == $talla && $tall[1] != "") {
                return (int) $tall[1];
            }
        }
    }

    /**
     * Posiblemente este metodo se uso para modicar el stock
     */
    public function quitarStock($id, $talla, $cantidad)
    {
        $stock = $this->BD->ConsultaLibre("talla_prod", "producto", "activo=? AND ID=?", array(1,$id));
      //  print_r($stock[0]["talla_prod"]);
        $tallastock = explode(',', trim($stock[0]["talla_prod"]));
        $nuevostock = '';
        foreach ($tallastock as $vuelta) {
            $tall = explode(':', trim($vuelta));
            if ($tall[0] == $talla) {
                $new = (string) (((int) $tall[1]) - $cantidad);
                $nuevostock .= "," . $tall[0] . ":" . $new;
            } else {
                $nuevostock .= "," . $tall[0] . ":" . $tall[1];
            }
        }
        return ltrim($nuevostock, ',');
    }

    public function actualizarStockProd($id, $tallas)
    {
        if ($this->BD->updateStock($id, $tallas)) {
            return true;
        } else {
            return false;
        }
    }

    // EDITADO
    public function addProd($id, $talla, $cantidad = "")
    {
        $id = (int) $id;
        $talla = filter_var($talla, FILTER_SANITIZE_STRING);
        $cantidad = (int) $cantidad;
        
        if (!isset($_SESSION["carritoJDV"])) {
            $this->sesionCarrito();
        }
        
        $prod = $this->BD->ConsultaWhereSimple("producto", "activo=? AND ID=?", array(1,$id));
        $stock = $this->consultarStock($prod, $talla);
        
        if ($prod != "") {
            $existencia = false;
            foreach ($_SESSION["carritoJDV"] as $key => $value) { // RECORRE CADA PRODUCTO DEL ARRAY DEL CARRITO
                                                                  // AGREGAR PRODUCTO AL CARRITO DESDE EL BOTÓN "AGREGAR A CARRITO"
                if ($value[0] == $id && $cantidad <= 0 && $value[2] == $talla) {
                    $existencia = true;
                    if ($value[1] < $stock) { // LA CANTIDAD NO SOBREPASA AL STOCK
                        $_SESSION["carritoJDV"][$key][1] = $_SESSION["carritoJDV"][$key][1] + 1;
                     
                    } else { // LA CANTIDAD SOBREPASA AL STOCK
                        $_SESSION["carritoJDV"][$key][1] = $stock;

                    }
                    // AGREGAR PRODUCTO AL CARRITO DESDE EL SNIPER DEL CARRITO.PHP
                } else if ($value[0] == $id && $cantidad > 0 && $value[2] == $talla) {
                    $existencia = true;
                    if ($cantidad <= $stock) { // lA CANTIDAD NO SOBREPASA AL STOCK
                        $_SESSION["carritoJDV"][$key][1] = $cantidad;
                     
                    } else { // LA CANTIDAD SOBREPASA AL STOCK
                        $_SESSION["carritoJDV"][$key][1] = $stock;
                  
                    }
                }
            }

            unset($value);
            if (! $existencia) { // NO EXISTE EL PRODUCTO EN EL CARRITO DE COMPRAS
                $_SESSION["carritoJDV"][] = array($prod[0]["ID"],1,$talla);
            }
        }
    }

    public function deleteProd($id, $talla)
    {
        if (isset($_SESSION["carritoJDV"])) {
            foreach ($_SESSION["carritoJDV"] as $key => $value) {
                if ($value[0] == $id && $value[2] == $talla) {
                    unset($_SESSION["carritoJDV"][$key]);
                }
            }
            unset($value);
        }
    }

    public function contarProdsCarrito()
    {
        $total = 0;
        if (isset($_SESSION["carritoJDV"])) {
            foreach ($_SESSION["carritoJDV"] as $contenido) {
                $total += $contenido[1];
            }
        }
        return $total;
    }

    public function alerta($mensaje)
    {
        echo "<script>alert('" . $mensaje . "');</script>";
    }

    public function redireccionarJS($pagina)
    {
        echo "<script>location.href='" . $pagina . "'</script>";
    }

    // SECCIONES DE SESIÓN DE USUARIO 16% iva
    /**
     * Crear un nuevo metodo de autentificacion
     */
    public function autentificar($correo, $pass)
    {
        $md5='';
        if($pass!=''){
            $passwrd = $this->BD->ConsultaLibre("password_usuario", "usuarios", "correo_usuario=? AND activo=?", array($correo,1), 1);
            $md5=hash('MD5',$pass);
        //$result = password_verify($pass, $passwrd[0]["password_usuario"]);
        if ($passwrd[0]["password_usuario"]==$md5) {
            //Si la contraseña es verdadera
            $user = $this->BD->ConsultaLibre("ID, username_usuario, nombres_usuario, apellidos_usuario, correo_usuario, direccion_usuario, ciudad_usuario, estado_usuario, pais_usuario, codpost_usuario, telefono_usuario, vendedor_usuario, tipovendedor_usuario", "usuarios", "activo=? AND correo_usuario=?", 
            array(1,$correo), 1);
            //La sesion apunta a un usuario
            $_SESSION["usuarioJDV"] = $user[0];
            return true;
        } else {
            return false;
        }
        }
       
    }

    public function refershuser($correo)
    {
        $user = $this->BD->ConsultaLibre("*", "usuarios", "correo_usuario=?", array(
            $correo
        ), 1);
        if ($user[0] != "") {
            $_SESSION["usuarioJDV"] = $user[0];
            return true;
        } else {
            return false;
        }
    }

    public function sesionIniciada()
    {
        return (isset($_SESSION["usuarioJDV"]));
    }

    public function validarSesion()
    {
        if (! $this->sesionIniciada()) {
            $this->alerta("Inicia sesión para poder acceder a esta página");
            $this->redireccionarJS("index.php");
        }
    }

    public function registro($username, $nombres, $apellidos, $correo, $password, $vendedor = 0)
    {
        $registro = $this->BD->InsertLibrereg("usuarios", "username_usuario, nombres_usuario, apellidos_usuario, correo_usuario, password_usuario, vendedor_usuario", "'" . $username . "','" . $nombres . "','" . $apellidos . "','" . $correo . "','" . $password . "','" . $vendedor . "'");
        if ($registro == "yes") {
            return "true";
        } else {
            return $registro->errorInfo();
        }
    }

    // SECCIONES DE CONSULTA ÚNICA
    // EDITADO
    public function infoEmpresa()
    {
        $r = $this->BD->ConsultaWhereSimple("infoempresa", "ID=?", array(
            1
        ));
        return $r;
    }

    // SECCIONES PARA TODAS LAS PÁGINAS
    public function libreriasHead()
    {
        return '<head>

        <meta charset="utf-8">
        <meta name="google-site-verification" content="kNYMTEyFtg-H1r8HPsodW6V6zs8D-VXvBXl6IyKY7Zc" />
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
        <!--iconos-->
        
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/animate.min.css" rel="stylesheet">
        <link href="css/owl.carousel.css" rel="stylesheet">
        
        <link href="css/owl.theme.css" rel="stylesheet">
        <link href="css/estilos.css" rel="stylesheet">
        <link href="slider/bootstrap-touch-slider.css" rel="stylesheet">

        <!-- theme stylesheet -->
        <link href="css/style.blue.css" rel="stylesheet" id="theme-stylesheet">

        <!-- your stylesheet with modifications -->
        <link href="css/custom.css" rel="stylesheet">

        <script src="js/respond.min.js"></script>


        <link rel="shortcut icon" href="favicon.png">



    </head>';
    }

    
    // SECCIONES PARA TODAS LAS PÁGINAS
    public function libreriasHeadPayu()
    {
        return '<head>

        <meta charset="utf-8">
        <meta name="google-site-verification" content="kNYMTEyFtg-H1r8HPsodW6V6zs8D-VXvBXl6IyKY7Zc" />
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
        <!--iconos-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/animate.min.css" rel="stylesheet">
        <link href="../css/owl.carousel.css" rel="stylesheet">
        
        <link href="../css/owl.theme.css" rel="stylesheet">
        <link href="../css/estilos.css" rel="stylesheet">
        <link href="../slider/bootstrap-touch-slider.css" rel="stylesheet">

        <!-- theme stylesheet -->
        <link href="../css/style.blue.css" rel="stylesheet" id="theme-stylesheet">

        <!-- your stylesheet with modifications -->
        <link href="../css/custom.css" rel="stylesheet">

        <script src="../js/respond.min.js"></script>

        <link rel="shortcut icon" href="../favicon.png">



    </head>';
    }

    // EDITADO
    public function topBar()
    {
        $contacto = $this->infoEmpresa();
        if (! $this->sesionIniciada()) {
            return '<div class="container">
                <div class="col-md-6 offer" data-animate="fadeInDown">
                          <a href="#"><i class=" fa fa-phone-square"></i>' . $contacto[0]["telefono_empresa"] . '</a>
                </div>
                <div class="col-md-6" data-animate="fadeInDown">
                    <ul class="menu">
                        <li><a href="#" data-toggle="modal" data-target="#login-modal">Iniciar Sesión</a>
                       
                        </li>
                        <li><a href="register.php">Registrarse</a>
                        </li>
                    </ul>
                </div>
            </div>';
        } else {
            return '<div class="container">
                <div class="col-md-6 offer" data-animate="fadeInDown">
                          <a href="#"><i class=" fa fa-phone-square"></i>' . $contacto[0]["telefono_empresa"] . '</a>
                </div>
                <div class="col-md-6" data-animate="fadeInDown">
                    <ul class="menu">
                        <li style="color: #FFF">¡Bienvenido <strong>' . $_SESSION["usuarioJDV"]["username_usuario"] . '</strong>!
                        </li>
                        <li><a href="profile.php?ID=1">Mi perfil</a>
                        </li>
                        <li><a href="salir.php">Cerrar Sesion</a>
                        </li>
                    </ul>
                </div>
            </div>';
        }
    }
      // NUEVO PAYU
      public function topBarPayu()
      {
          $contacto = $this->infoEmpresa();
          if (! $this->sesionIniciada()) {
              return '<div class="container">
                  <div class="col-md-6 offer" data-animate="fadeInDown">
                            <a href="#"><i class=" fa fa-phone-square"></i>' . $contacto[0]["telefono_empresa"] . '</a>
                  </div>
                  <div class="col-md-6" data-animate="fadeInDown">
                      <ul class="menu">
                          <li><a href="#" data-toggle="modal" data-target="#login-modal">Iniciar Sesión</a>
                         
                          </li>
                          <li><a href="../register.php">Registrarse</a>
                          </li>
                      </ul>
                  </div>
              </div>';
          } else {
              return '<div class="container">
                  <div class="col-md-6 offer" data-animate="fadeInDown">
                            <a href="#"><i class=" fa fa-phone-square"></i>' . $contacto[0]["telefono_empresa"] . '</a>
                  </div>
                  <div class="col-md-6" data-animate="fadeInDown">
                      <ul class="menu">
                          <li style="color: #FFF">¡Bienvenido <strong>' . $_SESSION["usuarioJDV"]["username_usuario"] . '</strong>!
                          </li>
                          <li><a href="../profile.php?ID=1">Mi perfil</a>
                          </li>
                          <li><a href="../salir.php">Cerrar Sesion</a>
                          </li>
                      </ul>
                  </div>
              </div>';
          }
      }

    /**
     *Metodo de inicio de sesión de los usuarios 
     */
    public function modalLogin()
    {
        return '<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
                <div class="modal-dialog modal-sm">

                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="Login">Iniciar Sesión</h4>
                        </div>
                        <div class="modal-body">
                            <form action="procesos.php" method="post" id="form_session_user">
                                <p><strong>Correo electrónico</strong></p>
                                <div class="form-group">
                                    <input name="correo" type="text" class="form-control" id="correo" placeholder="Correo electrónico">
                                </div>
                                <p><strong>Contraseña</strong></p>
                                <div class="form-group">
                                    <input name="password" type="password" class="form-control" id="password" placeholder="Contraseña">
                                </div>

                                <p class="text-center">
                                    <input name="submit" value="Iniciar Sesión" class="btn btn-primary" type="submit">
                                </p>

                            </form>

                            <p class="text-center text-muted">¿Aún no tienes una cuenta?</p>
                            <p class="text-center text-muted"><a href="recoveryPass.php"><strong>Olvidaste tu contraseña</strong></a></p>
                            <p class="text-center text-muted">¡<a href="register.php"><strong>Registrate ahora</strong></a>! Es fácil y no lleva más de 1 minuto, tendrás acceso a compras y lista de deseos.</p>

                        </div>
                    </div>
                </div>
            </div>';
    }

    public function modalLoginPayu()
    {
        return '<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
                <div class="modal-dialog modal-sm">

                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="Login">Iniciar Sesión</h4>
                        </div>
                        <div class="modal-body">
                            <form action="../procesos.php" method="post">
                                <p><strong>Correo electrónico</strong></p>
                                <div class="form-group">
                                    <input name="correo" type="text" class="form-control" id="correo" placeholder="Correo electrónico">
                                </div>
                                <p><strong>Contraseña</strong></p>
                                <div class="form-group">
                                    <input name="password" type="password" class="form-control" id="password" placeholder="Contraseña">
                                </div>

                                <p class="text-center">
                                    <input name="submit" value="Iniciar Sesión" class="btn btn-primary" type="submit">
                                </p>

                            </form>

                            <p class="text-center text-muted">¿Aún no tienes una cuenta?</p>
                            <p class="text-center text-muted"><a href="recoveryPass.php"><strong>Olvide mi contraseña?</strong></a></p>
                            <p class="text-center text-muted">¡<a href="../register.php"><strong>Registrate ahora</strong></a>! Es fácil y no lleva más de 1 minuto, tendrás acceso a compras y lista de deseos.</p>

                        </div>
                    </div>
                </div>
            </div>';
    }

    // EDITADO
    public function subCategoriasMenu($idcategoria)
    {
        $r = $this->BD->ConsultaLibre("subcategoria.ID AS subid, nombre_subcat", "subcategoria, categoria", "subcategoria.activo=? AND categoria.ID=subcategoria.ID_categoria AND subcategoria.ID_categoria=? ORDER BY orden_subcat DESC", array(
            1,$idcategoria), 10);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<li><a href="category.php?cat=' . $contenido["subid"] . '">' . $contenido["nombre_subcat"] . '</a>
                                                    </li>';
        }
        return $i;
    }

    public function subCategoriasMenuPayu($idcategoria)
    {
        $r = $this->BD->ConsultaLibre("subcategoria.ID AS subid, nombre_subcat", "subcategoria, categoria", "subcategoria.activo=? AND categoria.ID=subcategoria.ID_categoria AND subcategoria.ID_categoria=? ORDER BY orden_subcat DESC", array(
            1,$idcategoria), 10);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<li><a href="../category.php?cat=' . $contenido["subid"] . '">' . $contenido["nombre_subcat"] . '</a>
                                                    </li>';
        }
        return $i;
    }

    // EDITADO
    public function categoriasMenu($idLinea)
    {
        $r = $this->BD->ConsultaLibre("categoria.ID AS catid, nombre_categoria", "categoria, linea", "categoria.activo=? AND categoria.ID_linea=linea.ID AND categoria.ID_linea=? ORDER BY orden_categoria ASC", array(
            1,$idLinea), 5);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<div class="col-sm-3">
                                                <h5>' . $contenido["nombre_categoria"] . '</h5>
                                                <ul>
                                                    ' . $this->subcategoriasmenu($contenido["catid"]) . '
                                                </ul>
                                            </div>';
        }
        return $i;
    }

    public function categoriasMenuPayu($idLinea)
    {
        $r = $this->BD->ConsultaLibre("categoria.ID AS catid, nombre_categoria", "categoria, linea", "categoria.activo=? AND categoria.ID_linea=linea.ID AND categoria.ID_linea=? ORDER BY orden_categoria ASC", array(
            1,$idLinea), 5);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<div class="col-sm-3">
                                                <h5>' . $contenido["nombre_categoria"] . '</h5>
                                                <ul>
                                                    ' . $this->subCategoriasMenuPayu($contenido["catid"]) . '
                                                </ul>
                                            </div>';
        }
        return $i;
    }

    public function prodRand($idLinea)
    {
        $r = $this->BD->ConsultaLibre("producto.img1_prod AS IMG, producto.ID AS IDPROD, producto.nombre_prod as NOMBRE, producto.precio_prod AS PRECIO, producto.preciodesc_prod AS PRECIODESC", "producto, subcategoria, categoria, linea", "producto.activo=? AND producto.ID_subcat=subcategoria.ID AND subcategoria.ID_categoria=categoria.ID AND categoria.ID_linea=linea.ID AND linea.ID=? ORDER BY RAND()", array(
            1,$idLinea), 3);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<div class="col-sm-3 hidden-sm hidden-xs text-center" style="background-color: #f0f0f0; border: 1px solid #e0e0e0; border-radius: 3px;">' . '<a href="detail.php?id=' . $contenido["IDPROD"] . '">' . '<img src="' . $contenido["IMG"] . '" alt="producto" style="height: 180px; border: 1px solid; border-radius: 3px;"> <br>' . '<b>' . $contenido["NOMBRE"] . '</b>' . '<br>' . ($contenido["PRECIODESC"] == "0" ? '$' . $contenido["PRECIO"] : '<del>$' . $contenido["PRECIO"] . '</del> $' . $contenido["PRECIODESC"]) . '</a>' . '</div>';
        }
        return $i;
    }

    public function prodRandPayu($idLinea)
    {
        $r = $this->BD->ConsultaLibre("producto.img1_prod AS IMG, producto.ID AS IDPROD, producto.nombre_prod as NOMBRE, producto.precio_prod AS PRECIO, producto.preciodesc_prod AS PRECIODESC", "producto, subcategoria, categoria, linea", "producto.activo=? AND producto.ID_subcat=subcategoria.ID AND subcategoria.ID_categoria=categoria.ID AND categoria.ID_linea=linea.ID AND linea.ID=? ORDER BY RAND()", array(
            1,$idLinea), 3);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<div class="col-sm-3 hidden-sm hidden-xs text-center" style="background-color: #f0f0f0; border: 1px solid #e0e0e0; border-radius: 3px;">' . '<a href="../detail.php?id=' . $contenido["IDPROD"] . '">' . '<img src="' . $contenido["IMG"] . '" alt="producto" style="height: 180px; border: 1px solid; border-radius: 3px;"> <br>' . '<b>' . $contenido["NOMBRE"] . '</b>' . '<br>' . ($contenido["PRECIODESC"] == "0" ? '$' . $contenido["PRECIO"] : '<del>$' . $contenido["PRECIO"] . '</del> $' . $contenido["PRECIODESC"]) . '</a>' . '</div>';
        }
        return $i;
    }
  
    public function lineasMenu()
    {
        $r = $this->BD->ConsultaMultiplesRegistros("linea", "activo=? ORDER BY orden_linea ASC ", array(
            1), 5);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<li class="dropdown yamm-fw">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200">' . $contenido["nombre_linea"] . ' <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="yamm-content">
                                        <div class="row">
                                            ' . $this->categoriasmenu($contenido["ID"]) . '
                                            ' . $this->prodRand($contenido["ID"]) . '
                                        </div>
                                    </div>
                                    <!-- /.yamm-content -->
                                </li>
                            </ul>
                        </li>';
        }
        return $i;
    }

    public function lineasMenuPayu()
    {
        $r = $this->BD->ConsultaMultiplesRegistros("linea", "activo=? ORDER BY orden_linea ASC ", array(
            1), 5);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<li class="dropdown yamm-fw">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200">' . $contenido["nombre_linea"] . ' <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="yamm-content">
                                        <div class="row">
                                            ' . $this->categoriasMenuPayu($contenido["ID"]) . '
                                            ' . $this->prodRandPayu($contenido["ID"]) . '
                                        </div>
                                    </div>
                                    <!-- /.yamm-content -->
                                </li>
                            </ul>
                        </li>';
        }
        return $i;
    }

    public function menu()
    {   
        /**
         * Pendiente contar el no. de articulos, para volver el COSTO DEL ENVIO dinamico
         */
        $total = 0;
        $total_r=0;
        $envioNormal=99;
        $envioSobrePeso=115;
        $numeroArticulos=0;
        if (isset($_SESSION["carritoJDV"])) {
            foreach ($_SESSION["carritoJDV"] as $contenido) {
                $prod = $this->BD->ConsultaWhereSimple("producto", "activo=? AND ID=?", array(1,$contenido[0]));
                ($prod[0]["preciodesc_prod"] > 0 ? ($total = $total + $prod[0]["preciodesc_prod"] * $contenido[1]) : ($total = $total + $prod[0]["precio_prod"] * $contenido[1]));

            }
            $numCarrito=(count($_SESSION["carritoJDV"]));
            for ($i=0; $i<$numCarrito; $i++) {
    			$numPiezas=$_SESSION["carritoJDV"][$i][1];
                $numeroArticulos+=$numPiezas;
             }
            if($numeroArticulos<=5){
                $total_r=round($total+($total * 0.16)+$envioNormal,0,PHP_ROUND_HALF_UP);
            }else{
                $total_r=round($total+($total * 0.16)+$envioSobrePeso,0,PHP_ROUND_HALF_UP);
            }
        }
        return '<div class="navbar navbar-default yamm" role="navigation" id="navbar" style="margin-bottom: 0px;">
            <div class="container">
                <div class="navbar-header">

                    <a class="" href="index.php" data-animate-hover="bounce">
                        <img id="idJade" src="img/LOGOJV2.png" alt="Obaju logo" class="hidden-xs">
                        <span class="sr-only">JADE-VÜ - go to homepage</span>
                    </a>
                    <div class="navbar-buttons">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">
                            <span class="sr-only">Toggle navigation</span>
                            <i class="fa fa-align-justify"></i>
                        </button>
                        <a class="btn btn-default navbar-toggle" href="basket.php">
                            <i class="fa fa-shopping-cart"></i>  <span class="hidden-xs">Carrito</span>
                        </a>
                    </div>
                </div>
                <!--/.navbar-header -->

                <div class="navbar-collapse collapse" id="navigation">

                    <ul class="nav navbar-nav navbar-left">
                        <li><a href="index.php">Inicio</a>
                        </li>
                        ' . $this->lineasmenu() . '

                        <li class="yamm-fw">
                            <a href="blog.php?pag=1">Blog</a>
                        
                        </li>

                        <li class="yamm-fw">
                            <a href="contact.php">Contacto</a>
                        
                        </li>
                        <li class="yamm-fw">
                            <a href="faq.php">Preguntas Frecuentes</a>
                        
                        </li>
                    </ul>

                </div>
                <!--/.nav-collapse -->

                <div class="navbar-buttons">

                    <div class="navbar-collapse collapse right" id="basket-overview">
                        <a href="basket.php" class="btn btn-primary navbar-btn"><i class="fa fa-shopping-cart"></i><span class="hidden-sm">Carrito ($' . $total_r . ')</span></a>
                    </div>
                    <!--/.nav-collapse -->

                </div>

                
                <!--/.nav-collapse -->

            </div>
            <!-- /.container -->
        </div>';
    }
    /**
     * Exclusivo para payu
     */
    public function menuPayu()
    {
        $total = 0;
        $total_r=0;
        $envioNormal=99;
        $envioSobrePeso=115;
        $numeroArticulos=0;
        if (isset($_SESSION["carritoJDV"])) {
            foreach ($_SESSION["carritoJDV"] as $contenido) {
                $prod = $this->BD->ConsultaWhereSimple("producto", "activo=? AND ID=?", array(1,$contenido[0]));
                ($prod[0]["preciodesc_prod"] > 0 ? ($total = $total + $prod[0]["preciodesc_prod"] * $contenido[1]) : ($total = $total + $prod[0]["precio_prod"] * $contenido[1]));

            }
            $numCarrito=(count($_SESSION["carritoJDV"]));
            for ($i=0; $i<$numCarrito; $i++) {
    			$numPiezas=$_SESSION["carritoJDV"][$i][1];
                $numeroArticulos+=$numPiezas;
             }
            if($numeroArticulos<=5){
                $total_r=round($total+($total * 0.16)+$envioNormal,0,PHP_ROUND_HALF_UP);
            }else{
                $total_r=round($total+($total * 0.16)+$envioSobrePeso,0,PHP_ROUND_HALF_UP);
            }
        }
        return '<div class="navbar navbar-default yamm" role="navigation" id="navbar" style="margin-bottom: 0px;">
            <div class="container">
                <div class="navbar-header">

                    <a class="" href="../index.php" data-animate-hover="bounce">
                        <img id="idJade" src="../img/LOGOJV2.png" alt="Obaju logo" class="hidden-xs">
                        <span class="sr-only">JADE-VÜ - go to homepage</span>
                    </a>
                    <div class="navbar-buttons">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">
                            <span class="sr-only">Toggle navigation</span>
                            <i class="fa fa-align-justify"></i>
                        </button>
                        <a class="btn btn-default navbar-toggle" href="../basket.php">
                            <i class="fa fa-shopping-cart"></i>  <span class="hidden-xs">Carrito</span>
                        </a>
                    </div>
                </div>
                <!--/.navbar-header -->

                <div class="navbar-collapse collapse" id="navigation">

                    <ul class="nav navbar-nav navbar-left">
                        <li><a href="../index.php">Inicio</a>
                        </li>
                        ' . $this->lineasMenuPayu() . '

                        <li class="yamm-fw">
                            <a href="../blog.php?pag=1">Blog</a>
                        
                        </li>

                        <li class="yamm-fw">
                            <a href="../contact.php">Contacto</a>
                        
                        </li>
                        <li class="yamm-fw">
                            <a href="../faq.php">Preguntas Frecuentes</a>
                        
                        </li>
                    </ul>

                </div>
                <!--/.nav-collapse -->

                <div class="navbar-buttons">

                    <div class="navbar-collapse collapse right" id="basket-overview">
                        <a href="../basket.php" class="btn btn-primary navbar-btn"><i class="fa fa-shopping-cart"></i><span class="hidden-sm">Carrito ($' . $total_r . ')</span></a>
                    </div>
                    <!--/.nav-collapse -->

                </div>

                
                <!--/.nav-collapse -->

            </div>
            <!-- /.container -->
        </div>';
    }

    public function resumenCompra()
    {
        $total = 0;
        $total_r=0;
        $envio=0;
        $envioNormal=99;
        $envioSobrePeso=115;
        $numeroArticulos=0;
        $costo=0;
        $iva=0;
        if (isset($_SESSION["carritoJDV"])) {
            foreach ($_SESSION["carritoJDV"] as $contenido) {
                $prod = $this->BD->ConsultaWhereSimple("producto", "activo=? AND ID=?", array(1,$contenido[0]));
                ($prod[0]["preciodesc_prod"] > 0 ? ($total = $total + $prod[0]["preciodesc_prod"] * $contenido[1]) : ($total = $total + $prod[0]["precio_prod"] * $contenido[1]));

            }
            $costo=round($total, 0, PHP_ROUND_HALF_UP);
            $iva=round($costo*0.16, 0, PHP_ROUND_HALF_UP);
            $numCarrito=(count($_SESSION["carritoJDV"]));

            for ($i=0; $i<$numCarrito; $i++) {
    			$numPiezas=$_SESSION["carritoJDV"][$i][1];
                $numeroArticulos+=$numPiezas;
             }
            if($numeroArticulos<=5){
                $envio=$envioNormal;
                $total_r=round($total+($total * 0.16)+$envioNormal,0,PHP_ROUND_HALF_UP);
            }else{
                $envio=$envioSobrePeso;
                $total_r=round($total+($total * 0.16)+$envioSobrePeso,0,PHP_ROUND_HALF_UP);
            }
        }
        return '<div class="box" id="order-summary">
                            <div class="box-header">
                                <h3 class="subTitulos">Resumen de compra</h3>
                            </div>
                            <p class="text-muted">Los costos de envío pueden variar dependiendo de la region donde te encuentres.</p>

                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Subtotal</td>
                                                <th>$' . $costo . '</th>
                                        </tr>
                                        <tr>
                                            <td>IVA (16%)</td>
                                                <th>$' .$iva. '</th>
                                        </tr>
                                        <tr>
                                            <td>Envio</td>
                                                <th>$' .$envio. '</th>
                                        </tr>
                                        <tr class="total">
                                            <td>Total a Pagar</td>
                                                <th>$' .$total_r. '</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>';
    }

    public function footer()
    {
        $contacto = $this->infoEmpresa();
        if (isset($_SESSION["usuarioJDV"])) {
            $secuser = '<ul>
                                <li>¡Hola <strong>' . $_SESSION["usuarioJDV"]["username_usuario"] . '</strong>!
                                </li>
                                <li><a href="profile.php">Mi perfil</a>
                                </li>
                                <li><a href="basket.php">Mi carrito</a>
                                </li>
                            </ul>';
        } else {
            $secuser = '<ul>
                                <li><a href="#" data-toggle="modal" data-target="#login-modal">Iniciar Sesión</a>
                                </li>
                                <li><a href="register.php">Registro</a>
                                </li>
                            </ul>';
        }
        return '<div id="footer" data-animate="fadeInUp">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            
                            <h4>Sección de usuario</h4>

                            ' . $secuser . '
                                
                            <hr>
                            
                            <h4>Ayuda</h4>

                            <ul>
                                <li><a href="aviso.php">Términos y Condiciones</a>
                                </li>
                                <li><a href="faq.php">Preguntas frecuentes</a>
                                </li>
                            </ul>

                            <hr class="hidden-md hidden-lg hidden-sm">

                        </div>
                        <!-- /.col-md-3 -->

                        <div class="col-md-4 col-sm-6">

                            <h4>Navegación</h4>
                            
                            <ul>
                                <li><a href="index.php">Inicio</a>
                                </li>
                                <li><a href="blog.php">Blog</a>
                                </li>
                                <li><a href="contact.php">Contacto</a>
                                </li>
                            </ul>
                            
                            
                            <h4>Horario de Atención</h4>

                            <p id="direccion_f">
                                ' . $contacto[0]["direccion_empresa"] . '
                            </p>
                            
                            <hr class="hidden-md hidden-lg">

                        </div>
                        <!-- /.col-md-3 -->

                        <!--<div class="col-md-3 col-sm-6">

                            

                            <hr class="hidden-md hidden-lg">

                        </div>
                         /.col-md-3 -->



                        <div class="col-md-4 col-sm-12">

                            <h4>Siguenos en nuestras redes</h4>

                            <p class="social">
                                <a href="https://www.facebook.com/jadevu.jeans?fb_dtsg_ag=AdwboBDG5hcLJvoCvf3wILBq2BZkCQBK2e-6MRKLHqEyKg%3AAdx0xR0iRzb_ruBZsUzhVUQ5D6RtOdp_3KNicKGXdmEKrg" class="facebook external" data-animate-hover="shake"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://www.instagram.com/jadevu.glamjeans/" class="instagram external" data-animate-hover="shake"><i class="fab fa-instagram"></i></a>
                                <a href="http://www.jade-vu.com/web/contact.php" class="email external" data-animate-hover="shake"><i class="fa fa-envelope"></i></a>
                            </p>


                        </div>
                        <!-- /.col-md-3 -->

                    </div>
                    <!-- /.row -->

                </div>
                <!-- /.container -->
            </div>
            <div id="copyright">
                <div class="container">
                    <div class="col-md-6">
                        <a href="http://www.jade-vu.com/web/BackEnd/"  class="gplus external"  data-animate-hover="shake" ><i class="fa fa-bars fa-lg" style="color:#FFFFFF; width:6; height:6;"/></i></a>
                        <p class="pull-left"> © JADE-VÜ es una marca perteneciente a CUBO eSTUDIO 2018.</p>
                        
                    </div>
                   
                </div>
            </div>';
    }

    public function footerPayu()
    {
        $contacto = $this->infoEmpresa();
        if (isset($_SESSION["usuarioJDV"])) {
            $secuser = '<ul>
                                <li>¡Hola <strong>' . $_SESSION["usuarioJDV"]["username_usuario"] . '</strong>!
                                </li>
                                <li><a href="../profile.php">Mi perfil</a>
                                </li>
                                <li><a href="../basket.php">Mi carrito</a>
                                </li>
                            </ul>';
        } else {
            $secuser = '<ul>
                                <li><a href="#" data-toggle="modal" data-target="#login-modal">Iniciar Sesión</a>
                                </li>
                                <li><a href="../register.php">Registro</a>
                                </li>
                            </ul>';
        }
        return '<div id="footer" data-animate="fadeInUp">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            
                            <h4>Sección de usuario</h4>

                            ' . $secuser . '
                                
                            <hr>
                            
                            <h4>Ayuda</h4>

                            <ul>
                                <li><a href="../aviso.php">Términos y Condiciones</a>
                                </li>
                                <li><a href="../faq.php">Preguntas frecuentes</a>
                                </li>
                            </ul>

                            <hr class="hidden-md hidden-lg hidden-sm">

                        </div>
                        <!-- /.col-md-3 -->

                        <div class="col-md-4 col-sm-6">

                            <h4>Navegación</h4>
                            
                            <ul>
                                <li><a href="../index.php">Inicio</a>
                                </li>
                                <li><a href="../blog.php">Blog</a>
                                </li>
                                <li><a href="../contact.php">Contacto</a>
                                </li>
                            </ul>
                            
                            
                            <h4>Horario de Atención</h4>

                            <p id="direccion_f">
                                ' . $contacto[0]["direccion_empresa"] . '
                            </p>
                            
                            <hr class="hidden-md hidden-lg">

                        </div>
                        <!-- /.col-md-3 -->

                        <!--<div class="col-md-3 col-sm-6">

                            

                            <hr class="hidden-md hidden-lg">

                        </div>
                         /.col-md-3 -->



                        <div class="col-md-4 col-sm-12">

                            <h4>Siguenos en nuestras redes</h4>

                            <p class="social">
                                <a href="#" class="facebook external" data-animate-hover="shake"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="twitter external" data-animate-hover="shake"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="instagram external" data-animate-hover="shake"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="gplus external" data-animate-hover="shake"><i class="fab fa-google-plus-g"></i></a>
                                <a href="#" class="email external" data-animate-hover="shake"><i class="fa fa-envelope"></i></a>
                            </p>


                        </div>
                        <!-- /.col-md-3 -->

                    </div>
                    <!-- /.row -->

                </div>
                <!-- /.container -->
            </div>
            <div id="copyright">
                <div class="container">
                <div class="col-md-6">
                    <a href="http://www.jade-vu.com/web/BackEnd/"  class="gplus external"  data-animate-hover="shake" ><i class="fa fa-bars fa-lg" style="color:#FFFFFF; width:6; height:6;"/></i></a>
                    <p class="pull-left"> © JADE-VÜ es una marca perteneciente a CUBO eSTUDIO 2018.</p>
                </div>
                </div>
            </div>';
    }

    // SECCIONES GLOBALES
    public function migasPan($cat = "", $prod = "", $blog = "", $noticia = "")
    {
        $i = "";
        // migas para productos y categorias
        if ($prod != "") {
            $r = $this->BD->ConsultaLibre("producto.ID AS idprod, subcategoria.ID AS idsubcat, producto.nombre_prod, nombre_subcat", "producto, subcategoria", "producto.ID_subcat=subcategoria.ID AND producto.ID=?", array(
                $prod), 1);
            $i .= '<li><a href="category.php?cat=' . $r[0]["idsubcat"] . '">' . $r[0]["nombre_subcat"] . '</a></li>' . '<li>' . $r[0]["nombre_prod"] . '</li>';
        } else if ($cat != "") {
            $r = $this->BD->ConsultaWhereSimple("subcategoria", "activo=? AND subcategoria.ID=? ", array(
                1,
                $cat
            ));
            $i .= '<li>' . $r[0]["nombre_subcat"] . '</li>';
        }        // migas para la noticia completa
        else if ($noticia != "") {
            $r = $this->BD->ConsultaLibre("*", "blog", "blog.ID=?", array(
                $noticia), 1);
            $i .= '<li><a href="blog.php?pag=1">Blog </a></li>' . '<li>' . $r[0]["Titulo_Noticia"] . '</li>';
        } else if ($blog != "") {
            $i .= '<li>Blog </li>';
        }
        return '<ul class="breadcrumb">

                            <li><a href="index.php">Inicio</a>
                            </li>
                            ' . $i . '
                        </ul>';
    }

    public function publicidad($cant)
    {
        $r = $this->BD->ConsultaMultiplesRegistros("publicidad", "activo=? ORDER BY RAND() ", array(
            1), $cant);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<div class="banner">
                            <a href="' . $contenido["enlace"] . '">
                                <img src="' . $contenido["imagen"] . '" alt="' . $contenido["nombre"] . '" class="img-responsive">
                            </a>
                        </div>';
        }
        return $i;
    }

    // SECCIONES DE INDEX
    public function sliderPrincipal()
    {
        $r = $this->BD->ConsultaMultiplesRegistros("slider", "activo=? ORDER BY orden ASC ", array(
            1), 6);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '
                            <div class="item">
                                <a href="' . $contenido["enlace"] . '"><img src="' . $contenido["imagen"] . '" alt="" class="img-responsive"></a>
                            </div>';
        }
        return $i;
    }

    public function sliderBig()
    {
        $r = $this->BD->ConsultaMultiplesRegistros("slider", "activo=? ORDER BY orden ASC", array(
            1), 6);
        $activo = true; // Indica el primer item
        $i = ""; // guardara los items como tal (imagenes, titulos, enlaces, etc)
        $botones = ""; // guardara los indicadores para cada uno de los items (los indicadores de debajo)
        $ind = 1; // indice de los botones, se inicia en 1 y continua aumentando
        foreach ($r as $contenido) {
            if ($activo == true) {
                $i .= '<div class="item active">

					<!-- Slide Background -->
					<img src="' . $contenido["imagen"] . '" alt="' . $contenido["titulo"] . '" class="slide-image" />
					<div class="bs-slider-overlay"></div>

					<div class="container">
						<div class="row">
							<!-- Slide Text Layer -->
							<div class="slide-text slide_style_center">
								<h1 data-animation="animated zoomInRight">' . $contenido["titulo"] . '</h1>
                                                                    <p data-animation="animated fadeInLeft">  </p>
								<a href="' . $contenido["enlace"] . '" target="_blank" class="btn btn-primary" data-animation="animated fadeInRight">Más información</a>
							</div>
						</div>
					</div>
				</div>';
                $botones .= '<li data-target="#bootstrap-touch-slider" data-slide-to="0" class="active"></li>';
                $activo = false;
            } else {
                $i .= '<div class="item">

					<!-- Slide Background -->
					<img src="' . $contenido["imagen"] . '" alt="' . $contenido["titulo"] . '" class="slide-image" />
					<div class="bs-slider-overlay"></div>

					<div class="container">
						<div class="row">
							<!-- Slide Text Layer -->
							<div class="slide-text slide_style_center">
								<h1 data-animation="animated zoomInRight">' . $contenido["titulo"] . '</h1>
                                                                    <p data-animation="animated fadeInLeft">  </p>
								<a href="' . $contenido["enlace"] . '" target="_blank" class="btn btn-primary" data-animation="animated fadeInRight">Más información</a>
							</div>
						</div>
					</div>
				</div>';
                $botones .= '<li data-target="#bootstrap-touch-slider" data-slide-to="' . $ind . '"></li>';
                $ind ++;
            }
        }
        $ind = 1;
        return array("items" => $i,"botones" => $botones);
    }

    public function sliderMarcas()
    {
        $r = $this->BD->ConsultaMultiplesRegistros("marcas_slider", "activo=? ORDER BY orden ASC ", array(
            1), 5);
        $i = "";
        $n=count($r);
        if($n>0){
            foreach ($r as $contenido) {
                $i .= '<div class="item">
                        <a href="' . $contenido["enlace"] . '">
                        <img src="' . $contenido["imagen"] . '" alt="' . $contenido["descripcion"] . '" class="img-responsive center-block">
                        </a>
                       </div>';
            }
        }
        return array("items" => $i);       
        
    }
    
  

    public function ventajas()
    {
        return '<div id="advantages">

                    <div class="container">
                        <div class="same-height-row">
                            <div class="col-sm-4">
                                <div class="box same-height clickable">
                                    <div class="icon"><i class="fa fa-heart"></i>
                                    </div>

                                    <h3><a href="#">Nos interesan nuestros clientes</a></h3>
                                    <p>Deseamos brindarles el mejor servicio posible</p>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="box same-height clickable">
                                    <div class="icon"><i class="fa fa-tags"></i>
                                    </div>

                                    <h3><a href="#">Los mejores precios </a></h3>
                                    <p>Contamos con los mejores precios del mercado en moda de Tehuacán.</p>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="box same-height clickable">
                                    <div class="icon"><i class="fa fa-thumbs-up"></i>
                                    </div>

                                    <h3><a href="#">100% garantía de satisfacción</a></h3>
                                    <p>Productos de calidad que garantizan tu satisfacción.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->

                    </div>
                    <!-- /.container -->

                </div>';
    }

    public function prodDestacados()
    {
        $r = $this->BD->ConsultaMultiplesRegistros("producto", "activo=? AND destacado_prod=? ORDER BY orden_prod DESC", array(
            1,1), 10);
        $i = "";

        foreach ($r as $contenido) {
            $i .= '<div class="item">
                                <div class="product">
                                    <div class="flip-container">
                                        <div class="flipper">
                                            <div class="front">
                                                <a href="detail.php?id=' . $contenido["ID"] . '">
                                                    <img src="' . $contenido["img1_prod"] . '" alt="" class="img-responsive">
                                                </a>
                                            </div>
                                            <div class="back">
                                                <a href="detail.php?id=' . $contenido["ID"] . '">
                                                    <img src="' . $contenido["img2_prod"] . '" alt="" class="img-responsive">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="detail.php?id=' . $contenido["ID"] . '" class="invisible">
                                        <img src="' . $contenido["img1_prod"] . '" alt="" class="img-responsive">
                                    </a>
                                    <div class="text">
                                        <h3><a href="detail.php?id=' . $contenido["ID"] . '"">' . $contenido["nombre_prod"] . '</a></h3>
                                        <p class="price">' . ($contenido["preciodesc_prod"] > 0 ? "<del>$" . $contenido["precio_prod"] . "</del> $" . $contenido["preciodesc_prod"] : "$" . $contenido["precio_prod"]) . '</p>
                                    </div>
                                    <!-- /.text -->
                                    ' . ($contenido["preciodesc_prod"] > 0 ? '<div class="ribbon sale">
                                        <div class="theribbon">OFERTA</div>
                                        <div class="ribbon-background"></div>
                                    </div>
                                    <!-- /.ribbon -->' : "") . ($contenido["nuevo_prod"] == 1 ? '<div class="ribbon new">
                                        <div class="theribbon">NUEVO</div>
                                        <div class="ribbon-background"></div>
                                    </div>
                                    <!-- /.ribbon -->' : "") . '                                    
                                </div>
                                <!-- /.product -->
                            </div>';
        }
        
        return '<div class="container">
                        <div class="product-slider">
                            ' . $i . '
                        </div>
                        <!-- /.product-slider -->
                    </div>';
    }

    public function ultimasNoticias()
    {
        $r = $this->BD->ConsultaMultiplesRegistros("blog", "activo=? ORDER BY ID desc ", array(
            1
        ), 2);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '
                            <div class="col-sm-6">
                                <div class="post">
                                    <h4><a href="post.php?post=' . $contenido["ID"] . '">' . $contenido["Titulo_Noticia"] . '</a></h4>
                                    <p class="author-category">Por <a href="#">' . $contenido["Autor"] . '</a>
                                    </p>
                                    <hr>
                                    <p class="intro">' . acortarTexto($contenido["Cuerpo"], 300) . '</p>
                                    <p class="read-more"><a href="post.php?post=' . $contenido["ID"] . '" class="btn btn-primary">Continuar leyendo</a>
                                    </p>
                                </div>
                            </div>
                        ';
        }
        return $i;
    }

    // SECCIONES DEL BLOG
    public function cuerpoPostPreviewPag($pag, $cantidad)
    {
        list ($datos, $totalpag) = $this->BD->ConsultaMultiRegPaginado("blog", "activo=? ORDER BY ID DESC ", array(
            1
        ), $cantidad, $pag);
        $i = "";
        foreach ($datos as $contenido) {
            $i .= '<div class="post">
                            <h2><a href="post.php?post=' . $contenido["ID"] . '">' . $contenido["Titulo_Noticia"] . '</a></h2>
                            <p class="author-category">Por <a href="#">' . $contenido["Autor"] . '</a> <span class="pull-right"><i class="fa fa-calendar-o"></i> ' . $contenido["Fecha"] . '</span>
                            </p>
                            <hr>
                            <p class="date-comments">
                                
                            </p>
                            <div class="image">
                                <a href="post.php?post=ID' . $contenido["ID"] . '">
                                    <img src="' . $contenido["Encabezado_img"] . '" class="img-responsive" alt="Example blog post alt">
                                </a>
                            </div>
                            <p class="intro">' . acortarTexto($contenido["Cuerpo"], 600) . '</p>
                            <p class="read-more"><a href="post.php?post=' . $contenido["ID"] . '" class="btn btn-primary">Continuar Leyendo</a>
                            </p>
                        </div>';
        }
        $paginas = "";
        for ($e = 1; $e <= $totalpag; $e ++) {
            if ($e == $pag) {
                $paginas .= '<li class="active"><a href="blog.php?pag=' . $e . '">' . $e . '</a>
                                </li>';
            } else {
                $paginas .= '<li><a href="blog.php?pag=' . $e . '">' . $e . '</a>
                                </li>';
            }
        }
        if ($pag <= 1) {
            $back = '<li class="disabled"><a>&laquo;</a>
                                </li>';
        } else {
            $back = '<li><a href="blog.php?pag=' . ($pag - 1) . '">&laquo;</a>
                                </li>';
        }
        if ($pag == ($e - 1)) {
            $foward = '<li class="disabled"><a>&raquo;</a>
                                </li>';
        } else {
            $foward = '<li><a href="blog.php?pag=' . ($pag + 1) . '">&raquo;</a>
                                </li>';
        }
        
        $paginador = '<div class="pages">
                            <ul class="pagination">
                               
                                ' . $back . $paginas . $foward . '
                                
                            </ul>
                        </div>';
        return array(
            $i,
            $paginador
        );
    }

    public function cuerpoPostPreview()
    {
        $r = $this->BD->ConsultaMultiplesRegistros("blog", "activo=? ORDER BY ID DESC ", array(
            1
        ), 4);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<div class="post">
                            <h2><a href="post.php?post=' . $contenido["ID"] . '">' . $contenido["Titulo_Noticia"] . '</a></h2>
                            <p class="author-category">Por <a href="#">' . $contenido["Autor"] . '</a> <span class="pull-right"><i class="fa fa-calendar-o"></i> ' . $contenido["Fecha"] . '</span>
                            </p>
                            <hr>
                            <p class="date-comments">
                                
                            </p>
                            <div class="image">
                                <a href="post.php?post=ID' . $contenido["ID"] . '">
                                    <img src="' . $contenido["Encabezado_img"] . '" class="img-responsive" alt="Example blog post alt">
                                </a>
                            </div>
                            <p class="intro">' . acortarTexto($contenido["Cuerpo"], 600) . '</p>
                            <p class="read-more"><a href="post.php?post=' . $contenido["ID"] . '" class="btn btn-primary">Continuar Leyendo</a>
                            </p>
                        </div>';
        }
        return $i;
    }

    public function cuerpoPostFull($ide)
    {
        $r = $this->BD->ConsultaWhereSimple("blog", "activo=? and ID=?", array(
            1,
            $ide
        ));
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<h1>' . $contenido["Titulo_Noticia"] . '</h1>
                            <p class="author-date">Por <a href="#">' . $contenido["Autor"] . '</a> | ' . $contenido["Fecha"] . '</p>
                            

                            <div id="post-content">
                                <p>
                                    <img src="' . $contenido["Encabezado_img"] . '" class="img-responsive" alt="Example blog post alt">
                                </p>
                                
                                <p>' . $contenido["Cuerpo"] . '</p>



        </div>';
            return $i;
        }
    }

    public function comentariosPost()
    {
        return '<div id="comments" data-animate="fadeInUp">
                                <h4>2 comments</h4>


                                <div class="row comment">
                                    <div class="col-sm-3 col-md-2 text-center-xs">
                                        <p>
                                            <img src="img/blog-avatar2.jpg" class="img-responsive img-circle" alt="">
                                        </p>
                                    </div>
                                    <div class="col-sm-9 col-md-10">
                                        <h5>Julie Alma</h5>
                                        <p class="posted"><i class="fa fa-clock-o"></i> September 23, 2011 at 12:00 am</p>
                                        <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper.
                                            Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
                                        <p class="reply"><a href="#"><i class="fa fa-reply"></i> Reply</a>
                                        </p>
                                    </div>
                                </div>
                                <!-- /.comment -->


                                <div class="row comment last">

                                    <div class="col-sm-3 col-md-2 text-center-xs">
                                        <p>
                                            <img src="img/blog-avatar.jpg" class="img-responsive img-circle" alt="">
                                        </p>
                                    </div>

                                    <div class="col-sm-9 col-md-10">
                                        <h5>Louise Armero</h5>
                                        <p class="posted"><i class="fa fa-clock-o"></i> September 23, 2012 at 12:00 am</p>
                                        <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper.
                                            Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
                                        <p class="reply"><a href="#"><i class="fa fa-reply"></i> Reply</a>
                                        </p>
                                    </div>

                                </div>
                                <!-- /.comment -->

                            </div>';
    }

    // SECCIONES DE PAG. DE CONTACTO
    public function infoContacto()
    {
        $contacto = $this->infoEmpresa();
        return '<div class="row">
                                <div class="col-sm-4">
                                    <h3 class="subTitulos"><i class="fa fa-map-marker"></i>Horario de Atención</h3>
                                    <p><strong>' . $contacto[0]["direccion_empresa"] . '</strong>
                                    </p>
                                </div>
                                <!-- /.col-sm-4 -->
                                <div class="col-sm-4">
                                    <h3 class="subTitulos"><i class="fa fa-phone"></i> Llámanos</h3>
                                    
                                    <p><strong>' . $contacto[0]["telefono_empresa"] . '</strong>
                                    </p>
                                </div>
                                <!-- /.col-sm-4 -->
                                <div class="col-sm-4">
                                    <h3 class="subTitulos"><i class="fa fa-envelope"></i> Envíanos un correo</h3>
                                    <p class="text-muted"><strong><a href="mailto:'. $contacto[0]["correo_empresa"] .'">' . $contacto[0]["correo_empresa"] . '</a></strong></p>
                                </div>
                                <!-- /.col-sm-4 -->
                            </div>';
    }

    // SECCIONES CARRITO DE COMPRAS
    public function productosCarrito()
    {
        $total = 0;
        $tabla = "";
        $numeroArticulos=0;
        if (isset($_SESSION["carritoJDV"])) {
            $numCarrito=(count($_SESSION["carritoJDV"]));
            for ($i=0; $i<$numCarrito; $i++) {
    			$numPiezas=$_SESSION["carritoJDV"][$i][1];
                $numeroArticulos+=$numPiezas;
             }
            $tabla = '<h1 class="subTitulos">Carrito de compras</h1>
                        <p class="text-muted">Actualmente tienes '.$numeroArticulos.' artículo(s) en tu carrito.</p>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio unitario</th>
                                        <th>Descuento</th>
                                        <th colspan="2">Total</th>
                                    </tr>
                                </thead>
                    <tbody>';

            foreach ($_SESSION["carritoJDV"] as $contenido) {
                $prod = $this->BD->ConsultaWhereSimple("producto", "activo=? AND ID=?", array(1,$contenido[0]));
                $tabla .= '<tr>
                                                <td>
                                                     <a href="detail.php?id=' . $prod[0]["ID"] . '">
                                                        <img src="' . $prod[0]["img1_prod"] . '" alt="' . $prod[0]["nombre_prod"] . '">
                                                    </a>
                                                </td>

                                                <td>
                                                <a href="detail.php?id=' . $prod[0]["ID"] . '">' . $prod[0]["nombre_prod"] . ' (' . $contenido[2] . ')</a>
                                                </td>
                                                
                                                <td><form method="POST" action="procesos.php" onchange="this.submit();">
                                                    <input type="hidden" name="talla_prod_add" value="' . $contenido[2] . '">
                                                    <input type="hidden" name="id_prod_cart_add" value="' . $prod[0]["ID"] . '">
                                                    <input type="number" name="cantidad_prod_add" value="' . $contenido[1] . '" class="quanitySniper">
                                                    
                                                    </form>
                                                     
                                                </td>
                                                
                                                <td>$' . $prod[0]["precio_prod"] . '</td>
                                                ' . ($prod[0]["preciodesc_prod"] > 0 ? "<td>$" . ($prod[0]["precio_prod"] - $prod[0]["preciodesc_prod"]) . "</td>" : "<td>$0</td>") . ($prod[0]["preciodesc_prod"] > 0 ? "<td>$" . $prod[0]["preciodesc_prod"] * $contenido[1] . "</td>" : "<td>$" . $prod[0]["precio_prod"] * $contenido[1] . "</td>") . '
                                                
                                                <td><a href="procesos.php?deleteprod=' . $prod[0]["ID"] . '&talla=' . $contenido[2] . '"><i class="far fa-trash-alt"></i></a>
                                                </td>
                                            </tr>';
                                            //Problema complejo
                                            $x=$contenido[1];
                ($prod[0]["preciodesc_prod"] > 0 ? ($total = $total + $prod[0]["preciodesc_prod"] * $contenido[1]) : ($total = $total + $prod[0]["precio_prod"] * $contenido[1]));
                
            }
        
            /**
             * Aqui ya muestra el no. de articulos del carrito
             *var_dump(count($_SESSION["carritoJDV"]));  
             *Cuando se inicia sesion, si solo se pone el correo y se da enter, entra a procesos pero ya no avanza
             */
            

            $tabla .= '</tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5">Total</th>
                                                <th colspan="2">$' . $total . '</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- /.table-responsive -->

                                <div class="box-footer">
                                    <div class="pull-right"><form method="post" action="procesos.php"><input type="hidden" name="compra" value="1">
                                        <a href="basket.php" class="btn btn-default"><i class="fas fa-sync-alt"></i> Actualizar cesta</a>
                                        
                                        <button type="submit" class="btn btn-primary">Proceder a la compra <i class="fa fa-chevron-right"></i>
                                        </button>
                                        </form>
                                    </div>
                                </div>';
        } else {
            return "Aun no cuentas con productos en tu carrito de compras.";
        }
        return $tabla;
    }

    public function productosInteres()
    {
        $r = $this->BD->ConsultaMultiplesRegistros("producto", "activo=? AND destacado_prod=? ORDER BY RAND() DESC", array(
            1,
            1
        ), 3);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<div class="col-md-3 col-sm-6">
                                <div class="product same-height">
                                    <div class="flip-container">
                                        <div class="flipper">
                                            <div class="front">
                                                <a href="detail.php?id=' . $contenido["ID"] . '">
                                                    <img src="' . $contenido["img1_prod"] . '" alt="" class="img-responsive">
                                                </a>
                                            </div>
                                            <div class="back">
                                                <a href="detail.php?id=' . $contenido["ID"] . '">
                                                    <img src="' . $contenido["img2_prod"] . '" alt="" class="img-responsive">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="detail.php?id=' . $contenido["ID"] . '" class="invisible">
                                        <img src="' . $contenido["img1_prod"] . '" alt="" class="img-responsive">
                                    </a>
                                    <div class="text">
                                        <h3>' . $contenido["nombre_prod"] . '</h3>
                                        <p class="price">' . ($contenido["preciodesc_prod"] > 0 ? "<del>$" . $contenido["precio_prod"] . "</del> $" . $contenido["preciodesc_prod"] : "$" . $contenido["precio_prod"]) . '</p>
                                    </div>
                                </div>
                                <!-- /.product -->
                            </div>';
        }
        return $i;
    }

    // SECCIONES CATEGORÍAS
    public function menuLatSubcat($idcategoria)
    {
        $r = $this->BD->ConsultaLibre("subcategoria.ID AS subid, nombre_subcat", "subcategoria, categoria", "subcategoria.activo=? AND categoria.ID=subcategoria.ID_categoria AND subcategoria.ID_categoria=? ORDER BY orden_subcat ASC", array(
            1,
            $idcategoria
        ), 10);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<li><a href="category.php?cat=' . $contenido["subid"] . '">' . $contenido["nombre_subcat"] . '</a>
                                            </li>';
        }
        return $i;
    }

    public function menuLatCategorias($idLinea)
    {
        $r = $this->BD->ConsultaLibre("categoria.ID AS catid, nombre_categoria", "categoria, linea", "categoria.activo=? AND categoria.ID_linea=linea.ID AND categoria.ID_linea=? ORDER BY orden_categoria ASC", array(
            1,
            $idLinea
        ), 5);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<li><a>' . $contenido["nombre_categoria"] . '</a>
                <ul>
                    ' . $this->menuLatSubcat($contenido["catid"]) . '
                </ul>
                    </li>';
        }
        return $i;
    }

    public function menuLatLinea()
    {
        $r = $this->BD->ConsultaMultiplesRegistros("linea", "activo=? ORDER BY orden_linea ASC ", array(
            1
        ), 5);
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<li class="active">
                                        <a>' . $contenido["nombre_linea"] . ' </a>
                                        ' . $this->menuLatCategorias($contenido["ID"]) . '
                                    </li>';
        }
        return $i;
    }

    public function menuLatCat()
    {
        return '<div class="panel panel-default sidebar-menu">

                            <div class="panel-heading">
                                <h3 class="panel-title subTitulos">Categorias</h3>
                            </div>

                            <div class="panel-body">
                                <ul class="nav nav-pills nav-stacked category-menu">
                                    ' . $this->menuLatLinea() . '
                                    
                                </ul>

                            </div>
                        </div>';
    }

    public function headerSubcar($idsub)
    {
        $r = $this->BD->ConsultaWhereSimple("subcategoria", "activo=? AND ID=?", array(
            1,
            $idsub
        ));
        return '<div class="box">
                            <h1 class="subTitulos">' . $r[0]["nombre_subcat"] . '</h1>
                            <p>' . $r[0]["descripcion_subcat"] . '</p>
                        </div>';
    }

    public function menuColor2($subcat, $pag = "", $order = "")
    {
        $colores = $this->BD->ConsultaLibre("color_prod, hexa_prod", "producto", "activo=? AND ID_subcat=? GROUP BY color_prod", array(
            1,
            $subcat
        ));
        $i = '<button type="button" class="btn" data-filter="all">Todos</button>';
        
        foreach ($colores as $contenido) {
            $i .= '<button type="button" class="btn" style="color:#fff; background: #' . $contenido["hexa_prod"] . '; border: #' . $contenido["hexa_prod"] . '" data-filter="' . preg_replace('/[^0-9]/', '', $contenido["hexa_prod"]) . '">' . $contenido["color_prod"] . '</button>';
        }
        
        return '<div class="panel panel-default sidebar-menu">

                            <div class="panel-heading">
                                <h3 class="panel-title">Colores</h3>
                            </div>

                            <div class="panel-body">

                                    <div class="form-group">
                                        ' . $i . '
                                    </div>

                            </div>
                        </div>';
    }

    public function menuLatColor($subcat, $pag = "", $order = "", $precolor = "")
    {
        $colores = $this->BD->ConsultaLibre("color_prod, hexa_prod", "producto", "activo=? AND ID_subcat=? GROUP BY color_prod", array(
            1,
            $subcat
        ));
        $i = "";
        $e = 0;
        foreach ($colores as $contenido) {
            $url1 = "'category.php?pag=" . $pag . "&cat=" . $subcat . "&order=" . $order . "&color='+this.value";
            $url2 = "'category.php?pag=" . $pag . "&cat=" . $subcat . "&order=" . $order . "'";
            if ($precolor == $contenido["color_prod"]) {
                $i .= '<div class="checkbox">
                                            <label>
                                                <input type="checkbox" value=' . $contenido["color_prod"] . ' onchange="location.href=' . $url2 . '" checked> <span class="colour" style="background: #' . $contenido["hexa_prod"] . '"></span> ' . $contenido["color_prod"] . '
                                            </label>
                                        </div>';
                $e = 1;
            }
            if ($e == 0) {
                $i .= '<div class="checkbox">
                                            <label>
                                                <input type="checkbox" value=' . $contenido["color_prod"] . ' onchange="location.href=' . $url1 . '"> <span class="colour" style="background: #' . $contenido["hexa_prod"] . '"></span> ' . $contenido["color_prod"] . '
                                            </label>
                                        </div>';
            }
            $e = 0;
        }
        return '<div class="panel panel-default sidebar-menu">

                            <div class="panel-heading">
                                <h3 class="panel-title">Colores</h3>
                            </div>

                            <div class="panel-body">

                                <form>
                                    <div class="form-group">
                                        ' . $i . '
                                    </div>

                                </form>

                            </div>
                        </div>';
    }

    public function productosCategoria($ide, $pag, $cantidad, $orden, $orderby = "", $color = "")
    {
        if ($color != "") {
            list ($datos, $totalpag) = $this->BD->ConsultaMultiRegPaginado("producto", "activo=? AND ID_subcat=? AND color_prod=? ORDER BY " . $orden . " ", array(
                1,
                $ide,
                $color
            ), $cantidad, $pag);
        } else {
            list ($datos, $totalpag) = $this->BD->ConsultaMultiRegPaginado("producto", "activo=? AND ID_subcat=? ORDER BY " . $orden . " ", array(
                1,
                $ide
            ), $cantidad, $pag);
        }
        
        $i = "";
        $prodcant = 0;
        foreach ($datos as $contenido) {
            $prodcant = $prodcant + 1;
            $i .= '<div class="col-md-4 col-sm-6 filtr-item" data-category="' . preg_replace('/[^0-9]/', '', $contenido["hexa_prod"]) . '">
                                <div class="product">
                                    <div class="flip-container">
                                    <div class="cf">
                                        <img class="bottom img-responsive" src="' . $contenido["img1_prod"] . '" /><img class="top img-responsive" src="' . $contenido["img2_prod"] . '" />
                                    </div>
                                        <!--<div class="flipper">
                                            <div class="front">
                                                <a href="detail.php?id=' . $contenido["ID"] . '">
                                                    <img src="' . $contenido["img1_prod"] . '" alt="" class="img-responsive">
                                                </a>
                                            </div>
                                            <div class="back">
                                                <a href="detail.php?id=' . $contenido["ID"] . '"">
                                                    <img src="' . $contenido["img2_prod"] . '" alt="" class="img-responsive">
                                                </a>
                                            </div>
                                        </div>-->
                                    </div>
                                    <a href="detail.php?id=' . $contenido["ID"] . '"" class="invisible">
                                        <img src="' . $contenido["img1_prod"] . '" alt="" class="img-responsive">
                                    </a>
                                    <div class="text">
                                        <h3><a href="detail.php?id=' . $contenido["ID"] . '"">' . $contenido["nombre_prod"] . '</a></h3>
                                        <p class="price">' . ($contenido["preciodesc_prod"] > 0 ? "<del>$" . $contenido["precio_prod"] . "</del> $" . $contenido["preciodesc_prod"] : "$" . $contenido["precio_prod"]) . '</p>
                                        <p class="buttons">
                                            <a href="detail.php?id=' . $contenido["ID"] . '" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Ver producto</a>
                                        </p>
                                    </div>
                                    <!-- /.text -->

                                    ' . ($contenido["preciodesc_prod"] > 0 ? '<div class="ribbon sale">
                                        <div class="theribbon">OFERTA</div>
                                        <div class="ribbon-background"></div>
                                    </div>
                                    <!-- /.ribbon -->' : "") . ($contenido["nuevo_prod"] == 1 ? '<div class="ribbon new">
                                        <div class="theribbon">NUEVO</div>
                                        <div class="ribbon-background"></div>
                                    </div>
                                    <!-- /.ribbon -->' : "") . '
                                </div>
                                <!-- /.product -->
                            </div>';
        }
        $paginas = "";
        for ($e = 1; $e <= $totalpag; $e ++) {
            if ($e == $pag) {
                $paginas .= '<li class="active"><a href="category.php?pag=' . $e . '&cat=' . $ide . '&order=' . $orderby . '">' . $e . '</a>
                                </li>';
            } else {
                $paginas .= '<li><a href="category.php?pag=' . $e . '&cat=' . $ide . '&order=' . $orderby . '">' . $e . '</a>
                                </li>';
            }
        }
        if ($pag <= 1) {
            $back = '<li class="disabled"><a>&laquo;</a>
                                </li>';
        } else {
            $back = '<li><a href="category.php?pag=' . ($pag - 1) . '&cat=' . $ide . '&order=' . $orderby . '">&laquo;</a>
                                </li>';
        }
        if ($pag == ($e - 1)) {
            $foward = '<li class="disabled"><a>&raquo;</a>
                                </li>';
        } else {
            $foward = '<li><a href="category.php?pag=' . ($pag + 1) . '&cat=' . $ide . '&order=' . $orderby . '">&raquo;</a>
                                </li>';
        }
        
        $paginador = '<div class="pages">
                            <ul class="pagination">
                               
                                ' . $back . $paginas . $foward . '
                                
                            </ul>
                        </div>';
        return array(
            $i,
            $paginador,
            $prodcant
        );
    }

    // EDITADO
    public function orderBy($cat, $pag, $preorder, $precolor)
    {
        $cate = "'category.php?pag=" . $pag . "&cat=" . $cat . "&color=" . $precolor . "&order='+this.value";
        switch ($preorder) {
            case 1:
                $values = '<option disabled>Orden</option>
                       <option value="0">Relevancia</option>
                       <option value="1" selected>Menor precio</option>
                       <option value="2">Mayor precio</option>';
                break;
            case 2:
                $values = '<option disabled>Orden</option>
                           <option value="0">Relevancia</option>
                           <option value="1">Menor precio</option>
                           <option value="2" selected>Mayor precio</option>';
                break;
            case 0:
                $values = '<option disabled>Orden</option>
                           <option value="0" selected>Relevancia</option>
                           <option value="1">Menor precio</option>
                           <option value="2">Mayor precio</option>';
                break;
            default:
                $values = '<option disabled selected>Orden</option>
                           <option value="0">Relevancia</option>
                           <option value="1">Menor precio</option>
                           <option value="2">Mayor precio</option>';
        }
        return '<div class="products-sort-by">
                                                    <strong>Ordenar por</strong>
                                                    <select name="sort-by" class="form-control" onchange="location.href=' . $cate . '">
                                                        
                                                        ' . $values . '
                                                    </select>
                                                </div>';
    }

    // SECCIONES PRODUCTO INDIVIDUAL
    public function generarTallas($tallas)
    {
        /**
         * Hacer una lista que tome cada valor recorrido de las tallas. Despues ahora si hacer un IF
         * para comprobar los que sean 0
         */
        $AregTallas = explode(',', trim($tallas));
        $agotado = false;
        $inputTallas="";
        foreach ($AregTallas as $vuelta) {
            $tall = explode(':', trim($vuelta));
            if ($tall[1] > 0) {
                $inputTallas .= "<option value='$tall[0]'>" . $tall[0] . "</option>";
            } else {
                $inputTallas .= "<option value='$tall[0]' disabled='disabled'>" . $tall[0] . " (Agotado)</option>";
                $agotado = true;
            }
        }
        return array(
            $inputTallas,
            $agotado
        );
    }

    public function productoIndividual($ide)
    {
        $resConsulta = $this->BD->ConsultaWhereSimple("producto", "activo=? AND ID=?", array(1,$ide));
        $btnCarrito = '<button type="submit" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Añadir al carrito</button>';
       
        return '<div class="row" id="productMain">
                            <div class="col-sm-6">
                                <div id="mainImage">
                                    <img src="' . $resConsulta[0]["img1_prod"] . '" alt="" class="img-responsive">
                                </div>

                                ' . ($resConsulta[0]["preciodesc_prod"] > 0 ? '<div class="ribbon sale">
                                        <div class="theribbon">OFERTA</div>
                                        <div class="ribbon-background"></div>
                                    </div>
                                    <!-- /.ribbon -->' : "") . ($resConsulta[0]["nuevo_prod"] == 1 ? '<div class="ribbon new">
                                        <div class="theribbon">NUEVO</div>
                                        <div class="ribbon-background"></div>
                                    </div>
                                    <!-- /.ribbon -->' : "") . '

                            </div>
                            <div class="col-sm-6">
                                <div class="box">
                                <form method="post" action="procesos.php">
                                    <h1 class="text-center">' . $resConsulta[0]["nombre_prod"] . '</h1>
                                    <p class="price">' . ($resConsulta[0]["preciodesc_prod"] > 0 ? "<del>$" . $resConsulta[0]["precio_prod"] . "</del> $" . $resConsulta[0]["preciodesc_prod"] : "$" . $resConsulta[0]["precio_prod"]) . '</p>
                                        <div class="row">
                                        <div class="col-sm-12"><div class="form-group"><label for="lastname">Talla</label>
                                        
                                            <select name="tall_prod" class="form-control">
                                                ' . $this->generarTallas($resConsulta[0]["talla_prod"])[0] . '
                                            </select></div></div>
                                        <br>
                                        
                                        </div>
                                    <div class="row">
                                    
                                        <p class="text-center buttons">
                                            
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <input type="hidden" name="id_prod_cart" value="' . $resConsulta[0]["ID"] . '">
                                                    ' . $btnCarrito . '
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="hidden" name="id_prod_list" value="' . $resConsulta[0]["ID"] . '">
                                                    <button type="submit" class="btn btn-default"><i class="fa fa-heart"></i>Lista de deseos</button>
                                                </div>
                                            </div>
                                            
                                        </p>
                                    </div>
                                    </form>
                                </div>
                                
                                <div class="row" id="thumbs">
   
                                    <div class="col-xs-4">
                                        <a href="' . $resConsulta[0]["img1_prod"] . '" class="thumb">
                                            <img src="' . $resConsulta[0]["img1_prod"] . '" alt="" class="img-responsive">
                                        </a>
                                    </div>

                                    <div class="col-xs-4">
                                        <a href="' . $resConsulta[0]["img2_prod"] . '" class="thumb">
                                            <img src="' . $resConsulta[0]["img2_prod"] . '" alt="" class="img-responsive">
                                        </a>
                                    </div>

                                    ' . ($resConsulta[0]["img3_prod"] != "" ? '<div class="col-xs-4">
                                        <a href="' . $resConsulta[0]["img3_prod"] . '" class="thumb">
                                            <img src="' . $resConsulta[0]["img3_prod"] . '" alt="" class="img-responsive">
                                        </a>
                                    </div>' : '') . '

                                    ' . ($resConsulta[0]["img4_prod"] != "" ? '<div class="col-xs-4">
                                        <a href="' . $resConsulta[0]["img4_prod"] . '" class="thumb">
                                            <img src="' . $resConsulta[0]["img4_prod"] . '" alt="" class="img-responsive">
                                        </a>
                                    </div>' : '') . '

                                    ' . ($resConsulta[0]["img5_prod"] != "" ? '<div class="col-xs-4">
                                    <a href="' . $resConsulta[0]["img5_prod"] . '" class="thumb">
                                        <img src="' . $resConsulta[0]["img5_prod"] . '" alt="" class="img-responsive">
                                    </a>
                                </div>' : '') . '
                                
                                </div>
                                
                            </div>

                        </div>


                        <div class="box" id="details">
                        <ul>
                            <li><h4 class="subTitulos">Tipo de lavado</h4>
                            ' . $resConsulta[0]["lavado_prod"] . '</li>
                            <li><h4 class="subTitulos">Fit de la prenda</h4>
                            ' . $resConsulta[0]["fit_prod"] . '</li>
                            <li><h4 class="subTitulos">Color predominante</h4>
                            ' . $resConsulta[0]["color_prod"] . '</li>
                            <li><h4 class="subTitulos">Detalles adicionales</h4>
                           <p> ' . $resConsulta[0]["descripcion_prod"] . '</p></li>
                        </ul>
                        </div>';
    }
    //Mejorar la presentación de la descripción


    // SECCIONES DE PREGUNTAS FRECUENTES
    public function preguntas()
    {
        $r = $this->BD->ConsultaWhereSimple("preguntas", "activo=? GROUP BY orden ASC", array(
            1
        ));
        $i = "";
        foreach ($r as $contenido) {
            $i .= '<div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">

                                            <a data-toggle="collapse" data-parent="#accordion" href="#faq' . $contenido["ID"] . '">

                                                ' . $contenido["pregunta"] . '

                                            </a>

                                        </h4>
                                    </div>
                                    <div id="faq' . $contenido["ID"] . '" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <p>' . $contenido["respuesta"] . '</p>
                                        </div>
                                    </div>
                                </div>';
        }
        return $i;
    }

    // SECCIONES PERFIL USUARIO
    public function infoPer($ide)
    {
        switch ($_SESSION["usuarioJDV"]["vendedor_usuario"]) {
            case 1:
                $vendedor = array(
                    '
                        <select class="form-control" name="vendedor_info">
                                    <option value="1" selected>Si</option>
                                    <option value="0">No</option>
                                </select>',
                    1
                );
                break;
            case 0:
                $vendedor = array(
                    '
                        <select class="form-control" name="vendedor_info">
                                    <option value="1">Si</option>
                                    <option value="0" selected>No</option>
                                </select>',
                    0
                );
                break;
        }
        switch ($ide) {
            case 1:
                return '<ul class="nav nav-pills nav-justified">
                                    <li class="active"><a href="profile.php?ID=1"><i class="fa fa-user"></i><br>Información personal</a>
                                    </li>
                                    <li><a href="profile.php?ID=2"><i class="fa fa-map-marker"></i><br>Dirección</a>
                                    </li>
                                    <li><a href="profile.php?ID=3"><i class="fa fa-info-circle"></i><br>Información adicional</a>
                                    </li>
                                </ul>
                    <div class="content">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Nombre de Usuario">Nombre de usuario *</label>
                                                <input type="text" class="form-control" name="username_info" value="' . $_SESSION["usuarioJDV"]["username_usuario"] . '" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Nombre">Nombre(s) *</label>
                                                <input type="text" class="form-control" name="nombres_info" value="' . $_SESSION["usuarioJDV"]["nombres_usuario"] . '">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="APELLIDO">Apellidos *</label>
                                                <input type="text" class="form-control" name="apellidos_info" value="' . $_SESSION["usuarioJDV"]["apellidos_usuario"] . '">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Correo">Correo *</label>
                                                <input type="text" class="form-control" name="correo_info" value="' . $_SESSION["usuarioJDV"]["correo_usuario"] . '">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Contrasenia">Contraseña</label>
                                                <input type="password" class="form-control" name="password_info">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Confirmar contrasenia">Confirmar contraseña</label>
                                                <input type="password" class="form-control" name="password2_info">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </div>';
                break;
            case 2:
                return '<ul class="nav nav-pills nav-justified">
                                    <li><a href="profile.php?ID=1"><i class="fa fa-user"></i><br>Información personal</a>
                                    </li>
                                    <li class="active"><a href="profile.php?ID=2"><i class="fa fa-map-marker"></i><br>Dirección</a>
                                    </li>
                                    <li><a href="profile.php?ID=3"><i class="fa fa-info-circle"></i><br>Información adicional</a>
                                    </li>
                                </ul>
                    <div class="content">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Direcciono">Dirección</label>
                                                <input type="text" class="form-control" name="direccion_info" value="' . $_SESSION["usuarioJDV"]["direccion_usuario"] . '">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Nombre">Ciudad</label>
                                                <input type="text" class="form-control" name="ciudad_info" value="' . $_SESSION["usuarioJDV"]["ciudad_usuario"] . '">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="APELLIDO">Estado</label>
                                                <input type="text" class="form-control" name="estado_info" value="' . $_SESSION["usuarioJDV"]["estado_usuario"] . '">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Correo">Código Postal</label>
                                                <input type="text" class="form-control" name="codpost_info" value="' . $_SESSION["usuarioJDV"]["codpost_usuario"] . '">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->

                                    <div class="row">
                                    <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Contrasenia">Teléfono</label>
                                                <input type="text" class="form-control" name="telefono_info" value="' . $_SESSION["usuarioJDV"]["telefono_usuario"] . '">
                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                    <!-- /.row -->
                                </div>';
                break;
            case 3:
                return '<ul class="nav nav-pills nav-justified">
                                    <li><a href="profile.php?ID=1"><i class="fa fa-user"></i><br>Información personal</a>
                                    </li>
                                    <li><a href="profile.php?ID=2"><i class="fa fa-map-marker"></i><br>Dirección</a>
                                    </li>
                                    <li class="active"><a href="profile.php?ID=3"><i class="fa fa-info-circle"></i><br>Información adicional</a>
                                    </li>
                                </ul>
                                <div class="content">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Contrasenia">¿Eres vendedor?</label>
                                                ' . $vendedor[0] . '
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Contrasenia">Describe qué tipo de vendedor eres.</label>
                                                ' . ($vendedor[1] == 1 ? '<input type="text" class="form-control" name="tipovend_info" id="tipovend_info" value="' . $_SESSION["usuarioJDV"]["tipovendedor_usuario"] . '">' : '<input type="text" class="form-control" name="tipovend_info" id="tipovend_info" disabled="disabled">') . '
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </div>';
                break;
            default:
                return '<ul class="nav nav-pills nav-justified">
                                    <li class="active"><a href="profile.php?ID=1"><i class="fa fa-user"></i><br>Información personal</a>
                                    </li>
                                    <li><a href="profile.php?ID=2"><i class="fa fa-map-marker"></i><br>Dirección</a>
                                    </li>
                                    <li><a href="profile.php?ID=3"><i class="fa fa-info-circle"></i><br>Información adicional</a>
                                    </li>
                                </ul>
                    <div class="content">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Nombre de Usuario">Nombre de usuario *</label>
                                                <input type="text" class="form-control" name="username_info" value="' . $_SESSION["usuarioJDV"]["username_usuario"] . '" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Nombre">Nombre(s) *</label>
                                                <input type="text" class="form-control" name="nombres_info" value="' . $_SESSION["usuarioJDV"]["nombres_usuario"] . '">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="APELLIDO">Apellidos *</label>
                                                <input type="text" class="form-control" name="apellidos_info" value="' . $_SESSION["usuarioJDV"]["apellidos_usuario"] . '">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Correo">Correo *</label>
                                                <input type="text" class="form-control" name="correo_info" value="' . $_SESSION["usuarioJDV"]["correo_usuario"] . '">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Contrasenia">Contraseña *</label>
                                                <input type="password" class="form-control" name="password_info" value="' . $_SESSION["usuarioJDV"]["password_usuario"] .'">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Confirmar contrasenia">Confirmar contraseña *</label>
                                                <input type="password" class="form-control" name="password2_info"value="' . $_SESSION["usuarioJDV"]["password_usuario"] . '">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </div>';
                break;
        }
    }

    /**
     * Metodo que registra una nueva venta
     */
    public function updateCompraBeta($ref){
        if($ref!=""){
            $compra= $this->BD->ConsultaLibre("id_producto, piezas, talla", "ventas", "ventas.referencia=?",array($ref));
            $numCarrito=count($compra);
        					for ($i=0; $i<=$numCarrito; $i++) {
							 $idProducto=$compra[$i]["id_producto"];
            				 $numPiezas=$compra[$i]["piezas"];
							 $tallaSeleccionada=$compra[$i]["talla"];
                             $talla=$this->quitarStock($idProducto,$tallaSeleccionada,$numPiezas);
                             $this->actualizarStockProd($idProducto,$talla);	
						  }
        }
       
    }

    public function updatePersonal($nombres, $apellidos, $correo, $password = "")
    {
        if ($password == "") {
            $update = $this->BD->UpdateLibre("usuarios", "nombres_usuario=?, apellidos_usuario=?, correo_usuario=?", array(
                $nombres,
                $apellidos,
                $correo,
                $_SESSION["usuarioJDV"]["ID"]
            ), "ID=?");
        } else {
            $update = $this->BD->UpdateLibre("usuarios", "nombres_usuario=?, apellidos_usuario=?, correo_usuario=?, password_usuario=?", array(
                $nombres,
                $apellidos,
                $correo,
                $password,
                $_SESSION["usuarioJDV"]["ID"]
            ), "ID=?");
        }
        
        return $update;
    }

    public function updateDireccion($direccion, $ciudad = "México", $estado, $CP, $tel)
    {
        $update = $this->BD->UpdateLibre("usuarios", "direccion_usuario=?, ciudad_usuario=?, estado_usuario=?, codpost_usuario=?, telefono_usuario=?", array(
            $direccion,
            $ciudad,
            $estado,
            $CP,
            $tel,
            $_SESSION["usuarioJDV"]["ID"]
        ), "ID=?");
        return $update;
    }

    public function updateAdicional($vendedor, $tipo = 'NULL')
    {
        $update = $this->BD->UpdateLibre("usuarios", "vendedor_usuario=?, tipovendedor_usuario=?", array(
            $vendedor,
            $tipo,
            $_SESSION["usuarioJDV"]["ID"]
        ), "ID=?");
        return $update;
    }

    public function updatePasswordUser($id, $password){
        $passhash=hash('MD5',$password);
        $update = $this->BD->UpdateLibre("usuarios", "password_usuario=?", array(
           $passhash,
           $id
        ), "ID=?");
    }

    public function updateEstatusCompra($estatus, $referencia,$cod){
        $update=$this->BD->UpdateLibre("ventas","estatus=?,idEstatus=?",(array($estatus,$cod,$referencia)),"ventas.referencia=?");
        return $update;
    }
    //este esta mal
    public function updateInventario($referencia){
        if($referencia!="")
        $update=$this->BD->UpdateLibre("ventas","estatus=?",(array($estatus,$referencia)),"ventas.referencia=?");
        return $update;
    }

    public function avisoPrivacidad()
    {
        return "<section class='section_about' id='section_about' style='padding-top: 20px'>
        <div class='container'>
            <div class='row row_centered'>
                <div class='col-sm-12'>
                </div>
            </div>
            <div class='row'>
                <div class='col-sm-12'>
                  </div>
            </div>
        </div>
        <!-- / .container -->

    </section>";
    }

    public function verificarAcceso($password){
        if($password != ""){
            $buscarAcceso= $this->BD->ConsultaLibre("ID, nombres_usuario, apellidos_usuario, correo_usuario", "usuarios", "usuarios.password_usuario=?",array($password));
        }

        return $buscarAcceso;
    }

    public function buscarCuenta($email){
        if($email != ""){
            $buscarMail= $this->BD->ConsultaLibre("ID, nombres_usuario, apellidos_usuario, correo_usuario", "usuarios", "usuarios.correo_usuario=?",array($email));
        }
        return $buscarMail;
    }
    
    public function buscarCorreo($id){
        if($id != ""){
            $buscarMail= $this->BD->ConsultaLibre("ID ,correo_usuario", "usuarios", "usuarios.ID=?",array($id));
        }
        return $buscarMail;
    }
    
    //EN proceso
    public function buscarDatosCompraUsuario($referencia){
        if ($referencia != "") {
            $buscarCompra=$this->BD->ConsultaLibre("ventas.id_usuario", "ventas", "ventas.referencia=?", array($referencia), 1);
        }
        $buscarDatos=$this->BD->ConsultaLibre("nombres_usuario, apellidos_usuario, correo_usuario", "usuarios", "usuarios.ID=?", array($buscarCompra[0]["id_usuario"]));
    
        return $buscarDatos;
    }

    public function comprasListing($referenciaCompra)
   
    {
        $r = $this->BD->ConsultaJoinLibre("v.id ,v.referencia, p.nombre_prod, v.talla, v.piezas, p.fit_prod, p.precio_prod, p.img1_prod,v.fecha", "producto p", "ventas v", "p.ID=v.id_producto","v.referencia=".$referenciaCompra);
        $i = "";
        $idv=0;
        foreach ($r as $c) {
            $idv=$c["id"];
            $date = date_create($c["fecha"]);
            $i .= '<tr>
                    <td align="left">' . $c["nombre_prod"] . '</td>
                    <td align="left"><img src="' . $c["img1_prod"] . '" alt="'.$c["nombre_prod"].'" width="auto" height="175"></td>
                    <td align="left">' . $c["fit_prod"] . '</td>
                    <td align="left">' . $c["talla"] . '</td>
                    <td align="left">' . $c["piezas"] . '</td>
                    <td align="left">$' . $c["precio_prod"] . '</td>
                    </tr>';
        }
        return $i;
    }
 

    #NUEVO METODO
    public function sendMailEstatus($state_pol,$referencia,$cantidad){
        $nombre='';
        $apellido='';
        $tamanioArray=0;
        $telefono="+52 238 38 02 831";
          
        $arrayEstatus=$this->buscarDatosCompraUsuario($referencia);
        switch ($state_pol) {
              case 4:
                    /**
                     * MAIL TO COMPRADOR: EXITO
                     */
                    $to_email='ventas@jade-vu.com';
        
                    $correoCliente=$arrayEstatus[0]["correo_usuario"];
                    $asuntoComprador = "Su compra a sido confirmada";
                    $headersCliente = "MIME-Version: 1.0\r\n";
                    $headersCliente .= "Content-type: text/html; charset=iso-8859-1\r\n";
                    $headersCliente .= "From: Jade-VÜ Glam Jeans <".$to_email.">\r\n";
                    $headersCliente .= "Reply-To: ".$to_email."\r\n";
                    $headersCliente .= "Return-path: ".$to_email."\r\n";
                    $headersCliente .= 'X-Mailer: PHP/' . phpversion(); 
                    $web="http://www.jade-vu.com/web/";
                    $facebook="https://www.facebook.com/jadevu.jeans?fb_dtsg_ag=AdwboBDG5hcLJvoCvf3wILBq2BZkCQBK2e-6MRKLHqEyKg%3AAdx0xR0iRzb_ruBZsUzhVUQ5D6RtOdp_3KNicKGXdmEKrg";
                    $nombre=$arrayEstatus[0]["nombres_usuario"];
                    $apellido=$arrayEstatus[0]["apellidos_usuario"];

                    $mailComprador='<p>Estimado/a <strong>'.$nombre.' '.$apellido.'</strong></p>'. "\n";
                    $mailComprador.='<p>Es de nuestro agrado informarle que la transacci&#243;n con n&#250;mero
                    '.$referencia.' ha sido completada con &#233;xito.</p>'. "\n";
                    $mailComprador.='<p> Le solicitamos sea paciente mientras le hacemos llegar el no. de gu&#237;a
                    de su pedido para que conozca su estado en todo momento.</p>'. "\n";

                    $mailComprador.='<p>Si tiene alguna duda sobre la transacci&#243;n o quiere opinar sobre el
                    servicio estaremos encantados en atenderle con un mensaje a '.$to_email.' o al Tel&#233;fono '.$telefono.'</p>'. "\n";
                    $mailComprador.='<p>Sin m&#225;s por el momento, a nombre de todo el equipo de Jade-V&#220;
                    Glam Jeans le damos las gracias por su preferencia y lo invitamos a seguir
                    visit&#225;ndonos.</p>'. "\n";
                    $mailComprador.='<h5>Web oficial: <a href="'.$web.'">
                    Jade-VÜ Glam Jeans</a> 
                    o al correo <a href="mailto:'.$to_email.'">'.$to_email.'</a> </h5>'. "\n";
                    $mailComprador.='<h5>Facebook: <a href="'.$facebook.'">
                    Jade-VÜ Glam Jeans</a></h5>'. "\n";

                    mail($correoCliente, $asuntoComprador, $mailComprador,$headersCliente);

                    /**
                     * MAIL TO ADMIN: EXITO
                     */
                    $to_email='ventas@jade-vu.com';
                    
                    $asuntoAdmin = "La compra no. ".$referencia." acaba de ser CONFIRMADA";
                    $headersAdmin = "MIME-Version: 1.0\r\n";
                    $headersAdmin .= "Content-type: text/html; charset=iso-8859-1\r\n";
                    $headersAdmin .= "From: Sistema de Compras: Jade-VÜ Glam Jeans <".$to_email.">\r\n";
                    $headersAdmin .= "Reply-To: ".$to_email."\r\n";
                    $headersAdmin .= "Return-path: ".$to_email."\r\n";
                    $headersAdmin .= 'X-Mailer: PHP/' . phpversion(); 
                    $web="http://www.jade-vu.com/web/";
                    $webAdmin="http://www.jade-vu.com/web/BackEnd/";
                    $facebook="https://www.facebook.com/jadevu.jeans?fb_dtsg_ag=AdwboBDG5hcLJvoCvf3wILBq2BZkCQBK2e-6MRKLHqEyKg%3AAdx0xR0iRzb_ruBZsUzhVUQ5D6RtOdp_3KNicKGXdmEKrg";

                    $mailAdmin='<p>El sistema de confirmaci&#243;n de transacciones bancarias de Jade-V&#220;
                    le informa que la compra con c&#243;digo '.$referencia.' realizada por el
                    cliente de nombre '.$nombre.' '.$apellido.' por la cantidad de $'.$cantidad.' pesos ha
                    sido CONFIRMADA por el banco.</p>'. "\n";

                    $mailAdmin.='<p>Se le recomienda realizar &#233;l envi&#243; del pedido de su cliente a
                    brevedad, as&#237; como enviarle su correspondiente no. de gu&#237;a de la
                    paqueter&#237;a. Si necesita m&#225;s datos del cliente, recuerde que puede
                    acceder a ellos desde <h5><a href="'.$webAdmin.'">servicio de administraci&#243;n</a></h5> de la p&#225;gina.</p>'. "\n";

                    $mailAdmin.='<p> Es importante que recibe su historial bancario con frecuencia para poder
                    notar los movimientos que se realizan así como visitar la página de <h5><a href="https://www.payulatam.com/mx/">Payu</a></h5> para conocer sus ultimas actividades bancarias</p>'. "\n";
                    
                    $mailAdmin.='<h5>Web oficial: <a href="'.$web.'">
                    Jade-VÜ Glam Jeans</a></h5>'. "\n";
                    $mailAdmin.='<h5>Facebook: <a href="'.$facebook.'">
                    Jade-VÜ Glam Jeans</a></h5>'. "\n";

                    mail($to_email, $asuntoAdmin, $mailAdmin,$headersAdmin);

                    break;
              case 5:
                     /**
                     * MAIL TO COMPRADOR: CANCELADO
                     */
                    $to_email='ventas@jade-vu.com';

                    $correoCliente=$arrayEstatus[0]["correo_usuario"];
                    $asuntoComprador = "Jade-vu Glam Jeans : La transacción que solicito ha expirado";                    $headersCliente = "MIME-Version: 1.0\r\n";
                    $headersCliente .= "Content-type: text/html; charset=iso-8859-1\r\n";
                    $headersCliente .= "From: Jade-VÜ Glam Jeans <".$to_email.">\r\n";
                    $headersCliente .= "Reply-To: ".$to_email."\r\n";
                    $headersCliente .= "Return-path: ".$to_email."\r\n";
                    $headersCliente .= 'X-Mailer: PHP/' . phpversion(); 
                    $web="http://www.jade-vu.com/web/";
                    $facebook="https://www.facebook.com/jadevu.jeans?fb_dtsg_ag=AdwboBDG5hcLJvoCvf3wILBq2BZkCQBK2e-6MRKLHqEyKg%3AAdx0xR0iRzb_ruBZsUzhVUQ5D6RtOdp_3KNicKGXdmEKrg";

                    $nombre=$arrayEstatus[0]["nombres_usuario"];
                    $apellido=$arrayEstatus[0]["apellidos_usuario"];

                    $mailComprador='<p>Estimado/a <strong>'.$nombre.' '.$apellido.'</strong></p>'. "\n";
                    $mailComprador.='<p>Lamentamos informarle que la transacci&#243;n con n&#250;mero
                    '.$referencia.' ha expirado, por lo que es nuestro deber informarle que ya no debe realizar el dep&#243;sito del efectivo, no obstante, puede realizar un nuevo pedido en cualquier momento. Estaremos encantados en atenderle.</p>'. "\n";
                    
                    $mailComprador.='<p>Si tiene alguna duda sobre la transacci&#243;n o quiere opinar sobre el
                    servicio estaremos encantados en atenderle con un mensaje a '.$to_email.'
                    o al Tel&#233;fono '.$telefono.' </p>'. "\n";
                    
                    $mailComprador.='<p>Sin m&#225;s por el momento, a nombre de todo el equipo de Jade-V&#220;
                    Glam Jeans le damos las gracias por su preferencia y lo invitamos a seguir
                    visit&#225;ndonos.</p>'. "\n";
                    $mailComprador.='<h5>Web oficial: <a href="'.$web.'">
                    Jade-VÜ Glam Jeans</a> 
                    o al correo <a href="mailto:'.$to_email.'">'.$to_email.'</a> </h5>'. "\n";
                    $mailComprador.='<h5>Facebook: <a href="'.$facebook.'">
                    Jade-VÜ Glam Jeans</a></h5>'. "\n";

                    mail($correoCliente, $asuntoComprador, $mailComprador,$headersCliente);

                    /**
                     * MAIL TO ADMIN: CANCELADO
                     */
                    $to_email='ventas@jade-vu.com';

                    $asuntoAdmin = "La compra no. ".$referencia." acaba de EXPIRAR";
                    $headersAdmin = "MIME-Version: 1.0\r\n";
                    $headersAdmin .= "Content-type: text/html; charset=iso-8859-1\r\n";
                    $headersAdmin .= "From: Sistema de Compras: Jade-VÜ Glam Jeans <".$to_email.">\r\n";
                    $headersAdmin .= "Reply-To: ".$to_email."\r\n";
                    $headersAdmin .= "Return-path: ".$to_email."\r\n";
                    $headersAdmin .= 'X-Mailer: PHP/' . phpversion(); 
                    $web="http://www.jade-vu.com/web/";
                    $webAdmin="http://www.jade-vu.com/web/BackEnd/";
                    $facebook="https://www.facebook.com/jadevu.jeans?fb_dtsg_ag=AdwboBDG5hcLJvoCvf3wILBq2BZkCQBK2e-6MRKLHqEyKg%3AAdx0xR0iRzb_ruBZsUzhVUQ5D6RtOdp_3KNicKGXdmEKrg";

                    $mailAdmin='<p>El sistema de confirmaci&#243;n de transacciones bancarias de Jade-V&#220;
                    le informa que la compra con c&#243;digo '.$referencia.' realizada por el
                    cliente de nombre '.$nombre.' '.$apellido.' por la cantidad de $'.$cantidad.' pesos ha EXPIRADO por inactividad.</p>'. "\n";

                    $mailAdmin.='<p>Si necesita m&#225;s datos del cliente, recuerde que puede
                    acceder a ellos desde <h5><a href="'.$webAdmin.'">servicio de administraci&#243;n</a></h5> de la p&#225;gina.</p>'. "\n";

                    $mailAdmin.='<p> Es importante que recibe su historial bancario con frecuencia para poder
                    notar los movimientos que se realizan así como visitar la página de <h5><a href="https://www.payulatam.com/mx/">Payu</a></h5> para conocer sus ultimas actividades bancarias</p>'. "\n";
                    
                    $mailAdmin.='<h5>Web oficial: <a href="'.$web.'">
                    Jade-VÜ Glam Jeans</a></h5>'. "\n";
                    $mailAdmin.='<h5>Facebook: <a href="'.$facebook.'">
                    Jade-VÜ Glam Jeans</a></h5>'. "\n";

                    mail($to_email, $asuntoAdmin, $mailAdmin,$headersAdmin);

                    break;
              case 6:
                                     /**
                     * MAIL TO CLIENTE: RECHAZADO
                     */
              $to_email='ventas@jade-vu.com';

              $correoCliente=$arrayEstatus[0]["correo_usuario"];
              $asuntoComprador = "Jade-vu Glam Jeans : La transacción que solicito ha sido rechazada";                    $headersCliente = "MIME-Version: 1.0\r\n";
              $headersCliente .= "Content-type: text/html; charset=iso-8859-1\r\n";
              $headersCliente .= "From: Jade-VÜ Glam Jeans <".$to_email.">\r\n";
              $headersCliente .= "Reply-To: ".$to_email."\r\n";
              $headersCliente .= "Return-path: ".$to_email."\r\n";
              $headersCliente .= 'X-Mailer: PHP/' . phpversion(); 
              $web="http://www.jade-vu.com/web/";
              $facebook="https://www.facebook.com/jadevu.jeans?fb_dtsg_ag=AdwboBDG5hcLJvoCvf3wILBq2BZkCQBK2e-6MRKLHqEyKg%3AAdx0xR0iRzb_ruBZsUzhVUQ5D6RtOdp_3KNicKGXdmEKrg";

              $nombre=$arrayEstatus[0]["nombres_usuario"];
              $apellido=$arrayEstatus[0]["apellidos_usuario"];

              $mailComprador='<p>Estimado/a <strong>'.$nombre.' '.$apellido.'</strong></p>'. "\n";
              $mailComprador.='<p>Lamentamos informarle que la transacci&#243;n con n&#250;mero
              '.$referencia.' ha sido rechazada, por lo que es nuestro deber informarle que debe verificar su estado de cuenta para poder notar su devolución.</p>'. "\n";
              
              $mailComprador.='<p>Si tiene alguna duda sobre la transacci&#243;n o tiene problemas para la resolución de su problema, estaremos encantados en atenderle con un mensaje a '.$to_email.'
              o al Tel&#233;fono '.$telefono.' </p>'. "\n";
              
              $mailComprador.='<p>Sin m&#225;s por el momento, a nombre de todo el equipo de Jade-V&#220;
              Glam Jeans le damos las gracias por su preferencia y lo invitamos a seguir
              visit&#225;ndonos.</p>'. "\n";
              $mailComprador.='<h5>Web oficial: <a href="'.$web.'">
              Jade-VÜ Glam Jeans</a> 
              o al correo <a href="mailto:'.$to_email.'">'.$to_email.'</a> </h5>'. "\n";
              $mailComprador.='<h5>Facebook: <a href="'.$facebook.'">
              Jade-VÜ Glam Jeans</a></h5>'. "\n";

              mail($correoCliente, $asuntoComprador, $mailComprador,$headersCliente);

              /**
               * MAIL TO ADMIN: CANCELADO
               */
              $to_email='ventas@jade-vu.com';
              
              $asuntoAdmin = "La compra no. ".$referencia." acaba de ser RECHAZADA";
              $headersAdmin = "MIME-Version: 1.0\r\n";
              $headersAdmin .= "Content-type: text/html; charset=iso-8859-1\r\n";
              $headersAdmin .= "From: Sistema de Compras: Jade-VÜ Glam Jeans <".$to_email.">\r\n";
              $headersAdmin .= "Reply-To: ".$to_email."\r\n";
              $headersAdmin .= "Return-path: ".$to_email."\r\n";
              $headersAdmin .= 'X-Mailer: PHP/' . phpversion(); 
              $web="http://www.jade-vu.com/web/";
              $webAdmin="http://www.jade-vu.com/web/BackEnd/";
              $facebook="https://www.facebook.com/jadevu.jeans?fb_dtsg_ag=AdwboBDG5hcLJvoCvf3wILBq2BZkCQBK2e-6MRKLHqEyKg%3AAdx0xR0iRzb_ruBZsUzhVUQ5D6RtOdp_3KNicKGXdmEKrg";

              $mailAdmin='<p>El sistema de confirmaci&#243;n de transacciones bancarias de Jade-V&#220;
              le informa que la compra con c&#243;digo '.$referencia.' realizada por el
              cliente de nombre '.$nombre.' '.$apellido.' por la cantidad de $'.$cantidad.' pesos ha sido RECHAZADA por un problema con la transferencia o deposito bancario.</p>'. "\n";

              $mailAdmin.='<p>Si necesita m&#225;s datos del cliente, recuerde que puede
              acceder a ellos desde <h5><a href="'.$webAdmin.'">servicio de administraci&#243;n</a></h5> de la p&#225;gina.</p>'. "\n";

              $mailAdmin.='<p> Es importante que recibe su historial bancario con frecuencia para poder
              notar los movimientos que se realizan así como visitar la página de <h5><a href="https://www.payulatam.com/mx/">Payu</a></h5> para conocer sus ultimas actividades bancarias</p>'. "\n";
              
              $mailAdmin.='<h5>Web oficial: <a href="'.$web.'">
              Jade-VÜ Glam Jeans</a></h5>'. "\n";
              $mailAdmin.='<h5>Facebook: <a href="'.$facebook.'">
              Jade-VÜ Glam Jeans</a></h5>'. "\n";

              mail($to_email, $asuntoAdmin, $mailAdmin,$headersAdmin);

                    break;
              default:
                    break;
        }
  }

 

}
