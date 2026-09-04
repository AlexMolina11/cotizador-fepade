<?php







//Incluimos la conexion a la base de datos



require_once "../config/Conexion.php";







class Cotizacion{



    //Implementar nuestro constructor



    public function __construct()



    {



    }



    //Implementamos un método para insertar registros



    public function insertar($idcotizacion, $codreferencia, $cli_nrc, $cli_empresa, $cli_contacto, $cli_telefono, $cli_email, $tipoevento, $tipoalquiler, $exentacot, $descripcion_cot, $usuario, $ip, $idorgeje) 

    { 

        // usuario y ip (asegúrate que $_SESSION['login'] está definido)

        $usuario = $_SESSION["login"];

        $ip = $_SERVER["REMOTE_ADDR"];



        // Consecutivo por año

        $anio = date("Y");



        $ultimo = $this->obtenerUltimoRefPorAnio($anio);



        if ($ultimo && isset($ultimo['CODREFERENCIA'])) {

            $partes = explode("-", $ultimo['CODREFERENCIA']);

            $num = intval(str_replace("REF", "", $partes[0])) + 1;

        } else {

            $num = 1;

        }



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



        // Sufijo SA / AU

        $tipo = ($tipoalquiler == 1) ? "AU" : "SA";



        // Construir CODREFERENCIA inicial

        $codreferencia = "REF{$num}-{$ini}-{$anio}-AL-{$tipo}";



        $mensajeExtra = "";



        // --- Validar solo REF[#] --- //

        $inicioNum = $num;



        while ($this->existeRefNumero($num, $anio)) {

            $num++;

        }



        // Si cambió, informar al usuario

        if ($num != $inicioNum) {

            $codreferencia = "REF{$num}-{$ini}-{$anio}-AL-{$tipo}";

            $mensajeExtra = "El número de referencia ya existía. Se ha registrado como: $codreferencia";

        }



        // === INSERT ===

        $sql = "INSERT INTO cot_cotizaciones(

                CODREFERENCIA, NRC, EMPRESA, NOMBRECONTACTO, TELCONTACTO, CORREOCONTACTO, 

                IDTIPOEVENTO, IDTIPOALQUILER, EXENTACOT, DESCRIPCIONCOT, 

                USURECOT, IPREGCOT, IDORGEJE, FECHAREGCOT, FECHAMODCOT, USUMODCOT

            ) 

            VALUES (

                '$codreferencia', '$cli_nrc', '$cli_empresa', '$cli_contacto', '$cli_telefono', '$cli_email',

                $tipoevento, $tipoalquiler, '$exentacot', '$descripcion_cot',

                '$usuario', '$ip', '$idorgeje', NOW(), NOW(), '$usuario'

            )";



        $resultado = ejecutarConsulta($sql);



        // Devolver mensaje final

        if ($resultado) {

            if ($mensajeExtra != "") {

                return "Cotización registrada.\n$mensajeExtra";

            } else {

                return "Cotización registrada con éxito.";

            }

        }



        return "La cotización no se pudo registrar.";

    }



    public function existeCodRef($cod) {

        $sql = "SELECT IDCOTIZACION FROM cot_cotizaciones WHERE CODREFERENCIA = '$cod' LIMIT 1";

        return ejecutarConsultaSimpleFila($sql);

    }



    public function existeRefNumero($num, $anio) {

        $sql = "SELECT 1 

                FROM cot_cotizaciones 

                WHERE CODREFERENCIA LIKE 'REF{$num}-%-$anio-%'

                LIMIT 1";

        return ejecutarConsultaSimpleFila($sql);

    }



    public function obtenerUltimoRefPorAnio($anio) {

        $sql = "SELECT CODREFERENCIA

                FROM cot_cotizaciones

                WHERE CODREFERENCIA LIKE '%-$anio-%'

                ORDER BY IDCOTIZACION DESC

                LIMIT 1";



        return ejecutarConsultaSimpleFila($sql);

    }



    //Implementamos un método para editar registros



    public function editar($idcotizacion,$codreferencia,$cli_nrc,$cli_empresa,$cli_contacto,$cli_telefono,$cli_email,$tipoevento,$tipoalquiler,$exentacot,$descripcion_cot){ 



            $usuario = $_SESSION["login"];	



        	$sql="UPDATE cot_cotizaciones SET CODREFERENCIA='$codreferencia',



                               NRC='$cli_nrc', 						   



							   EMPRESA='$cli_empresa',



							   NOMBRECONTACTO='$cli_contacto',



							   TELCONTACTO='$cli_telefono',



							   CORREOCONTACTO='$cli_email',							   							 



							   IDTIPOEVENTO=$tipoevento,



							   IDTIPOALQUILER=$tipoalquiler,

                               

                               EXENTACOT = '$exentacot',



                               DESCRIPCIONCOT='$descripcion_cot',



                               FECHAMODCOT=NOW(),



                               USUMODCOT='$usuario'						 



					    WHERE IDCOTIZACION=$idcotizacion";		   		



        	return ejecutarConsulta($sql);		



    }







    //Se agregó el query para función eliminar



    public function eliminar($idcotizacion){



        $sql="DELETE FROM cot_cotizaciones WHERE IDCOTIZACION='$idcotizacion'";



        return ejecutarConsulta($sql);



    }







    //Implementar un mastodon para mostrar los datos de un registro a modificar



    public function mostrar($idcotizacion){



        $sqlActividad="SELECT 



                        o.IDCOTIZACION, 



                        o.CODREFERENCIA, 



                        o.NRC,



                        o.DESCRIPCIONCOT,



                        o.EMPRESA,



                        o.NOMBRECONTACTO,



                        o.TELCONTACTO,



                        o.CORREOCONTACTO,



                        o.IDENTIDAD, 



                        o.IDTIPOEVENTO,



                        o.EXENTACOT,



                        te.NOMBRETIPOEVENTO, 



                        o.IDTIPOALQUILER,



                        ta.NOMBRETIPOALQUILER, 



                        o.ESTADOCOT 



                    FROM cot_cotizaciones as o



                    LEFT OUTER JOIN cot_tipo_evento as te 



                    ON te.IDTIPOEVENTO = o.IDTIPOEVENTO



                    LEFT OUTER JOIN cot_tipo_alquiler as ta 



                    ON ta.IDTIPOALQUILER = o.IDTIPOALQUILER



                WHERE o.IDCOTIZACION=$idcotizacion";



	



        $cotizacion = ejecutarConsultaSimpleFila($sqlActividad);



        return $cotizacion;



    }







    //Implementar un método para mostrar todos los registros



    public function listar(){



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



                        o.DESCRIPCIONCOT,



                        o.EMPRESA,



                        o.NOMBRECONTACTO,



                        o.TELCONTACTO,



                        o.CORREOCONTACTO,



                        o.IDENTIDAD, 



                        te.NOMBRETIPOEVENTO, 



                        ta.NOMBRETIPOALQUILER,



                        o.USURECOT,



                        o.FECHAREGCOT,



                        o.ESTADOCOT,



                        e.NOMBREESTADOCOT



                    FROM cot_cotizaciones as o



                    LEFT OUTER JOIN cot_tipo_evento as te 



                    ON te.IDTIPOEVENTO = o.IDTIPOEVENTO



                    LEFT OUTER JOIN cot_tipo_alquiler as ta 



                    ON ta.IDTIPOALQUILER = o.IDTIPOALQUILER



                    LEFT OUTER JOIN cot_estados as e 



                    ON e.IDESTADOCOT = o.ESTADOCOT



                    $where3"; 



        } else if($permisorol=="2") {



            //Permiso rol == 2 es Permiso Total a tu Organización Ejecutora



            $sql = "SELECT 



                        o.IDCOTIZACION, 



                        o.CODREFERENCIA, 



                        o.NRC,



                        o.DESCRIPCIONCOT,



                        o.EMPRESA,



                        o.NOMBRECONTACTO,



                        o.TELCONTACTO,



                        o.CORREOCONTACTO,



                        o.IDENTIDAD, 



                        te.NOMBRETIPOEVENTO, 



                        ta.NOMBRETIPOALQUILER,



                        o.USURECOT,



                        o.FECHAREGCOT,



                        o.ESTADOCOT,



                        e.NOMBREESTADOCOT



                    FROM cot_cotizaciones as o



                    LEFT OUTER JOIN cot_tipo_evento as te 



                    ON te.IDTIPOEVENTO = o.IDTIPOEVENTO



                    LEFT OUTER JOIN cot_tipo_alquiler as ta 



                    ON ta.IDTIPOALQUILER = o.IDTIPOALQUILER



                    LEFT OUTER JOIN cot_estados as e 



                    ON e.IDESTADOCOT = o.ESTADOCOT



                    $where3b o.IDORGEJE = '$orgeje'";



        } else {



            //Permiso rol == 0 es Permiso unicamente a los registros del usuario



            $sql = "SELECT 



                        o.IDCOTIZACION, 



                        o.CODREFERENCIA, 



                        o.NRC,



                        o.DESCRIPCIONCOT,



                        o.EMPRESA,



                        o.NOMBRECONTACTO,



                        o.TELCONTACTO,



                        o.CORREOCONTACTO,



                        o.IDENTIDAD, 



                        te.NOMBRETIPOEVENTO, 



                        ta.NOMBRETIPOALQUILER,



                        o.USURECOT,



                        o.FECHAREGCOT,



                        o.ESTADOCOT,



                        e.NOMBREESTADOCOT



                    FROM cot_cotizaciones as o



                    LEFT OUTER JOIN cot_tipo_evento as te 



                    ON te.IDTIPOEVENTO = o.IDTIPOEVENTO



                    LEFT OUTER JOIN cot_tipo_alquiler as ta 



                    ON ta.IDTIPOALQUILER = o.IDTIPOALQUILER



                    LEFT OUTER JOIN cot_estados as e 



                    ON e.IDESTADOCOT = o.ESTADOCOT



                    $where3b o.USUREACT='$usuario'";



        }



        return ejecutarConsulta($sql);



    }







    // Listar estados disponibles



    public function listarEstados(){



        $sql = "SELECT IDESTADOCOT, NOMBREESTADOCOT FROM cot_estados WHERE ESTADOESTCOT = 1"; // solo activos



        return ejecutarConsulta($sql);



    }



    //Cambiar estado

    public function cambiarEstado($idcotizacion, $estado, $observaciones = null, $idobservacion = null, $obsTexto = null){



        $usuario = $_SESSION["login"];



        $idcotizacion = intval($idcotizacion);

        $estado = intval($estado);



        // defaults

        $idObsSql = "NULL";

        $obsTextoSql = "NULL";

        $obsFinal = null;



        // Si viene catálogo seleccionado

        if ($idobservacion !== null && intval($idobservacion) > 0) {



            $idobservacion = intval($idobservacion);

            $idObsSql = $idobservacion;



            $rowObs = $this->obtenerNombreObservacion($idobservacion);



            if ($rowObs) {



                $esOtros = intval($rowObs["ES_OTROS"]) === 1;

                $nombre  = $rowObs["NOMBREOBSERVACION"];



                if ($esOtros) {

                    $obsTexto = trim((string)$obsTexto);

                    if ($obsTexto !== "") {

                        // escape básico

                        $obsTextoEsc = str_replace(["\\","'","\""], ["\\\\","\\'","\\\""], $obsTexto);

                        $obsTextoSql = "'$obsTextoEsc'";

                        $obsFinal = "Otros: " . $obsTexto;

                    } else {

                        $obsFinal = "Otros";

                    }

                } else {

                    $obsFinal = $nombre;

                }

            }

        }



        // Si por alguna razón no se pudo formar obsFinal, cae a observaciones directo

        if ($obsFinal === null && $observaciones !== null && trim($observaciones) !== "") {

            $obsFinal = trim($observaciones);

        }



        // SQL para OBSERVACIONES final

        $obsSql = "NULL";

        if ($obsFinal !== null && trim($obsFinal) !== "") {

            $obsFinalEsc = str_replace(["\\","'","\""], ["\\\\","\\'","\\\""], $obsFinal);

            $obsSql = "'$obsFinalEsc'";

        }



        $sql = "UPDATE cot_cotizaciones 

                SET ESTADOCOT = $estado,

                    OBSERVACIONES = $obsSql,

                    IDOBSERVACION = $idObsSql,

                    OBSERVACION_TEXTO = $obsTextoSql,

                    FECHAMODCOT = NOW(),

                    USUMODCOT = '$usuario'

                WHERE IDCOTIZACION = $idcotizacion";



        return ejecutarConsulta($sql);

    }



    // Listar catálogo de observaciones activas

    public function listarObservaciones(){

        $sql = "SELECT IDOBSERVACION, NOMBREOBSERVACION, ES_OTROS

                FROM cot_observaciones

                WHERE ACTIVA = 1

                ORDER BY ES_OTROS ASC, IDOBSERVACION ASC";

        return ejecutarConsulta($sql);

    }



    // Saber si una observación es "Otros"

    public function esObservacionOtros($idobservacion){

        $idobservacion = intval($idobservacion);

        $sql = "SELECT ES_OTROS 

                FROM cot_observaciones 

                WHERE IDOBSERVACION = $idobservacion 

                LIMIT 1";

        $row = ejecutarConsultaSimpleFila($sql);

        return $row ? intval($row["ES_OTROS"]) === 1 : false;

    }



    public function obtenerEstadoActual($idcotizacion) {

        $idcotizacion = intval($idcotizacion);

        $sql = "SELECT ESTADOCOT FROM cot_cotizaciones WHERE IDCOTIZACION = $idcotizacion LIMIT 1";

        return ejecutarConsultaSimpleFila($sql);

    }



    public function insertarHistorialEstado($idcotizacion, $estado, $usuario) {

        $idcotizacion = intval($idcotizacion);

        $estado = intval($estado);



        $usuarioSql = ($usuario !== null && $usuario !== '')

            ? "'" . limpiarCadena($usuario) . "'"

            : "NULL";



        $sql = "INSERT INTO cot_estado_historial (IDCOTIZACION, ESTADOCOT, FECHAEVENTO, USUARIO)

                VALUES ($idcotizacion, $estado, NOW(), $usuarioSql)";

        return ejecutarConsulta($sql);

    }



    public function transicionPermitida($actual, $nuevo, $rol) {

        $actual = intval($actual);

        $nuevo  = intval($nuevo);



        // Permitir reiniciar a "En proceso" desde cualquier estado

        if ($nuevo === 1) return true;



        // Si quieres bloquear "mismo estado"

        if ($nuevo === $actual) return false;



        // Reglas

        if ($actual === 1) return ($nuevo === 2);

        if ($actual === 2) return ($nuevo === 3 && in_array($rol, [1,3], true)); // ajusta rol si aplica

        if ($actual === 3) return ($nuevo === 8);               // Autorizada -> Enviada

        if ($actual === 8) return in_array($nuevo, [4,5,6,7], true); // Enviada -> cliente/otros



        // Si ya está en estados finales, solo reinicio (arriba)

        if (in_array($actual, [4,5,6,7], true)) return false;



        return false;

    }



    public function obtenerNombreObservacion($idobservacion){

        $idobservacion = intval($idobservacion);

        $sql = "SELECT NOMBREOBSERVACION, ES_OTROS

                FROM cot_observaciones

                WHERE IDOBSERVACION = $idobservacion

                LIMIT 1";

        return ejecutarConsultaSimpleFila($sql);

    }



    public function obtenerCodReferencia($idcotizacion)

    {

        $sql = "SELECT CODREFERENCIA 

                FROM cot_cotizaciones 

                WHERE IDCOTIZACION = '$idcotizacion'";

        return ejecutarConsultaSimpleFila($sql);

    }



    public function obtenerTimeline($idcotizacion){

        $idcotizacion = intval($idcotizacion);



        $sql = "SELECT 

                h.ESTADOCOT,

                e.NOMBREESTADOCOT,

                h.FECHAEVENTO,

                h.USUARIO,

                c.OBSERVACIONES

                FROM cot_estado_historial h

                INNER JOIN cot_estados e ON e.IDESTADOCOT = h.ESTADOCOT

                INNER JOIN cot_cotizaciones c ON c.IDCOTIZACION = h.IDCOTIZACION

                WHERE h.IDCOTIZACION = $idcotizacion

                ORDER BY h.FECHAEVENTO ASC, h.IDHIST ASC";



        return ejecutarConsulta($sql);

    }



    //Query con tipo evento



	public function selectTipoEvento(){



        //Validando que muestre solo los activos



        $sql="SELECT * FROM cot_tipo_evento WHERE ESTADOTIPOEVENT = 1 ORDER BY NOMBRETIPOEVENTO ASC";



        return ejecutarConsulta($sql);



    }







    //Query con tipo alquiler



    public function selTipoAlquiler(){



        $sql="SELECT * FROM cot_tipo_alquiler WHERE ESTADOTIPOALQUILER = 1 ORDER BY NOMBRETIPOALQUILER ASC";



        return ejecutarConsulta($sql);



    }







    // Obtener tipo de alquiler por cotización



    public function obtenerTipoAlquiler($idcotizacion){



        $sql = "SELECT IDTIPOALQUILER FROM cot_cotizaciones WHERE IDCOTIZACION = '$idcotizacion' LIMIT 1";



        return ejecutarConsultaSimpleFila($sql);



    }

    public function obtenerCorreoVendedor($idcotizacion)
    {
        $sql = "SELECT
                    u.EMAIL,
                    u.NOMBREUSU,
                    c.CODREFERENCIA
                FROM cot_cotizaciones c
                INNER JOIN usuarios u
                    ON u.USERUSU = c.USURECOT
                WHERE c.IDCOTIZACION ='$idcotizacion'
                LIMIT 1";

        return ejecutarConsultaSimpleFila($sql);
    }



}