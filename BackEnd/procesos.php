<?php
include_once 'PHP/util.php';
include_once 'PHP/bd.php';
include_once 'PHP/secciones.php';
$secciones = new secciones($BD);
if (isset($_POST["usuario"]) && isset($_POST["password"]) && ! $secciones->sesionIniciada()) {
    if ($_POST["usuario"] != "" && $_POST["password"] != "") {
        if ($secciones->autentificarBCKND(filter_var($_POST["usuario"], FILTER_SANITIZE_STRING), filter_var($_POST["password"], FILTER_SANITIZE_STRING))) {
            $secciones->alerta("Login correcto!");
            $secciones->redireccionarJS("sections.php");
        } else {
            $secciones->alerta("El Password o usuario son incorrectos");
            $secciones->redireccionarJS("index.php");
        }
    }
} else if (isset($_POST["usuario"]) && isset($_POST["password"]) && $secciones->sesionIniciada()) {
    $secciones->alerta("SESION YA INICIADA");
    $secciones->redireccionarJS("sections.php");
}// NOTICIAS
else if (isset($_POST["titulo_noticia"]) && isset($_POST["autor_noticia"]) && isset($_POST["imagen_noticia"]) && isset($_POST["cuerpo"]) && isset($_POST["accion"])) {
    if ($_POST["titulo_noticia"] != "" && $_POST["autor_noticia"] != "" && $_POST["imagen_noticia"] != "" && $_POST["cuerpo"] != "") {
        // NOTICIA NUEVA
        if ($_POST["accion"] == 1) {
            if ($secciones->blogNew(filter_var($_POST["titulo_noticia"], FILTER_SANITIZE_STRING), filter_var($_POST["autor_noticia"], FILTER_SANITIZE_STRING), filter_var($_POST["imagen_noticia"], FILTER_SANITIZE_STRING), $_POST["cuerpo"])) {
                $secciones->alerta("Datos guardados con exito!");
                $secciones->redireccionarJS("entradasblog.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("entradasblog.php");
            }
            // MODIFICAR NOTICIA
        } else if ($_POST["accion"] == 2) {
            if ($secciones->blogUpdate(filter_var($_POST["titulo_noticia"], FILTER_SANITIZE_STRING), filter_var($_POST["autor_noticia"], FILTER_SANITIZE_STRING), filter_var($_POST["imagen_noticia"], FILTER_SANITIZE_STRING), $_POST["cuerpo"], filter_var($_GET["IDBLOG"], FILTER_SANITIZE_NUMBER_INT))) {
                $secciones->alerta("Datos modificados con exito!");
                $secciones->redireccionarJS("entradasblog.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("entradasblog.php");
            }
            // ERROR DE VAIRABLE ACCION
        } else {
            $secciones->alerta("Algo salió mal :( intentalo de nuevo");
            $secciones->redireccionarJS("entradasblog.php");
        }
        // ERROR
    } else {
        $secciones->alerta("Por favor, llena los datos marcados con (*)");
        if (isset($_GET["IDBLOG"])) {
            $secciones->redireccionarJS("blog_edit.php?ID=" . $_GET["IDBLOG"]);
        } else {
            $secciones->redireccionarJS("blog_edit.php?ID=");
        }
    }
}// SLIDER PRINCIPAL
else if (isset($_POST["nombre_sliderp"]) && isset($_POST["orden_sliderp"]) && isset($_POST["imagen_sliderp"]) && isset($_POST["enlace_sliderp"])) {
    if ($_POST["nombre_sliderp"] != "" && $_POST["orden_sliderp"] != "" && $_POST["imagen_sliderp"] != "") {
        // IMAGEN DE SLIDERP NUEVA
        if ($_POST["accion"] == 1) {
            if ($secciones->sliderpNew(filter_var($_POST["nombre_sliderp"], FILTER_SANITIZE_STRING), filter_var($_POST["orden_sliderp"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["imagen_sliderp"], FILTER_SANITIZE_STRING), filter_var($_POST["enlace_sliderp"], FILTER_SANITIZE_URL))) {
                $secciones->alerta("Datos guardados con exito!");
                $secciones->redireccionarJS("sliderprincipal.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("sliderprincipal.php");
            }
            // MODIFICAR IMAGEN DE SLIDERP
        } else if ($_POST["accion"] == 2) {
            if ($secciones->sliderpUpdate(filter_var($_POST["nombre_sliderp"], FILTER_SANITIZE_STRING), filter_var($_POST["orden_sliderp"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["imagen_sliderp"], FILTER_SANITIZE_STRING), filter_var($_POST["enlace_sliderp"], FILTER_SANITIZE_URL), filter_var($_GET["IDSLIDERP"], FILTER_SANITIZE_NUMBER_INT))) {
                $secciones->alerta("Datos modificados con exito!");
                $secciones->redireccionarJS("sliderprincipal.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("sliderprincipal.php");
            }
            // ERROR DE VAIRABLE ACCION
        } else {
            $secciones->alerta("Algo salió mal :( intentalo de nuevo");
            $secciones->redireccionarJS("sliderprincipal.php");
        }
        // ERROR
    } else {
        $secciones->alerta("Por favor, llena los datos marcados con (*)");
        if (isset($_GET["IDSLIDERP"])) {
            $secciones->redireccionarJS("sliderp_edit.php?ID=" . $_GET["IDSLIDERP"]);
        } else {
            $secciones->redireccionarJS("sliderp_edit.php?ID=");
        }
    }
}// SLIDER MARCAS
else if (isset($_POST["nombre_sliderm"]) && isset($_POST["orden_sliderm"]) && isset($_POST["descripcion_sliderm"]) && isset($_POST["imagen_sliderm"]) && isset($_POST["enlace_sliderm"])) {
    if ($_POST["nombre_sliderm"] != "" && $_POST["orden_sliderm"] != "" && $_POST["imagen_sliderm"] != "") {
        if ($_POST["accion"] == 1) {
            if ($secciones->slidermNew(filter_var($_POST["nombre_sliderm"], FILTER_SANITIZE_STRING), filter_var($_POST["orden_sliderm"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["imagen_sliderm"], FILTER_SANITIZE_STRING), filter_var($_POST["descripcion_sliderm"], FILTER_SANITIZE_STRING), filter_var($_POST["enlace_sliderm"], FILTER_SANITIZE_URL))) {
                $secciones->alerta("Datos guardados con exito!");
                $secciones->redireccionarJS("slidermarcas.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("slidermarcas.php");
            }
            // MODIFICAR IMAGEN DE SLIDERM
        } else if ($_POST["accion"] == 2) {
            if ($secciones->slidermUpdate(filter_var($_POST["nombre_sliderm"], FILTER_SANITIZE_STRING), filter_var($_POST["orden_sliderm"], FILTER_SANITIZE_NUMBER_INT), filter_var($_GET["IDSLIDERM"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["imagen_sliderm"], FILTER_SANITIZE_URL), $_POST["descripcion_sliderm"], filter_var($_POST["enlace_sliderm"], FILTER_SANITIZE_URL))) {
                $secciones->alerta("Datos modificados con exito!");
                $secciones->redireccionarJS("slidermarcas.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("slidermarcas.php");
            }
            // ERROR EN LA VARIABLE ACCION
        } else {
            $secciones->alerta("Algo salió mal :( intentalo de nuevo");
            $secciones->redireccionarJS("slidermarcas.php");
        }
    } else {
        $secciones->alerta("Por favor, llena los datos marcados con (*)");
        if (isset($_GET["IDSLIDERM"])) {
            $secciones->redireccionarJS("sliderm_edit.php?ID=" . $_GET["IDSLIDERM"]);
        } else {
            $secciones->redireccionarJS("sliderm_edit.php?ID=");
        }
    }
}// PREGUNTAS FRECUENTES
else if (isset($_POST["pregunta_preguntas"]) && isset($_POST["respuesta_preguntas"]) && isset($_POST["orden_preguntas"])) {
    if ($_POST["pregunta_preguntas"] != "" && $_POST["respuesta_preguntas"] != "" && $_POST["orden_preguntas"] != "") {
        // PREGUNTA NUEVA
        if ($_POST["accion"] == 1) {
            if ($secciones->preguntasNew(filter_var($_POST["pregunta_preguntas"], FILTER_SANITIZE_STRING), $_POST["respuesta_preguntas"], filter_var($_POST["orden_preguntas"], FILTER_SANITIZE_NUMBER_INT))) {
                $secciones->alerta("Datos guardados con exito!");
                $secciones->redireccionarJS("preguntasfrec.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("preguntasfrec.php");
            }
        } // MODIFICAR PREGUNTA
else if ($_POST["accion"] == 2) {
            if ($secciones->preguntasUpdate(filter_var($_POST["pregunta_preguntas"], FILTER_SANITIZE_STRING), $_POST["respuesta_preguntas"], filter_var($_POST["orden_preguntas"], FILTER_SANITIZE_NUMBER_INT), filter_var($_GET["IDPREG"], FILTER_SANITIZE_NUMBER_INT))) {
                $secciones->alerta("Datos modificados con exito!");
                $secciones->redireccionarJS("preguntasfrec.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("preguntasfrec.php");
            }
            // ERROR DE VAIRABLE ACCION
        } else {
            $secciones->alerta("Algo salió mal :( intentalo de nuevo");
            $secciones->redireccionarJS("preguntasfrec.php");
        }
    } else {
        $secciones->alerta("Por favor, llena los datos marcados con (*)");
        if (isset($_GET["IDPREG"])) {
            $secciones->redireccionarJS("preguntas_edit.php?ID=" . $_GET["IDPREG"]);
        } else {
            $secciones->redireccionarJS("preguntas_edit.php?ID=");
        }
    }
}// BANNER PUBLICIDAD
else if (isset($_POST["nombre_banner"]) && isset($_POST["orden_banner"]) && isset($_POST["imagen_banner"]) && isset($_POST["enlace_banner"])) {
    if ($_POST["nombre_banner"] != "" && $_POST["orden_banner"] != "" && $_POST["imagen_banner"] != "") {
        // BANNER NUEVO
        if ($_POST["accion"] == 1) {
            if ($secciones->bannersNew(filter_var($_POST["nombre_banner"], FILTER_SANITIZE_STRING), filter_var($_POST["orden_banner"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["imagen_banner"], FILTER_SANITIZE_URL), filter_var($_POST["enlace_banner"], FILTER_SANITIZE_URL))) {
                $secciones->alerta("Datos guardados con exito!");
                $secciones->redireccionarJS("publicidad.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("publicidad.php");
            }
        } // MODIFICAR BANNER
else if ($_POST["accion"] == 2) {
            if ($secciones->bannersUpdate(filter_var($_POST["nombre_banner"], FILTER_SANITIZE_STRING), filter_var($_POST["orden_banner"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["imagen_banner"], FILTER_SANITIZE_URL), filter_var($_POST["enlace_banner"], FILTER_SANITIZE_URL), filter_var($_GET["IDBANNER"], FILTER_SANITIZE_NUMBER_INT))) {
                $secciones->alerta("Datos modificados con exito!");
                $secciones->redireccionarJS("publicidad.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("publicidad.php");
            }
        } // ERROR DE VARIABLE ACCION
else {
            $secciones->alerta("Algo salió mal :( intentalo de nuevo");
            $secciones->redireccionarJS("publicidad.php");
        }
    } // ERROR
else {
        $secciones->alerta("Por favor, llena los datos marcados con (*)");
        if (isset($_GET["IDBANNER"])) {
            $secciones->redireccionarJS("publicidad_edit.php?ID=" . $_GET["IDBANNER"]);
        } else {
            $secciones->redireccionarJS("publicidad_edit.php?ID=");
        }
    }
}// INFORMACIÓN DE LA EMPRESA
else if (isset($_POST["nombre_empresa"]) && isset($_POST["direccion_empresa"]) && isset($_POST["cp_empresa"]) && isset($_POST["telefono_empresa"]) && isset($_POST["correo_empresa"]) && isset($_POST["fb_empresa"]) && isset($_POST["insta_empresa"]) && isset($_POST["twitter_empresa"])) {
    if ($_POST["nombre_empresa"] != "" && $_POST["direccion_empresa"] != "" && $_POST["cp_empresa"] != "" && $_POST["telefono_empresa"] != "" && $_POST["correo_empresa"] != "") {
        if ($_POST["accion"] == 2) {
            if ($secciones->empresaUpdate(filter_var($_POST["nombre_empresa"], FILTER_SANITIZE_STRING), filter_var($_POST["direccion_empresa"], FILTER_SANITIZE_STRING), filter_var($_POST["cp_empresa"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["telefono_empresa"], FILTER_SANITIZE_STRING), filter_var($_POST["correo_empresa"], FILTER_SANITIZE_EMAIL), filter_var($_POST["fb_empresa"], FILTER_SANITIZE_URL), filter_var($_POST["insta_empresa"], FILTER_SANITIZE_URL), filter_var($_POST["twitter_empresa"], FILTER_SANITIZE_URL))) {
                $secciones->alerta("Datos modificados con exito!");
                $secciones->redireccionarJS("infoEmpresa.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("infoEmpresa.php");
            }
        } // ERROR DE VARIABLE ACCION
else {
            $secciones->alerta("Algo salió mal :( intentalo de nuevo");
            $secciones->redireccionarJS("infoEmpresa.php");
        }
    } else {
        $secciones->alerta("Por favor, llena los datos marcados con (*)");
        $secciones->redireccionarJS("infoEmpresa_edit.php");
    }
}// USUARIOS
else if (isset($_POST["username_usuario"]) && isset($_POST["nombres_usuario"]) && isset($_POST["apellidos_usuario"]) && isset($_POST["correo_usuario"]) 
        && isset($_POST["password_usuario"]) && isset($_POST["direccion_usuario"]) && isset($_POST["ciudad_usuario"]) && isset($_POST["estado_usuario"]) 
        && isset($_POST["pais_usuario"]) && isset($_POST["codpost_usuario"]) && isset($_POST["telefono_usuario"]) && isset($_POST["vendedor_usuario"]) 
        && isset($_POST["tipovendedor_usuario"])) {
    if ($_POST["username_usuario"] != "" && $_POST["nombres_usuario"] != "" && $_POST["apellidos_usuario"] != "" && $_POST["correo_usuario"] != "" 
        && $_POST["password_usuario"] != "") {
        // USUARIO NUEVO
        if ($_POST["accion"] == 1) {
            if ($secciones->usuariosNew(filter_var($_POST["username_usuario"], FILTER_SANITIZE_STRING), filter_var($_POST["nombres_usuario"], FILTER_SANITIZE_STRING), 
                                        filter_var($_POST["apellidos_usuario"], FILTER_SANITIZE_STRING), filter_var($_POST["correo_usuario"], FILTER_SANITIZE_EMAIL), 
                                        filter_var($_POST["password_usuario"], FILTER_SANITIZE_STRING), filter_var($_POST["vendedor_usuario"], FILTER_SANITIZE_NUMBER_INT),
                                        filter_var($_POST["direccion_usuario"], FILTER_SANITIZE_STRING), filter_var($_POST["ciudad_usuario"], FILTER_SANITIZE_STRING), 
                                        filter_var($_POST["estado_usuario"], FILTER_SANITIZE_STRING), filter_var($_POST["pais_usuario"], FILTER_SANITIZE_STRING), 
                                        filter_var($_POST["codpost_usuario"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["telefono_usuario"], FILTER_SANITIZE_STRING), 
                                        filter_var($_POST["tipovendedor_usuario"], FILTER_SANITIZE_STRING))) {
                $secciones->alerta("Datos guardados con exito!");
                $secciones->redireccionarJS("usuarios.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("usuarios.php");
            }
        } // MODIFICAR USUARIO
else if ($_POST["accion"] == 2) {
            if ($secciones->usuariosUpdate(filter_var($_POST["username_usuario"], FILTER_SANITIZE_STRING), filter_var($_POST["nombres_usuario"], FILTER_SANITIZE_STRING), 
                                           filter_var($_POST["apellidos_usuario"], FILTER_SANITIZE_STRING), filter_var($_POST["correo_usuario"], FILTER_SANITIZE_EMAIL), 
                                           filter_var($_POST["password_usuario"], FILTER_SANITIZE_STRING), filter_var($_POST["vendedor_usuario"], FILTER_SANITIZE_NUMBER_INT),
                                           filter_var($_GET["IDUSER"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["direccion_usuario"], FILTER_SANITIZE_STRING), 
                                           filter_var($_POST["ciudad_usuario"], FILTER_SANITIZE_STRING), filter_var($_POST["estado_usuario"], FILTER_SANITIZE_STRING), 
                                           filter_var($_POST["pais_usuario"], FILTER_SANITIZE_STRING), filter_var($_POST["codpost_usuario"], FILTER_SANITIZE_NUMBER_INT), 
                                           filter_var($_POST["telefono_usuario"], FILTER_SANITIZE_STRING), filter_var($_POST["tipovendedor_usuario"], FILTER_SANITIZE_STRING))) {
                $secciones->alerta("Datos modificados con exito!");
                $secciones->redireccionarJS("usuarios.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("usuarios.php");
            }
        } // ERROR DE VARIABLE ACCION
else {
            $secciones->alerta("Algo salió mal :( intentalo de nuevo");
            $secciones->redireccionarJS("usuarios.php");
        }
    } // ERROR
else {
        $secciones->alerta("Por favor, llena los datos marcados con (*)");
        if (isset($_GET["IDUSER"])) {
            $secciones->redireccionarJS("usuarios_edit.php?ID=" . $_GET["IDUSER"]);
        } else {
            $secciones->redireccionarJS("usuarios_edit.php?ID=");
        }
    }
}

// USUARIOS_RESPONSABLE
else if (isset($_POST["usuario_bcknd"]) && isset($_POST["nombres_bcknd"]) && isset($_POST["apellidos_bcknd"]) && isset($_POST["password_bcknd"]) 
        && isset($_POST["privilegios_bcknd"])) {
    if ($_POST["usuario_bcknd"] != "" && $_POST["nombres_bcknd"] != "" && $_POST["apellidos_bcknd"] != "" && $_POST["password_bcknd"] != "" 
        && $_POST["privilegios_bcknd"] != "") {
        // USUARIO NUEVO RESPONSABLE
        if ($_POST["accion"] == 1) {
            if ($secciones->responsableNew(filter_var($_POST["usuario_bcknd"], FILTER_SANITIZE_STRING),
                                           filter_var($_POST["nombres_bcknd"], FILTER_SANITIZE_STRING), 
                                           filter_var($_POST["apellidos_bcknd"], FILTER_SANITIZE_STRING),
                                           filter_var($_POST["password_bcknd"], FILTER_SANITIZE_STRING), 
                                           filter_var($_POST["privilegios_bcknd"], FILTER_SANITIZE_NUMBER_INT)))
                                   {
                $secciones->alerta("Datos guardados con exito!");
                $secciones->redireccionarJS("responsable.php");
            } else {
                $secciones->alerta("Algo salió mal (PARAMETROS: NUEVO) :( intentalo de nuevo");
                $secciones->redireccionarJS("responsable.php");
            }
        } // MODIFICAR USUARIO RESPONSABLE
else if ($_POST["accion"] == 2) {
            if ($secciones->responsableUpdate(filter_var($_POST["usuario_bcknd"], FILTER_SANITIZE_STRING),
                                              filter_var($_POST["nombres_bcknd"], FILTER_SANITIZE_STRING), 
                                              filter_var($_POST["apellidos_bcknd"], FILTER_SANITIZE_STRING),
                                              filter_var($_POST["password_bcknd"], FILTER_SANITIZE_STRING), 
                                              filter_var($_POST["privilegios_bcknd"], FILTER_SANITIZE_NUMBER_INT),
                                              filter_var($_GET["IDUSER"], FILTER_SANITIZE_NUMBER_INT))) {
                $secciones->alerta("Datos actualizados con exito!");
                $secciones->redireccionarJS("responsable.php");
            } else {
                $secciones->alerta("Algo salió mal (PARAMETROS: UPDATE) :( intentalo de nuevo");
                $secciones->redireccionarJS("responsable.php");
            }
        } 
else {
            $secciones->alerta("Algo salió mal (ID: UPDATE) :( intentalo de nuevo");
            $secciones->redireccionarJS("responsable.php");
        }
    }
else {
        $secciones->alerta("Por favor, llena los datos marcados con (*)");
        if (isset($_GET["IDUSER"])) {
            $secciones->redireccionarJS("responsable_edit.php?ID=" . $_GET["IDUSER"]);
        } else {
            $secciones->redireccionarJS("responsable_edit.php?ID=");
        }
    }
}


// LINEA
else if (isset($_POST["nombre_linea"]) && isset($_POST["orden_linea"])) {
    if ($_POST["nombre_linea"] != "" && $_POST["orden_linea"] != "") {
        // LINEA NUEVA
        if ($_POST["accion"] == 1) {
            if ($secciones->lineasNew(filter_var($_POST["nombre_linea"], FILTER_SANITIZE_STRING), filter_var($_POST["orden_linea"], FILTER_SANITIZE_NUMBER_INT))) {
                $secciones->alerta("Datos guardados con exito!");
                $secciones->redireccionarJS("lineas.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("lineas.php");
            }
        } // MODIFICAR LINEA
else if ($_POST["accion"] == 2) {
            if ($secciones->lineasUpdate(filter_var($_POST["nombre_linea"], FILTER_SANITIZE_STRING), filter_var($_POST["orden_linea"], FILTER_SANITIZE_NUMBER_INT), filter_var($_GET["IDLINEA"], FILTER_SANITIZE_NUMBER_INT))) {
                $secciones->alerta("Datos modificados con exito!");
                $secciones->redireccionarJS("lineas.php");
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("lineas.php");
            }
        }        // ERROR DE VARIABLE ACCION
        else {
            $secciones->alerta("Algo salió mal :( intentalo de nuevo");
            $secciones->redireccionarJS("lineas.php");
        }
    } // ERROR
else {
        $secciones->alerta("Por favor, llena los datos marcados con (*)");
        if (isset($_GET["IDLINEA"])) {
            $secciones->redireccionarJS("lineas_edit.php?ID=" . $_GET["IDLINEA"]);
        } else {
            $secciones->redireccionarJS("lineas_edit.php?ID=");
        }
    }
}// CATEGORIA
else if (isset($_POST["nombre_categoria"]) && isset($_POST["orden_categoria"])) {
    if ($_POST["nombre_categoria"] != "" && $_POST["orden_categoria"] != "") {
        // CATEGORIA NUEVA
        if ($_POST["accion"] == 1) {
            if ($secciones->categoriasNew(filter_var($_POST["nombre_categoria"], FILTER_SANITIZE_STRING), filter_var($_POST["orden_categoria"], FILTER_SANITIZE_NUMBER_INT), filter_var($_GET["IDLINEA"], FILTER_SANITIZE_NUMBER_INT))) {
                $secciones->alerta("Datos guardados con exito!");
                $secciones->redireccionarJS("categorias.php?ID=" . filter_var($_GET["IDLINEA"], FILTER_SANITIZE_NUMBER_INT));
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("categorias.php?ID=" . filter_var($_GET["IDLINEA"], FILTER_SANITIZE_NUMBER_INT));
            }
        } // MODIFICAR CATEGORIA
else if ($_POST["accion"] == 2) {
            if ($secciones->categoriasUpdate(filter_var($_POST["nombre_categoria"], FILTER_SANITIZE_STRING), filter_var($_POST["orden_categoria"], FILTER_SANITIZE_NUMBER_INT), filter_var($_GET["IDCATEGORIA"], FILTER_SANITIZE_NUMBER_INT))) {
                $secciones->alerta("Datos modificados con exito!");
                $secciones->redireccionarJS("categorias.php?ID=" . filter_var($_GET["IDLINEA"], FILTER_SANITIZE_NUMBER_INT));
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("categorias.php?ID=" . filter_var($_GET["IDLINEA"], FILTER_SANITIZE_NUMBER_INT));
            }
        }        // ERROR DE VARIABLE ACCION
        else {
            $secciones->alerta("Algo salió mal :( intentalo de nuevo");
            $secciones->redireccionarJS("categorias.php?ID=" . filter_var($_GET["IDCATEGORIA"], FILTER_SANITIZE_NUMBER_INT));
        }
    } // ERROR
else {
        $secciones->alerta("Por favor, llena los datos marcados con (*)");
        if (isset($_GET["IDCATEGORIA"])) {
            $secciones->redireccionarJS("categorias_edit.php?ID=" . $_GET["IDCATEGORIA"]);
        } else {
            $secciones->redireccionarJS("categorias_edit.php?ID=");
        }
    }
}// SUBCATEGORIA
else if (isset($_POST["nombre_subcat"]) && isset($_POST["orden_subcat"])) {
    if ($_POST["nombre_subcat"] != "" && $_POST["orden_subcat"] != "") {
        // LINEA NUEVA
        if ($_POST["accion"] == 1) {
            if ($secciones->subcategoriasNew(filter_var($_POST["nombre_subcat"], FILTER_SANITIZE_STRING), filter_var($_POST["orden_subcat"], FILTER_SANITIZE_NUMBER_INT), filter_var($_GET["IDCATEGORIA"], FILTER_SANITIZE_NUMBER_INT))) {
                $secciones->alerta("Datos guardados con exito!");
                $secciones->redireccionarJS("subcategorias.php?ID=" . filter_var($_GET["IDCATEGORIA"], FILTER_SANITIZE_NUMBER_INT));
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("subcategorias.php?ID=" . filter_var($_GET["IDCATEGORIA"], FILTER_SANITIZE_NUMBER_INT));
            }
        } // MODIFICAR LINEA
else if ($_POST["accion"] == 2) {
            if ($secciones->subcategoriasUpdate(filter_var($_POST["nombre_subcat"], FILTER_SANITIZE_STRING), filter_var($_POST["orden_subcat"], FILTER_SANITIZE_NUMBER_INT), filter_var($_GET["IDSUBCAT"], FILTER_SANITIZE_NUMBER_INT))) {
                $secciones->alerta("Datos modificados con exito!");
                $secciones->redireccionarJS("subcategorias.php?ID=" . filter_var($_GET["IDCATEGORIA"], FILTER_SANITIZE_NUMBER_INT));
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("subcategorias.php?ID=" . filter_var($_GET["IDCATEGORIA"], FILTER_SANITIZE_NUMBER_INT));
            }
        }        // ERROR DE VARIABLE ACCION
        else {
            $secciones->alerta("Algo salió mal :( intentalo de nuevo");
            $secciones->redireccionarJS("subcategorias.php?ID=" . filter_var($_GET["IDCATEGORIA"], FILTER_SANITIZE_NUMBER_INT));
        }
    } // ERROR
else {
        $secciones->alerta("Por favor, llena los datos marcados con (*)");
        if (isset($_GET["IDSUBCAT"])) {
            $secciones->redireccionarJS("subcategorias_edit.php?ID=" . $_GET["IDSUBCAT"]);
        } else {
            $secciones->redireccionarJS("subcategorias_edit.php?ID=");
        }
    }
}// PRODUCTO

else if (isset($_POST["nombre_prod"]) && isset($_POST["orden_prod"]) && isset($_POST["talla_prod"]) && isset($_POST["fit_prod"]) && isset($_POST["lavado_prod"]) 
        && isset($_POST["precio_prod"]) && isset($_POST["preciodesc_prod"]) && isset($_POST["img1_prod"]) && isset($_POST["img2_prod"]) && isset($_POST["img3_prod"]) 
        && isset($_POST["img4_prod"]) && isset($_POST["img5_prod"]) && isset($_POST["descripcion_prod"]) && isset($_POST["color_prod"]) && isset($_POST["destacado_prod"]) 
        && isset($_POST["nuevo_prod"])) {

        if ($_POST["nombre_prod"] != "" && $_POST["orden_prod"] != "" && $_POST["precio_prod"] != "" && $_POST["img1_prod"] != "" && $_POST["img2_prod"] != "" && $_POST["descripcion_prod"]
             != "" && $_POST["destacado_prod"] != "" && $_POST["nuevo_prod"] != "") {
        // PRODUCTO NUEVO
        if ($_POST["accion"] == 1) {
            if ($secciones->productosNew(filter_var($_POST["orden_prod"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["nombre_prod"], 
                FILTER_SANITIZE_STRING), filter_var($_POST["fit_prod"], FILTER_SANITIZE_STRING), filter_var($_POST["precio_prod"], 
                FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), filter_var($_POST["img1_prod"], FILTER_SANITIZE_URL), filter_var($_POST["img2_prod"], 
                FILTER_SANITIZE_URL), $_POST["descripcion_prod"], filter_var($_POST["destacado_prod"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["nuevo_prod"],
                FILTER_SANITIZE_NUMBER_INT), filter_var($_GET["IDSUBCAT"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["color_prod"], 
                FILTER_SANITIZE_STRING), filter_var($_POST["hexa_prod"], FILTER_SANITIZE_STRING), filter_var($_POST["img3_prod"], 
                FILTER_SANITIZE_URL), filter_var($_POST["img4_prod"], FILTER_SANITIZE_URL),filter_var($_POST["img5_prod"], 
                FILTER_SANITIZE_URL), filter_var($_POST["talla_prod"], FILTER_SANITIZE_STRING), filter_var($_POST["preciodesc_prod"], 
                FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), filter_var($_POST["lavado_prod"], FILTER_SANITIZE_STRING))) {
                    
                $secciones->redireccionarJS("productos.php?ID=" . filter_var($_GET["IDSUBCAT"], FILTER_SANITIZE_NUMBER_INT));
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("productos.php?ID=" . filter_var($_GET["IDSUBCAT"], FILTER_SANITIZE_NUMBER_INT));
            }
        } // EDTAR PRODUCTO
        else if ($_POST["accion"] == 2) {
            if ($secciones->productosUpdate(filter_var($_POST["orden_prod"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["nombre_prod"], 
                FILTER_SANITIZE_STRING), filter_var($_POST["fit_prod"], FILTER_SANITIZE_STRING), filter_var($_POST["precio_prod"], 
                FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), filter_var($_POST["img1_prod"], FILTER_SANITIZE_URL), filter_var($_POST["img2_prod"], 
                FILTER_SANITIZE_URL), $_POST["descripcion_prod"], filter_var($_POST["destacado_prod"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["nuevo_prod"], 
                FILTER_SANITIZE_NUMBER_INT), filter_var($_GET["IDPROD"], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST["color_prod"], 
                FILTER_SANITIZE_STRING), filter_var($_POST["hexa_prod"], FILTER_SANITIZE_STRING), filter_var($_POST["img3_prod"], 
                FILTER_SANITIZE_URL), filter_var($_POST["img4_prod"], FILTER_SANITIZE_URL), filter_var($_POST["img5_prod"], 
                FILTER_SANITIZE_URL), filter_var($_POST["talla_prod"], FILTER_SANITIZE_STRING), filter_var($_POST["preciodesc_prod"], 
                FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION ), filter_var($_POST["lavado_prod"], FILTER_SANITIZE_STRING))) {
                $secciones->alerta("Datos modificados con exito!");
                $secciones->redireccionarJS("productos.php?ID=" . filter_var($_GET["IDSUBCAT"], FILTER_SANITIZE_NUMBER_INT));
            } else {
                $secciones->alerta("Algo salió mal :( intentalo de nuevo");
                $secciones->redireccionarJS("productos.php?ID=" . filter_var($_GET["IDSUBCAT"], FILTER_SANITIZE_NUMBER_INT));
            }
        } // ERROR DE VARIABLE ACCION
else {
            $secciones->alerta("Algo salió mal :( intentalo de nuevo");
            $secciones->redireccionarJS("productos.php?ID=" . filter_var($_GET["IDSUBCAT"], FILTER_SANITIZE_NUMBER_INT));
        }
    } // ERROR
else {
        $secciones->alerta("Por favor, llena los datos marcados con (*)");
        //if (isset($_GET["IDPROD"])) {
          //  $secciones->redireccionarJS("productos_edit.php?ID=" . $_GET["IDPROD"]);
        //} else {
          //  $secciones->redireccionarJS("productos_edit.php?ID=");
       // }
    }
}
    

