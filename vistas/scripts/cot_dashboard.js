let chartPipelineEstados = null;
let chartTiempoPromedio = null;
let chartPorcentajeTiempo = null;
let chartDineroTipoAlquiler = null;
let chartParticipantesAprobadosFecha = null;

let tablaDetalleDashboard = null;



function init() {

    establecerFechasMesActual();

    cargarFiltros();

    cargarDashboard();



    $("#btnFiltrar").on("click", function () {

        cargarDashboard();

    });



    $("#filtro_periodo").on("change", function () {

        aplicarPeriodoRapido($(this).val());

    });



    $("#btnExportarResumen").on("click", function () {

        exportarReporte("resumen");

    });



    $("#btnExportarDetalle").on("click", function () {

        exportarReporte("detalle");

    });

}



function obtenerFiltros() {

    return {

        desde: $("#filtro_desde").val(),

        hasta: $("#filtro_hasta").val(),

        tipo_alquiler: $("#filtro_tipo_alquiler").val(),

        ejecutivo: $("#filtro_ejecutivo").val()

    };

}



function cargarFiltros() {

    $.ajax({

        url: "../ajax/cot_dashboard.php?op=filtros",

        type: "POST",

        dataType: "json",

        success: function (resp) {

            if (!resp.ok) {

                bootbox.alert(resp.mensaje || "No se pudieron cargar los filtros.");

                return;

            }



            let htmlTipos = '<option value="">Todos</option>';

            resp.tipos_alquiler.forEach(function (item) {

                htmlTipos += `<option value="${escapeHtml(item.IDTIPOALQUILER)}">${escapeHtml(item.NOMBRETIPOALQUILER)}</option>`;

            });

            $("#filtro_tipo_alquiler").html(htmlTipos);



            let htmlEjecutivos = '<option value="">Todos</option>';

            resp.ejecutivos.forEach(function (item) {

                htmlEjecutivos += `<option value="${escapeHtml(item.USURECOT)}">${escapeHtml(item.USURECOT)}</option>`;

            });

            $("#filtro_ejecutivo").html(htmlEjecutivos);

        },

        error: function () {

            bootbox.alert("Error al cargar filtros del dashboard.");

        }

    });

}



function cargarDashboard() {
    cargarKpis();
    cargarPipelineEstados();
    cargarTiemposEstados();
    cargarTiempoRespuestaFinales();
    cargarDineroAprobadoTipoAlquiler();

    cargarParticipantesAprobadosFecha();

    cargarDetalle();
}



function cargarKpis() {

    $.ajax({

        url: "../ajax/cot_dashboard.php?op=kpis",

        type: "POST",

        data: obtenerFiltros(),

        dataType: "json",

        success: function (resp) {

            if (!resp.ok) {

                bootbox.alert(resp.mensaje || "No se pudieron cargar los KPIs.");

                return;

            }



            const d = resp.data;



            $("#kpi_total_cotizaciones").text(formatoEntero(d.total_cotizaciones));

            $("#kpi_clientes").text(formatoEntero(d.clientes_unicos));

            $("#kpi_aprobadas").text(formatoEntero(d.cotizaciones_aprobadas));

            $("#kpi_tasa").text(formatoNumero(d.tasa_aprobacion) + "%");

            $("#kpi_dinero").text(formatoMoneda(d.dinero_aprobado));

            $("#kpi_ticket").text(formatoMoneda(d.ticket_promedio_aprobado));

        },

        error: function () {

            bootbox.alert("Error al cargar KPIs.");

        }

    });

}



function cargarPipelineEstados() {

    $.ajax({

        url: "../ajax/cot_dashboard.php?op=pipeline_estados",

        type: "POST",

        data: obtenerFiltros(),

        dataType: "json",

        success: function (resp) {

            if (!resp.ok) {

                bootbox.alert(resp.mensaje || "No se pudo cargar el pipeline.");

                return;

            }



            const labels = resp.data.map(x => x.estado_nombre);

            const valores = resp.data.map(x => x.cantidad);



            renderBarChart(

                "chartPipelineEstados",

                chartPipelineEstados,

                function (chart) { chartPipelineEstados = chart; },

                labels,

                valores,

                "Cotizaciones"

            );

        },

        error: function () {

            bootbox.alert("Error al cargar pipeline por estado.");

        }

    });

}



function cargarTiemposEstados() {

    $.ajax({

        url: "../ajax/cot_dashboard.php?op=tiempos_estados",

        type: "POST",

        data: obtenerFiltros(),

        dataType: "json",

        success: function (resp) {

            if (!resp.ok) {

                bootbox.alert(resp.mensaje || "No se pudieron cargar tiempos.");

                return;

            }



            const labels = resp.data.map(x => x.estado_nombre);

            const porcentaje = resp.data.map(x => x.porcentaje_tiempo);



            renderPieChart(

                "chartPorcentajeTiempo",

                chartPorcentajeTiempo,

                function (chart) { chartPorcentajeTiempo = chart; },

                labels,

                porcentaje,

                "% tiempo acumulado"

            );

        },

        error: function () {

            bootbox.alert("Error al cargar tiempos por estado.");

        }

    });

}



function cargarTiempoRespuestaFinales() {

    $.ajax({

        url: "../ajax/cot_dashboard.php?op=tiempo_respuesta_finales",

        type: "POST",

        data: obtenerFiltros(),

        dataType: "json",

        success: function (resp) {

            if (!resp.ok) {

                bootbox.alert(resp.mensaje || "No se pudo cargar el tiempo de respuesta.");

                return;

            }



            const labels = resp.data.map(x => x.estado_nombre);

            const promedioDias = resp.data.map(x => x.tiempo_promedio_dias);



            renderBarChart(

                "chartTiempoPromedio",

                chartTiempoPromedio,

                function (chart) { chartTiempoPromedio = chart; },

                labels,

                promedioDias,

                "Días promedio desde enviada"

            );

        },

        error: function () {

            bootbox.alert("Error al cargar tiempo promedio desde enviada hasta cierre.");

        }

    });

}



function cargarDineroAprobadoTipoAlquiler() {

    $.ajax({

        url: "../ajax/cot_dashboard.php?op=dinero_aprobado_tipo_alquiler",

        type: "POST",

        data: obtenerFiltros(),

        dataType: "json",

        success: function (resp) {

            if (!resp.ok) {

                bootbox.alert(resp.mensaje || "No se pudo cargar dinero aprobado por tipo.");

                return;

            }



            const labels = resp.data.map(x => x.tipo_alquiler);

            const valores = resp.data.map(x => x.dinero_aprobado);



            renderPieChart(

                "chartDineroTipoAlquiler",

                chartDineroTipoAlquiler,

                function (chart) { chartDineroTipoAlquiler = chart; },

                labels,

                valores,

                "Dinero aprobado"

            );

        },

        error: function () {

            bootbox.alert("Error al cargar dinero aprobado por tipo de alquiler.");

        }

    });

}



function cargarDetalle() {

    $.ajax({

        url: "../ajax/cot_dashboard.php?op=detalle",

        type: "POST",

        data: obtenerFiltros(),

        dataType: "json",

        success: function (resp) {

            if (!resp.ok) {

                bootbox.alert(resp.mensaje || "No se pudo cargar el detalle.");

                return;

            }



            if (tablaDetalleDashboard !== null) {

                tablaDetalleDashboard.destroy();

                tablaDetalleDashboard = null;

            }



            let html = "";



            resp.data.forEach(function (item) {

                html += `

                    <tr>

                        <td>${escapeHtml(item.CODREFERENCIA)}</td>

                        <td>${escapeHtml(item.EMPRESA)}</td>

                        <td>${escapeHtml(item.USURECOT)}</td>

                        <td>${escapeHtml(item.NOMBRETIPOALQUILER)}</td>

                        <td>${escapeHtml(item.NOMBREESTADOCOT)}</td>

                        <td>${escapeHtml(item.FECHA_REGISTRO)}</td>

                        <td>${escapeHtml(item.FECHA_PRIMER_DETALLE)}</td>

                        <td>${escapeHtml(item.FECHA_ULTIMO_DETALLE)}</td>

                        <td class="text-center">
                            ${formatoEntero(item.TOTAL_DETALLES)}
                        </td>

                        <td class="text-center">
                            ${formatoEntero(item.PARTICIPANTES_TOTALES)}
                        </td>

                        <td class="text-center">
                            ${formatoEntero(item.PROMEDIO_PARTICIPANTES)}
                        </td>
                        
                        <td class="text-center">
                            ${formatoNumero(item.PROMEDIO_HORAS)}
                        </td>
                        
                        <td class="text-center">
                            ${escapeHtml(item.ALIMENTACION)}
                        </td>

                        <td class="text-right">
                            ${formatoMoneda(item.TOTAL_COTIZACION)}
                        </td>

                    </tr>

                `;

            });



            $("#tbodyDetalleDashboard").html(html);



            tablaDetalleDashboard = $("#tablaDetalleDashboard").DataTable({

                responsive: true,

                autoWidth: false,

                pageLength: 10,

                order: [[5, "desc"]],

                language: {

                    lengthMenu: "Mostrar _MENU_ registros",

                    zeroRecords: "No se encontraron resultados",

                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",

                    infoEmpty: "Mostrando 0 a 0 de 0 registros",

                    infoFiltered: "(filtrado de _MAX_ registros totales)",

                    search: "Buscar:",

                    paginate: {

                        first: "Primero",

                        last: "Último",

                        next: "Siguiente",

                        previous: "Anterior"

                    }

                }

            });

        },

        error: function () {

            bootbox.alert("Error al cargar detalle de cotizaciones.");

        }

    });

}



function renderBarChart(canvasId, chartInstance, setInstance, labels, values, label) {

    const ctx = document.getElementById(canvasId).getContext("2d");



    if (chartInstance !== null) {

        chartInstance.destroy();

    }



    const chart = new Chart(ctx, {

        type: "bar",

        data: {

            labels: labels,

            datasets: [{

                label: label,

                data: values

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: true,

            plugins: {

                legend: {

                    display: true

                }

            },

            scales: {

                y: {

                    beginAtZero: true

                }

            }

        }

    });



    setInstance(chart);

}



function renderPieChart(canvasId, chartInstance, setInstance, labels, values, label) {

    const ctx = document.getElementById(canvasId).getContext("2d");



    if (chartInstance !== null) {

        chartInstance.destroy();

    }



    const chart = new Chart(ctx, {

        type: "doughnut",

        data: {

            labels: labels,

            datasets: [{

                label: label,

                data: values

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: true,

            plugins: {

                legend: {

                    position: "bottom"

                }

            }

        }

    });



    setInstance(chart);

}



function aplicarPeriodoRapido(periodo) {

    const hoy = new Date();

    let desde = "";

    let hasta = "";



    if (periodo === "") {

        return;

    }



    if (periodo === "hoy") {

        desde = formatearFecha(hoy);

        hasta = formatearFecha(hoy);

    }



    if (periodo === "mes") {

        desde = formatearFecha(new Date(hoy.getFullYear(), hoy.getMonth(), 1));

        hasta = formatearFecha(new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0));

    }



    if (periodo === "anio") {

        desde = formatearFecha(new Date(hoy.getFullYear(), 0, 1));

        hasta = formatearFecha(new Date(hoy.getFullYear(), 11, 31));

    }



    if (periodo === "trimestre") {

        const trimestre = Math.floor(hoy.getMonth() / 3);

        const mesInicio = trimestre * 3;



        desde = formatearFecha(new Date(hoy.getFullYear(), mesInicio, 1));

        hasta = formatearFecha(new Date(hoy.getFullYear(), mesInicio + 3, 0));

    }



    $("#filtro_desde").val(desde);

    $("#filtro_hasta").val(hasta);



    cargarDashboard();

}



function establecerFechasMesActual() {

    const hoy = new Date();

    const desde = new Date(hoy.getFullYear(), hoy.getMonth(), 1);

    const hasta = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);



    $("#filtro_desde").val(formatearFecha(desde));

    $("#filtro_hasta").val(formatearFecha(hasta));

    $("#filtro_periodo").val("mes");

}



function formatearFecha(fecha) {

    const y = fecha.getFullYear();

    const m = String(fecha.getMonth() + 1).padStart(2, "0");

    const d = String(fecha.getDate()).padStart(2, "0");



    return `${y}-${m}-${d}`;

}



function formatoEntero(valor) {

    return Number(valor || 0).toLocaleString("es-SV");

}



function formatoNumero(valor) {

    return Number(valor || 0).toLocaleString("es-SV", {

        minimumFractionDigits: 2,

        maximumFractionDigits: 2

    });

}



function formatoMoneda(valor) {

    return "$" + Number(valor || 0).toLocaleString("es-SV", {

        minimumFractionDigits: 2,

        maximumFractionDigits: 2

    });

}



function escapeHtml(text) {

    if (text === null || text === undefined) {

        return "";

    }



    return String(text)

        .replace(/&/g, "&amp;")

        .replace(/</g, "&lt;")

        .replace(/>/g, "&gt;")

        .replace(/"/g, "&quot;")

        .replace(/'/g, "&#039;");

}



function exportarReporte(tipo) {

    const filtros = obtenerFiltros();



    let op = "";



    if (tipo === "resumen") {

        op = "exportar_resumen";

    } else if (tipo === "detalle") {

        op = "exportar_detalle";

    } else {

        bootbox.alert("Tipo de reporte no válido.");

        return;

    }



    const params = $.param({

        desde: filtros.desde,

        hasta: filtros.hasta,

        tipo_alquiler: filtros.tipo_alquiler,

        ejecutivo: filtros.ejecutivo

    });



    window.open("../ajax/cot_dashboard.php?op=" + op + "&" + params, "_blank");

}

function cargarParticipantesAprobadosFecha() {

    $.ajax({

        url: "../ajax/cot_dashboard.php?op=participantes_aprobados_fecha",

        type: "POST",

        data: obtenerFiltros(),

        dataType: "json",

        success: function (resp) {

            if (!resp.ok) {

                bootbox.alert(
                    resp.mensaje ||
                    "No se pudo cargar el gráfico de participantes."
                );

                return;
            }

            const labels = resp.data.map(function (item) {
                return formatearFechaGrafico(item.fecha);
            });

            const valores = resp.data.map(function (item) {
                return Number(item.participantes);
            });

            renderLineChart(

                "chartParticipantesAprobadosFecha",

                chartParticipantesAprobadosFecha,

                function (chart) {
                    chartParticipantesAprobadosFecha = chart;
                },

                labels,

                valores,

                "Participantes"

            );

        },

        error: function () {

            bootbox.alert(
                "Error al cargar participantes de cotizaciones aprobadas."
            );

        }

    });

}

function renderLineChart(
    canvasId,
    chartInstance,
    setInstance,
    labels,
    values,
    label
) {

    const ctx = document
        .getElementById(canvasId)
        .getContext("2d");

    if (chartInstance !== null) {
        chartInstance.destroy();
    }

    const chart = new Chart(ctx, {

        type: "line",

        data: {

            labels: labels,

            datasets: [{

                label: label,

                data: values,

                fill: false,

                tension: 0.25,

                pointRadius: 4,

                pointHoverRadius: 6,

                borderWidth: 2

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: true,

            interaction: {

                mode: "index",

                intersect: false

            },

            plugins: {

                legend: {

                    display: true,

                    position: "top"

                },

                tooltip: {

                    callbacks: {

                        label: function (context) {

                            return "Participantes: " +
                                formatoEntero(context.raw);

                        }

                    }

                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {

                        precision: 0

                    },

                    title: {

                        display: true,

                        text: "Participantes"

                    }

                },

                x: {

                    title: {

                        display: true,

                        text: "Fecha"

                    }

                }

            }

        }

    });

    setInstance(chart);
}

function formatearFechaGrafico(fecha) {

    if (!fecha) {
        return "";
    }

    const partes = fecha.split("-");

    if (partes.length !== 3) {
        return fecha;
    }

    return partes[2] + "/" + partes[1] + "/" + partes[0];
}



init();