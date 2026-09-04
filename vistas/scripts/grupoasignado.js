var tabla;
//Función que se ejecuta al inicio
function init() {

    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });
	 
 //Cargamos los items al select tipo actividad
    $.post("../ajax/grupoasignado.php?op=selectGrupo", function(r) {
        $("#idgrupo").html(r);
        $("#idgrupo").selectpicker('refresh');
    });

	 $.post("../ajax/grupoasignado.php?op=selectTipo", function(r) {
        $("#tipoparticipante").html(r);
        $("#tipoparticipante").selectpicker('refresh');
    });	

	 $.post("../ajax/grupoasignado.php?op=selectMunicipio", function(r) {
        $("#municipioact").html(r);
        $("#municipioact").selectpicker('refresh');
    });	

	$('#municipioact').change(function(){
        var municipioact = $("#municipioact").val();  
        var tipoparticipante = $("#tipoparticipante").val();  
        $.post("../ajax/grupoasignado.php?op=listarParticipantes",{municipioact:municipioact,tipoparticipante:tipoparticipante},function(r) {             
            $("#participante").html(r);
            $("#participante").selectpicker('refresh');
        });
	});

	$('#tipoparticipante').change(function(){
        var municipioact = $("#municipioact").val();  
        var tipoparticipante = $("#tipoparticipante").val();  
        $.post("../ajax/grupoasignado.php?op=listarParticipantes",{municipioact:municipioact,tipoparticipante:tipoparticipante},function(r){             
            $("#participante").html(r);
            $("#participante").selectpicker('refresh');
        });
	});
}

//Función limpiar
function limpiar() {
    $("#idgrupo").val("0");
    $("#idgrupo").selectpicker("refresh");
    $("#municipioact").val("0")
    $("#municipioact").selectpicker("refresh");
    $("#participante").val("0");
    $("#participante").selectpicker("refresh");
    $("#tipoparticipante").val("0"); 
    $("#tipoparticipante").selectpicker("refresh"); 
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
    tabla = $("#tbllistado").dataTable({
        responsive: true,
        "aProcessing": true, //Activamos el procesamiento del datatables
        "aServerSide": true, //Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', //Definimos los elementos del control de tabla
        buttons: [
            'copy',
            'excel',
            'csv',
            'pdf',
            'print'
        ],
        "ajax": {
            url: '../ajax/grupoasignado.php?op=listar',
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, //paginación
        "order": [
                [0, "desc"]
            ], // Ordenar(columna,orden) 
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
function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/grupoasignado.php?op=guardaryeditar",
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

function mostrar(idgrupo) {   
    $.post(
        "../ajax/grupoasignado.php?op=mostrar", { idgrupo: idgrupo},
        function(data, status) {
            data = JSON.parse(data);
            mostrarform(true);
           	$("#idgrupo").val(data.IDGRUPO);
			$("#idgrupo").selectpicker('refresh');			
			$("#tipoparticipante").val(data.TIPOPARTICIPARNTE);
			$("#tipoparticipante").selectpicker('refresh');			
			$("#municipioact").val(data.IDMUNICIPIO);
			$("#municipioact").selectpicker('refresh');
		    $.post("../ajax/grupoasignado.php?op=listarSeleccionados&id=" + idgrupo, function(r) {
              $("#participante").html(r);
              $("#participante").selectpicker('refresh');   
            });
        }
    );
}

function eliminar(idgrupo,participante){   
    bootbox.confirm("Está Seguro eliminar al participante", function(result) {
        if (result) {
            $.ajax({
                url:"../ajax/grupoasignado.php?op=eliminar",
                data:{idgrupo:idgrupo,participante:participante},   
                type: "post",
                dataType: "json"                
            });
            bootbox.alert("Se ha eliminado el participante: "+participante+" del grupo "+idgrupo);
            tabla.ajax.reload();
            listar();              
        }
    });                               
}

init();