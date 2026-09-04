var tabla;
//Función que se ejecuta al inicio
function init(){
    //Cargamos los items al select actividad
    $.post("../ajax/consultas_asistencia.php?op=selectActividad", function(r) {
        $("#actividad").html(r);
        $("#actividad").selectpicker('refresh');
    });
    //Cargamos los items para corredor
    $.post("../ajax/consultas_asistencia.php?op=selectCorredor", function(r) {
        $("#corredor").html(r);
        $("#corredor").selectpicker('refresh');
    });
    listar(); 
  
}
function limpiarBusqueda(){  
    $("#actividad").val("0");
    $("#actividad").selectpicker('refresh');
    $("#corredor").val("0");
    $("#corredor").selectpicker('refresh');
    //Reseteamos las fechas
    /*var FechaHoy = new Date();
    var FechaHoyString;

    FechaHoyString = FechaHoy.getFullYear() + '-'
                + ('0' + (FechaHoy.getMonth()+1)).slice(-2) + '-'
                + ('0' + FechaHoy.getDate()).slice(-2);*/
    $("#fechainicio").val("");	
    //$("#fechafin").val(FechaHoyString);
    $("#fechafin").val("");
    //tabla.clear().draw();   
    listar();
}
//Función listar
function listar() {
	var actividad       = $("#actividad").val();
    //Se agregan corredor, fecha inicio y fin
    var corredor        = $("#corredor").val();
    var fechainicio     = $("#fechainicio").val();
	var fechafin        = $("#fechafin").val();
    
   tabla=$("#tbllistado").dataTable({
        "bJQueryUI": true,
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
                url:'../ajax/consultas_asistencia.php?op=consultaasistencia',
                type:"get",
                //data:datos,
                data:{actividad:actividad,corredor:corredor,fechainicio:fechainicio,fechafin:fechafin}, //Se agregó el corredor
                dataType:"json",
                error:function(e){
                    console.log(e.responseText);
                }
        },
        // Configuración de las columnas y sus anchos
        columnDefs: [
            { width: '4%', targets: 0 },  // Id Act.
            { width: '15%', targets: 1 }, // Actividad
            { width: '4%', targets: 2 },  // Id Jor.
            { width: '15%', targets: 3 }, // Jornada
            { width: '15%', targets: 4 }, // Descripción
            { width: '5%', targets: 5 },  // Fecha
            { width: '6%', targets: 6 },  // Hora Inicio
            { width: '5%', targets: 7 },  // Hora Fin
            { width: '5%', targets: 8 }, // Asistencia
            { width: '5%', targets: 9 }, // Usuario Registro
            { width: '5%', targets: 10 }, // Estado
            { width: '5%', targets: 11 }  // Opciones
        ],
        "bDestroy":true,
        "iDisplayLength":5,//paginación
        "order":[[0,"desc"]], // Ordenar(columna,orden)
      	"language":{           
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

init();
