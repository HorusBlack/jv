<?php
/**
 * Clase donde se maneja el inicio de sesion
 * Tambien se maneja el acceso al pago
 */

include_once 'PHP/util.php';
include_once 'PHP/bd.php';
include_once 'PHP/secciones.php';
$secciones = new secciones($BD);
$us='';
$ps='';

//Si el usuario y contraseña existen, y el inicio de sesion no esta iniciada
if (isset($_POST["correo"]) && isset($_POST["password"]) && ! $secciones->sesionIniciada()) {
    if ($_POST["correo"] != "" && $_POST["password"] != "") {
        $us=$_POST["correo"];
        $ps=$_POST["password"];
        if ($secciones->autentificar($us,$ps)) {
            $secciones->redireccionarJS("index.php");
        } else {
            $secciones->alerta("El Password o usuario son incorrectos");
            $secciones->redireccionarJS("index.php");
        }
    }
    //Si ya sesion ya se inicio
} else if (isset($_POST["correo"]) && isset($_POST["password"]) && $secciones->sesionIniciada()) {
    $secciones->redireccionarJS("index.php");
}
 /**
  * Preguntar que eso de ID_Prueba, talla_prueba
  */
else if (isset($_GET["ID_prueba"]) && isset($_GET["talla_prueba"]) && isset($_GET["cant_prueba"])) {
    echo $secciones->quitarStock($_GET["ID_prueba"], $_GET["talla_prueba"], $_GET["cant_prueba"]);
}

/**
 * REGISTRO NUEVO
 */
else if (isset($_POST["username_reg"]) && isset($_POST["nombres_reg"]) && isset($_POST["apellidos_reg"]) && isset($_POST["correo_reg"]) && isset($_POST["password_reg"]) && isset($_POST["password2_reg"])) {
    if ($_POST["username_reg"] != "" && $_POST["nombres_reg"] != "" && $_POST["apellidos_reg"] != "" && $_POST["correo_reg"] != "" && $_POST["password_reg"] != "" && $_POST["password2_reg"] != "") {
        if ($_POST["password_reg"] == $_POST["password2_reg"]) {
            $pass_cifrada=hash('MD5',filter_input(INPUT_POST, "password_reg", FILTER_SANITIZE_STRING));
            $dep = $secciones->registro(filter_input(INPUT_POST, "username_reg", FILTER_SANITIZE_STRING), filter_input(INPUT_POST, "nombres_reg", FILTER_SANITIZE_STRING), filter_input(INPUT_POST, "apellidos_reg", FILTER_SANITIZE_STRING), filter_input(INPUT_POST, "correo_reg", FILTER_SANITIZE_EMAIL), $pass_cifrada);
            if ($dep == "true") {
                $secciones->alerta("Registro exitoso");
                $secciones->autentificar(filter_input(INPUT_POST, "correo_reg", FILTER_SANITIZE_EMAIL), $pass_cifrada);
                $secciones->redireccionarJS("profile.php?ID=1");
            } else {
                if ($dep[1] == 1062) {
                    $secciones->alerta($dep[1] . " El nombre de usuario ya existe o la dirección de correo ya está en uso, por favor elija otro nombre de usuario o pongase en contacto con nosotros si cree que han utilizado su dirección de correo electrónico.");
                    $secciones->redireccionarJS("register.php");
                } else {
                    $secciones->alerta($dep[1] . " Algo salió mal :( intentalo de nuevo");
                    $secciones->redireccionarJS("register.php");
                }
            }
        } else {
            $secciones->alerta("Las contraseñas no coinciden, por favor verifícalas.");
            $secciones->redireccionarJS("register.php");
        }
    } else {
        $secciones->alerta("Los campos marcados con (*) son obligatorios.");
        $secciones->redireccionarJS("register.php");
    }

}

// ACTUALIZAR DATOS PERSONALES USUARIO
else if (isset($_POST["nombres_info"]) && isset($_POST["apellidos_info"]) && isset($_POST["correo_info"]) && isset($_POST["password_info"]) && isset($_POST["password2_info"])) {
    if ($_POST["nombres_info"] != "" && $_POST["apellidos_info"] != "" && $_POST["correo_info"] != "") {
        if ($_POST["password_info"] != "" && $_POST["password2_info"] != "") {
            if ($_POST["password_info"] == $_POST["password2_info"]) {
                $pass_cifrada=hash('MD5',filter_input(INPUT_POST, "password_info", FILTER_SANITIZE_STRING));
                $dep = $secciones->updatePersonal(filter_var($_POST["nombres_info"], FILTER_SANITIZE_STRING), filter_var($_POST["apellidos_info"], FILTER_SANITIZE_STRING), filter_var($_POST["correo_info"], FILTER_SANITIZE_EMAIL), $pass_cifrada);
                if ($dep == "true") {
                    $secciones->alerta("Datos modificados con exito!");
                    $secciones->refershuser(filter_input(INPUT_POST, "correo_info", FILTER_SANITIZE_EMAIL));
                    $secciones->redireccionarJS("profile.php?ID=2");
                } else {
                    if ($dep[0][1] == 1062) {
                        $secciones->alerta($dep[1] . " El nombre de usuario ya existe o la dirección de correo ya está en uso, por favor elija otro nombre de usuario o pongase en contacto con nosotros si cree que han utilizado su dirección de correo electrónico.");
                        $secciones->redireccionarJS("profile.php?ID=1");
                    } else {
                        $secciones->alerta($dep[1] . " aaa Algo salio mal :( intentalo de nuevo");
                        $secciones->redireccionarJS("profile.php?ID=1");
                    }
                }
            } else {
                $secciones->alerta("Las contraseñas no coinciden, por favor revisalas.");
                $secciones->redireccionarJS("profile.php?ID=1");
            }
        } else {
            if ($_POST["password_info"] == $_POST["password2_info"]) {
                $dep = $secciones->updatePersonal(filter_var($_POST["nombres_info"], FILTER_SANITIZE_STRING), filter_var($_POST["apellidos_info"], FILTER_SANITIZE_STRING), filter_var($_POST["correo_info"], FILTER_SANITIZE_EMAIL));
                if ($dep == "true") {
                    $secciones->alerta("Datos modificados con exito!");
                    $secciones->refershuser(filter_input(INPUT_POST, "correo_info", FILTER_SANITIZE_EMAIL));
                    $secciones->redireccionarJS("profile.php?ID=2");
                } else {
                    if ($dep[0][1] == 1062) {
                        $secciones->alerta($dep[1] . " El nombre de usuario ya existe o la dirección de correo ya está en uso, por favor elija otro nombre de usuario o pongase en contacto con nosotros si cree que han utilizado su dirección de correo electrónico.");
                        $secciones->redireccionarJS("profile.php?ID=1");
                    } else {
                        $secciones->alerta($dep[1] . "Algo salio mal, intentalo de nuevo");
                        $secciones->redireccionarJS("profile.php?ID=1");
                    }
                }
            } else {
                $secciones->alerta("Las contraseñas no coinciden, por favor revisalas.");
                $secciones->redireccionarJS("profile.php?ID=1");
            }
        }
    } else {
        $secciones->alerta("Los campos marcados con (*) son obligatorios.");
        $secciones->redireccionarJS("profile.php?ID=1");
    }
}

// ACTUALIZAR DIRECCION USUARIO
else if (isset($_POST["direccion_info"]) && isset($_POST["ciudad_info"]) && isset($_POST["estado_info"]) && isset($_POST["codpost_info"]) && isset($_POST["telefono_info"])) {
    if ($secciones->updateDireccion(filter_var($_POST["direccion_info"], FILTER_SANITIZE_STRING), filter_var($_POST["ciudad_info"], FILTER_SANITIZE_STRING), filter_var($_POST["estado_info"], FILTER_SANITIZE_STRING), filter_var($_POST["codpost_info"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["telefono_info"], FILTER_SANITIZE_NUMBER_INT))) {
        $secciones->alerta("Datos modificados con exito!");
        $secciones->refershuser($_SESSION["usuarioJDV"][correo_usuario]);
        $secciones->redireccionarJS("profile.php?ID=3");
    } else {
        $secciones->alerta("Algo salio mal :( intentalo de nuevo");
        $secciones->redireccionarJS("profile.php?ID=2");
    }
}

// ACTUALIZAR INFORMACION ADICIONAL USUARIO
else if (isset($_POST["vendedor_info"])) {
    if ($_POST["vendedor_info"] != "") {
        if ($secciones->updateAdicional(filter_var($_POST["vendedor_info"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["tipovend_info"], FILTER_SANITIZE_STRING))) {
            $secciones->alerta("Datos modificados con exito!");
            $secciones->refershuser($_SESSION["usuarioJDV"][correo_usuario], $_SESSION["usuarioJDV"][password_usuario]);
            $secciones->redireccionarJS("index.php");
        } else {
            $secciones->alerta("Algo salio mal :( intentalo de nuevo");
            $secciones->redireccionarJS("profile.php?ID=3");
        }
    } else {
        $secciones->alerta("Los campos marcados con (*) son obligatorios.");
        $secciones->redireccionarJS("profile.php?ID=3");
    }
}

// AÑADIR PRODUCTO AL CARRO DESDE BASKET.PHP
/**
 * Puede que aqui se pueda sacar el numero
 */
else if (isset($_POST["id_prod_cart_add"]) && isset($_POST["talla_prod_add"]) && isset($_POST["cantidad_prod_add"])) {
    $secciones->addProd(filter_var($_POST["id_prod_cart_add"], FILTER_SANITIZE_NUMBER_INT), 
                        filter_var($_POST["talla_prod_add"], FILTER_SANITIZE_STRING), 
                        filter_var($_POST["cantidad_prod_add"], FILTER_SANITIZE_NUMBER_INT));
    $secciones->redireccionarJS("basket.php");
}// AÑADIR PRODUCTO DESDE LA PAGINA DEL PRODUCTO INDIVIDUAL
else if (isset($_POST["id_prod_cart"]) && isset($_POST["tall_prod"])) {
    $secciones->addProd(filter_var($_POST["id_prod_cart"], FILTER_SANITIZE_NUMBER_INT), $_POST["tall_prod"]);
    $secciones->redireccionarJS("basket.php");
}// BORRAR PRODUCTO DEL CARRITO
else if (isset($_GET["deleteprod"]) && isset($_GET["talla"])) {
    $secciones->deleteprod($_GET["deleteprod"], $_GET["talla"]);
    if ($secciones->contarprodsCarrito() <= 0) {
        unset($_SESSION["carritoJDV"]);
    }
    $secciones->redireccionarJS("basket.php");
}

// VALIDACIÓN PARA PASAR AL PROCESO DE COMPRA
else if (isset($_POST["compra"])) {
    if ($_POST["compra"] == 1 && ! $secciones->sesionIniciada()) { // si no ha iniciado sesion
        $secciones->alerta("Debes tener una cuenta para poder realizar compras.");
        $secciones->redireccionarJS("register.php");
    } else if ($_POST["compra"] == 1 && $secciones->sesionIniciada()) {
        if ($secciones->contarprodsCarrito() > 0) { // si hay producto
            if ($_SESSION["usuarioJDV"]['telefono_usuario'] == "" || $_SESSION["usuarioJDV"]['codpost_usuario'] == "" || $_SESSION["usuarioJDV"]['direccion_usuario'] == "") { // si no a llenado datos
                $secciones->alerta("Debes completar tus datos antes de comprar");
                $secciones->redireccionarJS("profile.php?ID=2");
            } else {
                $secciones->redireccionarJS("./payu/procesoActual.php");
            }
        } else {
            $secciones->alerta("Debes de agregar productos a tu carrito de compra");
            $secciones->redireccionarJS("basket.php");
        }
    }
}
