<?php

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../config/Conexion.php'; // tu conexión a BD

$iddetallecot = $_GET['iddetallecot'] ?? '';

if ($iddetallecot != '') {
    // Consulta principal usando la vista
    $stmt = $conexion->prepare("SELECT * FROM vw_cotizacion_detalle WHERE IDDETALLECOT = ?");
    $stmt->bind_param("i", $iddetallecot);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();
    
    // Obtener todos los insumos del detalle ORDENADOS POR TIPO DE INSUMO
    if ($fila) {
        $stmt_insumos = $conexion->prepare("
            SELECT * FROM vw_cotizacion_detalle 
            WHERE IDDETALLECOT = ? AND IDINSUMO IS NOT NULL
            ORDER BY NOMBRETIPOINSUMO ASC, NOMBREINSUMO ASC
        ");
        $stmt_insumos->bind_param("i", $iddetallecot);
        $stmt_insumos->execute();
        $insumos = $stmt_insumos->get_result();
        
        // Agrupar insumos por tipo para mostrar de manera más organizada
        $insumos_agrupados = [];
        if ($insumos->num_rows > 0) {
            while ($insumo = $insumos->fetch_assoc()) {
                $tipo = $insumo['NOMBRETIPOINSUMO'] ?? 'Sin tipo';
                if (!isset($insumos_agrupados[$tipo])) {
                    $insumos_agrupados[$tipo] = [];
                }
                $insumos_agrupados[$tipo][] = $insumo;
            }
        }
    }
} else {
    $fila = null;
}

// Función auxiliar para formatear precios
function formatPrice($price) {
    return $price ? '$' . number_format($price, 2) : 'N/A';
}

// Función auxiliar para formatear fechas
function formatDate($date) {
    if ($date) {
        $dateObj = new DateTime($date);
        return $dateObj->format('d/m/Y');
    }
    return 'N/A';
}

// Función auxiliar para formatear fechas con hora
function formatDateTime($datetime) {
    if ($datetime) {
        $dateObj = new DateTime($datetime);
        return $dateObj->format('d/m/Y H:i');
    }
    return 'N/A';
}

// Función auxiliar para formatear horas
function formatTime($time) {
    if ($time) {
        $timeObj = new DateTime($time);
        return $timeObj->format('H:i');
    }
    return 'N/A';
}

// Función para obtener el estado con color
function getStatusBadge($estado) {
    // Mapeo de códigos numéricos a llaves de estado
    $mapaEstados = [
        0 => 'PENDIENTE',
        1 => 'EN_PROCESO',
        2 => 'FINALIZADA',
        3 => 'APROBADA',
        4 => 'DEEGADA',
        5 => 'DENEGADA_POR_ESPACIO',
        6 => 'SUSPENDIDA'
    ];

    // Badges definidos
    $badges = [
        'PENDIENTE'             => ['class' => 'status-pending',   'icon' => 'fas fa-clock',        'text' => 'Pendiente'],
        'EN_PROESO'             => ['class' => 'status-pending',  'icon' => 'fas fa-clock',         'text' => 'En proceso'],
        'FINALIZADA'            => ['class' => 'status-approved',  'icon' => 'fas fa-check-circle', 'text' => 'Finalizada'],
        'APROBADO'              => ['class' => 'status-approved',  'icon' => 'fas fa-check-circle', 'text' => 'Aprobada'],
        'DENEGADA'              => ['class' => 'status-rejected',  'icon' => 'fas fa-times-circle', 'text' => 'Denegada'],
        'DENEGADA_POR_ESPACIO'  => ['class' => 'status-rejected',  'icon' => 'fas fa-times-circle', 'text' => 'Denegada por falta de espacio'],
        'SUSPENDIDA'            => ['class' => 'status-cancelled', 'icon' => 'fas fa-ban',          'text' => 'Suspendida']
    ];

    // Convertir estado numérico a texto si existe en el mapa
    $claveEstado = $mapaEstados[$estado] ?? 'PENDIENTE';

    // Obtener configuración del badge o fallback
    $status = $badges[$claveEstado] ?? ['class' => 'status-pending', 'icon' => 'fas fa-question', 'text' => $claveEstado];

    return "<span class='status-badge {$status['class']}'><i class='{$status['icon']}'></i> {$status['text']}</span>";
}

// Función para obtener color del tipo de insumo
function getTipoInsumoColor($tipo) {
    $colores = [
        'Mobiliario' => '#3498db',
        'Decoración' => '#e74c3c',
        'Iluminación' => '#f39c12',
        'Sonido' => '#9b59b6',
        'Catering' => '#2ecc71',
        'Textil' => '#e67e22',
        'Tecnología' => '#1abc9c',
        'default' => '#95a5a6'
    ];
    
    return $colores[$tipo] ?? $colores['default'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa - Cotización <?= $fila ? htmlspecialchars($fila['CODREFERENCIA'] ?? 'N/A') : '' ?></title>
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #343a40;
            --secondary-color: #006d3e8c;
            --success-color: #127f3fff;
            --warning-color: #CC8E00;
            --danger-color: #B0291C;
            --info-color: #17b893ff;
            --light-bg: #f8f9fa;
            --border-color: #dee2e6;
        }

        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            line-height: 1.6;
        }

        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .cotization-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 0;
        }

        .cotization-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 300;
        }

        .cotization-header .subtitle {
            opacity: 0.9;
            margin-top: 10px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 15px;
            opacity: 0.9;
        }

        .header-info-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cotization-body {
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .section {
            padding: 30px;
            border-bottom: 1px solid var(--border-color);
        }

        .section:last-child {
            border-bottom: none;
        }

        .section-title {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--secondary-color);
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--secondary-color);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: var(--light-bg);
            border-radius: 8px;
            border-left: 4px solid var(--secondary-color);
        }

        .info-item i {
            margin-right: 12px;
            color: var(--secondary-color);
            width: 20px;
            text-align: center;
        }

        .info-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-right: 8px;
        }

        .info-value {
            color: #555;
        }

        /* Estilos para agrupación por tipo de insumo */
        .tipo-insumo-group {
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .tipo-insumo-header {
            padding: 15px 20px;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .tipo-insumo-header i {
            margin-right: 10px;
        }

        .tipo-insumo-count {
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
        }

        .insumos-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .insumos-table th {
            background: #f8f9fa;
            color: var(--primary-color);
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border-color);
        }

        .insumos-table td {
            padding: 15px 12px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: top;
        }

        .insumos-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .insumos-table tbody tr:last-child td {
            border-bottom: none;
        }

        .price-cell {
            font-weight: 600;
            color: var(--success-color);
        }

        .quantity-badge {
            background: var(--secondary-color);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .description-text {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .action-buttons {
            padding: 20px 30px;
            background: var(--light-bg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-custom {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary-custom {
            background: var(--secondary-color);
            color: white;
        }

        .btn-primary-custom:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        .btn-success-custom {
            background: var(--success-color);
            color: white;
        }

        .btn-success-custom:hover {
            background: #229954;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(39, 174, 96, 0.3);
        }

        .btn-secondary-custom {
            background: #6c757d;
            color: white;
        }

        .btn-secondary-custom:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status-process {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .empty-state {
            text-align: center;
            padding: 60px 30px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-top: 4px solid var(--secondary-color);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 5px;
        }

        .audit-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid var(--info-color);
        }

        .audit-info h6 {
            color: var(--info-color);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .audit-item {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .tipo-subtotal {
            background: #f8f9fa;
            font-weight: 600;
            color: var(--primary-color);
        }

        .tipo-subtotal td {
            padding: 12px;
            border-top: 2px solid var(--border-color);
        }

        @media print {
            body { background: white; }
            .action-buttons { display: none; }
            .cotization-body { box-shadow: none; }
            .section { page-break-inside: avoid; }
        }

        @media (max-width: 768px) {
            .container-fluid { padding: 10px; }
            .cotization-header { padding: 20px; }
            .cotization-header h1 { font-size: 1.8rem; }
            .section { padding: 20px; }
            .info-grid { grid-template-columns: 1fr; }
            .insumos-table { font-size: 0.9rem; }
            .insumos-table th, .insumos-table td { padding: 10px 8px; }
            .action-buttons { justify-content: center; }
            .btn-group { justify-content: center; width: 100%; }
            .header-info { justify-content: center; }
            .tipo-insumo-header { flex-direction: column; gap: 10px; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <?php if ($fila): ?>
            <!-- Header -->
            <div class="cotization-header">
                <h1><i class="fas fa-file-invoice-dollar"></i> Cotización</h1>
                <div class="subtitle">
                    <div>
                        <strong><?= htmlspecialchars($fila['CODREFERENCIA'] ?? 'Sin código') ?></strong>
                        <?php if (!empty($fila['NRC'])): ?>
                            | NRC: <?= htmlspecialchars($fila['NRC']) ?>
                        <?php endif; ?>
                    </div>
                    <?= getStatusBadge($fila['ESTADOCOT'] ?? 'PENDIENTE') ?>
                </div>
                <div class="header-info">
                    <?php if (!empty($fila['NOMBREAREARES'])): ?>
                    <div class="header-info-item">
                        <i class="fas fa-users"></i>
                        <span><?= htmlspecialchars($fila['NOMBREAREARES']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($fila['NOMBREORGEJE'])): ?>
                    <div class="header-info-item">
                        <i class="fas fa-building"></i>
                        <span><?= htmlspecialchars($fila['NOMBREORGEJE']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($fila['FECHAREGCOT'])): ?>
                    <div class="header-info-item">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Creado: <?= formatDate($fila['FECHAREGCOT']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="cotization-body">
                <!-- Información del Cliente -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-building"></i> Información del Cliente
                    </h2>
                    <div class="info-grid">
                        <?php if (!empty($fila['EMPRESA'])): ?>
                        <div class="info-item">
                            <i class="fas fa-building"></i>
                            <span class="info-label">Empresa:</span>
                            <span class="info-value"><?= htmlspecialchars($fila['EMPRESA']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($fila['NOMBRECONTACTO'])): ?>
                        <div class="info-item">
                            <i class="fas fa-user"></i>
                            <span class="info-label">Contacto:</span>
                            <span class="info-value"><?= htmlspecialchars($fila['NOMBRECONTACTO']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($fila['TELCONTACTO'])): ?>
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <span class="info-label">Teléfono:</span>
                            <span class="info-value"><?= htmlspecialchars($fila['TELCONTACTO']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($fila['CORREOCONTACTO'])): ?>
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span class="info-label">Correo:</span>
                            <span class="info-value"><?= htmlspecialchars($fila['CORREOCONTACTO']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Detalles del Evento -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-calendar-alt"></i> Detalles del Evento
                    </h2>
                    <div class="info-grid">
                        <?php if (!empty($fila['NOMBRETIPOEVENTO'])): ?>
                        <div class="info-item">
                            <i class="fas fa-tag"></i>
                            <span class="info-label">Tipo de evento:</span>
                            <span class="info-value"><?= htmlspecialchars($fila['NOMBRETIPOEVENTO']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($fila['NOMBRETIPOALQUILER'])): ?>
                        <div class="info-item">
                            <i class="fas fa-handshake"></i>
                            <span class="info-label">Tipo de alquiler:</span>
                            <span class="info-value"><?= htmlspecialchars($fila['NOMBRETIPOALQUILER']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($fila['FECHACOT'])): ?>
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <span class="info-label">Fecha evento:</span>
                            <span class="info-value"><?= formatDate($fila['FECHACOT']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($fila['HORAINICOT'])): ?>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <span class="info-label">Hora inicio:</span>
                            <span class="info-value"><?= formatTime($fila['HORAINICOT']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($fila['HORAFINCOT'])): ?>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <span class="info-label">Hora fin:</span>
                            <span class="info-value"><?= formatTime($fila['HORAFINCOT']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($fila['DURACIONCOT'])): ?>
                        <div class="info-item">
                            <i class="fas fa-hourglass-half"></i>
                            <span class="info-label">Duración:</span>
                            <span class="info-value"><?= htmlspecialchars($fila['DURACIONCOT']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($fila['CANTIDADCOT'])): ?>
                        <div class="info-item">
                            <i class="fas fa-sort-numeric-up"></i>
                            <span class="info-label">Cantidad solicitada:</span>
                            <span class="info-value"><?= htmlspecialchars($fila['CANTIDADCOT']) ?></span>
                        </div>
                        <?php endif; ?>

                        <div class="info-item">
                            <i class="fas fa-info-circle"></i>
                            <span class="info-label">Estado detalle:</span>
                            <span class="info-value"><?= getStatusBadge($fila['ESTADODETALLE'] ?? 'PENDIENTE') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Insumos y Servicios AGRUPADOS POR TIPO -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-boxes"></i> Insumos y Servicios por Categoría
                    </h2>

                    <?php
                    if (isset($insumos_agrupados) && !empty($insumos_agrupados)):
                        $totalItems = 0;
                        $totalValue = 0;
                        $totalTipos = count($insumos_agrupados);
                        
                        // Calcular totales generales
                        foreach ($insumos_agrupados as $tipo => $insumos_tipo) {
                            foreach ($insumos_tipo as $insumo) {
                                $totalItems++;
                                if (!empty($insumo['TOTAL'])) {
                                    $totalValue += $insumo['TOTAL'];
                                }
                            }
                        }
                    ?>
                        
                        <!-- Mostrar cada tipo de insumo -->
                        <?php foreach ($insumos_agrupados as $tipo => $insumos_tipo): 
                            $subtotalTipo = 0;
                            $cantidadTipo = count($insumos_tipo);
                            
                            // Calcular subtotal del tipo
                            foreach ($insumos_tipo as $insumo) {
                                if (!empty($insumo['TOTAL'])) {
                                    $subtotalTipo += $insumo['TOTAL'];
                                }
                            }
                            
                            $colorTipo = getTipoInsumoColor($tipo);
                        ?>
                            <div class="tipo-insumo-group">
                                <div class="tipo-insumo-header" style="background-color: <?= $colorTipo ?>;">
                                    <div>
                                        <i class="fas fa-layer-group"></i>
                                        <?= htmlspecialchars($tipo) ?>
                                    </div>
                                    <div class="tipo-insumo-count">
                                        <?= $cantidadTipo ?> item<?= $cantidadTipo > 1 ? 's' : '' ?>
                                    </div>
                                </div>
                                
                                <table class="insumos-table">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-cube"></i> Insumo</th>
                                            <th><i class="fas fa-sort-numeric-up"></i> Cantidad</th>
                                            <th><i class="fas fa-dollar-sign"></i> Precio</th>
                                            <th><i class="fas fa-money-bill-wave"></i> Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($insumos_tipo as $insumo): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($insumo['NOMBREINSUMO'] ?? 'Sin nombre') ?></strong>
                                                    <?php if (!empty($insumo['DESCRIPCIONINS'])): ?>
                                                        <div class="description-text"><?= htmlspecialchars($insumo['DESCRIPCIONINS']) ?></div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($insumo['CANTIDAD'])): ?>
                                                        <span class="quantity-badge"><?= htmlspecialchars($insumo['CANTIDAD']) ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="price-cell"><?= formatPrice($insumo['PRECIO'] ?? null) ?></td>
                                                <td class="price-cell"><?= formatPrice($insumo['TOTAL'] ?? null) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        
                                        <!-- Fila de subtotal por tipo -->
                                        <tr class="tipo-subtotal">
                                            <td colspan="3"><strong>Subtotal <?= htmlspecialchars($tipo) ?>:</strong></td>
                                            <td class="price-cell"><strong><?= formatPrice($subtotalTipo) ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php endforeach; ?>

                        <!-- Resumen estadístico mejorado -->
                        <div class="summary-stats">
                            <div class="stat-card">
                                <div class="stat-number"><?= $totalTipos ?></div>
                                <div class="stat-label">Tipos de Insumos</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?= $totalItems ?></div>
                                <div class="stat-label">Total Insumos</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?= formatPrice($totalValue) ?></div>
                                <div class="stat-label">Valor Total</div>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h3>No hay insumos registrados</h3>
                            <p>Esta cotización aún no tiene insumos asociados.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Información de Auditoría -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-history"></i> Información de Auditoría
                    </h2>
                    
                    <div class="audit-info">
                        <h6><i class="fas fa-file-alt"></i> Cotización Principal</h6>
                        <?php if (!empty($fila['USURECOT'])): ?>
                        <div class="audit-item">
                            <i class="fas fa-user"></i> Creado por: <?= htmlspecialchars($fila['USURECOT']) ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($fila['FECHAREGCOT'])): ?>
                        <div class="audit-item">
                            <i class="fas fa-calendar"></i> Fecha de registro: <?= formatDateTime($fila['FECHAREGCOT']) ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="audit-info">
                        <h6><i class="fas fa-list-alt"></i> Detalle de Cotización</h6>
                        <?php if (!empty($fila['USUDETALLE'])): ?>
                        <div class="audit-item">
                            <i class="fas fa-user"></i> Registrado por: <?= htmlspecialchars($fila['USUDETALLE']) ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($fila['FECHAREGDETALLE'])): ?>
                        <div class="audit-item">
                            <i class="fas fa-calendar"></i> Fecha de registro detalle: <?= formatDateTime($fila['FECHAREGDETALLE']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="action-buttons">
                <div class="btn-group">
                    <!--<button onclick="printDocument()" class="btn-custom btn-primary-custom">
                        <i class="fas fa-print"></i> Imprimir
                    </button>-->
                    <a href="cotizacion_detalle_pdf.php?iddetallecot=<?= $iddetallecot ?>" 
                        class="btn-custom btn-success-custom">
                        <i class="fas fa-file-pdf"></i> Descargar PDF
                    </a>
                </div>
                <div class="btn-group">
                    <a href="javascript:window.close()" class="btn-custom btn-secondary-custom">
                        <i class="fas fa-times"></i> Cerrar
                    </a>
                </div>
            </div>

        <?php else: ?>
            <div class="cotization-body">
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h2>Cotización no encontrada</h2>
                    <p>No se pudo encontrar la cotización solicitada. Verifique el ID y vuelva a intentar.</p>
                    <a href="javascript:history.back()" class="btn-custom btn-secondary-custom">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Función para imprimir con configuración mejorada
        function printDocument() {
            const originalTitle = document.title;
            document.title = `Cotización_<?= $fila ? htmlspecialchars($fila['CODREFERENCIA'] ?? 'Sin_codigo') : 'Sin_datos' ?>`;
            
            window.print();
            
            // Restaurar título original después de imprimir
            setTimeout(() => {
                document.title = originalTitle;
            }, 1000);
        }

        // Función para exportar PDF
        function exportToPDF() {
            confirmAction('¿Desea exportar esta cotización a PDF?', function() {
                // Ocultar botones para la exportación
                const actionButtons = document.querySelector('.action-buttons');
                actionButtons.style.display = 'none';
                
                // Simular exportación (aquí integrarías tu lógica real)
                setTimeout(() => {
                    actionButtons.style.display = 'flex';
                    alert('PDF generado exitosamente (función de ejemplo)');
                }, 1000);
            });
        }

        // Función para confirmar acciones
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }

        // Mejorar la impresión
        window.addEventListener('beforeprint', function() {
            document.title = 'Cotización <?= $fila ? htmlspecialchars($fila['CODREFERENCIA'] ?? '') : '' ?>';
        });

        // Añadir efectos de carga suaves
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.section');
            sections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    section.style.transition = 'all 0.5s ease';
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Tooltip para badges de estado
            const statusBadges = document.querySelectorAll('.status-badge');
            statusBadges.forEach(badge => {
                badge.title = 'Estado actual de la cotización';
            });

            // Efecto hover para filas de tabla
            const tableRows = document.querySelectorAll('.insumos-table tbody tr:not(.tipo-subtotal)');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                    this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                    this.style.boxShadow = 'none';
                });
            });

            // Efecto para grupos de tipo de insumo
            const tipoGroups = document.querySelectorAll('.tipo-insumo-group');
            tipoGroups.forEach((group, index) => {
                group.style.opacity = '0';
                group.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    group.style.transition = 'all 0.6s ease';
                    group.style.opacity = '1';
                    group.style.transform = 'translateX(0)';
                }, (index * 200) + 500); // Delay después de las secciones principales
            });
        });

        // Función para filtrar por tipo de insumo
        function filterByTipo(tipo) {
            const grupos = document.querySelectorAll('.tipo-insumo-group');
            grupos.forEach(grupo => {
                const header = grupo.querySelector('.tipo-insumo-header');
                const tipoTexto = header.textContent.trim();
                
                if (tipo === 'all' || tipoTexto.includes(tipo)) {
                    grupo.style.display = 'block';
                } else {
                    grupo.style.display = 'none';
                }
            });
        }

        // Función para mostrar/ocultar detalles de un tipo
        function toggleTipo(tipoElement) {
            const grupo = tipoElement.closest('.tipo-insumo-group');
            const tabla = grupo.querySelector('.insumos-table');
            
            if (tabla.style.display === 'none') {
                tabla.style.display = 'table';
                tipoElement.querySelector('i').className = 'fas fa-chevron-up';
            } else {
                tabla.style.display = 'none';
                tipoElement.querySelector('i').className = 'fas fa-chevron-down';
            }
        }

        // Función para expandir/colapsar todos los grupos
        function toggleAllGroups(expand) {
            const grupos = document.querySelectorAll('.tipo-insumo-group');
            grupos.forEach(grupo => {
                const tabla = grupo.querySelector('.insumos-table');
                const header = grupo.querySelector('.tipo-insumo-header');
                
                if (expand) {
                    tabla.style.display = 'table';
                    if (header.querySelector('i')) {
                        header.querySelector('i').className = 'fas fa-layer-group';
                    }
                } else {
                    tabla.style.display = 'none';
                    if (header.querySelector('i')) {
                        header.querySelector('i').className = 'fas fa-layer-group';
                    }
                }
            });
        }

        // Función para resaltar tipo de insumo específico
        function highlightTipo(tipo) {
            const grupos = document.querySelectorAll('.tipo-insumo-group');
            grupos.forEach(grupo => {
                const header = grupo.querySelector('.tipo-insumo-header');
                const tipoTexto = header.textContent.trim();
                
                if (tipoTexto.includes(tipo)) {
                    grupo.style.transform = 'scale(1.02)';
                    grupo.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
                    grupo.style.zIndex = '10';
                    
                    // Remover el resaltado después de 3 segundos
                    setTimeout(() => {
                        grupo.style.transform = 'scale(1)';
                        grupo.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
                        grupo.style.zIndex = '1';
                    }, 3000);
                }
            });
        }

        // Función para obtener estadísticas por tipo
        function getTipoStats() {
            const grupos = document.querySelectorAll('.tipo-insumo-group');
            const stats = {};
            
            grupos.forEach(grupo => {
                const header = grupo.querySelector('.tipo-insumo-header');
                const tipo = header.textContent.trim().split('\n')[0].trim();
                const filas = grupo.querySelectorAll('.insumos-table tbody tr:not(.tipo-subtotal)');
                const subtotal = grupo.querySelector('.tipo-subtotal .price-cell:last-child').textContent;
                
                stats[tipo] = {
                    cantidad: filas.length,
                    subtotal: subtotal
                };
            });
            
            return stats;
        }

        // Función para búsqueda rápida en insumos
        function quickSearch(searchTerm) {
            const filas = document.querySelectorAll('.insumos-table tbody tr:not(.tipo-subtotal)');
            let found = false;
            
            filas.forEach(fila => {
                const texto = fila.textContent.toLowerCase();
                if (texto.includes(searchTerm.toLowerCase())) {
                    fila.style.backgroundColor = '#fff3cd';
                    fila.style.border = '2px solid #ffc107';
                    found = true;
                    
                    // Remover resaltado después de 3 segundos
                    setTimeout(() => {
                        fila.style.backgroundColor = '';
                        fila.style.border = '';
                    }, 3000);
                    
                    // Scroll hacia el elemento encontrado
                    fila.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
            
            if (!found) {
                alert('No se encontraron insumos que coincidan con: ' + searchTerm);
            }
        }
    </script>
</body>
</html>