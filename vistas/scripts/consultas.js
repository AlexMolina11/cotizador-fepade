var tabla;
//Función que se ejecuta al inicio
function init(){

    $.post("../ajax/participantes.php?op=selectTipoParticipante", function(r) {
        $("#tipopar").html(r);
        $("#tipopar").selectpicker('refresh');
    });

    //Cargamos los items al select Departamento
    $.post("../ajax/consultas.php?op=selectDepartamento", function(r) {
        $("#departamento").html(r);
        $("#departamento").selectpicker('refresh');
    });

    //Se agregó validación, si se selecciona un departamento mostrar lo siguiente
    $("#departamento").change(function(){
        var iddepartamento = $("#departamento").val();  
        if(iddepartamento == 0) {
            $.post("../ajax/consultas.php?op=selectMunicipio", function(r) {
                $("#municipio").html(r);
                $("#municipio").selectpicker('refresh');
            });
        } else {
            $.post("../ajax/comunidad.php?op=selectDist",{iddepartamento:iddepartamento},function(r) {             
                $("#municipio").html(r);
                $("#municipio").selectpicker('refresh');
            });
        }
    });
    
    //Cargamos los items al select municipio
    $.post("../ajax/consultas.php?op=selectMunicipio", function(r) {
        $("#municipio").html(r);
        $("#municipio").selectpicker('refresh');
    });
    
    //Se agregó validación, si se selecciona un municipio mostrar lo siguiente
    $("#municipio").change(function(){
        var idmunicipio = $("#municipio").val();
        if(idmunicipio == 0) {
            //Cargamos los items al select Departamento
            $.post("../ajax/consultas.php?op=selectDepartamento", function(r) {
                $("#departamento").html(r);
                $("#departamento").selectpicker('refresh');
            });
        } else {  
            $.post("../ajax/comunidad.php?op=selectDepa",{idmunicipio:idmunicipio},function(r) {             
                $("#departamento").html(r);
                $("#departamento").selectpicker('refresh');
            });
        }
    });

     //Cargamos los items al select institución
    $.post("../ajax/consultas.php?op=selectInstitucion", function(r) {
        $("#institucion").html(r);
        $("#institucion").selectpicker('refresh');
    });
    
    //Cargamos los items al select Corredor
    $.post("../ajax/consultas.php?op=selectCorredor", function(r) {
        $("#corredor").html(r);
        $("#corredor").selectpicker('refresh');
    });

    listar(); 
  
}
function limpiarBusqueda(){  
    /*$("#municipio").val("0");
    $("#municipio").selectpicker('refresh');
    $("#departamento").val("0"); //Se agrego el departamento
    $("#departamento").selectpicker('refresh'); //se agregó el departamento
    $("#institucion").val("0");
    $("#institucion").selectpicker('refresh');
    $("#corredor").val("0"); //Se agrego el corredor
    $("#corredor").selectpicker('refresh'); //se agregó el corredor
    $("#tipopar").val("0");
    $("#tipopar").selectpicker('refresh'); 
    tabla.clear().draw();   */
    location.reload();
}
//Función listar
function listar() {

	var municipio       = $("#municipio").val();
    var departamento     = $("#departamento").val(); //Se agregó el departamento
    var institucion     = $("#institucion").val();
    var corredor     = $("#corredor").val(); //Se agregó el corredor
    var tipopar         = $("#tipopar").val();
    
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
                url:'../ajax/consultas.php?op=consultaparticipante',
                type:"get",
                //data:datos,
                //data:{municipio:municipio,institucion:institucion,tipopar:tipopar},
                data:{municipio:municipio,departamento:departamento,institucion:institucion,corredor:corredor,tipopar:tipopar}, //Se agregó el corredor y departamento
                dataType:"json",
                error:function(e){
                    console.log(e.responseText);
                }
        },
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
