var tabla;
//Función que se ejecuta al inicio
function init() { 
    
 /* $.post("../ajax/repAsistencias.php?op=selectActividades",function(r) {
    $("#actividades").html(r);
    $("#actividades").selectpicker('refresh');
  });*/
   $.post("../ajax/repAsistencias.php?op=selectTipoActividad", function(r) {
        $("#tipoactividad").html(r);
        $("#tipoactividad").selectpicker('refresh');
    });
    $.post("../ajax/repAsistencias.php?op=selectTipoParticipante", function(r) {
        $("#tipoparticipante").html(r);
        $("#tipoparticipante").selectpicker('refresh');
    });
    $("#tipoactividad").change(function(){		
		var tipoactividad =  $("#tipoactividad").val();
		var actividades =  $("#actividades").val();
		$.post("../ajax/repAsistencias.php?op=selectActividadTipo",{tipoactividad:tipoactividad}, function(r) {
        $("#actividades").html(r);
        $("#actividades").selectpicker('refresh');
        	$.post("../ajax/repAsistencias.php?op=selectActividad",{actividades:actividades}, function(r) {
            $("#actividades").val(r);
            //$("#actividades").selectpicker('refresh');
      });
	});
    });
/*	$("#actividades").change(function(){		
		var actividades =  $("#actividades").val();
		$.post("../ajax/repAsistencias.php?op=selectActividad",{actividades:actividades}, function(r) {
        $("#actividades").val(r);
        //$("#actividades").selectpicker('refresh');
      });
	});*/

    listar();	
}

//Función limpiar
function limpiar() {    
    $("#actividades").val("0");
    $("#actividades").selectpicker('refresh');	
    $("#tipoactividad").val("0"); 
    $("#tipoactividad").selectpicker('refresh');   
    $("#tipoparticipante").val("0"); 
    $("#tipoparticipante").selectpicker('refresh');	
	$("#fechaini").val("");	
    $("#fechafin").val("");
    tabla.clear().draw();
}

//Función listar
function listar() {
    var fechaini   = $("#fechaini").val();
	var fechafin      = $("#fechafin").val();
	var actividades    = $("#actividades").val();
	var tipoactividad    = $("#tipoactividad").val();
	var tipoparticipante    = $("#tipoparticipante").val();
		
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
                url:'../ajax/repAsistencias.php?op=listar',
                type:"get",
                //data:datos,
                data:{fechaini:fechaini,fechafin:fechafin,actividades:actividades,tipoactividad:tipoactividad,tipoparticipante:tipoparticipante},
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