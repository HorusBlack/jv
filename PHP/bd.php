<?php
$BD = new BD();

class BD
{

  
    var $cadena = "mysql:host=localhost;dbname=jadevu";

    var $usuario = "horusblack";

    var $password = "1w2qaxsz";
    var $conn;

    var $depuracion = true;

    // abriendo conexion
    function AbrirConexion()
    {
        try {
            $this->conn = new PDO($this->cadena, $this->usuario, $this->password);
            $this->conn->exec("set character set utf8");
            // Cambio la excepcion general a una especifica
        } catch (PDOException $e) {
            if ($this->depuracion) {
                echo $e->getMessage();
                print_r($e->getMessage());
            }
            $this->conn = NULL;
            die();
        }
    }

    // Cerrando conexion a la BD
    function CerrarConexion()
    {
        $this->conn = NULL;
    }

    /**
     * Selecciona todos los datos de una tabla dada y retorna un arreglo de los
     * resultados
     */
    function ConsultaWhereSimple($tabla, $condiciones, $parametro)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare("SELECT * FROM " . $tabla . " where " . $condiciones);
        if ($sentencia->execute($parametro)) {
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
        }
        $this->CerrarConexion();
    }

    /**
     * Selecciona un determinado número de registros y los retorna de existir, en un arreglo
     */
    function ConsultaMultiplesRegistros($tabla, $condiciones, $parametro, $cantidad)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare("SELECT * FROM " . $tabla . " where " . $condiciones . " LIMIT " . $cantidad);
        if ($sentencia->execute($parametro)) {
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
        }
        $this->CerrarConexion();
    }

    /**
     * Consulta que se encarga de obtener los datos del paginador
     */
    function ConsultaMultiRegPaginado($tabla, $condiciones, $parametro, $cantidad, $pag)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare("SELECT * FROM " . $tabla . " where " . $condiciones);
        if ($sentencia->execute($parametro)) {
            
            $desde = ($pag - 1) * $cantidad;
            $numfila = $sentencia->rowCount();
            // ceil redondea el número
            $totalpag = ceil($numfila / $cantidad);
            $query = $this->conn->prepare("SELECT * FROM " . $tabla . " where " . $condiciones . "LIMIT " . $desde . "," . $cantidad);
            if ($query->execute($parametro)) {
                return array(
                    $query->fetchAll(PDO::FETCH_ASSOC),
                    $totalpag
                );
            } else {
                if ($this->depuracion) {
                    echo var_dump($query->errorInfo());
                }
                return null;
            }
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
        }
        $this->CerrarConexion();
    }

    function InsertLibre($tabla, $columnas, $valores)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare("INSERT INTO " . $tabla . "(" . $columnas . ") VALUES (" . $valores . ")");
        if ($sentencia->execute()) {
            return true;
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
        }
        $this->CerrarConexion();
    }

    function InsertLibrereg($tabla, $columnas, $valores)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare("INSERT INTO " . $tabla . "(" . $columnas . ") VALUES (" . $valores . ")");
        if ($sentencia->execute()) {
            return "yes";
        } else {
            
            return $sentencia;
        }
        $this->CerrarConexion();
    }

    function UpdateLibre($tabla, $columnas, $valores, $condicion)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare("UPDATE " . $tabla . " SET " . $columnas . " WHERE " . $condicion);
        //UPDATE `producto` SET `talla_prod`='S:10,M:10' WHERE producto.ID=7
        if ($sentencia->execute($valores)) {
            return true;
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
        }
        $this->CerrarConexion();
    }

    function updateStock($id, $valores)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare('UPDATE producto SET talla_prod="'.$valores.'" WHERE ID="'.$id.'"');
        //UPDATE `producto` SET `talla_prod`='S:10,M:10' WHERE producto.ID=7
        if ($sentencia->execute()) {
            return true;
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
        }
        $this->CerrarConexion();
    }

    function ConsultaLibre($columnas, $tabla, $condiciones, $parametro, $cantidad = "")
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        if ($cantidad != "") {
            $sentencia = $this->conn->prepare("SELECT " . $columnas . " FROM " . $tabla . " WHERE " . $condiciones . " LIMIT " . $cantidad);
        } else {
            $sentencia = $this->conn->prepare("SELECT " . $columnas . " FROM " . $tabla . " WHERE " . $condiciones);
        }
        if ($sentencia->execute($parametro)) {
            return $sentencia->fetchALL(PDO::FETCH_ASSOC);
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
        }
        $this->CerrarConexion();
    }

    function ConsultaJoinLibre($columnas, $tabla, $join, $condiciones, $parametro, $cantidad)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare("SELECT " . $columnas . " FROM " . $tabla . " JOIN " . $join . " WHERE " . $condiciones . " LIMIT " . $cantidad);
        if ($sentencia->execute($parametro)) {
            return $sentencia->fetchALL(PDO::FETCH_ASSOC);
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
        }
        $this->CerrarConexion();
    }
   
    
}
