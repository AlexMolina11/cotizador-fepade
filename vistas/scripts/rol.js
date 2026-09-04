var tabla;
//Función que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();

  $("#formulario").on("submit", function(e) {
    guardaryeditar(e);
  });

  //Mostrar los permisos
  $.post("../ajax/roles.php?op=permisos&id=",function(r){
    $("#acceso").html(r);
  });

  //Mostrar los permisos
  $.post("../ajax/roles.php?op=permisosacciones&id=",function(r){
    $("#accesoacciones").html(r);
  });

  //Se agregó post para tipo de Participante
  $.post("../ajax/roles.php?op=selectAreaResponsable", function(r) {
    $("#permisoareares").html(r);
    $("#permisoareares").selectpicker('refresh');
  });
}

//Función limpiar
function limpiar() {
  $("#nombrerol").val("");
  $("#permiso").val("");
  $("#idrol").val("");
  //Se agregó area responsable
	$("#permisoareares").val("0");
	$("#permisoareares").selectpicker('refresh');
} 

//Función mostrar formulario
function mostrarform(flag) {
  limpiar();
  if (flag) {
    $("#listadoregistros").hide();
    $("#formularioregistros").show();
    $("#btnGuardar").prop("disabled", false);
    $("#btnagregar").hide(); 
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
  $.post("../ajax/roles.php?op=permisos&id=",function(r){
    $("#acceso").html(r);
  });

  $.post("../ajax/roles.php?op=permisosacciones&id=",function(r){
    $("#accesoacciones").html(r);
  });
}

//Función listar
function listar() {
  tabla = $("#tbllistado")
    .dataTable({
      responsive:true,
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "Bfrtip", //Definimos los elementos del control de tabla
      buttons: ["copy", "excel", "print"],
      ajax: {
        url: "../ajax/roles.php?op=listar",
        type: "get", 
        dataType: "json",
        error: function(e) {
          console.log(e.responseText);
        }
      },
      bDestroy: true,
      iDisplayLength: 5, //paginación
      order: [[0, "desc"]] // Ordenar(columna,orden)
    })
    .DataTable();
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
    url: "../ajax/roles.php?op=guardaryeditar",
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
  $.post("../ajax/roles.php?op=permisos&id=",function(r){
    $("#acceso").html(r);
  });

  $.post("../ajax/roles.php?op=permisosacciones&id=",function(r){
    $("#accesoacciones").html(r);
  });
}

function mostrar(idrol) {
 
  $.post(
    "../ajax/roles.php?op=mostrar",
    { idrol: idrol },
    function(data, status) {
      data = JSON.parse(data);
      
      mostrarform(true);
      
      $("#nombrerol").val(data.NOMBREROL);
      $("#idrol").val(data.IDROL);
      /*if(data.PERMISOROL == 1){
        $("#permiso").prop('checked', true);
      } else {
        $("#permiso").prop('checked', false);
      }*/
      $("#permiso").val(data.PERMISOROL);
      $("#permiso").selectpicker('refresh');
      
      //Se agregó area responsable
      $("#permisoareares").val(data.PERMISOAREARES);
      $("#permisoareares").selectpicker('refresh');	
    });
  
    $.post("../ajax/roles.php?op=permisos&id="+idrol,function(r){
      $("#acceso").html(r);
    });

    $.post("../ajax/roles.php?op=permisosacciones&id="+idrol,function(r){
      $("#accesoacciones").html(r);
    });
}

//Función para desactivar registros
function desactivar(idrol) {
  bootbox.confirm("Está Seguro de desactivar el usuario?", function(result) {
    if (result) {
      $.post(
        "../ajax/roles.php?op=desactivar",
        { idrol: idrol },
        function(e) {
          bootbox.alert(e);
          tabla.ajax.reload();
        }
      );
    }
  });
}

//Función para activar registros
function activar(idrol) {
  bootbox.confirm("Está Seguro de activar el usuario", function(result) {
    if (result) {
      $.post(
        "../ajax/roles.php?op=activar",
        { idrol: idrol },
        function(e) {
          bootbox.alert(e);
          tabla.ajax.reload();
        }
      );
    }
  });
}

init();
