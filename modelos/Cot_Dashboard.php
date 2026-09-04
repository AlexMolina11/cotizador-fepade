<?php

require "../config/Conexion.php";



class CotDashboard

{

    public function __construct()

    {

    }



    private function limpiarTexto($valor)

    {

        return isset($valor) ? trim((string)$valor) : '';

    }



    private function escapar($valor)

    {

        global $conexion;

        return mysqli_real_escape_string($conexion, $valor);

    }



    private function normalizarFecha($fecha)

    {

        $fecha = $this->limpiarTexto($fecha);



        if ($fecha === '') {

            return null;

        }



        $dt = DateTime::createFromFormat('Y-m-d', $fecha);



        if ($dt && $dt->format('Y-m-d') === $fecha) {

            return $fecha;

        }



        return null;

    }



    private function normalizarEntero($valor)

    {

        if ($valor === '' || $valor === null) {

            return null;

        }



        if (filter_var($valor, FILTER_VALIDATE_INT) !== false) {

            return (int)$valor;

        }



        return null;

    }



    private function construirWhereDashboard($desde, $hasta, $tipoAlquiler, $ejecutivo)

    {

        $condiciones = array();



        $desde = $this->normalizarFecha($desde);

        $hasta = $this->normalizarFecha($hasta);

        $tipoAlquiler = $this->normalizarEntero($tipoAlquiler);

        $ejecutivo = $this->limpiarTexto($ejecutivo);



        if (!empty($desde) && !empty($hasta)) {

            $desdeSql = $this->escapar($desde);

            $hastaSql = $this->escapar($hasta);



            $condiciones[] = "(

                FECHA_REGISTRO BETWEEN '{$desdeSql}' AND '{$hastaSql}'

                OR (

                    FECHA_PRIMER_DETALLE <= '{$hastaSql}'

                    AND FECHA_ULTIMO_DETALLE >= '{$desdeSql}'

                )

            )";

        }



        if (!empty($tipoAlquiler)) {

            $condiciones[] = "IDTIPOALQUILER = {$tipoAlquiler}";

        }



        if (!empty($ejecutivo)) {

            $ejecutivoSql = $this->escapar($ejecutivo);

            $condiciones[] = "USURECOT = '{$ejecutivoSql}'";

        }



        if (count($condiciones) === 0) {

            return "";

        }



        return " WHERE " . implode(" AND ", $condiciones);

    }
    
    private function construirWhereDetalleDashboard(
        $desde,
        $hasta,
        $tipoAlquiler,
        $ejecutivo
    ) {
        $condiciones = array();
    
        $desde = $this->normalizarFecha($desde);
        $hasta = $this->normalizarFecha($hasta);
        $tipoAlquiler = $this->normalizarEntero($tipoAlquiler);
        $ejecutivo = $this->limpiarTexto($ejecutivo);
    
        if (!empty($desde) && !empty($hasta)) {
    
            $desdeSql = $this->escapar($desde);
            $hastaSql = $this->escapar($hasta);
    
            $condiciones[] = "(
                b.FECHA_REGISTRO BETWEEN '{$desdeSql}' AND '{$hastaSql}'
    
                OR EXISTS (
    
                    SELECT 1
    
                    FROM cot_detalle_cotizaciones df
    
                    WHERE df.IDCOTIZACION = b.IDCOTIZACION
                      AND df.ESTADOCOT = 1
                      AND DATE(df.FECHACOT)
                          BETWEEN '{$desdeSql}' AND '{$hastaSql}'
    
                )
            )";
        }
    
        if (!empty($tipoAlquiler)) {
            $condiciones[] = "b.IDTIPOALQUILER = {$tipoAlquiler}";
        }
    
        if (!empty($ejecutivo)) {
    
            $ejecutivoSql = $this->escapar($ejecutivo);
    
            $condiciones[] = "b.USURECOT = '{$ejecutivoSql}'";
        }
    
        if (count($condiciones) === 0) {
            return "";
        }
    
        return " WHERE " . implode(
            " AND ",
            $condiciones
        );
    }



    public function listarEjecutivos()

    {

        $sql = "SELECT DISTINCT USURECOT

                FROM vw_cot_dashboard_base

                WHERE USURECOT IS NOT NULL

                  AND TRIM(USURECOT) <> ''

                ORDER BY USURECOT ASC";



        return ejecutarConsulta($sql);

    }



    public function listarTiposAlquiler()

    {

        $sql = "SELECT IDTIPOALQUILER, NOMBRETIPOALQUILER

                FROM cot_tipo_alquiler

                WHERE ESTADOTIPOALQUILER = 1

                ORDER BY NOMBRETIPOALQUILER ASC";



        return ejecutarConsulta($sql);

    }



    public function obtenerKpis($desde, $hasta, $tipoAlquiler, $ejecutivo)

    {

        $where = $this->construirWhereDashboard($desde, $hasta, $tipoAlquiler, $ejecutivo);



        $sql = "SELECT

                    COUNT(DISTINCT IDCOTIZACION) AS total_cotizaciones,

                    COUNT(DISTINCT EMPRESA) AS clientes_unicos,

                    SUM(CASE WHEN ESTADOCOT = 4 THEN 1 ELSE 0 END) AS cotizaciones_aprobadas,

                    COALESCE(SUM(CASE WHEN ESTADOCOT = 4 THEN TOTAL_COTIZACION ELSE 0 END), 0) AS dinero_aprobado,

                    COALESCE(AVG(CASE WHEN ESTADOCOT = 4 THEN TOTAL_COTIZACION ELSE NULL END), 0) AS ticket_promedio_aprobado

                FROM vw_cot_dashboard_base

                {$where}";



        return ejecutarConsultaSimpleFila($sql);

    }



    public function obtenerPipelineEstados($desde, $hasta, $tipoAlquiler, $ejecutivo)

    {

        $where = $this->construirWhereDashboard($desde, $hasta, $tipoAlquiler, $ejecutivo);



        $sql = "SELECT

                    ESTADOCOT,

                    NOMBREESTADOCOT,

                    COUNT(*) AS cantidad,

                    COALESCE(SUM(TOTAL_COTIZACION), 0) AS monto_total

                FROM vw_cot_dashboard_base

                {$where}

                GROUP BY ESTADOCOT, NOMBREESTADOCOT

                ORDER BY ESTADOCOT ASC";



        return ejecutarConsulta($sql);

    }



    public function obtenerDineroAprobadoPorTipoAlquiler($desde, $hasta, $tipoAlquiler, $ejecutivo)

    {

        $where = $this->construirWhereDashboard($desde, $hasta, $tipoAlquiler, $ejecutivo);



        if ($where == "") {

            $where = " WHERE ESTADOCOT = 4 ";

        } else {

            $where .= " AND ESTADOCOT = 4 ";

        }



        $sql = "SELECT

                    IDTIPOALQUILER,

                    NOMBRETIPOALQUILER,

                    COALESCE(SUM(TOTAL_COTIZACION), 0) AS dinero_aprobado

                FROM vw_cot_dashboard_base

                {$where}

                GROUP BY IDTIPOALQUILER, NOMBRETIPOALQUILER

                ORDER BY NOMBRETIPOALQUILER ASC";



        return ejecutarConsulta($sql);

    }

    public function obtenerParticipantesAprobadosPorFecha(
        $desde,
        $hasta,
        $tipoAlquiler,
        $ejecutivo
    ) {
        $where = $this->construirWhereDashboard(
            $desde,
            $hasta,
            $tipoAlquiler,
            $ejecutivo
        );

        /*
        * Agregamos las condiciones propias del gráfico:
        *
        * ESTADOCOT = 4  -> Aprobada por el cliente
        * d.ESTADOCOT = 1 -> detalle activo
        */

        if ($where == "") {
            $where = " WHERE b.ESTADOCOT = 4
                        AND d.ESTADOCOT = 1 ";
        } else {
            $where .= " AND b.ESTADOCOT = 4
                        AND d.ESTADOCOT = 1 ";
        }

        /*
        * El filtro principal del dashboard puede incluir cotizaciones
        * cuya fecha de registro o rango de detalles coincida.
        *
        * Para este gráfico además queremos que el punto graficado
        * corresponda estrictamente a FECHACOT.
        */

        $fechaDesde = $this->normalizarFecha($desde);
        $fechaHasta = $this->normalizarFecha($hasta);

        if (!empty($fechaDesde)) {
            $fechaDesdeSql = $this->escapar($fechaDesde);
            $where .= " AND DATE(d.FECHACOT) >= '{$fechaDesdeSql}' ";
        }

        if (!empty($fechaHasta)) {
            $fechaHastaSql = $this->escapar($fechaHasta);
            $where .= " AND DATE(d.FECHACOT) <= '{$fechaHastaSql}' ";
        }

        $sql = "SELECT
                    DATE(d.FECHACOT) AS FECHA,
                    COALESCE(SUM(d.CANTIDADCOT), 0) AS PARTICIPANTES
                FROM vw_cot_dashboard_base b

                INNER JOIN cot_detalle_cotizaciones d
                    ON d.IDCOTIZACION = b.IDCOTIZACION

                {$where}

                GROUP BY DATE(d.FECHACOT)

                ORDER BY DATE(d.FECHACOT) ASC";

        return ejecutarConsulta($sql);
    }



    public function obtenerDetalleCotizaciones($desde, $hasta, $tipoAlquiler, $ejecutivo)
    {
        $where = $this->construirWhereDetalleDashboard(
            $desde,
            $hasta,
            $tipoAlquiler,
            $ejecutivo
        );
    
        $sql = "SELECT
                    b.IDCOTIZACION,
                    b.CODREFERENCIA,
                    b.EMPRESA,
                    b.NOMBRECONTACTO,
                    b.TELCONTACTO,
                    b.CORREOCONTACTO,
                    b.USURECOT,
                    b.IDTIPOALQUILER,
                    b.NOMBRETIPOALQUILER,
                    b.ESTADOCOT,
                    b.NOMBREESTADOCOT,
                    b.FECHA_REGISTRO,
    
                    COALESCE(d.FECHA_PRIMER_DETALLE, '') AS FECHA_PRIMER_DETALLE,
                    COALESCE(d.FECHA_ULTIMO_DETALLE, '') AS FECHA_ULTIMO_DETALLE,
    
                    COALESCE(d.TOTAL_DETALLES, 0) AS TOTAL_DETALLES,
    
                    CASE
                        WHEN COALESCE(d.TOTAL_DETALLES, 0) > 0 THEN 1
                        ELSE 0
                    END AS TIENE_DETALLE,
    
                    COALESCE(
                        d.PARTICIPANTES_TOTALES,
                        0
                    ) AS PARTICIPANTES_TOTALES,
    
                    COALESCE(
                        d.PROMEDIO_PARTICIPANTES,
                        0
                    ) AS PROMEDIO_PARTICIPANTES,
    
                    COALESCE(
                        d.PROMEDIO_HORAS,
                        0
                    ) AS PROMEDIO_HORAS,
    
                    CASE
                        WHEN COALESCE(a.TIENE_ALIMENTACION, 0) > 0
                        THEN 'SI'
                        ELSE 'NO'
                    END AS ALIMENTACION,
    
                    COALESCE(
                        t.TOTAL_COTIZACION,
                        0
                    ) AS TOTAL_COTIZACION
    
                FROM vw_cot_dashboard_base b
    
                /* =========================================
                   DATOS DE DETALLES ACTIVOS
                   ========================================= */
                LEFT JOIN (
    
                    SELECT
                        IDCOTIZACION,
    
                        MIN(FECHACOT)
                            AS FECHA_PRIMER_DETALLE,
    
                        MAX(FECHACOT)
                            AS FECHA_ULTIMO_DETALLE,
    
                        COUNT(*)
                            AS TOTAL_DETALLES,
    
                        SUM(
                            COALESCE(CANTIDADCOT, 0)
                        ) AS PARTICIPANTES_TOTALES,
    
                        AVG(
                            COALESCE(CANTIDADCOT, 0)
                        ) AS PROMEDIO_PARTICIPANTES,
    
                        AVG(
                            COALESCE(DURACIONCOT, 0)
                        ) AS PROMEDIO_HORAS
    
                    FROM cot_detalle_cotizaciones
    
                    WHERE ESTADOCOT = 1
    
                    GROUP BY IDCOTIZACION
    
                ) d
                    ON d.IDCOTIZACION = b.IDCOTIZACION
    
    
                /* =========================================
                   ALIMENTACIÓN
                   SOLO DETALLES ACTIVOS
                   ========================================= */
                LEFT JOIN (
    
                    SELECT
                        dc.IDCOTIZACION,
                        COUNT(*) AS TIENE_ALIMENTACION
    
                    FROM cot_detalle_cotizaciones dc
    
                    INNER JOIN cot_detalle_insumos di
                        ON di.IDDETALLECOT = dc.IDDETALLECOT
    
                    INNER JOIN cot_insumos i
                        ON i.IDINSUMO = di.IDINSUMO
    
                    WHERE dc.ESTADOCOT = 1
                      AND i.IDTIPOINSUMO = 2
    
                    GROUP BY dc.IDCOTIZACION
    
                ) a
                    ON a.IDCOTIZACION = b.IDCOTIZACION
    
    
                /* =========================================
                   TOTAL MONETARIO
                   SOLO INSUMOS DE DETALLES ACTIVOS
                   ========================================= */
                LEFT JOIN (
    
                    SELECT
                        dc.IDCOTIZACION,
    
                        SUM(
                            COALESCE(di.TOTAL, 0)
                        ) AS TOTAL_COTIZACION
    
                    FROM cot_detalle_cotizaciones dc
    
                    INNER JOIN cot_detalle_insumos di
                        ON di.IDDETALLECOT = dc.IDDETALLECOT
    
                    WHERE dc.ESTADOCOT = 1
    
                    GROUP BY dc.IDCOTIZACION
    
                ) t
                    ON t.IDCOTIZACION = b.IDCOTIZACION
    
                {$where}
    
                ORDER BY
                    b.FECHA_REGISTRO DESC,
                    b.IDCOTIZACION DESC";
    
        return ejecutarConsulta($sql);
    }



    public function obtenerHistorialParaTiempos($desde, $hasta, $tipoAlquiler, $ejecutivo)

    {

        $whereBase = $this->construirWhereDashboard($desde, $hasta, $tipoAlquiler, $ejecutivo);



        $sql = "SELECT

                    h.IDCOTIZACION,

                    h.ESTADOCOT,

                    e.NOMBREESTADOCOT,

                    h.FECHAEVENTO,

                    b.USURECOT,

                    b.IDTIPOALQUILER,

                    b.NOMBRETIPOALQUILER,

                    b.EMPRESA

                FROM cot_estado_historial h

                INNER JOIN cot_estados e

                    ON e.IDESTADOCOT = h.ESTADOCOT

                INNER JOIN vw_cot_dashboard_base b

                    ON b.IDCOTIZACION = h.IDCOTIZACION

                {$whereBase}

                ORDER BY h.IDCOTIZACION ASC, h.FECHAEVENTO ASC";



        return ejecutarConsulta($sql);

    }



    public function calcularTiemposPorEstado($desde, $hasta, $tipoAlquiler, $ejecutivo)

    {

        $rspta = $this->obtenerHistorialParaTiempos($desde, $hasta, $tipoAlquiler, $ejecutivo);



        $historial = array();



        while ($row = $rspta->fetch_object()) {

            $id = (int)$row->IDCOTIZACION;



            if (!isset($historial[$id])) {

                $historial[$id] = array();

            }



            $historial[$id][] = $row;

        }



        $resumen = array();

        $tiempoTotalGlobal = 0;



        foreach ($historial as $idCotizacion => $eventos) {

            $totalEventos = count($eventos);



            for ($i = 0; $i < $totalEventos; $i++) {

                $actual = $eventos[$i];

                $inicio = strtotime($actual->FECHAEVENTO);



                if ($inicio === false) {

                    continue;

                }



                if (isset($eventos[$i + 1])) {

                    $fin = strtotime($eventos[$i + 1]->FECHAEVENTO);

                } else {

                    $fin = time();

                }



                if ($fin === false || $fin < $inicio) {

                    $fin = $inicio;

                }



                $duracion = $fin - $inicio;



                $estadoId = (int)$actual->ESTADOCOT;

                $estadoNombre = $actual->NOMBREESTADOCOT;



                if (!isset($resumen[$estadoId])) {

                    $resumen[$estadoId] = array(

                        'estado_id' => $estadoId,

                        'estado_nombre' => $estadoNombre,

                        'tiempo_total_segundos' => 0,

                        'cotizaciones' => array()

                    );

                }



                $resumen[$estadoId]['tiempo_total_segundos'] += $duracion;

                $resumen[$estadoId]['cotizaciones'][$idCotizacion] = true;

                $tiempoTotalGlobal += $duracion;

            }

        }



        $resultado = array();



        foreach ($resumen as $estadoId => $info) {

            $cantidadCotizaciones = count($info['cotizaciones']);



            $tiempoPromedio = $cantidadCotizaciones > 0

                ? $info['tiempo_total_segundos'] / $cantidadCotizaciones

                : 0;



            $porcentajeTiempo = $tiempoTotalGlobal > 0

                ? ($info['tiempo_total_segundos'] / $tiempoTotalGlobal) * 100

                : 0;



            $resultado[] = array(

                'estado_id' => $estadoId,

                'estado_nombre' => $info['estado_nombre'],

                'tiempo_total_segundos' => $info['tiempo_total_segundos'],

                'tiempo_total_horas' => round($info['tiempo_total_segundos'] / 3600, 2),

                'tiempo_total_dias' => round($info['tiempo_total_segundos'] / 86400, 2),

                'cantidad_cotizaciones' => $cantidadCotizaciones,

                'tiempo_promedio_segundos' => round($tiempoPromedio, 2),

                'tiempo_promedio_horas' => round($tiempoPromedio / 3600, 2),

                'tiempo_promedio_dias' => round($tiempoPromedio / 86400, 2),

                'porcentaje_tiempo' => round($porcentajeTiempo, 2)

            );

        }



        usort($resultado, function ($a, $b) {

            return $a['estado_id'] <=> $b['estado_id'];

        });



        return $resultado;

    }



    public function obtenerTiempoRespuestaEstadosFinales($desde, $hasta, $tipoAlquiler, $ejecutivo)

    {

        $whereBase = $this->construirWhereDashboard($desde, $hasta, $tipoAlquiler, $ejecutivo);



        $sql = "SELECT

                    b.IDCOTIZACION,

                    b.CODREFERENCIA,

                    b.EMPRESA,

                    b.USURECOT,

                    b.IDTIPOALQUILER,

                    b.NOMBRETIPOALQUILER,

                    b.ESTADOCOT,

                    b.NOMBREESTADOCOT,

                    MIN(CASE WHEN h.ESTADOCOT = 8 THEN h.FECHAEVENTO END) AS FECHA_ENVIADA,

                    MIN(CASE WHEN h.ESTADOCOT IN (4,5,6,7) THEN h.FECHAEVENTO END) AS FECHA_FINAL,

                    b.ESTADOCOT AS ESTADO_FINAL

                FROM vw_cot_dashboard_base b

                INNER JOIN cot_estado_historial h

                    ON h.IDCOTIZACION = b.IDCOTIZACION

                {$whereBase}

                GROUP BY

                    b.IDCOTIZACION,

                    b.CODREFERENCIA,

                    b.EMPRESA,

                    b.USURECOT,

                    b.IDTIPOALQUILER,

                    b.NOMBRETIPOALQUILER,

                    b.ESTADOCOT,

                    b.NOMBREESTADOCOT

                HAVING 

                    FECHA_ENVIADA IS NOT NULL

                    AND FECHA_FINAL IS NOT NULL

                    AND ESTADO_FINAL IN (4,5,6,7)

                ORDER BY b.ESTADOCOT ASC";



        $rspta = ejecutarConsulta($sql);



        $resumen = array();



        while ($row = $rspta->fetch_object()) {

            $estadoId = (int)$row->ESTADO_FINAL;

            $estadoNombre = $row->NOMBREESTADOCOT;



            $inicio = strtotime($row->FECHA_ENVIADA);

            $fin = strtotime($row->FECHA_FINAL);



            if ($inicio === false || $fin === false || $fin < $inicio) {

                continue;

            }



            $duracion = $fin - $inicio;



            if (!isset($resumen[$estadoId])) {

                $resumen[$estadoId] = array(

                    "estado_id" => $estadoId,

                    "estado_nombre" => $estadoNombre,

                    "total_segundos" => 0,

                    "cantidad" => 0

                );

            }



            $resumen[$estadoId]["total_segundos"] += $duracion;

            $resumen[$estadoId]["cantidad"]++;

        }



        $resultado = array();



        foreach ($resumen as $estadoId => $info) {

            $promedio = $info["cantidad"] > 0

                ? $info["total_segundos"] / $info["cantidad"]

                : 0;



            $resultado[] = array(

                "estado_id" => $estadoId,

                "estado_nombre" => $info["estado_nombre"],

                "cantidad" => $info["cantidad"],

                "tiempo_promedio_segundos" => round($promedio, 2),

                "tiempo_promedio_horas" => round($promedio / 3600, 2),

                "tiempo_promedio_dias" => round($promedio / 86400, 2)

            );

        }



        usort($resultado, function ($a, $b) {

            return $a["estado_id"] <=> $b["estado_id"];

        });



        return $resultado;

    }



    public function obtenerResumenEstados($desde, $hasta, $tipoAlquiler, $ejecutivo)

    {

        return $this->obtenerPipelineEstados($desde, $hasta, $tipoAlquiler, $ejecutivo);

    }



    public function obtenerResumenDineroPorTipo($desde, $hasta, $tipoAlquiler, $ejecutivo)

    {

        return $this->obtenerDineroAprobadoPorTipoAlquiler($desde, $hasta, $tipoAlquiler, $ejecutivo);

    }



    public function obtenerNombreTipoAlquilerPorId($idTipoAlquiler)

    {

        $idTipoAlquiler = $this->normalizarEntero($idTipoAlquiler);



        if (empty($idTipoAlquiler)) {

            return "Todos";

        }



        $sql = "SELECT NOMBRETIPOALQUILER

                FROM cot_tipo_alquiler

                WHERE IDTIPOALQUILER = {$idTipoAlquiler}

                LIMIT 1";



        $row = ejecutarConsultaSimpleFila($sql);



        if ($row && isset($row["NOMBRETIPOALQUILER"])) {

            return $row["NOMBRETIPOALQUILER"];

        }



        return "Tipo alquiler no encontrado";

    }

}

?>