var tabla2;
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    $("#formulario").on("submit",function(e)
    {
      guardaryeditar(e);      
    });
   
	$.post("../ajax/asistencias.php?op=selectActividad", function(r) {
        $("#idactividades").html(r);
        $("#idactividades").selectpicker('refresh');
    }); 
	 
	$.post("../ajax/asistencias.php?op=selectGrupos", function(r) {
        $("#idgrupo").html(r);
        $("#idgrupo").selectpicker('refresh');
    });

    $.post("../ajax/asistencias.php?op=selectDepartamentos", function(r) {
        $("#iddepartamento").html(r);
        $("#iddepartamento").selectpicker('refresh');
    });
	
	$.post("../ajax/asistencias.php?op=selectMunicipios", function(r) {
        $("#idmunicipio").html(r);
        $("#idmunicipio").selectpicker('refresh');
    });

    $.post("../ajax/asistencias.php?op=selectCentroeducativo", function(r) {
        $("#idcentroeducativo").html(r);
        $("#idcentroeducativo").selectpicker('refresh');
    });
    
	$('#idgrupo').change(function(){
        var idgrupo = $("#idgrupo").val();
        var idmunicipio = $("#idmunicipio").val();    
        var iddepartamento = $("#iddepartamento").val();
        var idcentroeducativo = $("#idcentroeducativo").val(); 
        $.post("../ajax/asistencias.php?op=listarParticipantes",{idgrupo:idgrupo, idmunicipio:idmunicipio, iddepartamento:iddepartamento, idcentroeducativo:idcentroeducativo},function(r) {             
            listarParticipantes();
        });
	});
    
	$('#idmunicipio').change(function(){
        var idgrupo = $("#idgrupo").val();
        var idmunicipio = $("#idmunicipio").val();
        var iddepartamento = $("#iddepartamento").val();
        var idcentroeducativo = $("#idcentroeducativo").val();   
        $.post("../ajax/asistencias.php?op=listarParticipantes",{idgrupo:idgrupo, idmunicipio:idmunicipio, iddepartamento:iddepartamento, idcentroeducativo:idcentroeducativo},function(r) {             
            listarParticipantes();
        });
    });	

    $('#iddepartamento').change(function(){
        var idgrupo = $("#idgrupo").val();
        var idmunicipio = $("#idmunicipio").val();    
        var iddepartamento = $("#iddepartamento").val();
        var idcentroeducativo = $("#idcentroeducativo").val(); 
        $.post("../ajax/asistencias.php?op=listarParticipantes",{idgrupo:idgrupo, idmunicipio:idmunicipio, iddepartamento:iddepartamento, idcentroeducativo:idcentroeducativo},function(r) {             
            listarParticipantes();
        });
	});
    
	$('#idcentroeducativo').change(function(){
        var idgrupo = $("#idgrupo").val();
        var idmunicipio = $("#idmunicipio").val();
        var iddepartamento = $("#iddepartamento").val();
        var idcentroeducativo = $("#idcentroeducativo").val();   
        $.post("../ajax/asistencias.php?op=listarParticipantes",{idgrupo:idgrupo, idmunicipio:idmunicipio, iddepartamento:iddepartamento, idcentroeducativo:idcentroeducativo},function(r) {             
            listarParticipantes();
        });
    });	

    $('#idactividades').change(function(){
        var idactividades = $("#idactividades").val();  
         $.post("../ajax/jornadas.php?op=obtenerFecha",{idactividades:idactividades},function(r) {             
           $("#fechaactividad").val(r);
         });         
     });
    
    $('#idjornadas').change(function(){      
        $.post("../ajax/asistencias.php?op=mostrarNombre&id=" + idjornadas, function(r) {
            $("#nombre").val(r);     
        });    
    });

    $('#horainijor').timepicker({
        timeFormat: 'H:i:s',
        minTime: '05:00', // 11:45:00 AM,
        maxHour: 20,
        maxMinutes: 30,
        interval: 30 // 15 minutes
    });
    $('#horafinjor').timepicker({
       timeFormat: 'H:i:s',
        minTime: '05:00:00', // 11:45:00 AM,
        maxHour: 20,
        maxMinutes: 30,
        interval: 30 // 15 minutes
    });  
   
    $('#fechajorn').datepicker({
    	language: 'es',
    	dateFormat: 'dd/mm/yyyy'
    });
}

//Función limpiar
function limpiar(){
  $("#idactividades").val("0");
  $("#idactividades").selectpicker("refresh");
  $("#idjornadas").val("");
  $("#fechajorn").val("");  
  $("#idmunicipio").val("0");
  $("#idmunicipio").selectpicker("refresh");
  $("#idgrupo").val("0");
  $("#idgrupo").selectpicker("refresh");
  $("#nombrejor").val(""); 
  $("#horainijor").val("");
  $("#horafinjor").val("");
  $("#objetivojor").val(""); 
  $(".filas").remove();  
  
}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
		listarParticipantes();
    }else{
        $("#listadoregistros").show();
        $("#formularioregistros").hide(); 
        $("#btnagregar").show();
    }
}

//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);
}

//Funcion listar participantes
function listarParticipantes() {
    var idgrupo       = $("#idgrupo").val();
    var idmunicipio   = $("#idmunicipio").val();
    var iddepartamento = $("#iddepartamento").val(); //Se agregó el departamento
    var idcentroeducativo = $("#idcentroeducativo").val(); //Se agregó el centro educativo
	
    tabla2 = $("#tblparticipantes")
        .dataTable({
            "bJQueryUI": true,
            responsive: true,
            aProcessing: true, //Activamos el procesamiento del datatables
            aServerSide: true, //Paginación y filtrado realizados por el servidor
            dom: "Bfrtip", //Definimos los elementos del control de tabla
            buttons: [],
            ajax: {
                url: "../ajax/asistencias.php?op=listarParticipantes",
				data:{idgrupo:idgrupo,idmunicipio:idmunicipio,iddepartamento:iddepartamento,idcentroeducativo:idcentroeducativo}, //Se agregó idcentroeducativo y departamento
                type: "get",
                dataType: "json",
                error: function(e) {
                    console.log(e.responseText);
                }
            },
            bDestroy: true,
            iDisplayLength: 10, //paginación
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
}

//Función para guardar o editar
function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    var fechainicial = $("#fechajorn").val();
    var fechafinal = $("#fechaactividad").val();
    fechainicial = fechainicial.substr(6, 4) + '-' + fechainicial.substr(3, 2) + '-' + fechainicial.substr(0, 2);
    //validar hora
    var horaini = document.getElementById("horainijor").value;
    var horafin = document.getElementById("horafinjor").value;
    var hora1 = (horaini).split(":");
    var hora2 = (horafin).split(":");
    var t1 = 00;
    var t2 = 00;
    t1 = parseInt(hora1[0]) * 100 + parseInt(hora1[1]);
    t2 = parseInt(hora2[0]) * 100 + parseInt(hora2[1]);
    //alert(fechafinal + '>=' + fechainicial);
    if (fechafinal > fechainicial) {
        bootbox.alert('La fecha debe ser mayor o igual a la de la actividad, ingresela nuevamente');
        document.getElementById("fechajorn").focus();
        document.getElementById("fechajorn").style.borderColor = "red";
        document.getElementById("fechajorn").value = "";
    } else if (t1 > t2) {
        bootbox.alert('Hora de fin no puede ser menor a la hora de inicio');
        document.getElementById("horafinjor").focus();
        document.getElementById("horafinjor").style.borderColor = "red";
    } else {
        $.ajax({
            url: "../ajax/asistencias.php?op=guardaryeditar",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function(datos) {
                bootbox.alert({
                    message:    datos,
                    closeButton: false,
                    callback:  function() {
                        mostrarform(false);
                        listarParticipantes();
                        listar();
                        limpiar();
                        document.getElementById("horafinjor").style.borderColor = "";
                        document.getElementById("fechajorn").style.borderColor = "";
                    }
                });
            }
        });
    }

}


function mostrar(idjornadas) {
   contarTablaDetalle();
   $.post("../ajax/asistencias.php?op=mostrar",{idjornadas:idjornadas},function(data,status)
   {
            data = JSON.parse(data);
            mostrarform(true);

            //Asignamos datos a las variables html
            $("#nombreact_txt").html(data.NOMBREACT);
            $("#nombreact2_txt").html(data.NOMBREACT);
            $("#fechajor_txt").html(data.FECHA);
            $("#horainijor_txt").html(data.HORAINIJOR);
            $("#horafinjor_txt").html(data.HORAFINJOR);
            //Asignamos datos a los inputs del formulario
            $("#idjornadas").val(data.IDJORNADAS);
			$("#idactividades").val(data.IDACTIVIDADES);
			$("#idactividades").selectpicker('refresh');
			$("#nombrejor").val(data.NOMBREJOR); 		
			$("#objetivojor").val(data.OBJETIVOJOR);			
			$("#fechajorn").val(data.FECHA);
			$("#fechajorn").datepicker("setDate", data.FECHA);
			$("#horainijor").val(data.HORAINIJOR);	
			$("#horafinjor").val(data.HORAFINJOR);		
            $("#fechaactividad").val(data.FECHAINICIOACT);
            //Ocultar y mostrar los botones
            $("#btnGuardar").show();
            $("#btnCancelar").show();
            $("#btnAgregarParticipantes").show();

        });

    $.post("../ajax/asistencias.php?op=listarDetalle&id=" + idjornadas, function(r) {
        $("#detalles").html(r);
        //Contando elementos de la tabla
        contarTablaDetalle();
    });
}

//Declaración de variables para trabajar con las jornadas y sus asistencias

var partAgregado="";
var cont = 0;
var detalles = 0;
//$("#guardar").hide();
$("#btnGuardar").hide();

function agregarDetalle(idparticipante, nombre) { 
    //Deshabilitamos el boton del partitipante
    $("#"+idparticipante).prop("disabled",true).addClass("btn-danger");
    //Validamos
    if (idparticipante != "") {

        var fila = "<tr class='filas' id='fila" + cont + "' >" +
           "<td><button type='button' class='btn btn-danger' onclick='eliminarDetalle(" + cont + ",\"" + idparticipante + "\")'  >X</button></td>" +
           "<td><input type='hidden' name='idparticipante[]' value='" + idparticipante + "'>" + nombre + "</td>" +          
           "</tr>";
        cont++;
        detalles = detalles + 1;
        partAgregado= partAgregado + idparticipante + "-";
        $('#detalles').append(fila);
        contarTablaDetalle();
        evaluar();
    } else {
        bootbox.alert("Error al ingresar los participantes!!!");
    }
}

function evaluar() {
    if (detalles > 0) {
        $("#btnGuardar").show();
    } else {
        $("#btnGuardar").hide();
        cont = 0;
    }
}

function eliminar(idjornadas,idparticipante){ 
   //var  nombre = document.getElementById("nombre").value; 
   //alert(nombre);
    bootbox.confirm("¿Está seguro de eliminar el participante?", function(result) {
        if (result) {
            $.ajax({
                url:"../ajax/asistencias.php?op=eliminar",
                data:{idjornadas:idjornadas,idparticipante:idparticipante},   
                type: "post",
                dataType: "json" 
            });
            $.post("../ajax/asistencias.php?op=listarDetalle&id=" + idjornadas, function(r) {
                $("#detalles").html(r);
                listarParticipantes();        
            }); 
            reducircontadorParticipantes(); 
        }
    });                               
}

function eliminarDetalle(indice, idparticipante) {
    $("#fila" + indice).remove();
    detalles = detalles - 1;
    contarTablaDetalle();
    //Habilitamos el boton del partitipante
    $("#"+idparticipante).prop("disabled",false).removeClass("btn-danger");
}

function reducircontadorParticipantes(){
    //-1 al contador de participantes 
    var total_par = $("#total_par").html();
    var num_total_par = Number(total_par);
    var nuevototal = num_total_par - 1;
    $("#total_par").html(nuevototal);  
}


function anular(idjornadas) {
    bootbox.confirm("¿Está seguro de desactivar la jornadas?", function(result) {
        if (result) {
            $.post("../ajax/asistencias.php?op=anular", { idjornadas: idjornadas}, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para activar registros
function activar(idjornadas) {
    bootbox.confirm("¿Está seguro de activar la jornada?", function(result) {
        if (result) {
            $.post("../ajax/asistencias.php?op=activar", { idjornadas: idjornadas }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}


//refrescar div
/*$('#btnGuardar').click(function(){
    setTimeout("location.reload()",1000);
});*/

//BUSQUEDA EN TABLA PARTICIPANTES
$("#search").keyup(function(e){
    var $searchInput = $("#search");
    var $tableRows = $("#detalles tbody tr");
    
    $searchInput.keyup(function(e) {
        var searchTerm = $searchInput.val().toLowerCase();
    
        // Normalizar el texto de búsqueda
        searchTerm = normalizeText(searchTerm);
    
        $tableRows.each(function() {
            var rowText = $(this).text().toLowerCase();
    
            // Normalizar el texto en la fila
            rowText = normalizeText(rowText);
    
            if (rowText.indexOf(searchTerm) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
});

// Función para normalizar el texto
function normalizeText(text) {
    return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}
});

/*function contarTablaDetalle(){
    var row=document.getElementById('detalles').rows.length; 
    for(i=0;i<row;i++){ 
        var filas = parseFloat(i+1);
        var totalDetalle = filas-1; //Se le resta la fila del thead de la tabla
        console.log('Row '+parseFloat(i+1)+' : '+document.getElementById('detalles').rows[i].cells.length +' column'); 
        $("#total_par").html(totalDetalle);
    } 
}*/

function contarTablaDetalle() {
    var table = document.getElementById('detalles');
    var rows = table.getElementsByClassName('filas');
    var totalDetalle = rows.length;

    var primeravezCount = 0;

    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var cells = row.getElementsByTagName('td');

        // Obtener la tercera celda (columna) que contiene el ícono "fa-check"
        var thirdCell = cells[2];

        // Verificar que thirdCell no sea undefined antes de intentar acceder a querySelector
        var iconElement = null; // Inicializamos a null
        if (thirdCell) {
            iconElement = thirdCell.querySelector('i.fa-check');
        }
        
        // Actualizamos el contador de participantes por primera vez si se encuentra el ícono de cheque marcado
        if (iconElement) {
            primeravezCount++;
        }
    }

    document.getElementById("total_par").innerHTML = totalDetalle;
    document.getElementById("primeravez_par").innerHTML = primeravezCount; // Actualizamos el contador de participantes por primera vez.
}



init();
