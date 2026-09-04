<?php

//Incluimos la conexion a la base de datos

require "../config/Conexion.php";



class Cotizacion_detalle{

    //Implementar nuestro constructor

    public function __construct()

    {



    }



    public function insertar($idcotizacion,$cantpar,$fechacot,$horainicot,$horafincot,$duracion,$idorgeje,$ip,$usuario){

	    $usuario = $_SESSION["login"];

		$ip= $_SERVER["REMOTE_ADDR"];

		$fecha = substr($fechacot, 6, 4)."-".substr($fechacot, 3, 2)."-".substr($fechacot, 0, 2);

        $sql="INSERT INTO cot_detalle_cotizaciones(IDCOTIZACION,CANTIDADCOT,FECHACOT,HORAINICOT,HORAFINCOT,DURACIONCOT,ESTADOCOT,IPREGCOT,USUREGCOT,FECHAREGCOT,USUMODCOT,FECHAMODCOT) 

		       VALUES ($idcotizacion,'$cantpar','$fechacot','$horainicot','$horafincot','$duracion',1,'$ip','$usuario',NOW(),'$usuario',NOW())";

        return ejecutarConsulta($sql);

    }



    public function editar($iddetallecot,$idcotizacion,$cantpar,$fechacot,$horainicot,$horafincot,$duracion){

        $fecha = substr($fechacot, 6, 4)."-".substr($fechacot, 3, 2)."-".substr($fechacot, 0, 2);

        $usuario = $_SESSION["login"];

        $sql="UPDATE `cot_detalle_cotizaciones` SET `IDCOTIZACION`=$idcotizacion,

					  `CANTIDADCOT`='$cantpar',

					  `FECHACOT`='$fechacot',

					  `HORAINICOT`='$horainicot',

					  `HORAFINCOT`='$horafincot',

					  `HORAFINCOT`='$horafincot',

					  `DURACIONCOT`='$duracion',

                      `FECHAMODCOT`=NOW(),

                      `USUMODCOT`='$usuario'

			  WHERE `IDDETALLECOT`=$iddetallecot";

        return ejecutarConsulta($sql);

    }

    

    //Se agregó query para eliminar

    /*public function eliminar($iddetallecot){

        $sql="DELETE FROM cot_detalle_cotizaciones WHERE IDDETALLECOT='$iddetallecot'";

        return ejecutarConsulta($sql);

    }*/



    //Implementamos un método para desactivar registros

    public function desactivar($iddetallecot){

        $sql="UPDATE cot_detalle_cotizaciones SET ESTADOCOT='0' WHERE IDDETALLECOT='$iddetallecot'";

        return ejecutarConsulta($sql);

    }

    //Implementamos un método para activar registros

    public function activar($iddetallecot){

        $sql="UPDATE cot_detalle_cotizaciones SET ESTADOCOT='1' WHERE IDDETALLECOT='$iddetallecot'";

        return ejecutarConsulta($sql);

    }



    //Implementar un método para mostrar los datos de un registro a modificar

    public function mostrar($iddetallecot){

        $sqlDetallecotizacion="SELECT 

                        det.IDDETALLECOT, 

                        det.IDCOTIZACION, 

                        o.CODREFERENCIA, 

                        det.CANTIDADCOT, 

                        det.FECHACOT, 

                        det.HORAINICOT, 

                        det.HORAFINCOT, 

                        det.DURACIONCOT, 

                        det.ESTADOCOT 

                    FROM cot_detalle_cotizaciones as det 

                    LEFT OUTER JOIN cot_cotizaciones as o ON o.IDCOTIZACION = det.IDCOTIZACION

                WHERE det.IDDETALLECOT=$iddetallecot";



        $detalle = ejecutarConsultaSimpleFila($sqlDetallecotizacion);

        return $detalle;

    }



    //Implementar un método para mostrar todos los registros

    public function listar($fecha, $cotizacion){

        $usuario     = $_SESSION["login"];

        $permisorol  = $_SESSION["permiso"];

        $orgeje      = $_SESSION["idorgeje"];

        $areares     = $_SESSION['areares'];



        // Construir condiciones WHERE basadas en los nuevos parámetros

        $where_conditions = [];

        

        // Condición para fecha

        if($fecha) {

            $where_conditions[] = 'DATE(det.FECHACOT) = "' . $fecha . '"';

        }

        

        // Condición para cotizacion

        if($cotizacion) {

            $where_conditions[] = 'det.IDCOTIZACION = "' . $cotizacion . '"';

        }

        

        // Query base único para todos los casos

        $sql = "SELECT 

                det.IDDETALLECOT, 

                det.IDCOTIZACION, 

                o.CODREFERENCIA, 

                det.CANTIDADCOT, 

                date_format(det.FECHACOT, '%d/%m/%Y') as 'FECHACOT', 

                det.HORAINICOT, 

                det.HORAFINCOT, 

                det.DURACIONCOT, 

                det.ESTADOCOT 

            FROM cot_detalle_cotizaciones as det 

            LEFT OUTER JOIN cot_cotizaciones as o ON o.IDCOTIZACION = det.IDCOTIZACION";



        // Agregar condiciones WHERE específicas según el permiso

        if($permisorol == "1"){

            // Permiso rol == 1 es Permiso total o Acceso Total

            // No se agregan condiciones adicionales de usuario/organización

            

        } else if($permisorol == "2") {

            // Permiso rol == 2 es Permiso Total a tu Organización Ejecutora

            $where_conditions[] = 'det.IDORGEJE = "' . $orgeje . '"';

            

        } else {

            // Permiso rol == 0 es Permiso únicamente a los registros del usuario

            // Aquí necesitarías definir cómo identificar los registros del usuario

            // Por ejemplo, si hay un campo USUREGCOT en det o similar

            $where_conditions[] = 'det.USUREGCOT = "' . $usuario . '"';

        }



        // Agregar todas las condiciones WHERE

        if(!empty($where_conditions)) {

            $sql .= " WHERE " . implode(" AND ", $where_conditions);

        }

        

        return ejecutarConsulta($sql);

    }



    //Implementar un método para listar los registros y mostrar en el select

    public function selectcotizacion(){

        $usuario        = $_SESSION["login"];

        $permisorol     = $_SESSION["permiso"];

        $orgeje         = $_SESSION["idorgeje"];

        $areares        = $_SESSION['areares'];



        //Agregamos condicionante de área responsable

        if($areares == "0"){

            //Significa que tomará todas las áreas responsables

            $where3 = "";

            $where3b = "WHERE";

        } else {

            $where3 = 'WHERE o.IDAREARESPONSABLE = "'.$areares.'"';

            $where3b = 'WHERE o.IDAREARESPONSABLE = "'.$areares.'" AND';

        }



        if($permisorol=="1") {

            //Permiso rol == 1 es Permiso total o Acceso Total

            $sql = "SELECT 

                        o.IDCOTIZACION, 

                        o.CODREFERENCIA, 

                        o.NRC,

                        o.EMPRESA,

                        o.NOMBRECONTACTO,

                        o.TELCONTACTO,

                        o.CORREOCONTACTO,

                        o.IDENTIDAD, 

                        te.NOMBRETIPOEVENTO, 

                        ta.NOMBRETIPOALQUILER, 

                        o.ESTADOCOT 

                    FROM cot_cotizaciones as o

                    LEFT OUTER JOIN cot_tipo_evento as te 

                    ON te.IDTIPOEVENTO = o.IDTIPOEVENTO

                    LEFT OUTER JOIN cot_tipo_alquiler as ta 

                    ON ta.IDTIPOALQUILER = o.IDTIPOALQUILER

                    $where3"; 

        } else if($permisorol=="2") {

            //Permiso rol == 2 es Permiso Total a tu Organización Ejecutora

            $sql = "SELECT 

                        o.IDCOTIZACION, 

                        o.CODREFERENCIA, 

                        o.NRC,

                        o.EMPRESA,

                        o.NOMBRECONTACTO,

                        o.TELCONTACTO,

                        o.CORREOCONTACTO,

                        o.IDENTIDAD, 

                        te.NOMBRETIPOEVENTO, 

                        ta.NOMBRETIPOALQUILER, 

                        o.ESTADOCOT 

                    FROM cot_cotizaciones as o

                    LEFT OUTER JOIN cot_tipo_evento as te 

                    ON te.IDTIPOEVENTO = o.IDTIPOEVENTO

                    LEFT OUTER JOIN cot_tipo_alquiler as ta 

                    ON ta.IDTIPOALQUILER = o.IDTIPOALQUILER

                    LEFT OUTER join usuarios u ON u.IDUSUARIOSUSU = o.USURECOT

                    $where3b o.IDORGEJE = '$orgeje'";

        } else {

            //Permiso rol == 0 es Permiso unicamente a los registros del usuario

            $sql = "SELECT 

                        o.IDCOTIZACION, 

                        o.CODREFERENCIA, 

                        o.NRC,

                        o.EMPRESA,

                        o.NOMBRECONTACTO,

                        o.TELCONTACTO,

                        o.CORREOCONTACTO,

                        o.IDENTIDAD, 

                        te.NOMBRETIPOEVENTO, 

                        ta.NOMBRETIPOALQUILER, 

                        o.ESTADOCOT 

                    FROM cot_cotizaciones as o

                    LEFT OUTER JOIN cot_tipo_evento as te 

                    ON te.IDTIPOEVENTO = o.IDTIPOEVENTO

                    LEFT OUTER JOIN cot_tipo_alquiler as ta 

                    ON ta.IDTIPOALQUILER = o.IDTIPOALQUILER

                    $where3b o.USURECOT='$usuario'";

        }

        return ejecutarConsulta($sql);

   }



   // Listar tipos de insumos

    public function listarTiposInsumos() {

        $sql = "SELECT IDTIPOINSUMO, NOMBRETIPOINSUMO 

                FROM cot_tipo_insumo 

                WHERE ESTADOTIPOINSUMO = 1";

        return ejecutarConsulta($sql);

    }



    // Listar insumos por tipo

    public function listarInsumosPorTipo($idtipoinsumo) {

        $sql = "SELECT IDINSUMO as 'ID', NOMBREINSUMO as 'Nombre insumo', PRECIO8H as 'Precio 8 horas', PRECIO4H as 'Precio 4 horas', PRECIOSALAREMODELADA as 'Precio Sala Remodelada', PRECIOUNITARIO as 'Precio unitario', PRECIOVENTA as 'Precio venta'

                FROM cot_insumos 

                WHERE IDTIPOINSUMO = '$idtipoinsumo'";

        return ejecutarConsulta($sql);

    }



    public function listarInsumosPorTipoConDetalle($idtipoinsumo, $iddetallecot) {

        $sql = "SELECT i.IDINSUMO as 'ID', i.NOMBREINSUMO as 'Nombre insumo', i.PRECIO8H as 'Precio 8 horas', i.PRECIO4H as 'Precio 4 horas', i.PRECIOSALAREMODELADA as 'Precio Sala Remodelada',

                    i.PRECIOUNITARIO as 'Precio unitario', i.PRECIOVENTA as 'Precio venta',

                    IFNULL(d.CANTIDAD,0) AS CANTIDAD,

                    IFNULL(d.TOTAL,0) AS TOTAL

                FROM cot_insumos i

                LEFT JOIN cot_detalle_insumos d 

                ON i.IDINSUMO = d.IDINSUMO 

                AND d.IDDETALLECOT = '$iddetallecot'

                WHERE i.IDTIPOINSUMO = '$idtipoinsumo'";

        return ejecutarConsulta($sql);

    }

  

}

?>