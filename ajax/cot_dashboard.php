<?php

session_start();



require_once "../modelos/Cot_Dashboard.php";



require_once "../vendor/autoload.php";



use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Style\Fill;

use PhpOffice\PhpSpreadsheet\Style\Border;

use PhpOffice\PhpSpreadsheet\Style\Alignment;



$cotDashboard = new CotDashboard();



function responderJson($data)

{

    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($data, JSON_UNESCAPED_UNICODE);

    exit;

}



function validarSesionDashboard()

{

    if (!isset($_SESSION["login"]) || !isset($_SESSION["idrol"])) {

        responderJson([

            "ok" => false,

            "mensaje" => "Sesión no válida o expirada."

        ]);

    }

}



function tieneAccionDashboard($accionCode)

{

    $idrol = isset($_SESSION["idrol"]) ? intval($_SESSION["idrol"]) : 0;

    //$accionCode = limpiarCadena($accionCode);



    if ($idrol <= 0) {

        return false;

    }



    $sql = "SELECT COUNT(*) AS total

            FROM accesoacciones aa

            INNER JOIN acciones a ON a.IDACCION = aa.IDACCION

            INNER JOIN modulos m ON m.IDMODULO = a.IDMODULO

            WHERE aa.IDROL = $idrol

              AND a.ACCIONCODE = '$accionCode'

              AND m.MODULOCODE = '046DAS'

              AND a.ESTADOACCIONES = 1

              AND m.ESTADOMODUDLO = 1";



    $rspta = ejecutarConsultaSimpleFila($sql);



    return isset($rspta["total"]) && intval($rspta["total"]) > 0;

}



function obtenerFiltrosRequest()

{

    return [

        "desde" => isset($_POST["desde"]) ? limpiarCadena($_POST["desde"]) : "",

        "hasta" => isset($_POST["hasta"]) ? limpiarCadena($_POST["hasta"]) : "",

        "tipo_alquiler" => isset($_POST["tipo_alquiler"]) ? limpiarCadena($_POST["tipo_alquiler"]) : "",

        "ejecutivo" => isset($_POST["ejecutivo"]) ? limpiarCadena($_POST["ejecutivo"]) : ""

    ];

}



function limpiarNombreArchivo($texto)

{

    return preg_replace('/[^A-Za-z0-9_\-]/', '_', $texto);

}



function aplicarEstiloTitulo($sheet, $rango)

{

    $sheet->getStyle($rango)->getFont()->setBold(true)->setSize(14);

}



function aplicarEstiloEncabezado($sheet, $rango)

{

    $sheet->getStyle($rango)->getFont()->setBold(true);

    $sheet->getStyle($rango)->getFill()

        ->setFillType(Fill::FILL_SOLID)

        ->getStartColor()

        ->setARGB('FFD9EAF7');



    $sheet->getStyle($rango)->getBorders()->getAllBorders()

        ->setBorderStyle(Border::BORDER_THIN);



    $sheet->getStyle($rango)->getAlignment()

        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

}



function aplicarBordes($sheet, $rango)

{

    $sheet->getStyle($rango)->getBorders()->getAllBorders()

        ->setBorderStyle(Border::BORDER_THIN);

}



function autoAjustarColumnas($sheet, $desde = 'A', $hasta = 'Z')

{

    foreach (range($desde, $hasta) as $col) {

        $sheet->getColumnDimension($col)->setAutoSize(true);

    }

}



function descargarXlsx($spreadsheet, $nombreArchivo)

{

    while (ob_get_level() > 0) {

        ob_end_clean();

    }



    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');

    header('Cache-Control: max-age=0');

    header('Pragma: public');



    $writer = new Xlsx($spreadsheet);

    $writer->save('php://output');

    exit;

}



validarSesionDashboard();



$op = isset($_GET["op"]) ? limpiarCadena($_GET["op"]) : "";



switch ($op) {



    case "filtros":



        if (!tieneAccionDashboard("138VER46")) {

            responderJson([

                "ok" => false,

                "mensaje" => "No tiene permiso para ver el dashboard."

            ]);

        }



        $ejecutivos = [];

        $tipos = [];



        $rsptaEjecutivos = $cotDashboard->listarEjecutivos();



        while ($reg = $rsptaEjecutivos->fetch_object()) {

            $ejecutivos[] = [

                "USURECOT" => $reg->USURECOT

            ];

        }



        $rsptaTipos = $cotDashboard->listarTiposAlquiler();



        while ($reg = $rsptaTipos->fetch_object()) {

            $tipos[] = [

                "IDTIPOALQUILER" => $reg->IDTIPOALQUILER,

                "NOMBRETIPOALQUILER" => $reg->NOMBRETIPOALQUILER

            ];

        }



        responderJson([

            "ok" => true,

            "ejecutivos" => $ejecutivos,

            "tipos_alquiler" => $tipos

        ]);



    break;



    case "kpis":



        if (!tieneAccionDashboard("138VER46")) {

            responderJson([

                "ok" => false,

                "mensaje" => "No tiene permiso para consultar KPIs."

            ]);

        }



        $f = obtenerFiltrosRequest();



        $data = $cotDashboard->obtenerKpis(

            $f["desde"],

            $f["hasta"],

            $f["tipo_alquiler"],

            $f["ejecutivo"]

        );



        $total = isset($data["total_cotizaciones"]) ? intval($data["total_cotizaciones"]) : 0;

        $aprobadas = isset($data["cotizaciones_aprobadas"]) ? intval($data["cotizaciones_aprobadas"]) : 0;

        $tasa = $total > 0 ? round(($aprobadas / $total) * 100, 2) : 0;



        responderJson([

            "ok" => true,

            "data" => [

                "total_cotizaciones" => $total,

                "clientes_unicos" => isset($data["clientes_unicos"]) ? intval($data["clientes_unicos"]) : 0,

                "cotizaciones_aprobadas" => $aprobadas,

                "dinero_aprobado" => isset($data["dinero_aprobado"]) ? floatval($data["dinero_aprobado"]) : 0,

                "ticket_promedio_aprobado" => isset($data["ticket_promedio_aprobado"]) ? floatval($data["ticket_promedio_aprobado"]) : 0,

                "tasa_aprobacion" => $tasa

            ]

        ]);



    break;



    case "pipeline_estados":



        if (!tieneAccionDashboard("138VER46")) {

            responderJson([

                "ok" => false,

                "mensaje" => "No tiene permiso para consultar pipeline."

            ]);

        }



        $f = obtenerFiltrosRequest();



        $rspta = $cotDashboard->obtenerPipelineEstados(

            $f["desde"],

            $f["hasta"],

            $f["tipo_alquiler"],

            $f["ejecutivo"]

        );



        $data = [];



        while ($reg = $rspta->fetch_object()) {

            $data[] = [

                "estado_id" => intval($reg->ESTADOCOT),

                "estado_nombre" => $reg->NOMBREESTADOCOT,

                "cantidad" => intval($reg->cantidad),

                "monto_total" => floatval($reg->monto_total)

            ];

        }



        responderJson([

            "ok" => true,

            "data" => $data

        ]);



    break;



    case "tiempos_estados":



        if (!tieneAccionDashboard("138VER46")) {

            responderJson([

                "ok" => false,

                "mensaje" => "No tiene permiso para consultar tiempos."

            ]);

        }



        $f = obtenerFiltrosRequest();



        $data = $cotDashboard->calcularTiemposPorEstado(

            $f["desde"],

            $f["hasta"],

            $f["tipo_alquiler"],

            $f["ejecutivo"]

        );



        responderJson([

            "ok" => true,

            "data" => $data

        ]);



    break;



    case "tiempo_respuesta_finales":



        if (!tieneAccionDashboard("138VER46")) {

            responderJson([

                "ok" => false,

                "mensaje" => "No tiene permiso para consultar tiempo de respuesta."

            ]);

        }



        $f = obtenerFiltrosRequest();



        $data = $cotDashboard->obtenerTiempoRespuestaEstadosFinales(

            $f["desde"],

            $f["hasta"],

            $f["tipo_alquiler"],

            $f["ejecutivo"]

        );



        responderJson([

            "ok" => true,

            "data" => $data

        ]);



    break;



    case "dinero_aprobado_tipo_alquiler":



        if (!tieneAccionDashboard("138VER46")) {

            responderJson([

                "ok" => false,

                "mensaje" => "No tiene permiso para consultar dinero aprobado."

            ]);

        }



        $f = obtenerFiltrosRequest();



        $rspta = $cotDashboard->obtenerDineroAprobadoPorTipoAlquiler(

            $f["desde"],

            $f["hasta"],

            $f["tipo_alquiler"],

            $f["ejecutivo"]

        );



        $data = [];



        while ($reg = $rspta->fetch_object()) {

            $data[] = [

                "tipo_alquiler" => $reg->NOMBRETIPOALQUILER,

                "dinero_aprobado" => floatval($reg->dinero_aprobado)

            ];

        }



        responderJson([

            "ok" => true,

            "data" => $data

        ]);



    break;

    case "participantes_aprobados_fecha":

        if (!tieneAccionDashboard("138VER46")) {
            responderJson([
                "ok" => false,
                "mensaje" => "No tiene permiso para consultar participantes."
            ]);
        }

        $f = obtenerFiltrosRequest();

        $rspta = $cotDashboard->obtenerParticipantesAprobadosPorFecha(
            $f["desde"],
            $f["hasta"],
            $f["tipo_alquiler"],
            $f["ejecutivo"]
        );

        $data = [];

        while ($reg = $rspta->fetch_object()) {

            $data[] = [
                "fecha" => $reg->FECHA,
                "participantes" => intval($reg->PARTICIPANTES)
            ];
        }

        responderJson([
            "ok" => true,
            "data" => $data
        ]);

    break;



    case "detalle":



        if (!tieneAccionDashboard("138VER46")) {

            responderJson([

                "ok" => false,

                "mensaje" => "No tiene permiso para consultar detalle."

            ]);

        }



        $f = obtenerFiltrosRequest();



        $rspta = $cotDashboard->obtenerDetalleCotizaciones(

            $f["desde"],

            $f["hasta"],

            $f["tipo_alquiler"],

            $f["ejecutivo"]

        );



        $data = [];



        while ($reg = $rspta->fetch_object()) {

            $data[] = [

                "IDCOTIZACION" => intval($reg->IDCOTIZACION),

                "CODREFERENCIA" => $reg->CODREFERENCIA,

                "EMPRESA" => $reg->EMPRESA,

                "USURECOT" => $reg->USURECOT,

                "NOMBRETIPOALQUILER" => $reg->NOMBRETIPOALQUILER,

                "NOMBREESTADOCOT" => $reg->NOMBREESTADOCOT,

                "FECHA_REGISTRO" => $reg->FECHA_REGISTRO,

                "FECHA_PRIMER_DETALLE" => $reg->FECHA_PRIMER_DETALLE,

                "FECHA_ULTIMO_DETALLE" => $reg->FECHA_ULTIMO_DETALLE,

                "TOTAL_DETALLES" => intval($reg->TOTAL_DETALLES),

                "PARTICIPANTES_TOTALES" => intval($reg->PARTICIPANTES_TOTALES),

                "PROMEDIO_PARTICIPANTES" => intval(
                    round(floatval($reg->PROMEDIO_PARTICIPANTES))
                ),
                
                "PROMEDIO_HORAS" => round(
                    floatval($reg->PROMEDIO_HORAS),
                    2
                ),
                
                "ALIMENTACION" => $reg->ALIMENTACION,

                "TIENE_DETALLE" => intval($reg->TIENE_DETALLE),

                "TOTAL_COTIZACION" => floatval($reg->TOTAL_COTIZACION)

            ];

        }



        responderJson([

            "ok" => true,

            "data" => $data

        ]);



    break;



        case "exportar_resumen":



        if (!tieneAccionDashboard("139EXP46")) {

            echo "No tiene permiso para exportar resumen.";

            exit;

        }



        $f = [

            "desde" => isset($_GET["desde"]) ? limpiarCadena($_GET["desde"]) : "",

            "hasta" => isset($_GET["hasta"]) ? limpiarCadena($_GET["hasta"]) : "",

            "tipo_alquiler" => isset($_GET["tipo_alquiler"]) ? limpiarCadena($_GET["tipo_alquiler"]) : "",

            "ejecutivo" => isset($_GET["ejecutivo"]) ? limpiarCadena($_GET["ejecutivo"]) : ""

        ];



        $nombreTipoAlquiler = $cotDashboard->obtenerNombreTipoAlquilerPorId($f["tipo_alquiler"]);



        $kpis = $cotDashboard->obtenerKpis($f["desde"], $f["hasta"], $f["tipo_alquiler"], $f["ejecutivo"]);

        $estados = $cotDashboard->obtenerResumenEstados($f["desde"], $f["hasta"], $f["tipo_alquiler"], $f["ejecutivo"]);

        $tiempos = $cotDashboard->calcularTiemposPorEstado($f["desde"], $f["hasta"], $f["tipo_alquiler"], $f["ejecutivo"]);

        $tiemposFinales = $cotDashboard->obtenerTiempoRespuestaEstadosFinales($f["desde"], $f["hasta"], $f["tipo_alquiler"], $f["ejecutivo"]);

        $dineroTipos = $cotDashboard->obtenerResumenDineroPorTipo($f["desde"], $f["hasta"], $f["tipo_alquiler"], $f["ejecutivo"]);



        $total = isset($kpis["total_cotizaciones"]) ? intval($kpis["total_cotizaciones"]) : 0;

        $aprobadas = isset($kpis["cotizaciones_aprobadas"]) ? intval($kpis["cotizaciones_aprobadas"]) : 0;

        $tasa = $total > 0 ? round(($aprobadas / $total) * 100, 2) : 0;



        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle("Resumen");



        $fila = 1;



        $sheet->setCellValue("A{$fila}", "Reporte Resumen - Dashboard de Cotizaciones");

        aplicarEstiloTitulo($sheet, "A{$fila}");

        $fila += 2;



        $sheet->setCellValue("A{$fila}", "Fecha inicio");

        $sheet->setCellValue("B{$fila}", $f["desde"]);

        $fila++;



        $sheet->setCellValue("A{$fila}", "Fecha fin");

        $sheet->setCellValue("B{$fila}", $f["hasta"]);

        $fila++;



        $sheet->setCellValue("A{$fila}", "Tipo alquiler");

        $sheet->setCellValue("B{$fila}", $nombreTipoAlquiler);

        $fila++;



        $sheet->setCellValue("A{$fila}", "Ejecutivo");

        $sheet->setCellValue("B{$fila}", $f["ejecutivo"] == "" ? "Todos" : $f["ejecutivo"]);

        aplicarBordes($sheet, "A3:B{$fila}");



        $fila += 2;



        $sheet->setCellValue("A{$fila}", "Indicadores principales");

        aplicarEstiloTitulo($sheet, "A{$fila}");

        $fila++;



        $sheet->fromArray([

            "Cotizaciones totales",

            "Clientes atendidos",

            "Cotizaciones aprobadas",

            "Tasa aprobación",

            "Dinero aprobado",

            "Ticket promedio aprobado"

        ], null, "A{$fila}");

        aplicarEstiloEncabezado($sheet, "A{$fila}:F{$fila}");

        $fila++;



        $sheet->fromArray([

            $total,

            intval($kpis["clientes_unicos"]),

            $aprobadas,

            $tasa / 100,

            floatval($kpis["dinero_aprobado"]),

            floatval($kpis["ticket_promedio_aprobado"])

        ], null, "A{$fila}");



        $sheet->getStyle("D{$fila}")->getNumberFormat()->setFormatCode('0.00%');

        $sheet->getStyle("E{$fila}:F{$fila}")->getNumberFormat()->setFormatCode('$#,##0.00');

        aplicarBordes($sheet, "A" . ($fila - 1) . ":F{$fila}");



        $fila += 2;



        $sheet->setCellValue("A{$fila}", "Cotizaciones por estado");

        aplicarEstiloTitulo($sheet, "A{$fila}");

        $fila++;



        $sheet->fromArray(["Estado", "Cantidad", "Monto total"], null, "A{$fila}");

        aplicarEstiloEncabezado($sheet, "A{$fila}:C{$fila}");

        $inicioTabla = $fila;

        $fila++;



        while ($row = $estados->fetch_object()) {

            $sheet->fromArray([

                $row->NOMBREESTADOCOT,

                intval($row->cantidad),

                floatval($row->monto_total)

            ], null, "A{$fila}");



            $sheet->getStyle("C{$fila}")->getNumberFormat()->setFormatCode('$#,##0.00');

            $fila++;

        }



        aplicarBordes($sheet, "A{$inicioTabla}:C" . ($fila - 1));



        $fila += 2;



        $sheet->setCellValue("A{$fila}", "Tiempo acumulado por estado");

        aplicarEstiloTitulo($sheet, "A{$fila}");

        $fila++;



        $sheet->fromArray([

            "Estado",

            "Cotizaciones",

            "Tiempo total días",

            "Tiempo promedio días",

            "Porcentaje tiempo"

        ], null, "A{$fila}");

        aplicarEstiloEncabezado($sheet, "A{$fila}:E{$fila}");

        $inicioTabla = $fila;

        $fila++;



        foreach ($tiempos as $t) {

            $sheet->fromArray([

                $t["estado_nombre"],

                intval($t["cantidad_cotizaciones"]),

                floatval($t["tiempo_total_dias"]),

                floatval($t["tiempo_promedio_dias"]),

                floatval($t["porcentaje_tiempo"]) / 100

            ], null, "A{$fila}");



            $sheet->getStyle("E{$fila}")->getNumberFormat()->setFormatCode('0.00%');

            $fila++;

        }



        aplicarBordes($sheet, "A{$inicioTabla}:E" . ($fila - 1));



        $fila += 2;



        $sheet->setCellValue("A{$fila}", "Tiempo promedio desde Enviada hasta cierre");

        aplicarEstiloTitulo($sheet, "A{$fila}");

        $fila++;



        $sheet->fromArray([

            "Estado final",

            "Cantidad de cotizaciones",

            "Tiempo promedio horas",

            "Tiempo promedio días"

        ], null, "A{$fila}");

        aplicarEstiloEncabezado($sheet, "A{$fila}:D{$fila}");

        $inicioTabla = $fila;

        $fila++;



        foreach ($tiemposFinales as $t) {

            $sheet->fromArray([

                $t["estado_nombre"],

                intval($t["cantidad"]),

                floatval($t["tiempo_promedio_horas"]),

                floatval($t["tiempo_promedio_dias"])

            ], null, "A{$fila}");

            $fila++;

        }



        aplicarBordes($sheet, "A{$inicioTabla}:D" . ($fila - 1));



        $fila += 2;



        $sheet->setCellValue("A{$fila}", "Dinero aprobado por tipo de alquiler");

        aplicarEstiloTitulo($sheet, "A{$fila}");

        $fila++;



        $sheet->fromArray(["Tipo alquiler", "Dinero aprobado"], null, "A{$fila}");

        aplicarEstiloEncabezado($sheet, "A{$fila}:B{$fila}");

        $inicioTabla = $fila;

        $fila++;



        while ($row = $dineroTipos->fetch_object()) {

            $sheet->fromArray([

                $row->NOMBRETIPOALQUILER,

                floatval($row->dinero_aprobado)

            ], null, "A{$fila}");



            $sheet->getStyle("B{$fila}")->getNumberFormat()->setFormatCode('$#,##0.00');

            $fila++;

        }



        aplicarBordes($sheet, "A{$inicioTabla}:B" . ($fila - 1));



        autoAjustarColumnas($sheet, 'A', 'F');



        $nombreArchivo = "reporte_resumen_dashboard_" . date("Ymd_His") . ".xlsx";

        descargarXlsx($spreadsheet, $nombreArchivo);



    break;



        case "exportar_detalle":



        if (!tieneAccionDashboard("140EXP46")) {

            echo "No tiene permiso para exportar detalle.";

            exit;

        }



        $f = [

            "desde" => isset($_GET["desde"]) ? limpiarCadena($_GET["desde"]) : "",

            "hasta" => isset($_GET["hasta"]) ? limpiarCadena($_GET["hasta"]) : "",

            "tipo_alquiler" => isset($_GET["tipo_alquiler"]) ? limpiarCadena($_GET["tipo_alquiler"]) : "",

            "ejecutivo" => isset($_GET["ejecutivo"]) ? limpiarCadena($_GET["ejecutivo"]) : ""

        ];



        $nombreTipoAlquiler = $cotDashboard->obtenerNombreTipoAlquilerPorId($f["tipo_alquiler"]);



        $detalle = $cotDashboard->obtenerDetalleCotizaciones(

            $f["desde"],

            $f["hasta"],

            $f["tipo_alquiler"],

            $f["ejecutivo"]

        );



        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle("Detalle");



        $fila = 1;



        $sheet->setCellValue("A{$fila}", "Reporte Detalle - Dashboard de Cotizaciones");

        aplicarEstiloTitulo($sheet, "A{$fila}");

        $fila += 2;



        $sheet->setCellValue("A{$fila}", "Fecha inicio");

        $sheet->setCellValue("B{$fila}", $f["desde"]);

        $fila++;



        $sheet->setCellValue("A{$fila}", "Fecha fin");

        $sheet->setCellValue("B{$fila}", $f["hasta"]);

        $fila++;



        $sheet->setCellValue("A{$fila}", "Tipo alquiler");

        $sheet->setCellValue("B{$fila}", $nombreTipoAlquiler);

        $fila++;



        $sheet->setCellValue("A{$fila}", "Ejecutivo");

        $sheet->setCellValue("B{$fila}", $f["ejecutivo"] == "" ? "Todos" : $f["ejecutivo"]);

        aplicarBordes($sheet, "A3:B{$fila}");



        $fila += 2;



        $encabezados = [

            "ID Cotización",

            "Referencia",

            "Empresa",

            "Contacto",

            "Teléfono",

            "Correo",

            "Ejecutivo",

            "Tipo alquiler",

            "Estado",

            "Fecha registro",

            "Primer detalle",

            "Último detalle",

            "Total detalles",

            "Participantes totales",

            "Promedio participantes por detalle",
            
            "Promedio horas",
            
            "Alimentación",

            "Tiene detalle",

            "Total cotización"

        ];



        $sheet->fromArray($encabezados, null, "A{$fila}");

        aplicarEstiloEncabezado($sheet, "A{$fila}:S{$fila}");

        $inicioTabla = $fila;

        $fila++;



        while ($row = $detalle->fetch_object()) {

            $sheet->fromArray([

                intval($row->IDCOTIZACION),

                $row->CODREFERENCIA,

                $row->EMPRESA,

                $row->NOMBRECONTACTO,

                $row->TELCONTACTO,

                $row->CORREOCONTACTO,

                $row->USURECOT,

                $row->NOMBRETIPOALQUILER,

                $row->NOMBREESTADOCOT,

                $row->FECHA_REGISTRO,

                $row->FECHA_PRIMER_DETALLE,

                $row->FECHA_ULTIMO_DETALLE,

                intval($row->TOTAL_DETALLES),

                intval($row->PARTICIPANTES_TOTALES),

                intval(
                    round(floatval($row->PROMEDIO_PARTICIPANTES))
                ),
                
                round(
                    floatval($row->PROMEDIO_HORAS),
                    2
                ),
                
                $row->ALIMENTACION,

                intval($row->TIENE_DETALLE) === 1 ? "Sí" : "No",

                floatval($row->TOTAL_COTIZACION)

            ], null, "A{$fila}");



            $sheet->getStyle("S{$fila}")
                ->getNumberFormat()
                ->setFormatCode('$#,##0.00');

            $fila++;

        }



        aplicarBordes($sheet, "A{$inicioTabla}:S" . ($fila - 1));
        
        autoAjustarColumnas($sheet, 'A', 'S');



        $nombreArchivo = "reporte_detalle_dashboard_" . date("Ymd_His") . ".xlsx";

        descargarXlsx($spreadsheet, $nombreArchivo);



    break;



    default:



        responderJson([

            "ok" => false,

            "mensaje" => "Operación no válida."

        ]);



    break;

}

?>