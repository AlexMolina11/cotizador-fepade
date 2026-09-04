var tabla;
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    
    $("#formulario").on("submit",function(e)
    {
      guardaryeditar(e);  
    });

    //Cargamos los items al select Departamento
     $.post("../ajax/comunidad.php?op=selectDepartamento", function(r) {
        $("#iddepartamento").html(r);
        $("#iddepartamento").selectpicker('refresh');
    });

    //Cargamos los items al select Municipio
    $.post("../ajax/grupo.php?op=selectMunicipio", function(r) {
        $("#idmunicipio").html(r);
        $("#idmunicipio").selectpicker('refresh');
    });

    //Se agregó validación, si se selecciona un municipio mostrar lo siguiente
    $("#idmunicipio").change(function(){
        var idmunicipio = $("#idmunicipio").val();
        if(idmunicipio == 0) {
            //Cargamos los items al select corredor
            $.post("../ajax/grupo.php?op=selectDepartamento", function(r) {
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');
            });
        } else {  
            $.post("../ajax/grupo.php?op=selectDepa",{idmunicipio:idmunicipio},function(r) {             
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');
            });
        }
    });

    //Se agregó validación, si se selecciona un departamento mostrar lo siguiente
    $("#iddepartamento").change(function(){
        var iddepartamento = $("#iddepartamento").val();  
        if(iddepartamento == 0) {
            $.post("../ajax/grupo.php?op=selectMunicipio", function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });
        } else {
            $.post("../ajax/grupo.php?op=selectMuni",{iddepartamento:iddepartamento},function(r) {             
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });
        }
    });

    //Cargamos los items al select Centro Educativo
    $.post("../ajax/grupo.php?op=selectCentroEducativo", function(r) {
        $("#idcentroedu").html(r);
        $("#idcentroedu").selectpicker('refresh');
    });
}

//Función limpiar
function limpiar(){
    $("#idgrupo").val("");
    $("#nombregrupo").val("");
    $("#iddepartamento").val(""); //se agregó departamento
    $("#iddepartamento").selectpicker('refresh'); //se agregó departamento
    $("#idmunicipio").val(0); //Se agregó municipio
    $("#idmunicipio").selectpicker('refresh'); //se agrego municipio
    $("#idcentroedu").val(0); //Se agregó Centro Educativo
    $("#idcentroedu").selectpicker('refresh'); //se agrego Centro Educativo
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
                url:'../ajax/grupo.php?op=listar',
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
      url:"../ajax/grupo.php?op=guardaryeditar",
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

function mostrar(idgrupo){
    $.post("../ajax/grupo.php?op=mostrar",{idgrupo:idgrupo},function(data,status)
    {
        data = JSON.parse(data);
        mostrarform(true);
        
        $("#nombregrupo").val(data.NOMBREGRUPO);
        $("#idgrupo").val(data.IDGRUPO);
        $("#iddepartamento").val(data.IDDEPARTAMENTO); //Se agregó Departamento
        $("#iddepartamento").selectpicker('refresh'); //Se agregó Departamento
        $("#idmunicipio").val(data.IDMUNICIPIO); //Se agregó Municipio
        $("#idmunicipio").selectpicker('refresh'); //Se agregó Municipio
        $("#idcentroedu").val(data.IDINSTITUCION); //Se agregó Centro Educativo
        $("#idcentroedu").selectpicker('refresh'); //Se agregó Centro Educativo
    });
}

//Agregamos validación para eliminar
function eliminar(idgrupo){
    bootbox.confirm("¿Está Seguro de eliminar el grupo?",function (result) {
        if(result){
            $.post("../ajax/grupo.php?op=eliminar",{idgrupo:idgrupo},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para desactivar registros
function desactivar(idgrupo){
    bootbox.confirm("Está Seguro de desactivar el Grupo",function (result) {
        if(result){
            $.post("../ajax/grupo.php?op=desactivar",{idgrupo:idgrupo},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para activar registros
function activar(idgrupo){
    bootbox.confirm("Está Seguro de activar el Grupo",function (result) {
        if(result){
            $.post("../ajax/grupo.php?op=activar",{idgrupo:idgrupo},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}
init();
