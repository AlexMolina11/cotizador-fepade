var tabla;
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    
    $("#formulario").on("submit",function(e)
    {
      guardaryeditar(e);  
    });
}

//Función limpiar
function limpiar(){
    $("#idtipoactividad").val("");
    $("#nombretac").val("");
}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#leyenda").show();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide()
    }else{
        $("#listadoregistros").show();
        $("#leyenda").hide();
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
    tabla=$("#tbllistado").dataTable({
        responsive:true,
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
                url:'../ajax/tipoactividad.php?op=listar',
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

//Función para guardar o editar
function guardaryeditar(e){
  e.preventDefault();//No se activará la acción predeterminada del evento
  $("#btnGuardar").prop("disabled",true);
  var formData = new FormData($("#formulario")[0]);
  
  $.ajax({
      url:"../ajax/tipoactividad.php?op=guardaryeditar",
      type:"POST",
      data:formData,
      contentType:false,
      processData:false,
      
      success: function(datos)
      { 
          bootbox.alert(datos);
          mostrarform(false);
          tabla.ajax.reload();
      }
  });
  limpiar();
}

function mostrar(idtipoactividad){
    $.post("../ajax/tipoactividad.php?op=mostrar",{idtipoactividad:idtipoactividad},function(data,status)
    {
        data = JSON.parse(data);
        mostrarform(true);
        
        $("#nombretac").val(data.NOMBRETAC);
        $("#idtipoactividad").val(data.IDTIPOACTIVIDAD);
    });
}

function eliminar(idtipoactividad){
    bootbox.confirm("¿Está Seguro de eliminar el tipo de actividad?",function (result) {
        if(result){
            $.post("../ajax/tipoactividad.php?op=eliminar",{idtipoactividad:idtipoactividad},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para desactivar registros
function desactivar(idtipoactividad){
    bootbox.confirm("Está Seguro de desactivar el tipo de actividad",function (result) {
        if(result){
            $.post("../ajax/tipoactividad.php?op=desactivar",{idtipoactividad:idtipoactividad},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para activar registros
function activar(idtipoactividad){
    bootbox.confirm("Está Seguro de activar el tipo de actividad",function (result) {
        if(result){
            $.post("../ajax/tipoactividad.php?op=activar",{idtipoactividad:idtipoactividad},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}


init();
