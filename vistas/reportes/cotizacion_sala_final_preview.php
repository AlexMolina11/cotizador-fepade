<?php

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../config/Conexion.php';



$idcotizacion = $_GET['idcotizacion'] ?? '';



/* ======================

   FUNCIONES AUXILIARES

   ====================== */

function formatPrice($price) {

    return $price ? '$' . number_format($price, 2) : '$0.00';

}



function formatDate($date) {

    if ($date) {

        $dateObj = new DateTime($date);

        return $dateObj->format('d/m/Y');

    }

    return 'N/A';

}



function formatTime($time) {

    if ($time) {

        $timeObj = new DateTime($time);

        return $timeObj->format('h:i a');

    }

    return 'N/A';

}



function calcularTotalGeneral($detalles_por_fecha) {

    $total = 0;

    foreach ($detalles_por_fecha as $detalles) {

        foreach ($detalles as $detalle) {

            foreach ($detalle['insumos'] as $insumo) {

                $total += $insumo['TOTAL'] ?? 0;

            }

        }

    }

    return $total;

}



function formatFechaActualEspanol() {

    $meses = [

        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',

        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',

        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'

    ];

    $dia = date('d');

    $mes = $meses[(int)date('m')];

    $anio = date('Y');

    return "$dia de $mes del $anio";

}



function obtenerRangoFechas($detalles_por_fecha) {

    if (empty($detalles_por_fecha)) {

        return "en la fecha indicada";

    }

    $fechas = array_keys($detalles_por_fecha);

    sort($fechas);

    $primera = reset($fechas);

    $ultima = end($fechas);



    return ($primera === $ultima)

        ? "en la fecha: <strong>" . formatDate($primera) . "</strong>"

        : "en las fechas del <strong>" . formatDate($primera) . "</strong> hasta el <strong>" . formatDate($ultima) . "</strong>";

}



/* ======================

   CONSULTAS PRINCIPALES

   ====================== */

$cotizacion = null;

$detalles_por_fecha = [];



if ($idcotizacion != '') {

    // Datos principales

    $stmt = $conexion->prepare("SELECT * FROM vw_cotizacion_final WHERE IDCOTIZACION = ? LIMIT 1");

    $stmt->bind_param("i", $idcotizacion);

    $stmt->execute();

    $resultado = $stmt->get_result();

    $cotizacion = $resultado->fetch_assoc();

    $exenta = $cotizacion['EXENTACOT'] ?? 0;

    $estadocot = $cotizacion['ESTADOCOT'] ?? 0;



    if ($cotizacion) {

        // Detalles de la cotización

        $stmt_detalles = $conexion->prepare("

            SELECT DISTINCT 

                IDDETALLECOT, FECHACOT, HORAINICOT, HORAFINCOT, DURACIONCOT, CANTIDADCOT

            FROM vw_cotizacion_final

            WHERE IDCOTIZACION = ?

            ORDER BY FECHACOT, HORAINICOT

        ");

        $stmt_detalles->bind_param("i", $idcotizacion);

        $stmt_detalles->execute();

        $detalles = $stmt_detalles->get_result();



        while ($detalle = $detalles->fetch_assoc()) {

            $fecha = $detalle['FECHACOT'];

            if (!isset($detalles_por_fecha[$fecha])) {

                $detalles_por_fecha[$fecha] = [];

            }



            // Insumos (consulta optimizada)

            $stmt_insumos = $conexion->prepare("

                SELECT 

                    di.IDDETALLEINSUMO,

                    di.CANTIDAD,

                    di.PRECIO,

                    di.TOTAL,

                    i.ALIASINSUMO,

                    i.DESCRIPCIONINS,

                    ti.NOMBRETIPOINSUMO

                FROM cot_detalle_insumos di

                INNER JOIN cot_insumos i ON i.IDINSUMO = di.IDINSUMO

                INNER JOIN cot_tipo_insumo ti ON ti.IDTIPOINSUMO = i.IDTIPOINSUMO

                WHERE di.IDDETALLECOT = ?

                ORDER BY ti.NOMBRETIPOINSUMO, i.ALIASINSUMO

            ");

            $stmt_insumos->bind_param("i", $detalle['IDDETALLECOT']);

            $stmt_insumos->execute();

            $res_insumos = $stmt_insumos->get_result();
            $insumos = $res_insumos->fetch_all(MYSQLI_ASSOC);
            
            $resultadoBase = $conexion->query("SELECT DATABASE() AS BASE_ACTUAL");
            $filaBase = $resultadoBase->fetch_assoc();
            $baseActual = $filaBase['BASE_ACTUAL'] ?? 'No identificada';
            
            if (
                isset($_GET['debug']) &&
                $_GET['debug'] === '1'
            ) {
                header('Content-Type: application/json; charset=utf-8');
            
                echo json_encode(
                    [
                        'archivo' => basename(__FILE__),
                        'base_datos' => $baseActual,
                        'idcotizacion' => $idcotizacion,
                        'iddetallecot' => $detalle['IDDETALLECOT'],
                        'cantidad_insumos' => count($insumos),
                        'insumos' => $insumos
                    ],
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE
                );
            
                exit;
            }

            // Si la cotización es exenta, ajustar precios
            /*if ($exenta) {
                foreach ($insumos as &$insumo) {
                    $insumo['PRECIO'] = $insumo['PRECIO'] / 1.13;
                    $insumo['TOTAL']  = $insumo['TOTAL'] / 1.13;
                }
                unset($insumo);
            }*/

            if ($exenta) {
                foreach ($insumos as &$insumo) {
                    $cantidad = (float)$insumo['CANTIDAD'];

                    $precioSinIva = round(((float)$insumo['PRECIO']) / 1.13, 2);
                    $totalSinIva  = round($precioSinIva * $cantidad, 2);

                    $insumo['PRECIO'] = $precioSinIva;
                    $insumo['TOTAL']  = $totalSinIva;
                }
                unset($insumo);
            }

            $detalle['insumos'] = $insumos;

            $detalles_por_fecha[$fecha][] = $detalle;

        }

    }

    // Obtener USURECOT desde cot_cotizaciones
    $stmtCot = $conexion->prepare("
        SELECT USURECOT 
        FROM cot_cotizaciones 
        WHERE IDCOTIZACION = ?
        LIMIT 1
    ");
    $stmtCot->bind_param("i", $idcotizacion);
    $stmtCot->execute();
    $resCot = $stmtCot->get_result();
    $cot = $resCot->fetch_assoc();

    $usuarioCot = $cot['USURECOT'] ?? null;

    // Obtener teléfono y extensión del usuario
    $telefonoUsuario = "";
    $extensionUsuario = "";

    if ($usuarioCot) {
        $stmtUsu = $conexion->prepare("
            SELECT NOMBREUSU, TELUSU, EXTENSIONUSU 
            FROM usuarios 
            WHERE USERUSU = ?
            LIMIT 1
        ");
        $stmtUsu->bind_param("s", $usuarioCot);
        $stmtUsu->execute();
        $resUsu = $stmtUsu->get_result();
        $usu = $resUsu->fetch_assoc();

        $nombreUsuario = $usu['NOMBREUSU'] ?? '';
        $telefonoUsuario = $usu['TELUSU'] ?? '';
        $extensionUsuario = $usu['EXTENSIONUSU'] ?? '';
    }

}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cotización - <?= $cotizacion ? htmlspecialchars($cotizacion['CODREFERENCIA']) : '' ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" type="text/css" href="../../public/dist/css/reporte-cotizacion-final.css">

    <style>

        /* ==== ESTILOS GENERALES ==== */

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { font-family: 'Arial', sans-serif; background: #f5f5f5; padding: 20px; font-size: 11pt; line-height: 1.4; }

        .container { max-width: 900px; margin: 0 auto; background: white; box-shadow: 0 0 20px rgba(0,0,0,0.1); }

        .header { background: #B0291C; color: white; padding: 30px; text-align: center; }

        .header h1 { font-size: 24pt; margin-bottom: 10px; font-weight: normal; }

        .header-info { font-size: 9pt; margin-top: 15px; line-height: 1.6; }

        .ref-number { background: white; color: #B0291C; padding: 15px 30px; border-bottom: 3px solid #B0291C; }

        .ref-number strong { font-size: 12pt; }

        .content { padding: 30px; }

        .section { margin-bottom: 25px; }

        .section-title { background: #B0291C; color: white; padding: 8px 15px; font-size: 11pt; font-weight: bold; margin-bottom: 15px; }

        .fecha-header { background: #fce6e4; padding: 12px; font-weight: bold; color: #B0291C; margin-top: 20px; margin-bottom: 10px; border-left: 4px solid #B0291C; }

        .horario-info { background: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 4px; font-size: 10pt; }

        .table-container { margin: 20px 0; overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; font-size: 9pt; }

        table th { background: #B0291C; color: white; padding: 10px 8px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 8pt; }

        table td { padding: 10px 8px; border-bottom: 1px solid #ddd; }

        .total-row { background: #B0291C; color: white; font-size: 11pt; font-weight: bold; }

        .precio-incluye { text-align: right; font-size: 9pt; color: #666; font-style: italic; margin-top: 5px; }

        .notes-section { background: #fff9e6; border: 1px solid #ffd700; padding: 20px; margin-top: 30px; border-radius: 4px; }

        .notes-section h3 { color: #cc8800; margin-bottom: 15px; font-size: 11pt; }

        .notes-section ul { margin-left: 20px; font-size: 9pt; line-height: 1.8; }

        .signature-section { margin-top: 40px; padding: 30px; border-top: 2px solid #ddd; }

        .signature-line { border-top: 2px solid #333; width: 300px; margin: 50px auto 10px; }

        .signature-label { text-align: center; font-size: 9pt; color: #666; }

        .footer { background: #f5f5f5; padding: 20px 30px; text-align: center; border-top: 3px solid #B0291C; font-size: 9pt; }

        @media print { body { background: white; padding: 0; } .container { box-shadow: none; } .section { page-break-inside: avoid; } }

        .header-logo {max-width: 300px;height: auto;margin-bottom: 15px;display: block;margin-left: auto;margin-right: auto;}

        @media screen and (max-width: 768px) {.header-logo {    max-width: 200px;}}

        @media print {.header-logo {    max-width: 250px;}}

        .action-buttons { padding: 20px 30px; background: var(--light-bg); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;}

        .btn-group { display: flex; gap: 10px; flex-wrap: wrap; }
        
        .btn-custom { padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; cursor: pointer; color: white!important; }

        .btn-primary-custom { background: var(--secondary-color); color: white!important; }

        .btn-primary-custom:hover { background: #2980b9; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3); }

        .btn-success-custom { background: #B0291C; color: white!important; }

        .btn-success-custom:hover { background: #881e15ff; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(39, 174, 96, 0.3); color: white!important; }

        .btn-secondary-custom { background: #6c757d; color: white!important; }

        .btn-secondary-custom:hover { background: #5a6268; transform: translateY(-2px); color: white!important; }

    </style>

</head>

<div id="pdfLoader"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.6); z-index:9999; align-items:center; justify-content:center;">
    
    <div style="background:#fff; padding:30px; border-radius:8px; text-align:center; max-width:420px;">
        <h3>Generando PDF</h3>
        <p>Por favor espere mientras se prepara el documento.</p>

        <p>
            <strong>Tiempo estimado:</strong>
            <span id="pdfCounter">30</span> segundos
        </p>

        <p id="pdfExtraMsg" style="font-size:14px; color:#666;">
            No cierre esta ventana.
        </p>

        <button type="button"
                onclick="cerrarEsperaPDF()"
                style="margin-top:15px; padding:8px 16px; background:#ccc; border:none; border-radius:4px; cursor:pointer;">
            Cerrar
        </button>
    </div>
</div>

<script>
let pdfInterval = null;

function mostrarEsperaPDF(tiempo = 30) {
    const modal = document.getElementById('pdfLoader');
    const counter = document.getElementById('pdfCounter');
    const extraMsg = document.getElementById('pdfExtraMsg');

    modal.style.display = 'flex';
    let segundos = tiempo;
    counter.textContent = segundos;
    extraMsg.textContent = 'No cierre esta ventana.';

    if (pdfInterval) clearInterval(pdfInterval);

    pdfInterval = setInterval(() => {
        segundos--;
        counter.textContent = segundos;

        if (segundos <= 0) {
            clearInterval(pdfInterval);
            counter.textContent = 'listo';
            extraMsg.textContent = 'El PDF debería descargarse en breve.';
        }
    }, 1000);
}

function cerrarEsperaPDF() {
    const modal = document.getElementById('pdfLoader');
    modal.style.display = 'none';

    if (pdfInterval) {
        clearInterval(pdfInterval);
        pdfInterval = null;
    }
}
</script>


<body>

<?php if ($cotizacion): ?>

<div class="container">

    

    <!-- Header -->

    <div class="header">

        <img src="../../public/images/logosfepade/Logo_FEPADE_horizontal_ISO_Blanco.png" alt="FEPADE Logo" class="header-logo">

        <div class="header-info">

            Calle El Pedregal y Calle de acceso a Escuela Militar Capitán General Gerardo Barrios,<br>

            Antiguo Cuscatlán, La Libertad, El Salvador, C.A.<br>

            Teléfonos: PBX (503) 2212-1600. Cel. (503) <?php echo $telefonoUsuario; ?><br> Tel. Directo (503) <?php echo $extensionUsuario; ?>

        </div>

    </div>



    <!-- Número de Referencia -->

    <div class="ref-number">

        <strong>Ref. <?= htmlspecialchars($cotizacion['CODREFERENCIA']) ?></strong>

        <span style="float: right;">Antiguo Cuscatlán, <?= formatFechaActualEspanol() ?></span>

    </div>



    <!-- Contenido -->

    <div class="content">

        <!-- Destinatario -->

        <div class="section">

            <p><strong>Señores</strong></p>

            <p><strong><?= htmlspecialchars($cotizacion['EMPRESA']) ?></strong></p>

            <p>Presente. Con atención a: <strong><?= htmlspecialchars($cotizacion['NOMBRECONTACTO']) ?></strong></p>

        </div>



        <!-- Saludo -->

        <div class="section">

            <p>Estimados Señores:</p>

            <p>Atentamente, presentamos para su consideración nuestra oferta de alquiler de sala, para la realización de su evento <strong><?= htmlspecialchars($cotizacion['NOMBRETIPOEVENTO']) ?></strong> <?= !empty($cotizacion['DESCRIPCIONCOT']) ? '(' . htmlspecialchars($cotizacion['DESCRIPCIONCOT']) . ')' : '' ?> <?= obtenerRangoFechas($detalles_por_fecha) ?> con los siguientes horarios según detalle:</p>

        </div>



        <!-- USO DE SALA -->

        <div class="section">

            <div class="section-title">USO DE SALA</div>

            <?php 

            $total_general = 0;

            foreach ($detalles_por_fecha as $fecha => $detalles): 

                $subtotal_fecha = 0;

            ?>

                <div class="fecha-header">

                    <i class="fas fa-calendar"></i> Fecha: <?= formatDate($fecha) ?>

                </div>



                <?php foreach ($detalles as $detalle): ?>

                    <?php if (!empty($detalle['HORAINICOT']) || !empty($detalle['HORAFINCOT'])): ?>

                    <div class="horario-info">

                        <strong>Horario:</strong> <?= formatTime($detalle['HORAINICOT']) ?> a <?= formatTime($detalle['HORAFINCOT']) ?>

                        <?php if (!empty($detalle['DURACIONCOT'])): ?>

                            (<?= htmlspecialchars($detalle['DURACIONCOT']) ?>)

                        <?php endif; ?>

                        <?php if (!empty($detalle['DESCRIPCIONDETALLE'])): ?>

                            - <?= htmlspecialchars($detalle['DESCRIPCIONDETALLE']) ?>

                        <?php endif; ?>

                    </div>

                    <?php endif; ?>



                    <?php if (!empty($detalle['insumos'])): ?>

                    <div class="table-container">

                        <table>

                            <thead>

                                <tr>

                                    <th>DESCRIPCIÓN</th>

                                    <th style="text-align: center; width: 100px;">PRECIO UNITARIO</th>

                                    <th style="text-align: center; width: 100px;">CANTIDAD</th>

                                    <th style="text-align: right; width: 120px;">COSTO TOTAL</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php 

                                $subtotal_detalle = 0;

                                foreach ($detalle['insumos'] as $insumo): 

                                    $subtotal_detalle += $insumo['TOTAL'] ?? 0;

                                ?>

                                <tr>

                                    <td>

                                        <strong><?= htmlspecialchars($insumo['ALIASINSUMO']) ?></strong>

                                        <?php if (!empty($insumo['DESCRIPCIONINS'])): ?>

                                            <br><small style="color: #666;"><?= htmlspecialchars($insumo['DESCRIPCIONINS']) ?></small>

                                        <?php endif; ?>

                                    </td>

                                    <td style="text-align: center;"><?= formatPrice($insumo['PRECIO']) ?></td>

                                    <td style="text-align: center;"><?= htmlspecialchars($insumo['CANTIDAD']) ?></td>

                                    <td style="text-align: right; font-weight: bold;"><?= formatPrice($insumo['TOTAL']) ?></td>

                                </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                    <?php 

                    $subtotal_fecha += $subtotal_detalle;

                    endif; 

                    ?>

                <?php endforeach; ?>



                <?php if (count($detalles_por_fecha) > 1): ?>

                <div style="text-align: right; margin: 15px 0; padding: 10px; background: #f5f5f5; font-weight: bold;">

                    SUBTOTAL <?= formatDate($fecha) ?>: <?= formatPrice($subtotal_fecha) ?>

                </div>

                <?php $total_general += $subtotal_fecha; endif; ?>

            <?php endforeach; ?>



            <!-- Total General -->

            <?php if (count($detalles_por_fecha) == 1) {

                $total_general = calcularTotalGeneral($detalles_por_fecha);

            } ?>

            <div class="table-container">

                <table>

                    <tfoot>

                        <tr class="total-row">

                            <td colspan="3" style="text-align: right; padding: 15px;"><strong>INVERSIÓN TOTAL</strong></td>

                            <td style="text-align: right; padding: 15px; font-size: 13pt;"><strong><?= formatPrice($total_general) ?></strong></td>

                        </tr>

                    </tfoot>

                </table>

            </div>

            <?php if ($exenta): ?>
                <div class="precio-incluye">
                    <strong>Esta cotización es exenta de IVA. Todos los precios mostrados ya no incluyen el 13%.</strong>
                </div>
            <?php else: ?>
                <div class="precio-incluye">PRECIOS INCLUYEN IVA</div>
            <?php endif; ?>

        </div>



        <div id="INFORMACION_USO_AUDITORIO" style="margin-top: 30px;">

            <!-- Nota Inicial -->

            <div style="margin-bottom: 30px;">

                <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                    <li><i><b>Salas Sujetas a Disponibilidad</b></i></li>

                    <li>En nuestros eventos el <b>rubro de Renta de Sala</b> está directamente vinculado al <b>consumo mínimo requerido</b>, el cual se define en función de la sala solicitada, la duración del evento y el número de asistentes.</li>

                </ul>

            </div>

            

            <!-- Incluye -->

            <div class="section">

                <div class="section-title">INCLUYE:</div>

                <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                    <li>Estación de agua y café permanente.</li>

                    <li>Uso de Jardines para Actividades de Grupo.</li>

                    <li>Montaje de Sala.</li>

                    <li>Aire Acondicionado.</li>

                    <li>Podium.</li>

                    <li>1 rotafolio, espacio para proyectar, pizarra, plumones y 1 regleta con extensión para conexiones.</li>

                    <li>Servicio de café, té y agua disponible fuera del salón. En FEPADE estamos comprometidos con el cuidado del medio ambiente, por lo que hemos reducido el uso de productos desechables. Agradecemos de antemano invitar a los asistentes a su evento a traer su propio termo o taza reutilizable.</li>

                    <li><u><b>Internet inalámbrico hasta 15 Mbps de cortesía.</b></u></li>

                    <!--<li>Estacionamiento hasta para 100 vehículos para la seguridad de los participantes.</li>-->

                    <li>Vigilancia las 24 horas del día.</li>

                    <li>Instalaciones e iluminación adecuada en los salones de capacitación (energía eléctrica o luz natural suficiente para poder trabajar sin dificultades visuales).</li>

                    <li>Salones libres de interferencia como ruidos, olores y otros distractores.</li>

                    <li>Servicios sanitarios limpios, suficientes y en buen funcionamiento (baños para personas con capacidades especiales).</li>

                    <li>Planta eléctrica y equipos que garantizan la continuidad de los eventos en casos fortuitos.</li>

                    <li>Servicio de ambulancia con EMI.</li>

                </ul>

            </div>



            <!-- No Incluye -->

            <div class="section">

                <div class="section-title">NO INCLUYE:</div>

                <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                    <li>Personal proporcionando servicios secretariales.</li>

                    <li>Personal para descarga, carga y colocación de equipos y/o materiales.</li>

                    <li>Atención a invitados.</li>

                    <li>Apoyo en la decoración del evento.</li>

                    <li>Equipo Audiovisual (Proyector, Sonido) en caso requerido será con costo adicional.</li>

                </ul>

            </div>



            <!-- Servicios de Internet -->

            <div class="section">

                <div class="section-title">REFERENTE A LOS SERVICIOS DE INTERNET:</div>

                <ol style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                    <li><strong>Uso exclusivo para fines académicos, institucionales o relacionados al evento.</strong><br>

                    La red debe utilizarse únicamente para actividades pertinentes al motivo de su visita.</li>

                    

                    <li><strong>Prohibido el acceso a sitios inapropiados o de contenido ilegal.</strong><br>

                    No está permitido ingresar a páginas con contenido violento, pornográfico, discriminatorio o que infrinja leyes nacionales o internacionales.</li>

                    

                    <li><strong>Evite descargas masivas o transmisión de archivos pesados.</strong><br>

                    Para garantizar un servicio estable a todos los usuarios, se recomienda no realizar descargas de gran tamaño, transmisión en alta definición o actualizaciones automáticas durante el evento, sugerimos realizar descargas de software previo al evento.</li>

                    

                    <li><strong>No se permite el uso de aplicaciones o herramientas que comprometan la seguridad de la red.</strong><br>

                    Está prohibido el uso de software para escaneo de red, VPNs no autorizadas, proxies, y cualquier otro mecanismo que altere el funcionamiento del sistema.</li>

                    

                    <li><strong>FEPADE se reserva el derecho de restringir el acceso.</strong><br>

                    En caso de detectar un uso indebido, FEPADE podrá limitar o suspender el acceso a la red al usuario o dispositivo involucrado.</li>

                    

                    <li><strong>Solicitud previa de servicios adicionales.</strong><br>

                    Si el evento requiere mayor intensidad o capacidad de Internet (por ejemplo, por transmisión en vivo, múltiples conexiones simultáneas, etc.), el cliente deberá informarlo con al menos 8 días hábiles de anticipación y se le presentará al cliente los aranceles correspondientes a la solicitud.</li>

                    

                    <li><strong>Costo adicional por incremento de ancho de banda.</strong><br>

                    La contratación de capacidad adicional conlleva un cargo extra, el cual será cotizado según el requerimiento técnico y tiempo de uso solicitado por el cliente (tiempo mínimo de cotización de ancho de banda 1 día).</li>

                    

                    <li><strong>Sujeto a disponibilidad técnica.</strong><br>

                    La provisión de mayor capacidad estará sujeta a disponibilidad de infraestructura el día del evento. FEPADE confirmará la factibilidad técnica al recibir la solicitud de parte del cliente y trasladará costos en caso de necesitar infraestructura.</li>

                    

                    <li><strong>Responsabilidad del uso.</strong><br>

                    El cliente es responsable de garantizar que el equipo utilizado (computadoras, cámaras, routers externos, etc.) esté en condiciones óptimas y no interfiera con la red institucional.</li>

                </ol>

            </div>



            <!-- Condiciones Especiales - Pagos -->

            <div class="section">

                <div class="section-title">CONDICIONES ESPECIALES - PAGOS:</div>

                <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                    <li><b><i>La Reservación de la sala para su evento se hará efectiva siempre y cuando sea cancelado el 50% del valor ofertado al momento de firmar el Formato de Aceptación del mismo; <u>caso contrario FEPADE procederá a eliminar dicha reserva.</u></b></i></li>

                    <li><b><i>El 50% restante del monto deberá ser cancelado 3 días antes de la realización del evento; <u>caso omiso FEPADE se reserva el derecho a no realizar la actividad.</u></b></i></li>

                </ul>

            </div>



            <!-- Otros -->

            <div class="section">

                <div class="section-title">OTROS:</div>

                <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                    <li>Se requiere la presencia de un Coordinador o Representante de su Empresa para la logística y recepción de participantes y <b><u>solicitar por escrito</u></b> requerimientos eventuales no contemplados en la oferta, así como para el pago de dichas eventualidades.</li>

                </ul>

            </div>



            <!-- Uso del Auditorio -->

            <div class="section">

                <div class="section-title">USO DE SALAS:</div>

                <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                    <li>FEPADE se reserva el derecho de uso de sala ante cualquier temática a desarrollar por parte del contratante.</li>

                    <li>Las especificaciones para los requerimientos de arreglo, equipo, y todas sus necesidades deberán indicarse por <b><u>escrito en el formato de solicitud de requerimientos</b></u> para prepararnos adecuadamente y brindarle un buen servicio.</li>

                    <li><b><u>FEPADE no se responsabiliza</b></u> por objetos robados, pérdidas u extravíos dentro de sus instalaciones, cada participante es responsable del cuido de los mismos.</li>

                    <li><b><u>FEPADE no se responsabiliz</b></u>a por pérdidas y daños ocasionados en su vehículo.</li>

                    <li><b><u>FEPADE se exime de toda responsabilidad</b></u> por los accidentes que pudiesen darse en el desarrollo del evento y sus diversas actividades.</li>

                    <li><b><u>Se prohíbe utilizar clavos u otros materiales que dañen las instalaciones.</b></u></li>

                    <li><b><u>NO se permite portar armas de fuego dentro de las instalaciones de FEPADE, si por cualquier motivo alguna persona porta armas, éstas deben ser reportadas y entregadas al equipo de seguridad de FEPADE.</b></u></li>

                    <li><b><u>Cualquier daño en las instalaciones, mantelería, cristalería, equipo y mobiliario será cargado al evento, equivalente al costo total de reparación o reemplazo.</b></u></li>

                    <li><b><u>En el caso de que se detecte un uso no aprobado del espacio o un cambio sustancial en el propósito del evento sin la debida aprobación, se aplicará una penalidad de US$350.00 por cada día que dicha actividad no autorizada tenga lugar. Esta penalidad se implementa para asegurar que el espacio se utilice de acuerdo con las condiciones y acuerdos previamente establecidos durante la reserva.</b></u></li>

                    <li><b><u>En caso de que se detecte la presencia de sustancias peligrosas, drogas incluyendo armas, durante el evento, se aplicará una penalidad de US$1,000.00 a la empresa contratante y se notificará a las autoridades pertinentes.</b></u></li>

                    <li><b><u>Cualquier daño en las instalaciones, mantelería, cristalería, equipo y mobiliario será cargado al evento, equivalente al costo total de reparación o reemplazo.</b></u></li>

                </ul>

            </div>



            <!-- Horarios -->

            <div class="section">

                <div class="section-title">HORARIOS:</div>

                <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                    <li><b><u>NO</b></u> es permitido el ingreso a las instalaciones en horario previo a lo indicado en la cotización.</li>

                    <li>El cliente está en la obligación de respetar el horario de inicio y de fin de su evento establecido en su cotización, ya que puede que exista un evento programado antes o después del mismo. En el caso que el cliente cambie la hora de salida sin previo aviso a último momento, se establecerá una <u>penalización de US$300.00</u> de cargo adicional en su evento por el inconveniente causado.</li>

                </ul>

            </div>



            <!-- Servicio de Alimentación -->

            <div class="section">

                <div class="section-title">SERVICIO DE ALIMENTACIÓN:</div>

                <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                    <li>El requerimiento del servicio de alimentación debe ser solicitado con 72 horas de anticipación a la fecha de su evento, caso contrario el menú ofrecido será el menú del día.</li>

                    <li>Cualquier cambio de menú requerido está sujeto a disponibilidad de cocina y recargo adicional de US$2.00 por plato.</li>

                    <li><b>Se cobrará un recargo</b> de US$3.00 por plato adicional. No se hará ningún cobro si éste incremento es notificado por escrito a FEPADE 72 horas antes del evento.</li>

                    <li><b><u>NO</b></u> es permitido el ingreso de alimentos, ni proveedores de alimentación.</li>

                    <li><b><u>NO</b></u> es permitido el ingreso de hieleras, bebidas ni alimentos a la Fundación.</li>

                    <li><b><u>NO</b></u> es permitido el ingreso de ningún tipo de bebida alcohólica a la Fundación.</li>

                    <li><b><u>Deberá cumplirse con puntualidad el horario pactado para las salidas a recesos</b></u> de forma que no interfiera con el desarrollo de otros eventos en la Fundación.</li>

                    <li><b><u>Los alimentos que no se consuman en el día del evento y sean parte de la cantidad contratada se colocarán en empaque individual hasta un máximo de 5 platos como cortesía, si la cantidad es mayor, se hará un recargo de $0.25 por cada térmico utilizado.</b></u></li>

                    <li>Deberá cumplirse con puntualidad el horario pactado para las salidas a recesos de forma que no interfiera con el desarrollo de otros eventos en la Fundación.</li>

                    <li>Se aplicará una penalidad de US$300.00 por ingreso no autorizado de alimentos externos.</li>

                    <li>Si el cliente requiere que los alimentos sean servidos, se aplicará un recargo del 10% sobre el precio unitario cotizado.</li>

                    <li>Si la asistencia a la hora del evento supera el número contratado, queda sujeto a disponibilidad de menú y capacidad del salón. FEPADE se reserva el derecho de variar el menú, tiempo de servicio y el precio previamente contratado aplicando un recargo del 15% adicional al extra requerido más $25.00 en concepto de transporte.</li>

                    <li>Los alimentos y bebidas no consumidos se entregarán al final del evento, FEPADE no se hace responsable si estos no son retirados por los organizadores del evento.</li>

                </ul>

            </div>



            <!-- Penalidades por Cambios y Cancelación -->

            <div class="section">

                <div class="section-title">PENALIDADES POR CAMBIOS Y SUSPENCIÓN DE EVENTO:</div>

                <p style="font-size: 9pt; margin-bottom: 10px;">Toda cancelación deberá ser notificada por escrito y tendrá como penalidad el cobro de un porcentaje según el siguiente detalle; esto dado a que se ha mantenido el espacio reservado y se ha negado el mismo a otros clientes:</p>

                <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                    <li>31 - 22 días antes del evento: Se cobrará el 30% del valor total contratado</li>

                    <li>21 - 8 días antes del evento: Se cobrará el 40% del valor total contratado</li>

                    <li>7 - 0 días antes del evento: Se cobrará el 50% del valor total contratado, en el caso que ya se haya solicitado alimentación, esta se entregará en empaques desechables, los cuales tendrán que ser retiradas en las instalaciones de FEPADE.</li>

                </ul>

                

                <p style="font-size: 9pt; margin-top: 15px; margin-bottom: 10px;"><strong>Cliente con crédito establecido:</strong></p>

                <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8; font-weight: 600;">

                    <li>31 - 22 días antes del evento: Se cobrará el 30% del valor total contratado</li>

                    <li>21 - 8 días antes del evento: Se cobrará el 40% del valor total contratado</li>

                    <li>7 - 0 días antes del evento: Se cobrará el 50% del valor total contratado, en el caso que ya se haya solicitado alimentación, esta se entregará en empaques desechables, los cuales tendrán que ser retiradas en las instalaciones de FEPADE.</li>

                </ul>

                

                <p style="font-size: 9pt; margin-top: 15px; font-weight: 600;">Todo cambio de fecha de un evento ya contratado será evaluado, debido a que hemos mantenido el espacio reservado y se ha negado el mismo a otras solicitudes. Queda sujeto a disponibilidad.</p>

            </div>



            <!-- Medidas de Bioseguridad -->

            <div class="section">

                <div class="section-title">MEDIDAS DE BIOSEGURIDAD</div>

                <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                    <li>Queda a discreción de los asistentes, el uso de mascarilla dentro de las instalaciones.</li>

                </ul>

            </div>



            <!-- Medidas de Seguridad Ocupacional -->

            <div class="section">

                <div class="section-title">MEDIDAS DE SEGURIDAD OCUPACIONAL</div>

                <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                    <li>Usar arnés y cuerda de vida al realizar trabajos en alturas mayores a 2.00 metros.</li>

                    <li>Usar casco de protección si existe riesgo de caída de objetos pesados sobre la cabeza.</li>

                    <li>Usar guantes de protección en la realización de trabajos eléctricos, mecánicos, de carga pesada y otros.</li>

                    <li>Notificar con anticipación a FEPADE la demanda de carga eléctrica de los equipos/luces que instalará.</li>

                    <li>En el proceso de subir elementos pesados a la tramoya, estos deben ser sujetados adecuadamente con lazos, cadenas, etc.</li>

                    <li>Sujetar adecuadamente con seguridad los elementos subidos cuando ya estén en la tramoya.</li>

                    <li>Colocar señalización temporal adecuada, con conos u otros elementos, en las áreas de trabajo.</li>

                </ul>

            </div>



            <!-- Nota de Vigencia -->

            <div class="section">

                <p style="font-size: 9pt; line-height: 1.8; background: #fff9e6; padding: 15px; border-left: 4px solid #ffd700;">

                    <strong>Esta cotización <u>tiene una vigencia de 5 días</u>, FEPADE</strong> procederá a cancelarla si no se recibe la confirmación por escrito del cliente en el período indicado.

                </p>

                <p style="font-size: 9pt; line-height: 1.8; margin-top: 10px;">

                    Si la cotización es aceptada, favor completar la sección <b><u>Aceptación de Oferta</b></u> anexa a esta cotización.

                </p>

            </div>



            <!-- Contáctos -->

            <div class="section">

                <div class="section-title">

                    Para facilitar la coordinación de su evento, puede contactarnos:

                </div>

                <table style="width: 100%; border-collapse: collapse; font-size: 9pt; line-height: 1.8;">

                    <tr>

                        <td style="border: none; padding: 2px 10px;">Mercadeo</td>

                        <td style="border: none; padding: 2px 10px;"><?php echo $nombreUsuario; ?></td>

                        <td style="border: none; padding: 2px 10px;"><?php echo $telefonoUsuario; ?></td>

                    </tr>

                    <tr>

                        <td style="border: none; padding: 2px 10px;">Logística</td>

                        <td style="border: none; padding: 2px 10px;">Lic. Fernando Valdivieso</td>

                        <td style="border: none; padding: 2px 10px;">2212-1678</td>

                    </tr>

                    <tr>

                        <td style="border: none; padding: 2px 10px;">Facturación y Cobro</td>

                        <td style="border: none; padding: 2px 10px;">Lic. Juan Francisco Menjivar</td>

                        <td style="border: none; padding: 2px 10px;">2212-1675</td>

                    </tr>

                </table>

            </div>

        </div>



        <!-- Firma -->

        <div class="signature-section">

            <p>Cordialmente,</p>

            <div class="signature-line"></div>

            <div class="signature-label">

                <strong>Ejecutivo de Mercadeo y Ventas</strong><br>

                FEPADE

            </div>

        </div>

    </div>



    <!-- Footer -->

    <div class="footer">

        Si la cotización es aceptada, favor confirmar por escrito para coordinar detalles.

    </div>



    <!-- Botones de acción -->

    <div class="action-buttons">

        <div class="btn-group">

            <?php if ($estadocot >= 3): ?> 

            <a href="cotizacion_sala_final_pdf.php?idcotizacion=<?= $idcotizacion ?>" 
                class="btn-custom btn-success-custom"
                onclick="mostrarEsperaPDF(30);"
                target="_blank" rel="noopener">
                <i class="fas fa-file-pdf"></i> Descargar PDF
            </a>

            <?php endif; ?>

        </div>

        <div class="btn-group">

            <a href="javascript:window.close()" class="btn-custom btn-secondary-custom">

                <i class="fas fa-times"></i> Cerrar

            </a>

        </div>

    </div>



    

</div>

<?php else: ?>

<div class="container">

    <div class="error-container">

        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>

        <h2 class="error-title">Cotización Incompleta</h2>

        <p class="error-message">
            Esta cotización no tiene fechas ni insumos.<br>
            ¿Desea agregarlos ahora?
        </p>

        <div class="error-actions">

            <button class="btn-primary" 
                    onclick="window.location.href='../cotizacion_detalle.php?idcotizacionnew=<?= $idcotizacion ?>'">
                <i class="fa fa-plus"></i> Agregar
            </button>

            <button class="btn-secondary" onclick="window.close()">
                <i class="fas fa-times"></i> Cerrar
            </button>

        </div>

    </div>

</div>



<style>

    .error-container {

        text-align: center;

        padding: 60px 30px;

        min-height: 400px;

        display: flex;

        flex-direction: column;

        justify-content: center;

        align-items: center;

    }

    

    .error-icon {

        font-size: 72px;

        color: #ff6b6b;

        margin-bottom: 20px;

        animation: pulse 2s ease-in-out infinite;

    }

    

    @keyframes pulse {

        0%, 100% { transform: scale(1); }

        50% { transform: scale(1.1); }

    }

    

    .error-title {

        color: #333;

        font-size: 28px;

        margin-bottom: 15px;

        font-weight: 600;

    }

    

    .error-message {

        color: #666;

        font-size: 14px;

        line-height: 1.6;

        margin-bottom: 30px;

        max-width: 500px;

    }

    

    .error-actions {

        display: flex;

        gap: 15px;

        flex-wrap: wrap;

        justify-content: center;

    }

    

    .btn-primary, .btn-secondary {

        padding: 12px 30px;

        font-size: 14px;

        border: none;

        border-radius: 5px;

        cursor: pointer;

        transition: all 0.3s ease;

        font-weight: 600;

        display: inline-flex;

        align-items: center;

        gap: 8px;

    }

    

    .btn-primary {

        background: #003366;

        color: white;

    }

    

    .btn-primary:hover {

        background: #004080;

        transform: translateY(-2px);

        box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);

    }

    

    .btn-secondary {

        background: #f5f5f5;

        color: #333;

        border: 2px solid #ddd;

    }

    

    .btn-secondary:hover {

        background: #e8e8e8;

        border-color: #ccc;

        transform: translateY(-2px);

    }

    

    .btn-primary:active, .btn-secondary:active {

        transform: translateY(0);

    }

</style>

<?php endif; ?>

</body>

</html>