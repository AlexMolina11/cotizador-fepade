var tabla;
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    $("#formulario").on("submit",function(e)
    {
	 guardaryeditar(e);
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
	 $.post("../ajax/jornadas.php?op=selectActividad", function(r) {
        $("#idactividades").html(r);
        $("#idactividades").selectpicker('refresh');
    });

    $('#idactividades').change(function(){
        var idactividades = $("#idactividades").val();  
         $.post("../ajax/jornadas.php?op=obtenerFecha",{idactividades:idactividades},function(r) {             
           $("#fechaactividad").val(r);
         });         
     });

    
    $('#fechajor').datepicker({
        language: 'es',
        dateFormat: 'dd/mm/yyyy'
      });
   
    //Mostrar el total de horas
    $("#horafinjor").change(function() {
        var horainicial = document.getElementById("horainijor").value;
        var horafinal = document.getElementById("horafinjor").value;
        
        //le quitamos los dos puntos del formato de hora
        var hi = horainicial.split(':');
        var hf = horafinal.split(':');

        // Convertimos hora en segundos
        var segundosiniciales = (+hi[0]) * 60 * 60 + (+hi[1]) * 60 + (+hi[2]); 
        var segundosfinales = (+hf[0]) * 60 * 60 + (+hf[1]) * 60 + (+hf[2]); 

        //Sumamos todos los segundos
        var totalsegundos = segundosfinales-segundosiniciales;

        console.log(segundosiniciales);
        console.log(segundosfinales);
        console.log(totalsegundos);

        var hour = Math.floor(totalsegundos / 3600);
        hour = (hour < 10)? '0' + hour : hour;
        var minute = Math.floor((totalsegundos / 60) % 60);
        minute = (minute < 10)? '0' + minute : minute;
        var second = totalsegundos % 60;
        second = (second < 10)? '0' + second : second;
        var duracion = hour + ':' + minute + ':' + second;
        
        document.getElementById('duracion').value = duracion;
    });

    //Mostrar el total de horas
    $("#horainijor").change(function() {
        var horainicial = document.getElementById("horainijor").value;
        var horafinal = document.getElementById("horafinjor").value;
        
        //le quitamos los dos puntos del formato de hora
        var hi = horainicial.split(':');
        var hf = horafinal.split(':');

        // Convertimos hora en segundos
        var segundosiniciales = (+hi[0]) * 60 * 60 + (+hi[1]) * 60 + (+hi[2]); 
        var segundosfinales = (+hf[0]) * 60 * 60 + (+hf[1]) * 60 + (+hf[2]); 

        //Sumamos todos los segundos
        var totalsegundos = segundosfinales-segundosiniciales;

        console.log(segundosiniciales);
        console.log(segundosfinales);
        console.log(totalsegundos);

        var hour = Math.floor(totalsegundos / 3600);
        hour = (hour < 10)? '0' + hour : hour;
        var minute = Math.floor((totalsegundos / 60) % 60);
        minute = (minute < 10)? '0' + minute : minute;
        var second = totalsegundos % 60;
        second = (second < 10)? '0' + second : second;
        var duracion = hour + ':' + minute + ':' + second;
        
        document.getElementById('duracion').value = duracion;
    });

    //Cargamos los items para los Responsables
    $.post("../ajax/jornadas.php?op=selectResponsable", function(r) {
        $("#responsablejor").html(r);
        $("#responsablejor").selectpicker('refresh');
    });
}

//Función limpiar
function limpiar(){
  $("#idactividades").focus();  
  $("#idjornadas").val("");
  $("#idactividades").val("");
  $("#idactividades").selectpicker('refresh');
  $("#nombrejor").val("");
  $("#objetivojor").val("");
  $("#lugarjor").val(""); //Se agregó lugar jornada
  $("#responsablejor").val([]); //se agregó responsable jornada
  $("#responsablejor").selectpicker('refresh');
  $("#fechajor").val("");
  $("#horainijor").val("");
  $("#horafinjor").val("");
  $("#duracion").val("");
  $("#fechainicio").val("");
  $("#fechafin").val("");
  
}



//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide()
    }else{
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
    //Se agregan fecha inicio y fin
    var fechainicio    = $("#fechainicio").val();
	var fechafin       = $("#fechafin").val();
    tabla=$("#tbllistado").dataTable({
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
                url:'../ajax/jornadas.php?op=listar',
                type:"get",
                data:{fechainicio:fechainicio,fechafin:fechafin},
                dataType:"json",
                error:function(e){
                    console.log(e.responseText);
                }
        },
        // Configuración de las columnas y sus anchos
        columnDefs: [
            { width: '5%', targets: 0 }, // Id Act.
            { width: '15%', targets: 1 }, // Actividad
            { width: '5%', targets: 2 }, // Id Jor.
            { width: '30%', targets: 3 }, // Jornada
            { width: '10%', targets: 4 }, // Fecha
            { width: '10%', targets: 5 }, // Hora Inicio
            { width: '10%', targets: 6 }, // Hora Fin
            { width: '10%', targets: 7 }, // Usuario Registro
            { width: '10%', targets: 8 }, // Estado
            { width: '10%', targets: 9 }, // Opciones
        ],
        "bDestroy":true,
        "iDisplayLength":5,//paginación
        "order":[[0,"desc"]], // Ordenar(columna,orden) 
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
function guardaryeditar(e){
  e.preventDefault();//No se activará la acción predeterminada del evento
  //$("#btnGuardar").prop("disabled",true);
  var formData = new FormData($("#formulario")[0]);
  
    //validar fecha
    var fechainicial = $("#fechajor").val();
    var fechafinal = $("#fechaactividad").val();
    //validar hora
    var horaini = document.getElementById("horainijor").value;
    var horafin = document.getElementById("horafinjor").value;

    //Convertimos a formaro yyyy-mm-dd
    fechaini = fechainicial.substr(6, 4) + '-' + fechainicial.substr(3, 2) + '-' + fechainicial.substr(0, 2);

    var hora1 = (horaini).split(":");
    var hora2 = (horafin).split(":");
    var t1 = '00';
    var t2 = '00';
    t1 = parseInt(hora1[0]) * 100 + parseInt(hora1[1]);
    t2 = parseInt(hora2[0]) * 100 + parseInt(hora2[1]);
    console.log(t1);
    console.log(t2);
    console.log(t2-t1);
    //Tiempo resultante es el resultado entre el tiempo 2 menos el tiempo 1
    var tr = t2 - t1; 

    if (fechafinal > fechaini) {
        bootbox.alert('La fecha ' + fechainicial + ' debe ser mayor o igual a la de la actividad, ¡Favor ingresarla nuevamente!');
        document.getElementById("fechajor").focus();
        document.getElementById("fechajor").style.borderColor = "red";
        document.getElementById("fechajor").value = "";
    //} else if (t1 >= t2) {
    } else if (tr < 100) {
        //bootbox.alert('Hora de fin no puede ser menor o igual a la hora de inicio');
        bootbox.alert('La jornada no puede durar menos de una hora.');
        //document.getElementById("horafinjor").focus();
        document.getElementById("horafinjor").style.borderColor = "red";
    } else {
        $.ajax({
            url: "../ajax/jornadas.php?op=guardaryeditar",
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
        document.getElementById("horafinjor").style.borderColor = "";
        document.getElementById("fechajor").style.borderColor = "";

    }
}

function mostrar(idjornadas){
    $.post("../ajax/jornadas.php?op=mostrar",{idjornadas:idjornadas},function(data,status)
    {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idjornadas").val(data.IDJORNADAS);
		$("#idactividades").val(data.IDACTIVIDADES);
        $("#idactividades").selectpicker('refresh');
        //$("#idactividades").val(data.NOMBREACT);
		$("#nombrejor").val(data.NOMBREJOR);
        $("#objetivojor").val(data.OBJETIVOJOR);
        $("#lugarjor").val(data.LUGARJOR);          
        $("#responsablejor").val(data.responsable_jornada);
        $("#responsablejor").selectpicker('refresh'); 
		//$("#fechajor").val(data.FECHA);
		$("#fechajor").datepicker("setDate", data.FECHA);
		$("#horainijor").val(data.HORAINIJOR);
		$("#horafinjor").val(data.HORAFINJOR); 
		$("#fechaactividad").val(data.FECHAINICIOACT);
        $("#duracion").val(data.DURACIONJOR);
    });
}

//Agregamos validación para eliminar
function eliminar(idjornadas){
    bootbox.confirm("¿Está Seguro de eliminar la jornada?",function (result) {
        if(result){
            $.post("../ajax/jornadas.php?op=eliminar",{idjornadas:idjornadas},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

function desactivar(idjornadas) {
    bootbox.confirm("Está Seguro de desactivar la Jornada", function(result) {
        if (result) {
            $.post("../ajax/jornadas.php?op=desactivar", { idjornadas: idjornadas }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para activar registros
function activar(idjornadas) {
    bootbox.confirm("Está Seguro de activar la Jornada", function(result) {
        if (result) {
            $.post("../ajax/jornadas.php?op=activar", { idjornadas: idjornadas }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}


init();
