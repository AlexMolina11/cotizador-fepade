var tabla;

//Función que se ejecuta al inicio

function init() {



    mostrarform(false);

    listar();



    $("#formulario").on("submit", function(e) {

        guardaryeditar(e);

    });

	      

    //Cargamos los items al select tipo evento

    $.post("../ajax/cotizacion.php?op=selectTipoEvento", function(r) {

        $("#tipoevento").html(r);

        $("#tipoevento").selectpicker('refresh');

    });



    // Cargar items tipo de alquiler

    $.post("../ajax/cotizacion.php?op=selTipoAlquiler", function(r) {             

        $("#tipoalquiler").html(r);

        $("#tipoalquiler").selectpicker('refresh');



        // Si ya hay un idcotizacion cargado (en editar), asignar su valor

        let idAlquiler = $("#tipoalquiler").data("selected");

        if (idAlquiler) {

            $("#tipoalquiler").val(idAlquiler).selectpicker('refresh');

        }

    });

}



//Función limpiar

function limpiar() {

    $("#idcotizacion").val("");

	$("#codreferencia").val("");

    $("input[name='exentacot'][value='0']").prop("checked", true);

    $("#cli_nrc").val("");

	$("#cli_empresa").val("");

	$("#cli_contacto").val("");

	$("#cli_telefono").val("");

	$("#cli_email").val("");

    $("#descripcion_cot").val("");

    //Select tipo evento

	$("#tipoevento").val("0");

	$("#tipoevento").selectpicker('refresh');

    //Se agregó tipo alquiler

	$("#tipoalquiler").val("0");

	$("#tipoalquiler").selectpicker('refresh');

}



//Función mostrar formulario

function mostrarform(flag) {

    if (flag) {

        $("#listadoregistros").hide();

        $("#formularioregistros").show();

        $("#btnGuardar").prop("disabled", false);

        $("#btnagregar").hide();



        // Si no hay idcotizacion → NUEVO

        if ($("#idcotizacion").val() === "") {

            console.log($("#idcotizacion").val());

            limpiar(); // limpiar solo en nuevo



            // Generar código

            $.post("../ajax/cotizacion.php?op=generarCodigoReferencia", function(codigo){

                $("#codreferencia").val(codigo);

            });

        }



        // Enganchar cambio de tipo de alquiler

        $("#tipoalquiler").off("change").on("change", function(){

            let tipo = $(this).find("option:selected").text().trim();

            let sufijo = tipo.substring(0, 2).toUpperCase();



            let partes = $("#codreferencia").val().split("-");

            let base = partes[0] + "-" + partes[1] + "-" + partes[2] + "-AL";



            $("#codreferencia").val(base + "-" + sufijo);

        });



    } else {

        $("#listadoregistros").show();

        $("#formularioregistros").hide();

        $("#btnagregar").show();

    }

}



//Función cancelarform

function cancelarform() {

    limpiar();

    mostrarform(false);

    //location.reload();

}



//Función listar

function listar() {

    tabla = $("#tbllistado").dataTable({

        responsive:true, //Activamos la tabla responsiva

        "aProcessing":true, //Activamos el procesamiento del datatables

        "aServerSide":true, //Paginación y filtrado realizados por el servidor

        dom:'Bfrtip', //Definimos los elementos del control de tabla

        buttons: [

            'copy',

            'excel',

            'csv',

            'pdf',

            'print'

        ],

        "ajax": {

            url: '../ajax/cotizacion.php?op=listar',

            type: "get",

            dataType: "json",

            error: function(e) {

                console.log(e.responseText);

            }

        },

        "bDestroy": true,

        "iDisplayLength": 10, //paginación

        "order": [[0, "desc"]],

		//"scrollX": true, //Se desactivó por que dañaba la tabla

		//"scrollCollapse": false, //Se desactivó por que dañaba la tabla

		"language": {           

            "sProcessing":     "Procesando...",

            "sLengthMenu":     "Mostrar _MENU_ registros",

            "sZeroRecords":    "No se encontraron resultados",

            "sEmptyTable":     "Ningún dato disponible en esta tabla",

            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",

            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",

            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",

            "sInfoPostFix":    "",

            "sSearch":         "Buscar:",

            "sUrl":            "",

            "sInfoThousands":  ",",

            "sLoadingRecords": "Cargando...",

            "oPaginate": {

                "sFirst":    "Primero",

                "sLast":     "Último",

                "sNext":     "Siguiente",

                "sPrevious": "Anterior"

            },

            "oAria": {

                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",

                "sSortDescending": ": Activar para ordenar la columna de manera descendente"

            }            

        }	

    }).DataTable();

    //Función para que el Buscador Datatable realice su función sin importar si se ponen tildes. 

    function removeAccents ( data ) {

        if ( data.normalize ) {

            //Use la API I18n si está disponible para dividir caracteres y acentos, luego elimine los acentos al por mayor. 

            //Tenga en cuenta que utilizamos los datos originales y los nuevos para permitir la búsqueda de cualquiera de las formas.

            return data +' '+ data

                .normalize('NFD')

                .replace(/[\u0300-\u036f]/g, '');

        }

        return data;

    }

    var searchType = jQuery.fn.DataTable.ext.type.search;

    searchType.string = function ( data ) {

        return ! data ?

            '' :

            typeof data === 'string' ?

                removeAccents( data ) :

                data;

    };

    searchType.html = function ( data ) {

        return ! data ?

            '' :

            typeof data === 'string' ?

                removeAccents( data.replace( /<.*?>/g, '' ) ) :

                data;

    };

}

$(document).ready(function () {
    const buscar = getParametro('buscar');

    if (buscar) {
        tabla.search(buscar).draw();
    }
});



//Función para guardar o editar

function guardaryeditar(e) {

    e.preventDefault();

    if (!validarTelefono()) return; // <-- valida teléfono

    if (!validarEmail()) return;    // valida email



    $("#btnGuardar").prop("disabled", true);

    var formData = new FormData($("#formulario")[0]);



    $.ajax({

        url: "../ajax/cotizacion.php?op=guardaryeditar",

        type: "POST",

        data: formData,

        contentType: false,

        processData: false,

        success: function(datos) {

            bootbox.alert({

                message: datos,

                callback: function(){ 

                    location.reload();

                }

            });

            limpiar();

        }

    });

}



function mostrar(idcotizacion) {

    $.post(

        "../ajax/cotizacion.php?op=mostrar", { idcotizacion: idcotizacion },

        function(data, status) {

            data = JSON.parse(data);



            // Primero asignar datos al formulario

            $("#idcotizacion").val(data.IDCOTIZACION);

            $("#codreferencia").val(data.CODREFERENCIA);

            $("input[name='exentacot'][value='" + data.EXENTACOT + "']").prop("checked", true);

            $("#cli_nrc").val(data.NRC);

            $("#cli_empresa").val(data.EMPRESA);

            $("#cli_contacto").val(data.NOMBRECONTACTO);

            $("#cli_telefono").val(data.TELCONTACTO);

            $("#cli_email").val(data.CORREOCONTACTO);

            $("#descripcion_cot").val(data.DESCRIPCIONCOT);



            // Selects

            $("#tipoevento").val(data.IDTIPOEVENTO).selectpicker('refresh');

            $("#tipoalquiler").val(data.IDTIPOALQUILER).selectpicker('refresh');



            $("#tipoalquiler").data("selected", data.IDTIPOALQUILER);



            // Ahora sí mostrar el formulario

            mostrarform(true);

        }

    );

}



//Se agregó validación para elimnar

function eliminar(idcotizacion){

    bootbox.confirm("¿Está Seguro de eliminar esta cotizacion?",function (result) {

        if(result){

            $.post("../ajax/cotizacion.php?op=eliminar",{idcotizacion:idcotizacion},function(e){

                bootbox.alert(e);

                tabla.ajax.reload();

            });

        }

    });

}

// Función para Cambiar estados dinámicamente
function cambiarestado(idcotizacion, estadoActual) {
    $.getJSON("../ajax/cotizacion.php?op=listar_estados", function(estados){
        if(!estados || estados.length === 0){
            alert("No hay estados configurados.");
            return;
        }

        // Construir HTML del modal
        let html = `
            <div class="modal fade" id="modalCambiarEstado" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-white">
                            <h5 class="modal-title">
                                <i class="fa fa-exchange-alt"></i> Cambiar Estado de Cotización
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row">
                                ${estados
                                .filter(item => {

                                        const idEstado = parseInt(item.id);

                                        // Regla especial: Autorizada (3)
                                        if (idEstado === 3) {
                                            return estadoActual == 2 && (IDROL_USUARIO == 3 || IDROL_USUARIO == 1);
                                        }

                                        // Resto de estados normales
                                        return estadosPermitidos(estadoActual).includes(idEstado);
                                    })
                                .map(item => `
                                    <div class="col-md-6 mb-3">
                                        <button class="btn btn-lg btn-block py-3 estado-btn ${getButtonClass(item.id)}" 
                                                data-estado="${item.id}" 
                                                data-nombre="${item.nombre}"
                                                ${estadoActual == item.id ? 'disabled style="opacity:0.6; cursor:not-allowed;"' : ''}
                                                style="font-size: 1.1rem; font-weight: 600; position: relative; width: 100%; margin-bottom: 10px;">
                                            ${getIcono(item.id)} ${item.nombre}
                                            ${estadoActual == item.id ? '<span class="badge badge-warning position-absolute" style="background-color: #3e403fff; font-size: 0.7rem;">Actual</span>' : ''}
                                        </button>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                                <i class="fa fa-times"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .estado-btn {
                    transition: all 0.3s ease;
                    border-width: 2px;
                }
                .estado-btn:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                }
            </style>
        `;

        // Remover modal anterior si existe
        $('#modalCambiarEstado').remove();
        
        // Agregar modal al body
        $('body').append(html);
        
        // Mostrar modal (Bootstrap 4)
        $('#modalCambiarEstado').modal('show');
        
        // Event listener para los botones de estado
        $('.estado-btn').on('click', function(){
            let nuevoEstado = $(this).data('estado');
            let nombreEstado = $(this).data('nombre');
            $('#modalCambiarEstado').modal('hide');
            confirmarCambioEstado(idcotizacion, nuevoEstado, nombreEstado);
        });
        
        // Limpiar modal al cerrarse
        $('#modalCambiarEstado').on('hidden.bs.modal', function () {
            $(this).remove();
        });
    });
}

// Función de confirmación
function confirmarCambioEstado(idcotizacion, nuevoEstado, nombreEstado){

    // Si es estado 2, agregamos advertencia dentro del modal
    let mensajeExtra = "";
    if (nuevoEstado == 2) {
        mensajeExtra = `
            <div class="alert alert-info mt-3" style="font-size: 16px;">
                <i class="fa fa-info-circle"></i> No olvide registrar el NRC del cliente
            </div>
        `;
    }

    let htmlConfirm = `
        <div class="modal fade" id="modalConfirmar" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">
                            <i class="fa fa-exclamation-triangle"></i> Confirmar Cambio
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center p-4">
                        <h5>¿Confirmar cambio a estado:</h5>
                        <h4 class="text-primary mt-3"><strong>${nombreEstado}</strong>?</h4>

                        ${mensajeExtra}
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary btn-lg px-5" data-dismiss="modal">
                            <i class="fa fa-times"></i> Cancelar
                        </button>

                        <button type="button" class="btn btn-success btn-lg px-5" id="btnConfirmarEstado">
                            <i class="fa fa-check"></i> Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    $('#modalConfirmar').remove();
    $('body').append(htmlConfirm);

    $('#modalConfirmar').modal('show');

    // Solo un evento, sin duplicaciones
    $('#btnConfirmarEstado').one('click', function(){
        
        // Al cerrarse ejecuta el cambio de estado
        $('#modalConfirmar').one('hidden.bs.modal', function () {

            // Estados que requieren observación: 5, 6, 7
            if ([5,6,7].includes(parseInt(nuevoEstado))) {
                pedirObservacionYActualizar(idcotizacion, nuevoEstado, nombreEstado);
            } else {
                actualizarEstado(idcotizacion, nuevoEstado, null, null);
            }

            $(this).remove();
        });
        $('#modalConfirmar').modal('hide');
    });
}

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo = "success"){
    let htmlAlerta = `
        <div class="modal fade" id="modalAlerta" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-${tipo} text-white">
                        <h5 class="modal-title">
                            <i class="fa fa-info-circle"></i> Información
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <h5>${mensaje}</h5>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-${tipo} btn-lg px-5" data-dismiss="modal">
                            Entendido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#modalAlerta').remove();
    $('body').append(htmlAlerta);
    
    $('#modalAlerta').modal('show');
    
    $('#modalAlerta').on('hidden.bs.modal', function () {
        $(this).remove();
    });
}

// Función AJAX centralizada
function actualizarEstado(idcotizacion, nuevoEstado, idobservacion, observacion_texto){
    $.post("../ajax/cotizacion.php?op=cambiarestado", 
        { 
          idcotizacion: idcotizacion, 
          estado: nuevoEstado,
          idobservacion: idobservacion,
          observacion_texto: observacion_texto
        }, 
        function(e) {
            console.log("Respuesta servidor:", e);
            mostrarAlerta(e, "success");
            tabla.ajax.reload();
        }
    ).fail(function(xhr){
        console.error("ERROR AJAX:", xhr.responseText);
        mostrarAlerta("Error al actualizar estado.", "danger");
    });
}

function pedirObservacionYActualizar(idcotizacion, nuevoEstado, nombreEstado){

    let htmlObs = `
      <div class="modal fade" id="modalObservacion" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title">
                <i class="fa fa-comment"></i> Observación requerida
              </h5>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body p-4">
              <p>Para cambiar el estado a <strong>${nombreEstado}</strong>, indique la razón:</p>

              <label class="font-weight-bold mb-1">Motivo</label>
              <select id="selObservacion" class="form-control">
                <option value="">Cargando opciones...</option>
              </select>

              <div id="wrapOtros" style="display:none; margin-top:12px;">
                <label class="font-weight-bold mb-1">Detalle (Otros)</label>
                <textarea id="txtObsOtros" class="form-control" rows="3" placeholder="Escriba la razón..."></textarea>
                <small class="text-muted">Obligatorio si selecciona “Otros”.</small>
              </div>

              <small class="text-muted d-block mt-2">Debe seleccionar un motivo.</small>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fa fa-times"></i> Cancelar
              </button>

              <button type="button" class="btn btn-danger" id="btnGuardarObs">
                <i class="fa fa-save"></i> Guardar y cambiar estado
              </button>
            </div>

          </div>
        </div>
      </div>
    `;

    $('#modalObservacion').remove();
    $('body').append(htmlObs);
    $('#modalObservacion').modal('show');

    // 1) Cargar catálogo
    $.getJSON("../ajax/cotizacion.php?op=listar_observaciones", function(lista){

        if(!Array.isArray(lista) || lista.length === 0){
            $("#selObservacion").html('<option value="">No hay observaciones configuradas</option>');
            return;
        }

        let opts = `<option value="">-- Seleccione un motivo --</option>`;
        lista.forEach(o => {
            // guardamos flag otros en data-otros
            opts += `<option value="${o.id}" data-otros="${o.otros}">${o.nombre}</option>`;
        });

        $("#selObservacion").html(opts);
    }).fail(function(xhr){
        console.error("ERROR cargar observaciones:", xhr.responseText);
        $("#selObservacion").html('<option value="">Error cargando observaciones</option>');
    });

    // 2) Mostrar/ocultar "Otros"
    $(document).off('change', '#selObservacion').on('change', '#selObservacion', function(){
        const sel = $(this).find('option:selected');
        const esOtros = parseInt(sel.data('otros') || 0) === 1;

        if(esOtros){
            $("#wrapOtros").slideDown(120);
            $("#txtObsOtros").focus();
        } else {
            $("#wrapOtros").slideUp(120);
            $("#txtObsOtros").val('');
        }
    });

    // 3) Guardar
    $('#btnGuardarObs').off('click').one('click', function(){

        const sel = $("#selObservacion").find('option:selected');
        const idobs = parseInt($("#selObservacion").val() || 0);
        const esOtros = parseInt(sel.data('otros') || 0) === 1;

        if(!idobs){
            alert("Debe seleccionar un motivo.");
            return;
        }

        let textoOtros = null;
        if(esOtros){
            textoOtros = ($("#txtObsOtros").val() || "").trim();
            if(textoOtros.length === 0){
                alert("Debe escribir el detalle cuando selecciona 'Otros'.");
                return;
            }
        }

        // cerrar y luego actualizar
        $('#modalObservacion').one('hidden.bs.modal', function(){
            actualizarEstado(idcotizacion, nuevoEstado, idobs, textoOtros);
            $(this).remove();
        });

        $('#modalObservacion').modal('hide');
    });

    $('#modalObservacion').on('hidden.bs.modal', function(){
        $(this).remove();
    });
}

// Clases de botones de Bootstrap
function getButtonClass(estadoId){
    estadoId = parseInt(estadoId);
    switch(estadoId){
        case 1: return 'btn-info';
        case 2: return 'btn-success';
        case 3: return 'btn-primary';
        case 8: return 'btn-warning';   // <-- Enviada
        case 4: return 'btn-success';
        case 5: return 'btn-danger';
        case 6: return 'btn-warning';
        case 7: return 'btn-dark';
        default: return 'btn-secondary';
    }
}

// Iconos para cada estado
function getIcono(estadoId){
    estadoId = parseInt(estadoId);
    switch(estadoId){
        case 1: return '<i class="fa fa-history"></i>';
        case 2: return '<i class="fa fa-check-circle"></i>';
        case 3: return '<i class="fa fa-thumbs-up"></i>';
        case 8: return '<i class="fa fa-paper-plane"></i>'; // <-- Enviada
        case 4: return '<i class="fa fa-check"></i>';
        case 5: return '<i class="fa fa-times-circle"></i>';
        case 6: return '<i class="fa fa-exclamation-triangle"></i>';
        case 7: return '<i class="fa fa-ban"></i>';
        default: return '<i class="fa fa-question-circle"></i>';
    }
}

function estadosPermitidos(estadoActual) {
    estadoActual = parseInt(estadoActual);

    switch(estadoActual){
        case 1:
        return [1, 2];            // En proceso -> Finalizada

        case 2:
        return [1, 3];            // Finalizada -> Autorizada (según tu regla especial de rol)

        case 3:
        return [1, 8];            // Autorizada -> Enviada SOLAMENTE (y reset a 1)

        case 8:
        return [1, 4, 5, 6, 7];   // Enviada -> opciones del cliente / suspendida (como estaba)

        case 4:
        case 5:
        case 6:
        case 7:
        return [1];               // Solo reiniciar flujo

        default:
        return [1];
    }
}


function agregardetalle(idcotizacion) {

    // Redirige a la vista detalle enviando el id como query string

    window.location.href = "cotizacion_detalle.php?idcotizacionnew=" + idcotizacion;

}



function validarTelefono() {
    const telefono = $("#cli_telefono").val().trim();
    const limpio = telefono.replace(/[\s\-\(\)\.]/g, '');
    const regex = /^\+[1-9]\d{6,14}$/;

    if (!regex.test(limpio)) {
        alert("El teléfono debe incluir código de país.\nEjemplo: +50371234567");
        return false;
    }

    return true;
}

$("#cli_telefono").on("input", function() {
    const telefono = $(this).val().trim();
    const limpio = telefono.replace(/[\s\-\(\)\.]/g, '');
    const regex = /^\+[1-9]\d{6,14}$/;

    if (telefono === "") {
        $("#telefonoHelp").hide();
        return;
    }

    if (!regex.test(limpio)) {
        $("#telefonoHelp").show();
    } else {
        $("#telefonoHelp").hide();
    }
});

$("#cli_telefono").on("focus", function() {
    if ($(this).val().trim() === "") {
        $(this).val("+");
    }
});


function validarEmail() {

    const email = $("#cli_email").val().trim();

    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(?:\.[a-zA-Z]{2,})?$/;



    if (!regex.test(email)) {

        alert("Correo inválido. Ejemplo: usuario@dominio.com o usuario@dominio.edu.sv");

        return false;

    }

    return true;

}



function vercotizacion(idcotizacion) {

    const ventana = window.open("", "_blank");

    $.ajax({
        url: "../ajax/cotizacion.php?op=vercotizacion",
        type: "POST",
        dataType: "json",
        cache: false,

        data: {
            idcotizacion: idcotizacion,
            _: Date.now()
        },

        success: function(result) {

            if (!result.success || !result.url) {

                if (ventana) {
                    ventana.close();
                }

                alert(
                    result.message ||
                    "No se pudo generar la cotización."
                );

                return;
            }

            const separador = result.url.includes("?")
                ? "&"
                : "?";

            const urlActualizada =
                result.url +
                separador +
                "_=" +
                Date.now();

            console.log(
                "Abriendo cotización:",
                urlActualizada
            );

            if (ventana) {
                ventana.location.replace(urlActualizada);
            } else {
                window.location.href = urlActualizada;
            }
        },

        error: function(xhr, status, error) {

            if (ventana) {
                ventana.close();
            }

            console.error(
                "Error al abrir la cotización:",
                {
                    status: status,
                    error: error,
                    respuesta: xhr.responseText
                }
            );

            alert(
                "Ocurrió un error al abrir la cotización."
            );
        }
    });
}

function verTimeline(idcotizacion){

  $.getJSON("../ajax/cotizacion.php?op=timeline&idcotizacion=" + idcotizacion, function(resp){

    if(!resp || !resp.success){
      mostrarAlerta(resp && resp.message ? resp.message : "No se pudo cargar el pipeline.", "danger");
      return;
    }

    let items = resp.data || [];
    if(items.length === 0){
      mostrarAlerta("Esta cotización no tiene historial de estados.", "info");
      return;
    }

    /* ==========================
       METADATA DE ESTADOS
    ========================== */
    const estadoMeta = (id)=>{
      id = parseInt(id);
      switch(id){
        case 1: return {cls:'tl-info', icon:'fa-history'};
        case 2: return {cls:'tl-success', icon:'fa-check-circle'};
        case 3: return {cls:'tl-primary', icon:'fa-thumbs-up'};
        case 8: return {cls:'tl-warning', icon:'fa-paper-plane'};
        case 4: return {cls:'tl-success', icon:'fa-check'};
        case 5: return {cls:'tl-danger', icon:'fa-times-circle'};
        case 6: return {cls:'tl-warning', icon:'fa-exclamation-triangle'};
        case 7: return {cls:'tl-dark', icon:'fa-ban'};
        default:return {cls:'tl-secondary', icon:'fa-question'};
      }
    };

    /* ==========================
       FECHAS / FORMATO
    ========================== */
    const parseDT = (s)=>{
      if(!s) return null;
      if(typeof s === 'string' && s.includes(" ") && !s.includes("T")){
        s = s.replace(" ","T");
      }
      const d = new Date(s);
      return isNaN(d.getTime()) ? null : d;
    };

    const pad=(n)=> n<10 ? "0"+n : ""+n;

    const fmtDate = (s)=>{
      const d = parseDT(s);
      if(!d) return s || "";
      return `${pad(d.getDate())}/${pad(d.getMonth()+1)}/${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
    };

    const fmtDelta=(ms)=>{
      if(ms == null || ms < 0) return "";
      const totalMin = Math.round(ms/60000);
      const days = Math.floor(totalMin/(60*24));
      const hrs  = Math.floor((totalMin%(60*24))/60);
      const mins = totalMin%60;

      if(days>0) return `+${days}d ${hrs}h`;
      if(hrs>0)  return `+${hrs}h ${mins}m`;
      return `+${mins}m`;
    };

    /* ==========================
       PREP / ORDEN
    ========================== */
    let parsed = items.map(x=> ({...x, _dt: parseDT(x.FECHAEVENTO)}));

    // total pipeline
    const first = parsed[0]._dt;
    const last  = parsed[parsed.length-1]._dt;
    const total = (first && last) ? fmtDelta(last - first).replace("+","") : "";

    // observación (si existe) - viene repetida, tomamos la primera no vacía
    const obs = (parsed.find(x => x.OBSERVACIONES && (""+x.OBSERVACIONES).trim() !== "") || {}).OBSERVACIONES;
    const estadoFinal = parseInt(parsed[parsed.length-1].ESTADOCOT);
    const requiereObs = [5,6,7].includes(estadoFinal);

    /* ==========================
       BREAKDOWN POR TRANSICIÓN
    ========================== */
    let transRows = [];
    for(let i=1;i<parsed.length;i++){
      const a = parsed[i-1];
      const b = parsed[i];
      if(a._dt && b._dt){
        const delta = fmtDelta(b._dt - a._dt).replace("+","");
        transRows.push(`
          <tr>
            <td>${a.NOMBREESTADOCOT}</td>
            <td style="text-align:center;">→</td>
            <td>${b.NOMBREESTADOCOT}</td>
            <td style="text-align:right;"><strong>${delta}</strong></td>
          </tr>
        `);
      }
    }

    const breakdownHTML = transRows.length ? `
      <div class="tl-break card">
        <div class="tl-break-title">
          <i class="fa fa-stopwatch"></i> Tiempos entre etapas
        </div>
        <div class="table-responsive">
          <table class="table table-sm mb-0">
            <thead>
              <tr>
                <th>Desde</th>
                <th></th>
                <th>Hasta</th>
                <th style="text-align:right;">Tiempo</th>
              </tr>
            </thead>
            <tbody>
              ${transRows.join("")}
            </tbody>
          </table>
        </div>
      </div>
    ` : "";

    /* ==========================
       TIMELINE VISUAL
    ========================== */
    let htmlItems = parsed.map((x,idx)=>{
      const meta = estadoMeta(x.ESTADOCOT);
      const prev = idx>0 ? parsed[idx-1]._dt : null;
      const delta = (prev && x._dt) ? fmtDelta(x._dt - prev) : "";

      // Mostrar observación SOLO en el evento final si es estado negativo
      const mostrarObsAqui = (idx === parsed.length-1 && requiereObs && obs);

      return `
        <div class="tl-item">

          <div class="tl-marker ${meta.cls}">
            <i class="fa ${meta.icon}"></i>
          </div>

          <div class="tl-card">
            <div class="tl-top">
              <div class="tl-title">
                ${x.NOMBREESTADOCOT}
                ${delta ? `<span class="tl-time">${delta}</span>` : ``}
              </div>
            </div>

            <div class="tl-sub">
              <span><i class="fa fa-calendar"></i> ${fmtDate(x.FECHAEVENTO)}</span>
              ${x.USUARIO ? `<span class="tl-user"><i class="fa fa-user"></i> ${x.USUARIO}</span>` : ``}
            </div>

            ${mostrarObsAqui ? `
              <div class="tl-obs">
                <div class="tl-obs-title"><i class="fa fa-comment"></i> Observación</div>
                <div class="tl-obs-body">${escapeHtml(obs)}</div>
              </div>
            ` : ""}

          </div>
        </div>
      `;
    }).join("");

    const lastMeta = estadoMeta(parsed[parsed.length-1].ESTADOCOT);

    const resumen = `
      <div class="tl-summary">
        <div>
          <div class="tl-estado">
            Estado actual:
            <span class="tl-badge ${lastMeta.cls}">
              <i class="fa ${lastMeta.icon}"></i> ${parsed[parsed.length-1].NOMBREESTADOCOT}
            </span>
          </div>
          <div class="tl-count">Eventos registrados: <b>${items.length}</b></div>
        </div>

        <div class="tl-total">
          <div>Duración total</div>
          <strong>${total || "-"}</strong>
        </div>
      </div>
    `;

    let htmlModal = `
      <div class="modal fade" id="modalTimeline">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header tl-header">
              <div>
                <h5 class="mb-0"><i class="fa fa-stream"></i> Pipeline de estados</h5>
                <small class="text-white-50">Historial de estados de la cotización</small>
              </div>
              <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
              ${resumen}
              ${breakdownHTML}
              <div class="tl-wrap">${htmlItems}</div>
            </div>

            <div class="modal-footer">
              <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>

          </div>
        </div>
      </div>

      <style>
        #modalTimeline .tl-header{ background:#1f2937; color:#fff; }
        #modalTimeline .tl-summary{
          display:flex; justify-content:space-between; align-items:center;
          margin-bottom:16px; padding:14px; border-radius:12px; background:#f7f7f7;
        }
        #modalTimeline .tl-estado{ font-weight:800; font-size:1rem; }
        #modalTimeline .tl-count{ font-size:13px; color:#666; margin-top:4px; }
        #modalTimeline .tl-total{ text-align:right; }
        #modalTimeline .tl-total strong{ font-size:22px; }

        #modalTimeline .tl-badge{
          display:inline-block; padding:6px 10px; border-radius:999px;
          color:#fff; font-size:.85rem; font-weight:900; margin-left:8px;
        }

        #modalTimeline .tl-break.card{
          border:0; border-radius:12px;
          box-shadow:0 3px 12px rgba(0,0,0,.08);
          margin-bottom:16px;
        }
        #modalTimeline .tl-break-title{
          padding:12px 14px;
          font-weight:900;
          border-bottom:1px solid rgba(0,0,0,.06);
        }
        #modalTimeline .tl-wrap{ position:relative; padding-left:42px; margin-top:10px; }
        #modalTimeline .tl-wrap:before{
          content:''; position:absolute; left:19px; top:4px; bottom:4px;
          width:2px; background:rgba(0,0,0,.12);
        }
        #modalTimeline .tl-item{ position:relative; margin-bottom:18px; }
        #modalTimeline .tl-marker{
          position:absolute; left:-42px; top:0;
          width:34px; height:34px; border-radius:50%;
          display:flex; align-items:center; justify-content:center;
          color:#fff; font-size:13px; box-shadow:0 6px 18px rgba(0,0,0,.16);
        }
        #modalTimeline .tl-card{
          background:#fff; padding:12px 14px; border-radius:12px;
          box-shadow:0 3px 12px rgba(0,0,0,.08);
        }
        #modalTimeline .tl-title{ font-weight:900; font-size:1.02rem; }
        #modalTimeline .tl-time{
          background:#eee; border-radius:999px; padding:2px 8px;
          font-size:11px; margin-left:8px; font-weight:800;
        }
        #modalTimeline .tl-sub{ font-size:12px; color:#777; margin-top:6px; }
        #modalTimeline .tl-user{ margin-left:10px; }

        #modalTimeline .tl-obs{
          margin-top:10px;
          border:1px solid rgba(0,0,0,.08);
          border-radius:10px;
          padding:10px 12px;
          background:#fff7f7;
        }
        #modalTimeline .tl-obs-title{
          font-weight:900;
          margin-bottom:6px;
          color:#a10000;
        }
        #modalTimeline .tl-obs-body{
          font-size:13px;
          color:#333;
          white-space: pre-wrap;
        }

        /* Colores aislados del theme */
        #modalTimeline .tl-info{ background:#17a2b8; }
        #modalTimeline .tl-success{ background:#28a745; }
        #modalTimeline .tl-primary{ background:#007bff; }
        #modalTimeline .tl-warning{ background:#f59e0b; color:#111; }
        #modalTimeline .tl-danger{ background:#dc3545; }
        #modalTimeline .tl-dark{ background:#374151; }
        #modalTimeline .tl-secondary{ background:#6c757d; }
        #modalTimeline .tl-badge.tl-warning{ color:#111; }
      </style>
    `;

    $('#modalTimeline').remove();
    $('body').append(htmlModal);
    $('#modalTimeline').modal('show');
    $('#modalTimeline').on('hidden.bs.modal', function(){ $(this).remove(); });

  });

  // Evitar XSS si una observación tiene caracteres HTML
  function escapeHtml(str){
    if(str == null) return '';
    return String(str)
      .replaceAll('&','&amp;')
      .replaceAll('<','&lt;')
      .replaceAll('>','&gt;')
      .replaceAll('"','&quot;')
      .replaceAll("'","&#039;");
  }
}



init();