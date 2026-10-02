var tabla;

//Función que se ejecuta al inicio

function init() {

    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    //Cargamos los items al select tipo insumo
    $.post("../ajax/gestioninsumo.php?op=selectTipoInsumo", function(r) {
        $("#tipoinsumo").html(r);
        $("#tipoinsumo").selectpicker('refresh');
    });

}

//Función limpiar
function limpiar() {
    $("#idinsumo").val("");
    $("#nombreinsumo").val("");
	$("#aliasinsumo").val("");
	$("#descripcion_insumo").val("");

    //Select tipo insumo
	$("#tipoinsumo").val("0");
	$("#tipoinsumo").selectpicker('refresh');
}



//Función mostrar formulario
function mostrarform(flag) {
    if (flag) {

        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();

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
            url: '../ajax/gestioninsumo.php?op=listar',
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
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/gestioninsumo.php?op=guardaryeditar",
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

//Función para mostrar los datos de un insumo en específico
function mostrar(idinsumo) {
    $.post(
        "../ajax/gestioninsumo.php?op=mostrar", { idinsumo: idinsumo },

        function(data, status) {
            
            data = JSON.parse(data);
            
            // Primero asignar datos al formulario
            $("#idinsumo").val(data.IDINSUMO);
            $("#nombreinsumo").val(data.NOMBREINSUMO);
            $("#aliasinsumo").val(data.ALIASINSUMO);
            $("#descripcion_insumo").val(data.DESCRIPCIONINS);

            // Selects
            $("#tipoinsumo").val(data.IDTIPOINSUMO).selectpicker('refresh');
            
            // Ahora sí mostrar el formulario
            mostrarform(true);
        }
    );
}

//Se agregó validación para elimnar
function eliminar(idinsumo){
    bootbox.confirm("¿Está Seguro de eliminar el insumo seleccionado?",function (result) {
        if(result){
            $.post("../ajax/gestioninsumo.php?op=eliminar",{idinsumo:idinsumo},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para desactivar registros
function desactivar(idinsumo){
    bootbox.confirm("¿Está seguro de desactivar el Insumo?",function (result) {
        if(result){
            $.post("../ajax/gestioninsumo.php?op=desactivar",{idinsumo:idinsumo},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para activar registros
function activar(idinsumo){
    bootbox.confirm("¿Está seguro de activar el Insumo?",function (result) {
        if(result){
            $.post("../ajax/gestioninsumo.php?op=activar",{idinsumo:idinsumo},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

init();