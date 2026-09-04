var tabla;

//Función que se ejecuta al inicio

function init(){

    mostrarform(false);

    listar();

    $("#formulario").on("submit",function(e)

    {

	 guardaryeditar(e);

    });



    //$("#btnVistaPrevia").hide();



    //Cargamos los items para las cotizaciones

    $.post("../ajax/cotizacion_detalle.php?op=selectCotizacion", function(r) {

        $("#idcotizacion").html(r);

        $("#idcotizacion").selectpicker('refresh');

        $("#bsc_cotizacion").html(r);

        $("#bsc_cotizacion").selectpicker('refresh');

        

        // 👇 MOVER ESTA LÓGICA AQUÍ (después de cargar las opciones)

        var idcotizacionUrl = getParameterByName("idcotizacionnew");

        if (idcotizacionUrl) {

            mostrarform(true);

            console.log("ID desde URL:", idcotizacionUrl);

            

            // Usar .val() en lugar de .html()

            $("#idcotizacion").val(idcotizacionUrl);

            $("#idcotizacion").selectpicker('refresh');

            

            // Verificar si se estableció correctamente

            console.log("Valor seleccionado:", $("#idcotizacion").val());

        }

    });

    cargarTiposInsumos();

    $('#horainicot').timepicker({

        timeFormat: 'H:i:s',

        minTime: '05:00', // 11:45:00 AM,

        maxHour: 20,

        maxMinutes: 30,

        interval: 30 // 15 minutes

    });

    $('#horafincot').timepicker({

       timeFormat: 'H:i:s',

        minTime: '05:00:00', // 11:45:00 AM,

        maxHour: 20,

        maxMinutes: 30,

        interval: 30 // 15 minutes

    });



    //Mostrar el total de horas

    $("#horafincot").change(function() {

        var horainicial = document.getElementById("horainicot").value;

        var horafinal = document.getElementById("horafincot").value;

        

        //le quitamos los dos puntos del formato de hora

        var hi = horainicial.split(':');

        var hf = horafinal.split(':');



        // Convertimos hora en segundos

        var segundosiniciales = (+hi[0]) * 60 * 60 + (+hi[1]) * 60 + (+hi[2]); 

        var segundosfinales = (+hf[0]) * 60 * 60 + (+hf[1]) * 60 + (+hf[2]); 



        //Sumamos todos los segundos

        var totalsegundos = segundosfinales-segundosiniciales;



        console.log(segundosiniciales);

        console.log(segundosfinales);

        console.log(totalsegundos);



        var hour = Math.floor(totalsegundos / 3600);

        hour = (hour < 10)? '0' + hour : hour;

        var minute = Math.floor((totalsegundos / 60) % 60);

        minute = (minute < 10)? '0' + minute : minute;

        var second = totalsegundos % 60;

        second = (second < 10)? '0' + second : second;

        var duracion = hour + ':' + minute + ':' + second;

        

        document.getElementById('duracion').value = duracion;

    });



    //Mostrar el total de horas

    $("#horainicot").change(function() {

        var horainicial = document.getElementById("horainicot").value;

        var horafinal = document.getElementById("horafincot").value;

        

        //le quitamos los dos puntos del formato de hora

        var hi = horainicial.split(':');

        var hf = horafinal.split(':');



        // Convertimos hora en segundos

        var segundosiniciales = (+hi[0]) * 60 * 60 + (+hi[1]) * 60 + (+hi[2]); 

        var segundosfinales = (+hf[0]) * 60 * 60 + (+hf[1]) * 60 + (+hf[2]); 



        //Sumamos todos los segundos

        var totalsegundos = segundosfinales-segundosiniciales;



        console.log(segundosiniciales);

        console.log(segundosfinales);

        console.log(totalsegundos);



        var hour = Math.floor(totalsegundos / 3600);

        hour = (hour < 10)? '0' + hour : hour;

        var minute = Math.floor((totalsegundos / 60) % 60);

        minute = (minute < 10)? '0' + minute : minute;

        var second = totalsegundos % 60;

        second = (second < 10)? '0' + second : second;

        var duracion = hour + ':' + minute + ':' + second;

        

        document.getElementById('duracion').value = duracion;

    });

}



//Función limpiar

function limpiar(){

  $("#idcotizacion").focus();  

  $("#iddetallecot").val("");

  $("#idcotizacion").val("");

  $("#idcotizacion").selectpicker('refresh');

  $("#cantpar").val("");

  $("#fechacot").val("");

  $("#horainicot").val("");

  $("#horafincot").val("");

  $("#duracion").val("");

  //Buscador

  $("#bsc_fecha").val("");

  $("#bsc_cotizacion").val("");

}



//Función mostrar formulario

function mostrarform(flag) {

    limpiar();

    if (flag) {

        $("#listadoregistros").hide();

        $("#formularioregistros").show();

        $("#btnGuardar").prop("disabled",false);

        $("#btnagregar").hide()



        var iddetallecot = $("#iddetallecot").val();

        if (iddetallecot && iddetallecot != "") {

            $("#seccion_insumos").show();

        } else {

            $("#seccion_insumos").hide();

        }

    }else{

        $("#listadoregistros").show();

        $("#formularioregistros").hide(); 

        $("#btnagregar").show() 

    }

}



//Función cancelarform

function cancelarform() {

    limpiar();

    mostrarform(false);

}



//Función listar

function listar() {

    //Se agregan fecha inicio y fin

    var fecha    = $("#bsc_fecha").val();

	var cotizacion   = $("#bsc_cotizacion").val();

    tabla=$("#tbllistado").dataTable({

        responsive: true,

        "aProcessing":true,//Activamos el procesamiento del datatables

        "aServerSide":true,//Paginación y filtrado realizados por el servidor

        dom:'Bfrtip',//Definimos los elementos del control de tabla

        buttons:[

            'copy',

            'excel',

            'pdf',

            'print'

        ],

        "ajax":{

                url:'../ajax/cotizacion_detalle.php?op=listar',

                type:"get",

                data:{fecha:fecha,cotizacion:cotizacion},

                dataType:"json",

                error:function(e){

                    console.log(e.responseText);

                }

        },

        // Configuración de las columnas y sus anchos

        columnDefs: [

            { width: '5%', targets: 0 }, // #

            { width: '30%', targets: 1 }, // Codigo Referencia

            { width: '15%', targets: 2 }, // Fecha

            { width: '10%', targets: 3 }, // Hora inicio

            { width: '10%', targets: 4 }, // Hora fin

            { width: '20%', targets: 5 }, // Cantidad Participantes

            { width: '10%', targets: 6 }, // Estado

            { width: '10%', targets: 7 }, // Opciones

        ],

        "bDestroy":true,

        "iDisplayLength":5,//paginación

        "order":[[0,"desc"]], // Ordenar(columna,orden) 

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



//Función para guardar o editar

function guardaryeditar(e){

  e.preventDefault();//No se activará la acción predeterminada del evento

  var formData = new FormData($("#formulario")[0]);

    //validar hora
    var horaini = document.getElementById("horainicot").value;
    var horafin = document.getElementById("horafincot").value;
    var hora1 = (horaini).split(":");
    var hora2 = (horafin).split(":");
    var t1 = '00';
    var t2 = '00';
    t1 = parseInt(hora1[0]) * 100 + parseInt(hora1[1]);
    t2 = parseInt(hora2[0]) * 100 + parseInt(hora2[1]);
    console.log(t1);
    console.log(t2);
    console.log(t2-t1);

    //Tiempo resultante es el resultado entre el tiempo 2 menos el tiempo 1
    var tr = t2 - t1; 

    if (tr < 100) {

        bootbox.alert('La jornada no puede durar menos de una hora.');
        document.getElementById("horafincot").style.borderColor = "red";

    } else {

        $.ajax({

            url: "../ajax/cotizacion_detalle.php?op=guardaryeditar",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function(datos) {

                bootbox.alert(datos);
                mostrarform(false);
                tabla.ajax.reload();

            }
        });

        limpiar();
        document.getElementById("horafincot").style.borderColor = "";
    }
}



function mostrar(iddetallecot){

    $.post("../ajax/cotizacion_detalle.php?op=mostrar",{iddetallecot:iddetallecot},function(data,status)
    {
        data = JSON.parse(data);
        mostrarform(true);

        $("#iddetallecot").val(data.IDDETALLECOT);
		$("#idcotizacion").val(data.IDCOTIZACION);
        $("#idcotizacion").selectpicker('refresh');
		$("#cantpar").val(data.CANTIDADCOT);
        $("#fechacot").val(data.FECHACOT);
        $("#horainicot").val(data.HORAINICOT);          
        $("#horafincot").val(data.HORAFINCOT);
        $("#duracion").val(data.DURACIONCOT);

        // Mostrar la sección de insumos solo si hay detalle cot
        mostrarSeccionInsumos();
    });
}



function mostrarSeccionInsumos() {

    var iddetallecot = $("#iddetallecot").val();

    if (iddetallecot && iddetallecot != "") {

        $("#seccion_insumos").show();

        document.getElementById('btn_preview').style.display = 'block';

    } else {

        $("#seccion_insumos").hide();

        document.getElementById('btnVistaPrevia').style.display = 'inline-block';

    }

}



//Agregamos validación para eliminar

/*function eliminar(iddetallecot){

    bootbox.confirm("¿Está Seguro de eliminar el siguiente detalle?",function (result) {

        if(result){

            $.post("../ajax/cotizacion_detalle.php?op=eliminar",{iddetallecot:iddetallecot},function(e){

                bootbox.alert(e);

                tabla.ajax.reload();

            });

        }

    });

}*/



function desactivar(iddetallecot) {

    bootbox.confirm("Está Seguro de desactivar el siguiente detalle", function(result) {

        if (result) {

            $.post("../ajax/cotizacion_detalle.php?op=desactivar", { iddetallecot: iddetallecot }, function(e) {

                bootbox.alert(e);

                tabla.ajax.reload();

            });

        }

    });

}



//Función para activar registros

function activar(iddetallecot) {

    bootbox.confirm("Está Seguro de activar el siguiente detalle", function(result) {

        if (result) {

            $.post("../ajax/cotizacion_detalle.php?op=activar", { iddetallecot: iddetallecot }, function(e) {

                bootbox.alert(e);

                tabla.ajax.reload();

            });

        }

    });

}



// Variable global para almacenar el tipo actual

let tipoInsumoActual = '';



// Cargar tipos de insumos

function cargarTiposInsumos() {

    $.post("../ajax/cotizacion_detalle.php?op=listarTiposInsumos", function(data) {

        data = JSON.parse(data);

        var html = "";

        data.forEach(function(item) {

            html += `<tr onclick="cargarInsumos(${item.id}, '${item.nombre}')">

                        <td>${item.nombre}</td>

                     </tr>`;

        });

        $("#tablaTiposInsumos tbody").html(html);

    });

}

// Cargar insumos por tipo - FUNCIÓN ACTUALIZADA Y CORREGIDA
function cargarInsumos(idtipo, nombreTipo = '') {
    let iddetallecot = $("#iddetallecot").val(); // viene del form al editar
    tipoInsumoActual = nombreTipo;

    $.post("../ajax/cotizacion_detalle.php?op=listarInsumosPorTipo",
        { idtipoinsumo: idtipo, iddetallecot: iddetallecot },
        function(res) {
            res = JSON.parse(res);

            // Construir encabezados dinámicos
            let thead = "<tr>";
            res.columns.forEach(c => { thead += `<th>${c}</th>`; });
            thead += "<th>Cantidad</th><th>Subtotal</th></tr>";
            $("#tablaInsumos thead").html(thead);

            // Construir filas
            let tbody = "";
            res.data.forEach((row, index) => {
                tbody += "<tr>";

                // Detectar columnas de precio dinámicamente
                let columnasPrecio = res.columns.filter(col => col.toLowerCase().includes("precio"));
                let preciosVacios = columnasPrecio.filter(col => !row[col] || row[col] === "" || row[col] === null);

                // Detectar precios de 8h / 4h
                let tienePrecio8H = row["Precio 8 horas"] !== null && row["Precio 8 horas"] !== "";
                let tienePrecio4H = row["Precio 4 horas"] !== null && row["Precio 4 horas"] !== "";
                let tienePrecioSala = row["Precio Sala Remodelada"] !== null && row["Precio Sala Remodelada"] !== "";

                // Ahora radios solo si los 3 existen
                let mostrarRadios = (tienePrecio8H && tienePrecio4H && tienePrecioSala);

                res.columns.forEach(c => {
                    //CASO 1: Algún precio es null → mostrar solo 1 input manual
                    if (preciosVacios.length > 0 && c === columnasPrecio[0]) {
                        tbody += `
                            <td colspan="${columnasPrecio.length}">
                                <input type="number" class="form-control precio-manual"
                                    data-fila="${index}"
                                    data-idinsumo="${row.ID}"
                                    placeholder="Ingrese precio">
                            </td>`;
                        return;
                    }

                    // Saltar otras columnas de precio si ya mostramos input manual
                    if (preciosVacios.length > 0 && columnasPrecio.includes(c)) {
                        return;
                    }

                    //CASO 2: Mostrar radios cuando todos los precios existen
                    if (columnasPrecio.includes(c) && mostrarRadios) {
                        let valor = row[c];
                        tbody += `
                            <td class="precio" data-col="${c}" data-precio="${valor}">
                                <label style="cursor: pointer;">
                                    <input type="radio" name="precio_${row.ID}" value="${valor}"
                                        class="radio-precio"
                                        data-fila="${index}"
                                        data-idinsumo="${row.ID}"
                                        data-tipo="${c}">
                                    $${valor}
                                </label>
                            </td>`;
                        return;
                    }

                    //CASO 3: Precios normales con valor (sin radios)
                    if (columnasPrecio.includes(c) && row[c] !== null && row[c] !== "") {
                        tbody += `<td class="precio" data-precio="${row[c]}">$${row[c]}</td>`;
                        return;
                    }

                    //CASO 4: Columnas normales
                    tbody += `<td>${row[c] ?? ""}</td>`;
                });

                // Control de habilitación
                let cantidadValue = parseInt(row.CANTIDAD) || 0;
                let cantidadAtributos = mostrarRadios 
                    ? 'disabled title="Seleccione un precio primero"' 
                    : '';

                tbody += `<td>
                    <input type="number" min="0" value="${cantidadValue}" 
                        class="form-control cantidad"
                        data-idinsumo="${row.ID}" 
                        data-fila="${index}" 
                        ${cantidadAtributos}>
                </td>`;

                tbody += `<td class="total">$${(parseFloat(row.TOTAL) || 0).toFixed(2)}</td>`;
                tbody += "</tr>";
            });

            $("#tablaInsumos tbody").html(tbody);

            //CORRECCIÓN: habilitar insumos sin radios
            $("#tablaInsumos tbody tr").each(function () {
                const fila = $(this);
                const tieneRadios = fila.find('input[type="radio"]').length > 0;
                const inputCantidad = fila.find('.cantidad');

                if (!tieneRadios) {
                    inputCantidad.prop("disabled", false);
                } else {
                    inputCantidad.prop("disabled", true);
                }
            });

            //CORRECCIÓN: evento radio que habilita el input cantidad correcto
            $(".radio-precio").on("change", function() {
                let fila = $(this).closest("tr");
                fila.find(".cantidad").prop("disabled", false)
                                      .attr("title", "Ingrese la cantidad");
            });

            // Agregar eventos después de crear la tabla
            agregarEventosRadioButtons();
            agregarEventosCantidad();
        }
    );
}



// Agregar eventos para radio buttons - NUEVA FUNCIÓN

function agregarEventosRadioButtons() {

    $(document).off('change', '.radio-precio').on('change', '.radio-precio', function() {

        let fila = $(this).data('fila');
        let idinsumo = $(this).data('idinsumo');
        let precio = parseFloat($(this).val());
        let tipo = $(this).data('tipo');

        // Habilitar el input de cantidad para esta fila
        let inputCantidad = $(`.cantidad[data-fila="${fila}"]`);
        inputCantidad.prop('disabled', false);

        // Calcular total si ya hay cantidad
        let cantidad = parseInt(inputCantidad.val()) || 0;
        let total = precio * cantidad;

        // Actualizar el total en la interfaz
        $(inputCantidad).closest('tr').find('.total').text('$' + total.toFixed(2));

        // Guardar automáticamente si hay cantidad > 0
        if (cantidad > 0) {
            guardarInsumo(idinsumo, cantidad, precio, total);
        }

        console.log(`Precio seleccionado: ${tipo} = $${precio} para insumo ${idinsumo}`);
    });
}



// Agregar eventos para cantidad - FUNCIÓN ACTUALIZADA
function agregarEventosCantidad() {

    // Evento para cantidad (tu código original)
    $(document).off('input', '.cantidad').on('input', '.cantidad', function() {

        let cantidad = parseInt($(this).val()) || 0;
        let idinsumo = $(this).data('idinsumo');
        let fila = $(this).data('fila');
        let precio = 0;

        // Buscar si hay un radio button seleccionado para esta fila
        let radioSeleccionado = $(`input[name="precio_${idinsumo}"]:checked`);

        if (radioSeleccionado.length > 0) {
            // Es un insumo de Uso de Salas con radio button seleccionado
            precio = parseFloat(radioSeleccionado.val());
        } else {
            // Caso: precio manual
            let precioManual = $(`.precio-manual[data-fila="${fila}"]`);
            if (precioManual.length > 0) {
                precio = parseFloat(precioManual.val()) || 0;
            } else {
                // Caso: precio normal leído desde data-precio
                let filaElement = $(this).closest('tr');
                let precioCell = filaElement.find('.precio[data-precio]').first();

                if (precioCell.length > 0) {
                    precio = parseFloat(precioCell.data('precio')) || 0;
                } else {
                    // Como fallback, buscar precio en texto
                    let celdas = filaElement.find('td');
                    celdas.each(function() {
                        let texto = $(this).text();
                        if (texto.match(/\$?\d+(\.\d+)?/) && 
                            !$(this).hasClass('total') && 
                            !$(this).find('input').length) {

                            let valorNumerico = parseFloat(texto.replace('$', '').replace(',', ''));
                            if (!isNaN(valorNumerico) && valorNumerico > 0) {
                                precio = valorNumerico;
                                return false; // salir del each
                            }
                        }
                    });
                }
            }
        }

        let total = precio * cantidad;

        // Actualizar el total en la interfaz
        $(this).closest('tr').find('.total').text('$' + total.toFixed(2));

        // Guardar o eliminar según la cantidad
        if (cantidad > 0) {
            guardarInsumo(idinsumo, cantidad, precio, total);
        } else {
            eliminarInsumo(idinsumo);
        }
    });


    // 🔹 NUEVO: evento para inputs de precio manual
    $(document).off('input', '.precio-manual').on('input', '.precio-manual', function () {

        let precio = parseFloat($(this).val()) || 0;
        let fila = $(this).data('fila');
        let idinsumo = $(this).data('idinsumo');

        // Buscar cantidad actual en la fila
        let cantidad = parseInt($(`.cantidad[data-fila="${fila}"]`).val()) || 0;
        let total = precio * cantidad;

        // Actualizar subtotal
        $(this).closest('tr').find('.total').text('$' + total.toFixed(2));

        // Guardar si hay cantidad
        if (cantidad > 0) {
            guardarInsumo(idinsumo, cantidad, precio, total);
        }
    });

}



// Función para guardar insumo - NUEVA FUNCIÓN

let guardadosPendientes = 0;

function guardarInsumo(idinsumo, cantidad, precio, total) {

    let iddetallecot = $("#iddetallecot").val();

    guardadosPendientes++;

    $("#btn_preview, #btnVistaPrevia")
        .prop("disabled", true)
        .attr("title", "Guardando cambios...");

    return $.ajax({
        url: "../ajax/cotizacion_detalle.php?op=guardarInsumo",
        type: "POST",
        data: {
            iddetallecot: iddetallecot,
            idinsumo: idinsumo,
            cantidad: cantidad,
            precio: precio,
            total: total
        },
        cache: false
    })
    .done(function(response) {

        response = String(response).trim();

        if (response !== "Guardado") {
            console.error("Error al guardar insumo:", response);
        }
    })
    .fail(function(xhr, status, error) {

        console.error("Error AJAX al guardar insumo:", {
            status: status,
            error: error,
            respuesta: xhr.responseText
        });
    })
    .always(function() {

        guardadosPendientes--;

        if (guardadosPendientes <= 0) {

            guardadosPendientes = 0;

            $("#btn_preview, #btnVistaPrevia")
                .prop("disabled", false)
                .attr("title", "Ver detalle");
        }
    });
}



// Función para eliminar insumo - NUEVA FUNCIÓN

function eliminarInsumo(idinsumo) {

    let iddetallecot = $("#iddetallecot").val();

    $.post("../ajax/cotizacion_detalle.php?op=eliminarInsumo", {
        iddetallecot: iddetallecot,
        idinsumo: idinsumo
    }, function(response) {
        if (response !== "Eliminado") {
            console.error("Error al eliminar insumo:", response);
        }
    });
}



function getParameterByName(name) {

    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));

}



function vistaPrevia() {

    var iddetallecot = $("#iddetallecot").val();

    if (!iddetallecot) {
        bootbox.alert("No se encontró el detalle de la cotización.");
        return;
    }

    if (guardadosPendientes > 0) {
        bootbox.alert("Todavía se están guardando los cambios. Intente nuevamente.");
        return;
    }

    var url = "../vistas/reportes/cotizacion_detalle_preview.php"
            + "?iddetallecot=" + encodeURIComponent(iddetallecot)
            + "&_=" + new Date().getTime();

    window.open(url, "_blank");
}



function verdetalle(iddetallecot) {

    if (guardadosPendientes > 0) {
        bootbox.alert("Todavía se están guardando los cambios. Intente nuevamente.");
        return;
    }

    var url = "../vistas/reportes/cotizacion_detalle_preview.php"
            + "?iddetallecot=" + encodeURIComponent(iddetallecot)
            + "&_=" + new Date().getTime();

    window.open(url, "_blank");
}

init();