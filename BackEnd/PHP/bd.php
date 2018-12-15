
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
        } catch (Exception $e) {
            if ($this->depuracion) {
                echo $e->getMessage();
                print_r($e->getMessage());
            }
            $this->conn = NULL;
            die();
        }
    }

    function CerrarConexion()
    {
        $this->conn = NULL;
    }

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
            $this->CerrarConexion();
        }
        $this->CerrarConexion();
    }

    function ConsultaMultiplesRegistros($tabla, $condiciones, $parametro, $cantidad)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare("SELECT * FROM " . $tabla . " where " . $condiciones . "LIMIT " . $cantidad);
        if ($sentencia->execute($parametro)) {
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
            $this->CerrarConexion();
        }
        $this->CerrarConexion();
    }

    function ConsultaLibre($columnas, $tabla, $condiciones, $parametro, $cantidad)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare("SELECT " . $columnas . " FROM " . $tabla . " WHERE " . $condiciones . " LIMIT " . $cantidad);
        if ($sentencia->execute($parametro)) {
            return $sentencia->fetchALL(PDO::FETCH_ASSOC);
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
            $this->CerrarConexion();
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
            $this->CerrarConexion();
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
        /**
         * UPDATE `usuarios_bcknd` SET `ID`=[value-1],`usuario_bcknd`=[value-2],`nombres_bcknd`=[value-3],
         * `apellidos_bcknd`=[value-4],`password_bcknd`=[value-5],`privilegios_bcknd`=[value-6],`activo_bcknd`=[value-7] WHERE 1
         */
        if ($sentencia->execute($valores)) {
            return true;
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
            $this->CerrarConexion();
        }
        $this->CerrarConexion();
    }

     /**
     * Mostrar todas las ventas, y despues poner un limite a 10
     */
    function ConsultaJoinMultiLibre($columnas, $tablaUno, $tablaDos, $condicionUno, $tablaTres, $condicionDos, $parametro)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare("SELECT " . $columnas . " FROM " . $tablaUno. " JOIN " . $tablaDos." ON ".$condicionUno
        ." JOIN ".$tablaTres." ON ".$condicionDos." WHERE ".$parametro);
        if ($sentencia->execute($parametro)) {
            return $sentencia->fetchALL(PDO::FETCH_ASSOC);
        }
        $this->CerrarConexion();
    }

    function ConsultaJoinVentas($columnas, $tabla, $join, $condiciones,$orderBy, $cantidad)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare("SELECT DISTINCT " . $columnas . " FROM " . $tabla . " JOIN " . $join . " ON " . $condiciones . " ORDER BY ". $orderBy . "DESC LIMIT " . $cantidad);
        if ($sentencia->execute()) {
            return $sentencia->fetchALL(PDO::FETCH_ASSOC);
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
        }
        $this->CerrarConexion();
    }

    function ConsultaJoinLibre($columnas, $tabla, $join, $condicionJoin,$condicionWhere)
    {
        if ($this->conn == NULL) {
            $this->AbrirConexion();
        }
        $s = $this->conn->prepare("set SESSION group_concat_max_len=9096");
        $s->execute();
        $sentencia = $this->conn->prepare("SELECT " . $columnas . " FROM " . $tabla . " JOIN " . $join . " ON " . $condicionJoin . " WHERE ". $condicionWhere);
        if ($sentencia->execute()) {
            return $sentencia->fetchALL(PDO::FETCH_ASSOC);
        } else {
            if ($this->depuracion) {
                echo var_dump($sentencia->errorInfo());
            }
            return null;
        }
        $this->CerrarConexion();
    }
    
    // SELECT subcategoria.* FROM categoria, subcategoria WHERE categoria.ID=subcategoria.ID_categoria AND categoria.ID=1 ORDER BY orden_subcat <----------CONSULTA PARA SUB CATEGORIAS
}
