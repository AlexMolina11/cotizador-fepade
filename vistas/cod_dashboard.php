<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
    exit;
}

require_once "../config/Conexion.php";

function tieneAccionVistaDashboard($accionCode)
{
    $idrol = isset($_SESSION["idrol"]) ? intval($_SESSION["idrol"]) : 0;
    $accionCode = limpiarCadena($accionCode);

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

if (!tieneAccionVistaDashboard("138VER46")) {
    require "header.php";

    echo '<div class="content-wrapper">
            <section class="content">
                <div class="alert alert-danger">
                    No tiene permisos para acceder al Dashboard de Cotizaciones.
                </div>
            </section>
          </div>';

    require "footer.php";
    ob_end_flush();
    exit;
}

$puedeExportarResumen = tieneAccionVistaDashboard("139EXP46");
$puedeExportarDetalle = tieneAccionVistaDashboard("140EXP46");

require "header.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Dashboard de Cotizaciones
            <small>Flujo comercial y pipeline de ventas</small>
        </h1>
    </section>

    <section class="content">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>

            <div class="box-body">
                <div class="row">

                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                        <label>Fecha inicio</label>
                        <input type="date" class="form-control" id="filtro_desde" name="filtro_desde">
                    </div>

                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                        <label>Fecha fin</label>
                        <input type="date" class="form-control" id="filtro_hasta" name="filtro_hasta">
                    </div>

                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                        <label>Período rápido</label>
                        <select class="form-control" id="filtro_periodo">
                            <option value="">Manual</option>
                            <option value="hoy">Hoy</option>
                            <option value="mes">Mes actual</option>
                            <option value="anio">Año actual</option>
                            <option value="trimestre">Trimestre actual</option>
                        </select>
                    </div>

                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                        <label>Tipo de alquiler</label>
                        <select class="form-control" id="filtro_tipo_alquiler">
                            <option value="">Todos</option>
                        </select>
                    </div>

                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                        <label>Ejecutivo</label>
                        <select class="form-control" id="filtro_ejecutivo">
                            <option value="">Todos</option>
                        </select>
                    </div>

                    <div class="form-group col-lg-2 col-md-12 col-sm-12 col-xs-12">
                        <label>&nbsp;</label>
                        <button class="btn btn-primary btn-block" id="btnFiltrar">
                            <i class="fa fa-search"></i> Consultar
                        </button>
                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <small class="text-muted">
                            El dashboard considera todas las cotizaciones activas. Si no tienen detalle, se toma la fecha de registro como referencia operativa.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 id="kpi_total_cotizaciones">0</h3>
                        <p>Cotizaciones</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 id="kpi_clientes">0</h3>
                        <p>Clientes atendidos</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 id="kpi_aprobadas">0</h3>
                        <p>Aprobadas</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3 id="kpi_tasa">0%</h3>
                        <p>Tasa aprobación</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-line-chart"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3 id="kpi_dinero">$0.00</h3>
                        <p>Dinero aprobado</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="small-box bg-teal">
                    <div class="inner">
                        <h3 id="kpi_ticket">$0.00</h3>
                        <p>Ticket promedio</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-calculator"></i>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cantidad de cotizaciones por estado</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="chartPipelineEstados" height="140"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tiempo promedio desde Enviada hasta cierre</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="chartTiempoPromedio" height="140"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Distribución del tiempo acumulado del pipeline</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="chartPorcentajeTiempo" height="140"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dinero aprobado por tipo de alquiler</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="chartDineroTipoAlquiler" height="140"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Acciones</h3>
            </div>

            <div class="box-body">
                <?php if ($puedeExportarResumen) { ?>
                    <button class="btn btn-success" id="btnExportarResumen">
                        <i class="fa fa-file-excel-o"></i> Exportar resumen
                    </button>
                <?php } ?>

                <?php if ($puedeExportarDetalle) { ?>
                    <button class="btn btn-primary" id="btnExportarDetalle">
                        <i class="fa fa-table"></i> Exportar detalle
                    </button>
                <?php } ?>

                <?php if (!$puedeExportarResumen && !$puedeExportarDetalle) { ?>
                    <p class="text-muted">No tiene permisos de exportación.</p>
                <?php } ?>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Detalle de cotizaciones</h3>
            </div>

            <div class="box-body table-responsive">
                <table id="tablaDetalleDashboard" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Referencia</th>
                            <th>Empresa</th>
                            <th>Ejecutivo</th>
                            <th>Tipo alquiler</th>
                            <th>Estado</th>
                            <th>Fecha registro</th>
                            <th>Primer detalle</th>
                            <th>Último detalle</th>
                            <th>Detalles</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyDetalleDashboard">
                    </tbody>
                </table>
            </div>
        </div>

    </section>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="scripts/cot_dashboard.js"></script>

<?php
require "footer.php";
ob_end_flush();
?>