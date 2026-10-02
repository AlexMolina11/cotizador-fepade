<?php

//Incluimos la conexion a la base de datos
require_once "../config/Conexion.php";

class Gestioninsumo{

    //Implementar nuestro constructor
    public function __construct()
    {
    }

    //Implementamos un método para insertar registros
    public function insertar($idinsumo, $nombreinsumo, $aliasinsumo, $descripcion_insumo, $tipoinsumo, $usuario) 
    { 
        // usuario y ip (asegúrate que $_SESSION['login'] está definido)
        $usuario = $_SESSION["login"];

        // Iniciales: primeras 2 letras de $usuario
        // Limpiar el nombre de usuario para quedar solo con letras
        $u = preg_replace('/[^A-Za-zÑñ]/u', '', (string)$usuario);
        $u = trim($u);

        if ($u === '') {
            // fallback si por alguna razón $usuario viene vacío
            $ini = 'XX';
        } else {
            $ini = strtoupper(substr($u, 0, 2));
            // si quedó una sola letra, duplicarla (ej: "A" -> "AA")
            if (strlen($ini) === 1) $ini = $ini . $ini;
        }

        // === INSERT ===
        $sql = "INSERT INTO cot_insumos(IDTIPOINSUMO, NOMBREINSUMO, ALIASINSUMO, DESCRIPCIONINS, FECHAREGINS, USUREGINS, ESTADOINS)VALUES($tipoinsumo, '$nombreinsumo', '$aliasinsumo', '$descripcion_insumo', NOW(), '$usuario', 1)";
        return ejecutarConsulta($sql);

    }

    //Implementamos un método para editar registros
    public function editar($idinsumo,$nombreinsumo,$aliasinsumo,$descripcion_insumo,$tipoinsumo)
    {

        $usuario = $_SESSION["login"];
        $sql = "UPDATE cot_insumos SET
                    IDTIPOINSUMO = '$tipoinsumo',
                    NOMBREINSUMO = '$nombreinsumo',
                    ALIASINSUMO = '$aliasinsumo',
                    DESCRIPCIONINS = '$descripcion_insumo',
                    FECHAMODINS = NOW(),
                    USUMODINS = '$usuario'
                WHERE IDINSUMO = '$idinsumo'";
    
        return ejecutarConsulta($sql);
    }   


    //Se agregó el query para función eliminar
    public function eliminar($idinsumo){
        $sql="DELETE FROM cot_insumos WHERE IDINSUMO='$idinsumo'";
        return ejecutarConsulta($sql);
    }

    //Implementar un mastodon para mostrar los datos de un registro a modificar
    public function mostrar($idinsumo){
        $sql = "SELECT
                    i.IDINSUMO,
                    i.IDTIPOINSUMO,
                    ti.NOMBRETIPOINSUMO,
                    i.NOMBREINSUMO,
                    i.ALIASINSUMO,
                    i.DESCRIPCIONINS
                FROM cot_insumos as i
                LEFT JOIN cot_tipo_insumo as ti
                ON i.IDTIPOINSUMO= ti.IDTIPOINSUMO
                WHERE i.IDINSUMO = '$idinsumo'";

        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){

        $sql = "SELECT
            i.IDINSUMO,
            i.IDTIPOINSUMO,
            ti.NOMBRETIPOINSUMO,
            i.NOMBREINSUMO,
            i.ALIASINSUMO,
            i.DESCRIPCIONINS,

            i.PRECIO8H,
            i.PRECIO4H,
            i.PRECIOSALAREMODELADA,
            i.PRECIOUNITARIO,
            i.PRECIOVENTA,

            i.EDITABLEINS,

            i.FECHAREGINS,
            i.FECHAMODINS,
            i.ESTADOINS

            FROM cot_insumos as i
            LEFT JOIN cot_tipo_insumo as ti
            ON i.IDTIPOINSUMO = ti.IDTIPOINSUMO
            ORDER BY i.IDINSUMO DESC";

        return ejecutarConsulta($sql);
    }

    public function selectTipoInsumo(){

        $sql = "SELECT
            ti.IDTIPOINSUMO,
            ti.NOMBRETIPOINSUMO
                FROM cot_tipo_insumo as ti
                WHERE ti.ESTADOTIPOINSUMO = 1
                ORDER BY ti.NOMBRETIPOINSUMO ASC";

        return ejecutarConsulta($sql);
    
        }

    public function activar($idinsumo){

        $sql = "UPDATE cot_insumos as i
                SET i.ESTADOINS = 1
                WHERE i.IDINSUMO = '$idinsumo'";

            return ejecutarConsulta($sql);
    }



    public function desactivar($idinsumo){

        $sql = "UPDATE cot_insumos as i
                SET i.ESTADOINS = 0
                WHERE i.IDINSUMO = '$idinsumo'";

            return ejecutarConsulta($sql);
    }

}