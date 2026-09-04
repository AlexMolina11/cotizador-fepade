<?php

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../config/Conexion.php';
require '../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$idcotizacion = $_GET['idcotizacion'] ?? '';

/* ===============================
   CONSULTAS
   =============================== */

if ($idcotizacion != '') {

    // Datos principales

    $stmt = $conexion->prepare("SELECT * FROM vw_cotizacion_final WHERE IDCOTIZACION = ? LIMIT 1");

    $stmt->bind_param("i", $idcotizacion);

    $stmt->execute();

    $resultado = $stmt->get_result();

    $cotizacion = $resultado->fetch_assoc();

    $exenta = $cotizacion['EXENTACOT'] ?? 0;



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
    $emailUsuario = "";

    if ($usuarioCot) {
        $stmtUsu = $conexion->prepare("
            SELECT NOMBREUSU, EMAIL, TELUSU, EXTENSIONUSU 
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
        $emailUsuario = $usu['EMAIL'] ?? '';
    }

} else {
    $cotizacion = null;
}

/* ===============================
   SI NO HAY DATOS, NO HAY PDF
   =============================== */
if (!$cotizacion) {
    die('Cotización no encontrada');
}

/* ===============================
   FUNCIONES (las mismas del preview)
   =============================== */
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

/* ===============================
   HTML → BUFFER
   =============================== */
ob_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <style>
        @page { margin: 15mm; }

        /* ==== ESTILOS GENERALES ==== */

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { background: white; font-size: 11pt; line-height: 1.4; }

        .container { max-width: 900px; margin: 0 auto; background: white; }

        .header { background: #B0291C; color: white; padding: 30px; text-align: center; }

        .header h1 { font-size: 24pt; margin-bottom: 10px; font-weight: normal; }

        .header-info { font-size: 9pt; margin-top: 15px; line-height: 1.6; }

        .ref-number { background: white; color: #B0291C; padding: 15px 30px; border-bottom: 3px solid #B0291C; }

        .ref-number strong { font-size: 12pt; }

        .content { padding: 30px; }

        .section { margin-bottom: 25px; padding-top: 25px; }

        .section-title { background: #B0291C; color: white; padding: 8px 15px; font-size: 11pt; font-weight: bold; margin-bottom: 15px; }

        .fecha-header { background: #fce6e4; padding: 12px; font-weight: bold; color: #B0291C; margin-top: 20px; margin-bottom: 10px; border-left: 4px solid #B0291C; }

        .horario-info { background: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 4px; font-size: 10pt; }

        .table-container { margin: 20px 0; overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; font-size: 9pt; padding-top: 10px;}

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

        .header-logo {max-width: 300px;height: auto;margin-bottom: 15px;display: block;margin-left: auto;margin-right: auto;}

        .aceptacion-oferta { font-size: 10.5px; line-height: 1.35; padding:20px;}

        .aceptacion-oferta p { margin-bottom: 5px;}

        .aceptacion-oferta h2 { text-align: center; font-size: 14px; margin-bottom: 10px; }

        .aceptacion-oferta .ref { font-size: 10px; }

        .aceptacion-oferta h3 { font-size: 11px; margin-top: 10px; margin-bottom: 5px; color: #006d3e; }

        .aceptacion-oferta span { float: right;}

        .aceptacion-oferta table td { border-bottom: none !important; padding: 0; }

        .cuadro-oferta { border: solid 1px black; padding: 10px;}

        .subtitulo-oferta { background: #B0291C; color: white; padding: 10px; font-weight: bold; margin-bottom: 10px; margin-top: 10px; }

        .texto-legal { text-align: justify; font-size: 10px; }

        .firmas { width: 100%; margin-top: 20px; }

        .firmas td { width: 50%; padding-top: 20px; vertical-align: top; }

        .nota { font-size: 9.5px; margin-top: 10px; }

        .checkbox { display: inline-block; width: 11px; height: 11px; border: 1px solid #000; margin-right: 13px; margin-left: 2px; margin-top: 1px; vertical-align: middle; }

        .form-table { width: 100%; border-collapse: collapse; font-size: 10.5px; border: 1px solid #000; }

        .form-table td { padding: 5px 4px; vertical-align: bottom; border: 1px solid #000; }

        .form-label { width: 22%; font-weight: bold; white-space: nowrap; padding-left: 5px!important;}

        .form-table tr { height: 22px; }

        .form-field { height: 16px; }

        .form-field tr { text-align: center; }

        .form-checkbox { white-space: nowrap;  vertical-align: middle; height: 16px; line-height: 16px;}

        .section,
        .cuadro-oferta,
        .signature-section {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- Header -->

        <div class="header">

            <img src="/public/images/logosfepade/Logo_FEPADE_horizontal_ISO_Blanco.png" class="header-logo">

            <div class="header-info">

                Calle El Pedregal y Calle de acceso a Escuela Militar Capitán General Gerardo Barrios,<br>

                Antiguo Cuscatlán, La Libertad, El Salvador, C.A.<br>

                Teléfonos: PBX (503) 2212-1600. Cel. (503) <?php echo $telefonoUsuario; ?> Tel. Directo (503) <?php echo $extensionUsuario; ?>

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

                <p>Estimados Señores:</p>

                <p>Atentamente, presentamos para su consideración nuestra oferta de alquiler de auditorio, para la realización de su evento <strong><?= htmlspecialchars($cotizacion['NOMBRETIPOEVENTO']) ?></strong> <?= !empty($cotizacion['DESCRIPCIONCOT']) ? '(' . htmlspecialchars($cotizacion['DESCRIPCIONCOT']) . ')' : '' ?> <?= obtenerRangoFechas($detalles_por_fecha) ?> con los siguientes horarios según detalle:</p>

            </div>

            <!-- USO DE AUDITORIO -->

            <div class="section-cotizacion">

                <div class="section-title">USO DE AUDITORIO</div>

                <?php 

                $total_general = 0;

                foreach ($detalles_por_fecha as $fecha => $detalles): 

                    $subtotal_fecha = 0;

                ?>

                    <div class="fecha-header">

                        Fecha: <?= formatDate($fecha) ?>

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

                <!-- Nota Inicial -->

                <p style="font-weight: 800; margin-bottom: 20px;">

                    <i>NOTA: El período mínimo para alquiler del auditorio es de 3 horas distribuidas de la siguiente manera: 1 hora montaje/preparativos + 2 horas desarrollo del evento.</i>

                </p>

            </div>



            <div id="INFORMACION_USO_AUDITORIO" style="margin-top: 30px;">

                <!-- Incluye -->

                <div class="section">

                    <div class="section-title">INCLUYE</div>

                    <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                        <li>Auditorio con capacidad hasta para 300 personas (butacas), pantalla electrónica, sonido, un micrófono, luces blancas (fluorescentes en escenario y en sala).</li>

                        <!--<li>Amplio parqueo dentro de las instalaciones con seguridad.</li>-->

                        <li>Internet inalámbrico.</li>

                        <!--<li>Espacio de parqueo hasta 150 vehículos.</li>-->

                        <li>Acceso para personas con capacidades especiales.</li>

                        <li>Servicio de ambulancia por EMI en casos de emergencia.</li>

                        <li>Incluye 3 técnicos para apoyo al evento.</li>

                        <li>Estación de agua en el lobby del Auditorio.</li>

                        <li>Planta eléctrica y equipos que garantizan la continuidad de los eventos en casos fortuitos.</li>

                        <li>Servicios sanitarios para visitantes (1 baño para personas con capacidades especiales).</li>

                        <li>Uso de espacio para taquilla.</li>

                        <li>Incluye 2 camerinos con baño y ducha.</li>

                    </ul>

                </div>



                <!-- No Incluye -->

                <div class="section">

                    <div class="section-title">NO INCLUYE</div>

                    <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                        <li>Personal proporcionando servicios secretariales.</li>

                        <li>Atención a invitados.</li>

                        <li>Apoyo en la decoración del evento.</li>

                        <li>Personal para descarga, carga y colocación de equipos y/o materiales.</li>

                        <li>Uso del Aire acondicionado para la(s) hora(s) de ensayo. Si lo requieren favor indicarlo dado que tiene un costo adicional.</li>

                    </ul>

                </div>



                <!-- Servicios de Internet -->

                <div class="section">

                    <div class="section-title">REFERENTE A LOS SERVICIOS DE INTERNET</div>

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

                    <div class="section-title">CONDICIONES ESPECIALES - PAGOS</div>

                    <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                        <li>La Reservación del auditorio para su evento se hará efectiva siempre y cuando sea cancelado el 50% del valor ofertado al momento de firmar el Formato de Aceptación del mismo; caso contrario FEPADE procederá a eliminar dicha reserva.</li>

                        <li>El 50% restante del monto deberá ser cancelado 3 días antes de la realización del evento; caso omiso FEPADE se reserva el derecho a no realizar la actividad.</li>

                        <li>Si el evento será desarrollado en fin de semana, éste deberá cancelarse el día viernes previo a dicho evento, a las 10:00 a.m. en el departamento de Contabilidad con el Lic. Juan Menjivar.</li>

                    </ul>

                </div>



                <!-- Otros -->

                <div class="section">

                    <div class="section-title">OTROS</div>

                    <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                        <li>Se requiere la presencia de un Coordinador o Representante de su Empresa para la logística y recepción de participantes y solicitar por escrito requerimientos eventuales no contemplados en la oferta, así como para el pago de dichas eventualidades.</li>

                        <li>Si durante el desarrollo del evento, el cliente requiere de horas adicionales a las acordadas, quedará sujeto a la disponibilidad y al cargo correspondiente por alquiler del Auditorio según tarifa.</li>

                    </ul>

                </div>



                <!-- Uso del Auditorio -->

                <div class="section">

                    <div class="section-title">USO DEL AUDITORIO</div>

                    <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                        <li>FEPADE se reserva el derecho de uso del Auditorio ante cualquier temática a desarrollar por parte del contratante.</li>

                        <li>Prohibido el ingreso de bebidas alcohólicas a la Fundación.</li>

                        <li>No se permite la venta de ningún tipo de servicio o producto dentro de las instalaciones de FEPADE.</li>

                        <li>Terminantemente prohibido el ingreso de hieleras, bebidas y/o comida al Auditorio.</li>

                        <li>Las especificaciones para los requerimientos de arreglo, equipo, y todas sus necesidades deberán indicarse por escrito en este formato para poder prepararnos adecuadamente y brindarle un buen servicio.</li>

                        <li>FEPADE no se responsabiliza por objetos robados, pérdidas u extravíos dentro de sus instalaciones, cada participante es responsable del cuido de los mismos.</li>

                        <li>FEPADE no se responsabiliza por pérdidas y daños ocasionados en su vehículo.</li>

                        <li>FEPADE se exime de toda responsabilidad por los accidentes que pudiesen darse en el desarrollo del evento y sus diversas actividades.</li>

                        <li>Se prohíbe utilizar clavos u otros materiales que dañen las instalaciones.</li>

                        <li>Materiales, mobiliario y/o equipos propiedad del cliente utilizados en el evento, deberán ser retirados por la empresa contratante al finalizar dicho evento.</li>

                        <li>NO se permite portar armas de fuego dentro de las instalaciones de FEPADE, si por cualquier motivo alguna persona porta armas, éstas deben ser reportadas y entregadas al equipo de seguridad en la recepción de FEPADE.</li>

                        <li>El Auditorio tiene una capacidad para 300 personas, por lo que FEPADE cerrará el acceso a las instalaciones al completarse la ocupación de las 300 butacas disponibles.</li>

                        <li>Cualquier daño en las instalaciones, mantelería, cristalería, equipo y mobiliario será cargado al evento, equivalente al costo total de reparación o reemplazo.</li>

                        <li>En el caso de que se detecte un uso no aprobado del espacio o un cambio sustancial en el propósito del evento sin la debida aprobación, se aplicará una penalidad de US$500.00 y se cancelarán las fechas posteriores en caso las hubiere. Esta penalidad se implementa para asegurar que el espacio se utilice de acuerdo con las condiciones y acuerdos previamente establecidos durante la reserva.</li>

                        <li>En caso de que se detecte la presencia de sustancias peligrosas, drogas incluyendo armas, durante el evento, se aplicará una penalidad de US$1,000.00 a la empresa contratante y se notificará a las autoridades pertinentes.</li>

                    </ul>

                </div>



                <!-- Horarios -->

                <div class="section">

                    <div class="section-title">HORARIOS</div>

                    <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                        <li>NO es permitido el ingreso a las instalaciones en horario previo a lo indicado en la cotización.</li>

                        <li>El cliente está en la obligación de respetar el horario de inicio y de fin de su evento establecido en su cotización, ya que puede que exista un evento programado antes o después del mismo. En el caso que el cliente cambie la hora de salida sin previo aviso a último momento, se establecerá una penalización de US$300.00 de cargo adicional en su evento por el inconveniente causado.</li>

                    </ul>

                </div>



                <!-- Servicio de Alimentación -->

                <div class="section">

                    <div class="section-title">SERVICIO DE ALIMENTACIÓN</div>

                    <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                        <li>El requerimiento del servicio de alimentación debe ser solicitado con 5 días de anticipación y cambios en el menú serán aceptados hasta 72 horas antes del evento, caso contrario el menú servido será el "menú del día".</li>

                        <li>Se cobrará un recargo de US$3.00 por plato adicional. No se hará ningún cobro si éste incremento es notificado por escrito a FEPADE 72 horas antes del evento.</li>

                        <li>NO es permitido el ingreso de alimentos, bebidas ni proveedores de alimentación.</li>

                        <li>NO es permitido el ingreso de ningún tipo de bebida alcohólica a la fundación.</li>

                        <li>Los alimentos que no se consuman en el día del evento y sean parte de la cantidad contratada se colocarán en empaque individual hasta un máximo de 5 platos, si la cantidad es mayor se pondrá un solo empaque por plato fuerte, guarnición y ensaladas, si el cliente los desea individual se hará un recargo de US$1.00 por cada térmico utilizado.</li>

                        <li>Deberá cumplirse con puntualidad el horario pactado para las salidas a recesos de forma que no interfiera con el desarrollo de otros eventos en la Fundación.</li>

                        <li>Se aplicará una penalidad de US$300.00 por ingreso no autorizado de alimentos externos.</li>

                    </ul>

                </div>



                <!-- Medidas de Bioseguridad -->

                <div class="section">

                    <div class="section-title">MEDIDAS DE BIOSEGURIDAD</div>

                    <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                        <li>Queda a discreción de los asistentes, el uso de mascarilla dentro de las instalaciones.</li>

                    </ul>

                </div>



                <!-- Penalidades por Cambios y Cancelación -->

                <div class="section">

                    <div class="section-title">PENALIDADES POR CAMBIOS Y CANCELACIÓN DE EVENTO</div>

                    <p style="font-size: 9pt; margin-bottom: 10px;">Toda cancelación deberá ser notificada por escrito y tendrá como penalidad el cobro de un porcentaje según el siguiente detalle; esto dado a que se ha mantenido el espacio reservado y se ha negado el mismo a otros clientes:</p>

                    <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                        <li>31 - 22 días antes del evento: Se cobrará el 30% del valor total contratado</li>

                        <li>21 - 8 días antes del evento: Se cobrará el 40% del valor total contratado</li>

                        <li>7 - 3 días antes del evento: Se cobrará el 50% del valor total contratado</li>

                        <li>2 - 1 días antes del evento: Se cobrará el 60% del valor total contratado y se entregará la alimentación en empaques desechables, los cuales tendrán que ser retiradas en las instalaciones de FEPADE.</li>

                    </ul>

                    

                    <p style="font-size: 9pt; margin-top: 15px; margin-bottom: 10px;"><strong>Cliente con crédito establecido:</strong></p>

                    <ul style="margin-left: 20px; font-size: 9pt; line-height: 1.8;">

                        <li>31 - 22 días antes del evento: Se cobrará el 30% del valor total contratado</li>

                        <li>21 - 8 días antes del evento: Se cobrará el 40% del valor total contratado</li>

                        <li>7 - 3 días antes del evento: Se cobrará el 50% del valor total contratado</li>

                        <li>2 - 1 días antes del evento: Se cobrará el 60% del valor total contratado y se entregará en empaques desechables, los cuales tendrán que ser retiradas en las instalaciones de FEPADE.</li>

                    </ul>

                    

                    <p style="font-size: 9pt; margin-top: 15px;">Todo cambio de fecha de un evento ya contratado será evaluado, debido a que hemos mantenido el espacio reservado y se ha negado el mismo a otras solicitudes. Queda sujeto a disponibilidad.</p>

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

                        <strong>Esta cotización tiene una vigencia de 5 días</strong>, FEPADE procederá a cancelarla si no se recibe la confirmación por escrito del cliente en el período indicado.

                    </p>

                    <p style="font-size: 9pt; line-height: 1.8; margin-top: 10px;">

                        Si la cotización es aceptada, favor completar la sección Aceptación de Oferta anexa a esta cotización.

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

        <!-- Formulario de aceptación de oferta-->
        <div style="page-break-before: always;"></div>

        <div class="aceptacion-oferta">

            <div class="subtitulo-oferta">
                ACEPTACIÓN DE OFERTA <span class="ref">Ref. <?= htmlspecialchars($cotizacion['CODREFERENCIA']) ?></span>
            </div>

            <div class="cuadro-oferta">
                <p><strong>Responsable de la empresa que estará presente en FEPADE el día del evento:</strong></p>

                <p>Nombre: ________________________________________________________________________________________</p>

                <p>
                    Teléfono fijo: ____________________ 
                    &nbsp;&nbsp; Móvil: ____________________ 
                    &nbsp;&nbsp; Correo electrónico: _________________________________
                </p>
            </div>

            <div class="subtitulo-oferta">
                MONTO ACEPTADO
            </div>

            <div class="cuadro-oferta">
                <p>
                    <strong>Monto Total Aceptado: US$ ____________________</strong>
                </p>

                <table style="width:100%; font-size:10.5px; border-collapse:collapse; margin-bottom: 5px;">
                    <tr>
                        <td style="width:50%; padding:0; vertical-align:middle;">
                            <span class="checkbox"></span>
                            <strong>50% anticipo para reservar: US$ ____________________</strong>
                        </td>
                        <td style="width:50%; padding:0; vertical-align:middle;">
                            <span class="checkbox"></span>
                            <strong>50% tres días antes del evento: US$ ____________________</strong>
                        </td>
                    </tr>
                </table>

                <p>
                    Persona encargada de pago: _______________________________________ 
                    &nbsp;&nbsp; N.° DUI: ____________________
                </p>
            </div>

            <div class="subtitulo-oferta">
                DATOS DE FACTURACIÓN
            </div>

            <div class="cuadro-oferta">
                <p><strong>a) PERSONA NATURAL</strong></p>

                <table class="form-table">
                    <tr>
                        <td class="form-label">Nombre:</td>
                        <td colspan="4" class="form-field"></td>
                    </tr>

                    <tr>
                        <td class="form-label">Teléfono fijo y celular:</td>
                         <td colspan="4" class="form-field"></td>
                    </tr>

                    <tr>
                        <td class="form-label">Actividad económica o giro:</td>
                        <td colspan="4" class="form-field"></td>
                    </tr>

                    <tr>
                        <td class="form-label">Tipo contribuyente:</td>
                        <td class="check-cell">
                            <span class="checkbox"></span> Otros Cont.
                        </td>

                        <td class="check-cell">
                            <span class="checkbox"></span> Mediano
                        </td>

                        <td class="check-cell">
                            <span class="checkbox"></span> Grande
                        </td>
                        <td class="check-cell">
                            
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label">n° DUI:</td>
                        <td colspan="1" class="form-field"></td>
                        <td class="form-label">NIT:</td>
                        <td colspan="2" class="form-field"></td>
                    </tr>

                    <tr>
                        <td class="form-label">NRC:</td>
                        <td class="form-field"></td>

                        <td class="form-label">Comprobante requerido:</td>

                        <td class="check-cell">
                            <span class="checkbox"></span> Crédito Fiscal
                        </td>

                        <td class="check-cell">
                            <span class="checkbox"></span> Consumidor Final
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label">Dirección:</td>
                        <td colspan="4" class="form-field"></td>
                    </tr>
                </table>


                <p style="margin-top:10px;"><strong>b) PERSONA JURÍDICA</strong></p>

                <table class="form-table">
                    <tr>
                        <td class="form-label">Razón social:</td>
                        <td colspan="3" class="form-field"></td>
                    </tr>

                    <tr>
                        <td class="form-label">Teléfono fijo y celular:</td>
                        <td colspan="3" class="form-field"></td>
                    </tr>

                    <tr>
                        <td class="form-label">Actividad económico o giro:</td>
                        <td colspan="3" class="form-field"></td>
                    </tr>

                    <tr>
                        <td class="form-label">Tipo contribuyente:</td>
                        <td class="check-cell text-align:center;">
                            <span class="checkbox"></span> Otros Cont.
                        </td>

                        <td class="check-cell">
                            <span class="checkbox"></span> Mediano
                        </td>

                        <td class="check-cell">
                            <span class="checkbox"></span> Grande
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label">NIT:</td>
                        <td class="form-field"></td>
                        <td class="form-label">NRC:</td>
                        <td class="form-field"></td>
                    </tr>

                    <tr>
                        <td class="form-label">Comprobante requerido:</td>

                        <td class="check-cell">
                            <span class="checkbox"></span> Crédito Fiscal
                        </td>

                        <td class="check-cell">
                            <span class="checkbox"></span> Consumidor Final
                        </td>

                        <td class="check-cell">
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label">Dirección:</td>
                        <td colspan="3" class="form-field"></td>
                    </tr>
                </table>

            </div>

            <div class="subtitulo-oferta">
                DECLARACIÓN JURADA
            </div>

            <div class="cuadro-oferta">

                <p class="texto-legal">
                    Declaro que los fondos con que se cancelará a FEPADE por los servicios proporcionados, provienen de actividades lícitas 
                    y no estarán de ninguna manera relacionados directa o indirectamente con actividades relacionadas al Lavado de Dinero y de Activos, y de Financiamiento al Terrorismo, u otras de carácter ilícito.
                    En caso de ser requerido por FEPADE me comprometo a proporcionar la documentación que compruebe el origen lícito de los fondos y demás 
                    que sea requerida para cumplir con las medidas de debida diligencia en función del adecuado conocimiento del cliente.
                </p>

                <p class="texto-legal">
                    Declaro de manera consciente y voluntaria que he tenido la oportunidad de leer y comprender en su totalidad 
                    la Política de protección a la niñez y adolescencia de FEPADE, y estoy dispuesto a cooperar en cualquier investigación cuando lo requiera.
                    Acepto plenamente cumplir con las disposiciones establecidas en dicha política interna la cual, se encuentra en la página web de FEPADE.
                </p>

            </div>

            <div class="cuadro-oferta">

                <table class="firmas" style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="width:70%; vertical-align:top;">
                            <strong>NOMBRE, CARGO Y FIRMA DE ACEPTACIÓN DE OFERTA:</strong><br><br>
                            ________________________________________________
                        </td>

                        <td style="width:30%; vertical-align:top;">
                            <strong>N.° DUI:</strong><br><br>
                            ________________________________________________
                        </td>
                    </tr>
                </table>

            </div>

            <p class="nota">
                <strong>Favor imprimir está página y enviarla al correo electrónico (<?php echo $emailUsuario; ?>)  
                firmada y sellada por la empresa como compromiso de aceptación de la cotización adjunta, que incluye condiciones especiales para la respectiva reserva y uso de auditorio.</strong>
            </p>

            <p class="nota">
                <strong>FEPADE se encuentra clasificada dentro de la categoría MEDIANO CONTRIBUYENTE, por lo que solicitamos adjuntarnos fotocopia de su DUI (PERSONA NATURAL), 
                tarjeta de contribuyente y NIT (PERSONA JURÍDICA), para realizar el proceso correspondiente.</strong>.
            </p>

        </div>

    </div>

</body>
</html>

<?php
$html = ob_get_clean();

/* ===============================
   DOMPDF
   =============================== */
   
ini_set('memory_limit', '512M');
set_time_limit(300);
$options = new Options();
$options->set('isRemoteEnabled', false);
$options->set('isHtml5ParserEnabled', false);
$options->set('defaultFont', 'Helvetica');
$options->setChroot(realpath(__DIR__ . '/../../'));

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

/* ===============================
   DESCARGA
   =============================== */
$dompdf->stream(
    "Cotizacion_{$cotizacion['CODREFERENCIA']}.pdf",
    ["Attachment" => true]
);

exit;
