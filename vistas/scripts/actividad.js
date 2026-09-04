var tabla;
//Función que se ejecuta al inicio
function init() {

    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    //Se agregó post para tipo de Participante
    $.post("../ajax/actividad.php?op=selectAreaResponsable", function(r) {
        $("#arearesponsable").html(r);
        $("#arearesponsable").selectpicker('refresh');
    });

    //Se agregó post para tipo de Participante o Actividad dirigida a
    $.post("../ajax/actividad.php?op=selectTipoParticipante", function(r) {
        $("#tipoparticipante").html(r);
        $("#tipoparticipante").selectpicker('refresh');
    });

    //Cargamos los items al select Departamento
    $.post("../ajax/actividad.php?op=selectDepartamento", function(r) {
        $("#departamentoact").html(r);
        $("#departamentoact").selectpicker('refresh');
    });

    //Se agregó validación, si se selecciona un departamento mostrar lo siguiente
    $("#departamentoact").change(function(){
        var iddepartamento = $("#departamentoact").val();  
        if(iddepartamento == 0) {
            $.post("../ajax/actividad.php?op=selectMunicipio", function(r) {
                $("#municipioact").html(r);
                $("#municipioact").selectpicker('refresh');
            });
            $.post("../ajax/actividad.php?op=selectCorredor", function(r) {
                $("#corredoract").html(r);
                $("#corredoract").selectpicker('refresh');
            });
        } else {
            $.post("../ajax/comunidad.php?op=selectDist",{iddepartamento:iddepartamento},function(r) {             
                $("#municipioact").html(r);
                $("#municipioact").selectpicker('refresh');
            });

            $.post("../ajax/comunidad.php?op=selectCorr",{iddepartamento:iddepartamento},function(r) {             
                $("#corredoract").html(r);
                $("#corredoract").selectpicker('refresh');
             });
        }
    });

    //Cargamos los items al select municipio
    $.post("../ajax/actividad.php?op=selectMunicipio", function(r) {
        $("#municipioact").html(r);
        $("#municipioact").selectpicker('refresh');
    });

    //Se agregó validación, si se selecciona un municipio mostrar lo siguiente
    $("#municipioact").change(function(){
        var idmunicipio = $("#municipioact").val();
        if(idmunicipio == 0) {
            //Cargamos todos los items al select Departamento
            $.post("../ajax/actividad.php?op=selectDepartamento", function(r) {
                $("#departamentoact").html(r);
                $("#departamentoact").selectpicker('refresh');
            });

            //Cargamos todos los items al select municipio
            $.post("../ajax/actividad.php?op=selectMunicipio", function(r) {
                $("#municipioact").html(r);
                $("#municipioact").selectpicker('refresh');
            });

            //Carga todos los items del select corredor
            /*$.post("../ajax/actividad.php?op=selectCorredor", function(r) {
                $("#corredoract").html(r);
                $("#corredoract").selectpicker('refresh');
            });*/
        } else {  
            $.post("../ajax/comunidad.php?op=selectDepa",{idmunicipio:idmunicipio},function(r) {             
                $("#departamentoact").html(r);
                $("#departamentoact").selectpicker('refresh');

                //Luego de seleccionar Departamento, limita los corredores
                var iddepartamento = $("#departamentoact").val();
                $.post("../ajax/comunidad.php?op=selectCorr",{iddepartamento:iddepartamento},function(r) {             
                    $("#corredoract").html(r);
                    $("#corredoract").selectpicker('refresh');
                });
            });
        }
    });
	      
    //Cargamos los items al select tipo actividad
    $.post("../ajax/actividad.php?op=selectTipoActividad", function(r) {
        $("#tipoact").html(r);
        $("#tipoact").selectpicker('refresh');
    });

    $('#tipoact').change(function(){
        var tipoact = $("#tipoact").val();  
        if(tipoact == 0) {
            //Cargamos todos los items para categoria de actividad
            $.post("../ajax/actividad.php?op=selCategoria",function(r) {             
                $("#categoriaact").html(r);
                $("#categoriaact").selectpicker('refresh');
            });
        } else {
            $.post("../ajax/actividad.php?op=selectCategoria",{tipoact:tipoact},function(r) {             
                $("#categoriaact").html(r);
                $("#categoriaact").selectpicker('refresh');
            });
        }
    });	

    //Cargamos los items para categoria de actividad
    $.post("../ajax/actividad.php?op=selCategoria",function(r) {             
        $("#categoriaact").html(r);
        $("#categoriaact").selectpicker('refresh');
    });

    $("#categoriaact").change(function() {
        var categoriaact = $("#categoriaact").val();
        if(categoriaact == 0) {
            //Cargamos todos los items al select tipo actividad
            $.post("../ajax/actividad.php?op=selectTipoActividad", function(r) {
                $("#tipoact").html(r);
                $("#tipoact").selectpicker('refresh');
            });
        } else {
            $.post("../ajax/actividad.php?op=selectTipoAct",{categoriaact:categoriaact}, function(r) {
                $("#tipoact").html(r);
                $("#tipoact").selectpicker('refresh');
            });
        }
    });

    //Cargamos los items para los Responsables
    /*$.post("../ajax/actividad.php?op=selectResponsable", function(r) {
        $("#responsableact").html(r);
        $("#responsableact").selectpicker('refresh');
    });*/
	
    //Cargamos los items para los Corredores
    $.post("../ajax/actividad.php?op=selectCorredor", function(r) {
        $("#corredoract").html(r);
        $("#corredoract").selectpicker('refresh');
    });

    $('#corredoract').change(function(){
        var corredoract = $("#corredoract").val();   
        if(corredoract == "") {
            //Cargamos los items al select Centros Educativos
            $.post("../ajax/actividad.php?op=selectInstituciones", function(r) {
                $("#institucionact").html(r);
                $("#institucionact").selectpicker('refresh');
            });
        } else {
            $.post("../ajax/actividad.php?op=selectInstitucion",{corredoract:corredoract},function(r) {             
                $("#institucionact").html(r);
                $("#institucionact").selectpicker('refresh');
            });
        }
    });	

	 //Cargamos los items al select Centros Educativos
    $.post("../ajax/actividad.php?op=selectInstituciones", function(r) {
        $("#institucionact").html(r);
        $("#institucionact").selectpicker('refresh');
    });

     //Cargamos los items al select comunidades
     $.post("../ajax/actividad.php?op=selectComunidades", function(r) {
        $("#actividad_comunidades").html(r);
        $("#actividad_comunidades").selectpicker('refresh');
    });

    $('#actividad_comunidades').selectpicker({
        liveSearch: true
    });
}

//Función limpiar
function limpiar() {
    $("#idactividad").val("");
	$("#nombreact").val("");
    $("#descripcionact").val("");
	$("#objetivoact").val("");
    //Se agregó area responsable
	$("#arearesponsable").val("0");
	$("#arearesponsable").selectpicker('refresh');
    //Se agregó tipo de participante
	$("#tipoparticipante").val("0");
	$("#tipoparticipante").selectpicker('refresh');
    $("#fechaact").val("");
    //$("#lugaract").val("");
    /*$("#responsableact").val("0");
	$("#responsableact").selectpicker('refresh');*/
	$("#municipioact").val("0");
    $("#municipioact").selectpicker('refresh');
    $("#departamentoact").val("0");
    $("#departamentoact").selectpicker('refresh'); 
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

    $("#actividad_comunidades").val([]); // Limpia la selección múltiple
    $("#actividad_comunidades").selectpicker('refresh'); // Refresca el select para mostrar que está vacío
    
    //Reseteamos las fechas
    $("#fechainicio").val("");	
    //$("#fechafin").val(FechaHoyString);
    $("#fechafin").val("");
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
    //limpiar();
    //mostrarform(false);
    location.reload();
}

//Función listar
function listar() {
    //Se agregan fecha inicio y fin
    var fechainicio   = $("#fechainicio").val();
	var fechafin      = $("#fechafin").val();
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
            url: '../ajax/actividad.php?op=listar',
            type: "get",
            data:{fechainicio:fechainicio,fechafin:fechafin},
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 5, //paginación
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
            bootbox.alert({
                message: datos,
                callback: function(){ 
                    /* your callback code */ 
                    location.reload();
                }
            })
            //mostrarform(false);
            //tabla.ajax.reload();
            //listar();
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
            //Se agregó area responsable
		    $("#arearesponsable").val(data.IDAREARES);
		    $("#arearesponsable").selectpicker('refresh');	
            //Se agregó tipo participante
		    $("#tipoparticipante").val(data.IDTIPOPARTICIPANTE);
		    $("#tipoparticipante").selectpicker('refresh');	
			//$("#fechaact").val(data.FECHA);
            $("#fechaact").val(data.FECHAINICIOACT);
			//$("#lugaract").val(data.LUGARACT);
            $("#financiamientoact").val(data.FINANCIAMIENTOACT);			
            $("#tipoact").val(data.IDTIPOACTIVIDAD);
            $("#tipoact").selectpicker('refresh');           
			/*$("#responsableact").val(data.CODIGORES);
            $("#responsableact").selectpicker('refresh');  */         
            $("#municipioact").val(data.IDMUNICIPIO); //Se agregpo Departamento
			$("#municipioact").selectpicker('refresh');	 //Se agregó Departamento
            $("#departamentoact").val(data.IDDEPARTAMENTO);
			$("#departamentoact").selectpicker('refresh');	
			$("#categoriaact").val(data.IDCATEGORIAACTIVIDAD);
			$("#categoriaact").selectpicker('refresh');
			//$("#categoriaact").val(data.NOMBRECAC);
			$("#corredoract").val(data.IDCORREDOR);
			$("#corredoract").selectpicker('refresh');			
			$("#institucionact").val(data.IDINSTITUCION);
			$("#institucionact").selectpicker('refresh');								
			
		    $("#idorganizacion option:selected").each(function(){
                $("#idorganizacion option:selected").html(data.IDORG);
            });
			
            // Configura los niveles educativos seleccionados
			$("#actividad_comunidades").val(data.actividad_comunidades);
            $("#actividad_comunidades").selectpicker('refresh');

            //Cargamos unicamente los items del participante
            var departamentoact = $("#departamentoact").val();
            var municipioact = $("#municipioact").val();
            var corredoract = $("#corredoract").val();
            var institucionact = $("#institucionact").val();

            /*$.post("../ajax/actividad.php?op=selectDepartamento_mostrar",{departamentoact:departamentoact},function(r) {
                $("#departamentoact").html(r);
                $("#departamentoact").selectpicker('refresh');
            });*/

            $.post("../ajax/actividad.php?op=selectMunicipio_mostrar",{departamentoact:departamentoact, municipioact:municipioact},function(r) {             
                $("#municipioact").html(r);
                $("#municipioact").selectpicker('refresh');
            });

            $.post("../ajax/actividad.php?op=selectCorredor_mostrar",{departamentoact:departamentoact, corredoract:corredoract},function(r) {             
                $("#corredoract").html(r);
                $("#corredoract").selectpicker('refresh');
            });

            $.post("../ajax/actividad.php?op=selectCentroEducativo_mostrar",{corredoract:corredoract, institucionact:institucionact},function(r) {             
                $("#institucionact").html(r);
                $("#institucionact").selectpicker('refresh');
            });
        }
    );
}

//Se agregó validación para elimnar
function eliminar(idactividad){
    bootbox.confirm("¿Está Seguro de eliminar la actividad?",function (result) {
        if(result){
            $.post("../ajax/actividad.php?op=eliminar",{idactividad:idactividad},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
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