var tabla;
//Función que se ejecuta al inicio
function init() {
    
 //Cargamos los items al select tipo actividad
    $.post("../ajax/repActividad.php?op=selectTipoActividad", function(r) {
        $("#tipoact").html(r);
        $("#tipoact").selectpicker('refresh');
    });
	 $.post("../ajax/repActividad.php?op=selectCorredor", function(r) {
        $("#corredoract").html(r);
        $("#corredoract").selectpicker('refresh');
    }); 
    listar();	
}

//Función limpiar
function limpiar() {    
    $("#tipoact").val("0");	
	 $("#tipoact").selectpicker('refresh');
    $("#corredoract").val("0");
	$("#corredoract").selectpicker('refresh');
	$("#fechainicio").val("0000-00-00");	
    $("#fechafin").val("0000-00-00");
    tabla.clear().draw();
}


//Función listar
function listar() {
    var fechainicio   = $("#fechainicio").val();
	var fechafin      = $("#fechafin").val();
	var tipoact       = $("#tipoact").val();
	var corredoract   = $("#corredoract").val();
    
  
    tabla = $("#tblreporte").dataTable({
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
                url:'../ajax/repActividad.php?op=listar',
                type:"get",
                //data:datos,
                data:{fechainicio:fechainicio,fechafin:fechafin,tipoact:tipoact,corredoract:corredoract},
                dataType:"json",
                error:function(e){
                    console.log(e.responseText);
                }
        },
        "bDestroy":true,
        "iDisplayLength":5,//paginación
        "order":[[0,"desc"]], // Ordenar(columna,orden)
      	"language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
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