var tabla;
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    
    $("#formulario").on("submit",function(e)
    {
      guardaryeditar(e);  
    });
	$.post("../ajax/corredor.php?op=selectPlanes", function(r) {
        $("#idplan").html(r);
        $("#idplan").selectpicker('refresh');
    }); 
    //Agregamos Organizaciones o alianzas
    $.post("../ajax/corredor.php?op=selectAlianzas", function(r) {
        $("#idalianza").html(r);
        $("#idalianza").selectpicker('refresh');
    });

    //Agregamos Organizaciones o alianzas
    $.post("../ajax/corredor.php?op=selectDepartamento", function(r) {
        $("#iddepartamento").html(r);
        $("#iddepartamento").selectpicker('refresh');
    });
}

//Función limpiar
function limpiar(){
    $("#idcorredor").val("");
    $("#idplan").val("0");
	$("#idplan").selectpicker('refresh');
    $("#idalianza").val("0"); //Se agregó Alianza
	$("#idalianza").selectpicker('refresh'); //Se agregó Alianza
    $("#nombrecor").val("");
    $("#descripcioncor").val("");
    //$("#sector").val(""); //Se agregó Sector
    //$("#sector").selectpicker('refresh'); //Se Agregó Sector
    $("#iddepartamento").val(""); //se agregó departamento
    $("#iddepartamento").selectpicker('refresh'); //se agregó departamento
    //limpiamos el contenedor de corredoralianzas 
    $("#alianzasContainer").empty();
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
    $("#idalianza").prop("required", true);
}

//Función listar
function listar() {
    tabla=$("#tbllistado").dataTable({
        responsive: true,
        "aProcessing":true,//Activamos el procesamiento del datatables
        "aServerSide":true,//Paginación y filtrado realizados por el servidor
        dom:'Bfrtip',//Definimos los elementos del control de tabla
        buttons:[
            'copy',
            'excel',
            'csv',
            'pdf',
            'print'
        ],
        "ajax":{
                url:'../ajax/corredor.php?op=listar',
                type:"get",
                dataType:"json",
                error:function(e){
                    console.log(e.responseText);
                }
        },
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
  $("#btnGuardar").prop("disabled",true);
  var formData = new FormData($("#formulario")[0]);
  
  $.ajax({
      url:"../ajax/corredor.php?op=guardaryeditar",
      type:"POST",
      data:formData,
      contentType:false,
      processData:false,
      
      success: function(datos)
      { 
          bootbox.alert(datos);
          mostrarform(false);
          tabla.ajax.reload();
          $("#idalianza").prop("required", true);
      }
  });
  limpiar();
}

function mostrar(idcorredor) {
    
    $("#idalianza").prop("required", false);
    
    // Primera solicitud AJAX para obtener los datos del corredor
    $.post("../ajax/corredor.php?op=mostrar", { idcorredor: idcorredor }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idcorredor").val(data.IDCORREDOR);
        $("#nombrecor").val(data.NOMBRECOR);
        $("#descripcioncor").val(data.DESCRIPCIONCOR);
        $("#idplan").val(data.IDPLAN);
        $("#idplan").selectpicker('refresh');
        //$("#idalianza").val(data.IDORGANIZACION);
        $("#idalianza").selectpicker('refresh');
        $("#iddepartamento").val(data.IDDEPARTAMENTO);
        $("#iddepartamento").selectpicker('refresh');

        $.post("../ajax/corredor.php?op=obtenerAlianzas", { idcorredor: idcorredor }, function (data, status) {
            var dataList = JSON.parse(data);
            var listContainer = $("#alianzasContainer");
            
            listContainer.empty();
            
            dataList.forEach(function (item) {
                var listItem = $("<li></li>");
                listItem.html(item.IDALIANZA + " - " + item.NOMBREORG); // Modifica esto para mostrar los datos que desees
                listContainer.append(listItem);
            });
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log("Error en la solicitud AJAX: " + errorThrown);
        });
    });
}

//Función para desactivar registros
function desactivar(idcorredor){
    bootbox.confirm("Está Seguro de desactivar el corredor",function (result) {
        if(result){
            $.post("../ajax/corredor.php?op=desactivar",{idcorredor:idcorredor},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para activar registros
function activar(idcorredor){
    bootbox.confirm("Está Seguro de activar el corredor",function (result) {
        if(result){
            $.post("../ajax/corredor.php?op=activar",{idcorredor:idcorredor},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}
init();
