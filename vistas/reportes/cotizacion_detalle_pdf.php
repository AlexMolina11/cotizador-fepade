<?php

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../config/Conexion.php';
require '../../vendor/autoload.php'; // dompdf autoload

use Dompdf\Dompdf;
use Dompdf\Options;

$iddetallecot = $_GET['iddetallecot'] ?? '';

if ($iddetallecot != '') {
    // Consulta principal
    $stmt = $conexion->prepare("SELECT * FROM vw_cotizacion_detalle WHERE IDDETALLECOT = ?");
    $stmt->bind_param("i", $iddetallecot);
    $stmt->execute();
    $fila = $stmt->get_result()->fetch_assoc();

    // Obtener insumos del detalle
    if ($fila) {
        $stmt_insumos = $conexion->prepare("
            SELECT * FROM vw_cotizacion_detalle 
            WHERE IDDETALLECOT = ? AND IDINSUMO IS NOT NULL
            ORDER BY NOMBREINSUMO
        ");
        $stmt_insumos->bind_param("i", $iddetallecot);
        $stmt_insumos->execute();
        $insumos = $stmt_insumos->get_result()->fetch_all(MYSQLI_ASSOC);
    }
} else {
    $fila = null;
}

// Funciones auxiliares
function formatPrice($price) {
    return $price ? '$' . number_format($price, 2) : 'N/A';
}

function formatDate($date) {
    return $date ? (new DateTime($date))->format('d/m/Y') : 'N/A';
}

function formatTime($time) {
    return $time ? (new DateTime($time))->format('H:i') : 'N/A';
}

function formatDateTime($datetime) {
    return $datetime ? (new DateTime($datetime))->format('d/m/Y H:i') : 'N/A';
}

function getStatusBadge($estado) {
    $mapaEstados = [
        0 => 'CANCELADO',
        1 => 'EN_PROCESO',
        2 => 'APROBADO',
        3 => 'RECHAZADO',
        4 => 'COMPLETADO'
    ];
    $badges = [
        'PENDIENTE'   => ['class' => 'status-pending',   'icon' => 'fas fa-clock',        'text' => 'Pendiente'],
        'APROBADO'    => ['class' => 'status-approved',  'icon' => 'fas fa-check-circle', 'text' => 'Aprobado'],
        'RECHAZADO'   => ['class' => 'status-rejected',  'icon' => 'fas fa-times-circle', 'text' => 'Rechazado'],
        'EN_PROCESO'  => ['class' => 'status-process',   'icon' => 'fas fa-spinner',      'text' => 'En Proceso'],
        'COMPLETADO'  => ['class' => 'status-completed', 'icon' => 'fas fa-check-double', 'text' => 'Completado'],
        'CANCELADO'   => ['class' => 'status-cancelled', 'icon' => 'fas fa-ban',          'text' => 'Cancelado']
    ];
    $claveEstado = $mapaEstados[$estado] ?? 'PENDIENTE';
    $status = $badges[$claveEstado] ?? ['class' => 'status-pending', 'icon' => 'fas fa-question', 'text' => $claveEstado];
    return "<span class='status-badge {$status['class']}'><i class='{$status['icon']}'></i> {$status['text']}</span>";
}

// Generar PDF si hay datos
if ($fila) {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            @page { margin: 15mm; }
            body { 
                font-family: DejaVu Sans, sans-serif; 
                font-size:12px; 
                color:#333; 
                line-height:1.3; 
                margin:0; 
                padding:0;
            }
            .header { 
                text-align:center; 
                margin-bottom:15px; 
                border-bottom:2px solid #006d3e; 
                padding-bottom:12px; 
            }
            h1 { color:#006d3e; font-size:22px; margin:0 0 8px 0; }
            .subtitle { font-size:13px; color:#666; }
            .section { 
                margin-bottom:15px; 
            }
            .section.no-break { 
                page-break-inside:avoid; 
            }
            .section-title { 
                background:#006d3e; 
                color:white; 
                padding:6px 10px; 
                font-size:13px; 
                font-weight:bold; 
                margin-bottom:10px; 
            }
            .info-grid { display:table; width:100%; margin-bottom:8px; }
            .info-row { display:table-row; }
            .info-cell { 
                display:table-cell; 
                padding:3px 8px; 
                border-bottom:1px solid #eee; 
                width:50%; 
            }
            .info-label { font-weight:bold; color:#006d3e; font-size:11px; }
            table { 
                width:100%; 
                border-collapse:collapse; 
                margin-top:5px; 
                margin-bottom:10px; 
                font-size:10px; 
            }
            th, td { border:1px solid #ccc; padding:6px; text-align:left; }
            th { background:#f8f9fa; font-weight:bold; color:#006d3e; font-size:11px; }
            th:nth-child(1) { width:50%; }
            th:nth-child(2) { width:15%; text-align:center; }
            th:nth-child(3) { width:17.5%; text-align:right; }
            th:nth-child(4) { width:17.5%; text-align:right; }
            td:nth-child(2) { text-align:center; }
            td:nth-child(3), td:nth-child(4) { text-align:right; font-weight:bold; }
            .description-text { font-size:9px; color:#555; margin-top:2px; }
            
            .summary-box { 
                background:#f0f8f0; 
                border:2px solid #006d3e; 
                padding:12px; 
                margin-top:10px; 
                margin-bottom:10px;
                border-radius:5px;
                page-break-inside:avoid;
            }
            .summary-row { 
                display:table; 
                width:100%; 
                margin-bottom:6px; 
            }
            .summary-row:last-child { 
                margin-bottom:0; 
                padding-top:6px; 
                border-top:2px solid #006d3e; 
            }
            .summary-label { 
                display:table-cell; 
                font-weight:bold; 
                color:#006d3e; 
                font-size:12px; 
            }
            .summary-value { 
                display:table-cell; 
                text-align:right; 
                font-weight:bold; 
                font-size:14px; 
                color:#006d3e; 
            }
            
            .audit-section { 
                background:#f8f9fa; 
                padding:10px; 
                border-left:4px solid #17a2b8; 
                margin-top:10px; 
            }
            .audit-item { font-size:10px; margin-bottom:3px; color:#666; }
            h3 { 
                color:#006d3e; 
                font-size:12px; 
                margin:10px 0 6px 0; 
                padding-bottom:4px; 
                border-bottom:1px solid #006d3e; 
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Cotización</h1>
            <div class="subtitle">
                <?= htmlspecialchars($fila['CODREFERENCIA'] ?? 'Sin código') ?>
                <?= !empty($fila['NRC']) ? "| NRC: " . htmlspecialchars($fila['NRC']) : '' ?>
            </div>
            <div style="margin-top:10px; font-size:12px;">
                Fecha de creación: <?= formatDate($fila['FECHAREGCOT']) ?>
            </div>
        </div>

        <!-- Cliente -->
        <div class="section no-break">
            <div class="section-title">Información del Cliente</div>
            <div class="info-grid">
                <?php foreach (['EMPRESA'=>'Empresa','NOMBRECONTACTO'=>'Contacto','TELCONTACTO'=>'Teléfono','CORREOCONTACTO'=>'Correo'] as $key => $label): ?>
                    <?php if (!empty($fila[$key])): ?>
                        <div class="info-row">
                            <div class="info-cell"><span class="info-label"><?= $label ?>:</span></div>
                            <div class="info-cell"><?= htmlspecialchars($fila[$key]) ?></div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Evento -->
        <div class="section no-break">
            <div class="section-title">Detalles del Evento</div>
            <div class="info-grid">
                <?php if (!empty($fila['NOMBRETIPOEVENTO'])): ?>
                    <div class="info-row">
                        <div class="info-cell"><span class="info-label">Tipo de evento:</span></div>
                        <div class="info-cell"><?= htmlspecialchars($fila['NOMBRETIPOEVENTO']) ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($fila['FECHACOT'])): ?>
                    <div class="info-row">
                        <div class="info-cell"><span class="info-label">Fecha evento:</span></div>
                        <div class="info-cell"><?= formatDate($fila['FECHACOT']) ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($fila['HORAINICOT']) && !empty($fila['HORAFINCOT'])): ?>
                    <div class="info-row">
                        <div class="info-cell"><span class="info-label">Horario:</span></div>
                        <div class="info-cell"><?= formatTime($fila['HORAINICOT']) ?> - <?= formatTime($fila['HORAFINCOT']) ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($fila['DURACIONCOT'])): ?>
                    <div class="info-row">
                        <div class="info-cell"><span class="info-label">Duración:</span></div>
                        <div class="info-cell"><?= htmlspecialchars($fila['DURACIONCOT']) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Insumos -->
        <div class="section">
            <div class="section-title">Insumos y Servicios por Categoría</div>
            <?php if (!empty($insumos)): 
                $insumosPorTipo = [];
                foreach ($insumos as $insumo) {
                    $tipo = $insumo['NOMBRETIPOINSUMO'] ?? 'Sin categoría';
                    $insumosPorTipo[$tipo][] = $insumo;
                }
                $totalItems = 0;
                $totalValue = 0;
            ?>
                <?php foreach ($insumosPorTipo as $tipo => $listaInsumos): ?>
                    <h3><?= htmlspecialchars($tipo) ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Insumo</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaInsumos as $insumo):
                                $totalItems++;
                                $totalValue += $insumo['TOTAL'] ?? 0;
                            ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($insumo['NOMBREINSUMO'] ?? 'Sin nombre') ?></strong>
                                        <?= !empty($insumo['DESCRIPCIONINS']) ? "<div class='description-text'>" . htmlspecialchars($insumo['DESCRIPCIONINS']) . "</div>" : '' ?>
                                    </td>
                                    <td><?= !empty($insumo['CANTIDAD']) ? htmlspecialchars($insumo['CANTIDAD']) : 'N/A' ?></td>
                                    <td><?= formatPrice($insumo['PRECIO'] ?? null) ?></td>
                                    <td><?= formatPrice($insumo['TOTAL'] ?? null) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
                
                <div class="summary-box">
                    <div class="summary-row">
                        <div class="summary-label">Total de Insumos:</div>
                        <div class="summary-value"><?= $totalItems ?> items</div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">Valor Total:</div>
                        <div class="summary-value"><?= formatPrice($totalValue) ?></div>
                    </div>
                </div>
            <?php else: ?>
                <div>No hay insumos registrados</div>
            <?php endif; ?>
        </div>

        <!-- Auditoría -->
        <div class="section no-break">
            <div class="section-title">Información de Auditoría</div>
            <div class="audit-section">
                <h6><i class="fas fa-file-alt"></i> Cotización Principal</h6>
                <?= !empty($fila['USURECOT']) ? "<div class='audit-item'>Creado por: ".htmlspecialchars($fila['USURECOT'])."</div>" : '' ?>
                <?= !empty($fila['FECHAREGCOT']) ? "<div class='audit-item'>Fecha de registro: ".formatDateTime($fila['FECHAREGCOT'])."</div>" : '' ?>
                <h6><i class="fas fa-file-alt"></i> Detalle de cotización</h6>
                <?= !empty($fila['USUDETALLE']) ? "<div class='audit-item'>Registrado por: ".htmlspecialchars($fila['USUDETALLE'])."</div>" : '' ?>
                <?= !empty($fila['FECHAREGDETALLE']) ? "<div class='audit-item'>Fecha detalle: ".formatDateTime($fila['FECHAREGDETALLE'])."</div>" : '' ?>
            </div>
        </div>
    </body>
    </html>
    <?php
    $html = ob_get_clean();

    // Generar PDF
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("cotizacion_" . ($fila['CODREFERENCIA'] ?? 'sin_codigo') . ".pdf", ["Attachment" => true]);
    exit;
}
?>