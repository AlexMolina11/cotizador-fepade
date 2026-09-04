var tabla;
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    
    $("#formulario").on("submit",function(e)
    {
      guardaryeditar(e);  
    });

    //Cargamos los items al select Distritos
    $.post("../ajax/cantonesycaserios.php?op=selectDistrito", function(r) {
        $("#idmunicipio").html(r);
        $("#idmunicipio").selectpicker('refresh');
    });

    //Cargamos los items al select Nuevos Municipio
    $.post("../ajax/cantonesycaserios.php?op=selectNvoMunicipio", function(r) {
        $("#idnvomunicipio").html(r);
        $("#idnvomunicipio").selectpicker('refresh');
    });

    //Cargamos los items al select Departamento
    $.post("../ajax/cantonesycaserios.php?op=selectDepartamento", function(r) {
        $("#iddepartamento").html(r);
        $("#iddepartamento").selectpicker('refresh');
    });

    //Se agregó validación, si se selecciona un departamento mostrar lo siguiente
    $("#iddepartamento").change(function(){
        var iddepartamento = $("#iddepartamento").val();  
        if(iddepartamento == 0) {
            $.post("../ajax/cantonesycaserios.php?op=selectDistrito", function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });
            $.post("../ajax/cantonesycaserios.php?op=selectNvoMunicipio", function(r) {
                $("#idnvomunicipio").html(r);
                $("#idnvomunicipio").selectpicker('refresh');
            });
        } else {
            $.post("../ajax/cantonesycaserios.php?op=selectDist", {iddepartamento: iddepartamento}, function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });
            $.post("../ajax/cantonesycaserios.php?op=selectnvoMuni", {iddepartamento: iddepartamento}, function(r) {
                $("#idnvomunicipio").html(r);
                $("#idnvomunicipio").selectpicker('refresh');
            });
        }
    });

    //Si seleccionamos distrito cambia municipio y departamento
    $("#idmunicipio").change(function(){
        var idmunicipio = $("#idmunicipio").val();
        if(idmunicipio == 0) {
            $.post("../ajax/cantonesycaserios.php?op=selectDepartamento", function(r) {
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');
            });
            $.post("../ajax/cantonesycaserios.php?op=selectNvoMunicipio", function(r) {
                $("#idnvomunicipio").html(r);
                $("#idnvomunicipio").selectpicker('refresh');
            });
        } else {
            $.post("../ajax/cantonesycaserios.php?op=selectDepa", {idmunicipio: idmunicipio}, function(r) {
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');
            });
            $.post("../ajax/cantonesycaserios.php?op=selectnvoMuniByDist", {idmunicipio: idmunicipio}, function(r) {
                $("#idnvomunicipio").html(r);
                $("#idnvomunicipio").selectpicker('refresh');
            });
        }
    });

    //Cuando se selecciona un distrito
    $("#idnvomunicipio").change(function(){
        var idnvomunicipio = $("#idnvomunicipio").val();
        if(idnvomunicipio == 0) {
            $.post("../ajax/cantonesycaserios.php?op=selectDepartamento", function(r) {
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');
            });
            $.post("../ajax/cantonesycaserios.php?op=selectDistrito", function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });
        } else { 
            $.post("../ajax/cantonesycaserios.php?op=selectDepaByNvoMuni", {idnvomunicipio: idnvomunicipio}, function(r) {
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');
            });
            $.post("../ajax/cantonesycaserios.php?op=selectDistByNvoMuni", {idnvomunicipio: idnvomunicipio}, function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });
        }
    });
}

//Función limpiar
function limpiar(){
    $("#idcantoncaserio").val("");
    $("#nombrecantoncaserio").val("");
    $("#idmunicipio").val(""); 
    $("#idmunicipio").selectpicker('refresh'); 
    $("#idnvomunicipio").val(""); 
    $("#idnvomunicipio").selectpicker('refresh'); 
    $("#iddepartamento").val(""); 
    $("#iddepartamento").selectpicker('refresh'); 
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
    location.reload(); //Recargamos la pagina cada que cancelamos el formulario.   
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
                url:'../ajax/cantonesycaserios.php?op=listar',
                type:"get",
                dataType:"json",
                error:function(e){
                    console.log(e.responseText);
                }
        },
        "bDestroy":true,
        "iDisplayLength":5,//paginación
        "order":[[0,"asc"]], // Ordenar(columna,orden) 
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
      url:"../ajax/cantonesycaserios.php?op=guardaryeditar",
      type:"POST",
      data:formData,
      contentType:false,
      processData:false,
      
      success: function(datos) {
        if(datos == "Existente"){
            bootbox.alert({ //Se modificó el bootbox para ejecutar una acción al poner ok
                message: "Cantón/Caserío ya existente, favor verificar los datos.",
                callback: function (result) {
                    $("#btnGuardar").prop("disabled",false);
                }
            });
        } else {
            bootbox.alert({ //Se modificó el bootbox para ejecutar una acción al poner ok
                message: datos,
                callback: function (result) {
                    tabla.ajax.reload();
                    location.reload();
                }
            });
        }
    }
  });
}

function mostrar(idcantoncaserio){
    limpiar();
    $.post("../ajax/cantonesycaserios.php?op=mostrar",{idcantoncaserio:idcantoncaserio},function(data,status)
    {
        data = JSON.parse(data);
        mostrarform(true);
        
        $("#nombrecantoncaserio").val(data.NOMBRECANTONCASERIO);
        $("#idcantoncaserio").val(data.IDCANTONCASERIO);
        $("#idmunicipio").val(data.IDMUNICIPIO); 
        $("#idmunicipio").selectpicker('refresh'); 
        $("#idnvomunicipio").val(data.IDNVOMUN); 
        $("#idnvomunicipio").selectpicker('refresh');
        $("#iddepartamento").val(data.IDDEPARTAMENTO); 
        $("#iddepartamento").selectpicker('refresh'); 
    });
}

function eliminar(idcantoncaserio){
    bootbox.confirm("¿Está Seguro de eliminar el cantón/caserío?",function (result) {
        if(result){
            $.post("../ajax/cantonesycaserios.php?op=eliminar",{idcantoncaserio:idcantoncaserio},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para desactivar registros
function desactivar(idcantoncaserio){
    bootbox.confirm("Está Seguro de desactivar el cantón/caserío",function (result) {
        if(result){
            $.post("../ajax/cantonesycaserios.php?op=desactivar",{idcantoncaserio:idcantoncaserio},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para activar registros
function activar(idcantoncaserio){
    bootbox.confirm("Está Seguro de activar el cantón/caserío",function (result) {
        if(result){
            $.post("../ajax/cantonesycaserios.php?op=activar",{idcantoncaserio:idcantoncaserio},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}
init();
