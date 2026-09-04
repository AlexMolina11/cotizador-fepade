var tabla;
//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    //Agregamos Modulo 
    $.post("../ajax/acciones.php?op=selectModulo", function(r) {
        $("#idmodulo").html(r);
        $("#idmodulo").selectpicker('refresh');
    });
}

//Función limpiar
function limpiar() {
    $("#idaccion").val("");      
    $("#nombreaccion").val(""); //Se agregó nombre accion
    $("#nombreaccion").selectpicker('refresh'); //Se agregó nombre accion
    $("#descripcionacc").val("");
    $("#idmodulo").val(""); //Se agregó Modulo 
	$("#idmodulo").selectpicker('refresh'); //Se agregó Modulo 
}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();      
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide()
    } else {
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
    tabla = $("#tbllistado")
        .dataTable({
            responsive:true,
            aProcessing: true, //Activamos el procesamiento del datatables
            aServerSide: true, //Paginación y filtrado realizados por el servidor
            dom: "Bfrtip", //Definimos los elementos del control de tabla
            buttons: ["copy", "excel", "csv", "pdf", "print"],
            ajax: {
                url: "../ajax/acciones.php?op=listar",
                type: "get",
                dataType: "json",
                error: function(e) {
                    console.log(e.responseText);
                }
            },
            bDestroy: true,
            iDisplayLength: 5, //paginación
            order: [
                    [0, "desc"]
                ], // Ordenar(columna,orden)
            language: {           
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
        })
        .DataTable();
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
function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/acciones.php?op=guardaryeditar",
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
}

function mostrar(idaccion) {

    $.post(
        "../ajax/acciones.php?op=mostrar", { idaccion: idaccion },
        function(data, status) {
            data = JSON.parse(data);
            mostrarform(true);
        
            $("#idaccion").val(data.IDACCION);        
            $("#nombreaccion").val(data.NOMBREACC); //Se agregó nombre accion
		    $("#nombreaccion").selectpicker('refresh'); //Se agregó nombre accion
            $("#descripcionacc").val(data.DESCRIPCIONACC);
            $("#idmodulo").val(data.IDMODULO); //Se agregó modulo
		    $("#idmodulo").selectpicker('refresh'); //Se agregó modulo
        }
    );
}


init();