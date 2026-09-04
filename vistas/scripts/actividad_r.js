var tabla;
//Función que se ejecuta al inicio
function init() {

    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });
	      
 //Cargamos los items al select tipo actividad
    $.post("../ajax/actividad.php?op=selectTipoActividad", function(r) {
        $("#tipoact").html(r);
        $("#tipoact").selectpicker('refresh');
    });
	
	 //Cargamos los items al select organizacion
   $.post("../ajax/actividad.php?op=selectInstituciones", function(r) {
        $("#institucionact").html(r);
        $("#institucionact").selectpicker('refresh');
    });
	 //Cargamos los items al select municipio
    $.post("../ajax/actividad.php?op=selectMunicipio", function(r) {
        $("#municipioact").html(r);
        $("#municipioact").selectpicker('refresh');
    });

	  $.post("../ajax/actividad.php?op=selectResponsable", function(r) {
        $("#responsableact").html(r);
        $("#responsableact").selectpicker('refresh');
    });
	 $.post("../ajax/actividad.php?op=selectCorredor", function(r) {
        $("#corredoract").html(r);
        $("#corredoract").selectpicker('refresh');
    });
    	 //Cargamos los items al select organizacion
     $.post("../ajax/actividad.php?op=selectOrganizacion", function(r) {
        $("#idorganizacion").html(r);
        $("#idorganizacion").selectpicker('refresh');
		
    });
	 $.post("../ajax/actividad.php?op=selCategoria",function(r) {             
            $("#categoriaact").html(r);
            $("#categoriaact").selectpicker('refresh');
         });
    $('#tipoact').change(function(){
        var tipoact = $("#tipoact").val();  
         $.post("../ajax/actividad.php?op=selectCategoria",{tipoact:tipoact},function(r) {             
            $("#categoriaact").html(r);
            $("#categoriaact").selectpicker('refresh');
         });
    });	
	
	$('#corredoract').change(function(){
        var corredoract = $("#corredoract").val();  
         $.post("../ajax/actividad.php?op=selectInstitucion",{corredoract:corredoract},function(r) {             
            $("#institucionact").html(r);
            $("#institucionact").selectpicker('refresh');
         });
    });	
	
	$('#fechaact').datepicker({
        language: 'es',
        dateFormat: 'dd/mm/yy'
      });
   
}

//Función limpiar
function limpiar() {
    $("#idactividad").val("");
	$("#nombreact").val("");
    $("#descripcionact").val("");
	$("#objetivoact").val("");
    $("#fechaact").val("");
    $("#lugaract").val("");
    $("#responsableact").val("0");
	$("#responsableact").selectpicker('refresh');
	$("#municipioact").val("0");
    $("#municipioact").selectpicker('refresh');
    $("#tipoact").val("0");
    $("#tipoact").selectpicker('refresh');
    $("#categoriaact").val("0");
	$("#categoriaact").selectpicker('refresh');
	$("#corredoract").val("0");
    $("#corredoract").selectpicker('refresh');
    $("#institucionact").val("0");
    $("#institucionact").selectpicker('refresh');
    $("#idorganizacion").val("0");
	$("#idorganizacion").selectpicker('refresh');
	$("#financiamientoact").val("");    	
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
        //responsive: true,
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
            url: '../ajax/actividad.php?op=listar',
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 5, //paginación
        "order": [
                [0, "desc"]
            ],
		"scrollX": true,
		"scrollCollapse": false,
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
}

//Función para guardar o editar
function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/actividad.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos) {
            bootbox.alert(datos);
            mostrarform(false);
            //tabla.ajax.reload();
            listar();
        }
    });
    limpiar();
}

function mostrar(idactividad) {
    $.post(
        "../ajax/actividad.php?op=mostrar", { idactividad: idactividad },
        function(data, status) {
            data = JSON.parse(data);
			//alert(data);
            mostrarform(true);
            $("#idactividad").val(data.IDACTIVIDADES);
			$("#nombreact").val(data.NOMBREACT);
			$("#descripcionact").val(data.DESCRIPCIONACT);
			$("#objetivoact").val(data.OBJETIVOACT);
			$("#fechaact").val(data.FECHAINICIOACT);
			$("#lugaract").val(data.LUGARACT);
            $("#financiamientoact").val(data.FINANCIAMIENTOACT);			
            $("#tipoact").val(data.IDTIPOACTIVIDAD);
            $("#tipoact").selectpicker('refresh');           
			$("#responsableact").val(data.CODIGORES);
            $("#responsableact").selectpicker('refresh');           
            $("#municipioact").val(data.IDMUNICIPIO);
			$("#municipioact").selectpicker('refresh');		
			$("#categoriaact").val(data.IDCATEGORIAACTIVIDAD);
			$("#categoriaact").selectpicker('refresh');
			//$("#categoriaact").val(data.NOMBRECAC);
			$("#corredoract").val(data.IDCORREDOR);
			$("#corredoract").selectpicker('refresh');			
			$("#institucionact").val(data.IDINSTITUCION);
			$("#institucionact").selectpicker('refresh');								
			
			$("#idorganizacion").val(data.IDORGANIZACION);
			$("#idorganizacion").selectpicker('refresh');			
		
        }
    );
}
//Función para desactivar registros
function desactivar(idactividad) {
    bootbox.confirm("Está seguro de desactivar la Actividad", function(result) {
        if (result) {
            $.post("../ajax/actividad.php?op=desactivar", { idactividad: idactividad }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para activar registros
function activar(idactividad) {
    bootbox.confirm("Está seguro de activar la Actividad", function(result) {
        if (result) {
            $.post("../ajax/actividad.php?op=activar", { idactividad: idactividad }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}
init();