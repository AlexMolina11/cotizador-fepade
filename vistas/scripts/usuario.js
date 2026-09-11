var tabla;
//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    cambiarclave(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    $("#actclave").on("submit", function(e) {
        editarclave(e);
    });

    //Cargamos los items al select categoria
    $.post("../ajax/usuario.php?op=selectRoles", function(r) {
        $("#idrol").html(r);
        $("#idrol").selectpicker('refresh');
    });

    //Cargamos los items al select Organización Ejecutora
    $.post("../ajax/usuario.php?op=selectOrganizacionEjecutora", function(r) {
        $("#orgejecutora").html(r);
        $("#orgejecutora").selectpicker('refresh');
    });

    function validarTelefono(input) {
        input.value = input.value
            .replace(/[^0-9-]/g, '')        // Solo números y guion
            .replace(/(\d{4})(\d)/, '$1-$2') // Inserta el guion automáticamente
            .replace(/(-\d{4})\d+/, '$1');   // Evita más de 8 dígitos
    }

    document.getElementById("telusu").addEventListener("input", function() {
        validarTelefono(this);
    });

    document.getElementById("extensionusu").addEventListener("input", function() {
        validarTelefono(this);
    });
}

//Función limpiar
function limpiar() {
    $("#nombreusu").val("");
    $("#email").val("");
    $("#telusu").val("");
    $("#extensionusu").val("");
    $("#login").val("");
    $("#login").prop("disabled", false);
    $("#clave").val("");
    $("#idrol").val("");
    $("#idrol").selectpicker('refresh');
    $("#idusuariosusu").val("");
    $("#passw").val("");
    //Agregamos Organización Ejecutora
    $("#orgejecutora").val("0");
    $("#orgejecutora").selectpicker('refresh');	
}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    $("#labelclave").show();
    $("#clave").show();
    
    if (flag) {

        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#frmnuevaclave").hide()
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide()
        //Quitamos read only despues de mostrar los datos y editar.
        $("#login").prop("readonly", false);
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#frmnuevaclave").hide()
        $("#btnagregar").show()
    }
}

//Función mostrar formulario
function cambiarclave(flag) {
    limpiar();
   
    if (flag) {

        $("#listadoregistros").hide();
        $("#formularioregistros").hide();
        $("#frmnuevaclave").show()
        $("#btnCambio").prop("disabled", false);
        $("#btnagregar").hide()
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#frmnuevaclave").hide()
        $("#btnagregar").show()
    }
}

//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);

}

//Función cancelarform actualizacion
function cancelaform() {
    limpiar();
    mostrarform(false);

}


//Función listar
function listar() {
    tabla = $("#tbllistado")
        .dataTable({
            aProcessing: true, //Activamos el procesamiento del datatables
            aServerSide: true, //Paginación y filtrado realizados por el servidor
            dom: "Bfrtip", //Definimos los elementos del control de tabla
            buttons: ["copy", "excel", "csv", "pdf", "print"],
            ajax: {
                url: "../ajax/usuario.php?op=listar",
                type: "get",
                dataType: "json",
                error: function(e) {
                    console.log(e.responseText);
                }
            },
            bDestroy: true,
            iDisplayLength: 5, //paginación
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
        url: "../ajax/usuario.php?op=guardaryeditar",
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

//Función para guardar o editar
function editarclave(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnCambio").prop("disabled", true);
    var formData = new FormData($("#actclave")[0]);

    $.ajax({
        url: "../ajax/usuario.php?op=editarclave",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos) {
            bootbox.alert(datos);
            cambiarclave(false);
            tabla.ajax.reload();
        }
    });
    limpiar();

}

function mostrar(idusuariosusu) {

    $.post(
        "../ajax/usuario.php?op=mostrar", { idusuariosusu: idusuariosusu },
        function(data, status) {
            data = JSON.parse(data);

            mostrarform(true);

            $("#nombreusu").val(data.NOMBREUSU);
            $("#idrol").val(data.IDROL);
            $("#idrol").selectpicker('refresh');

            $("#email").val(data.EMAIL);

            $("#telusu").val(data.TELUSU);
            $("#extensionusu").val(data.EXTENSIONUSU);

            $("#login").val(data.USERUSU);
            $("#login").prop("readonly", true);
            //$("#clave").val(data.CONTRASENAUSU);

            $("#idusuariosusu").val(data.IDUSUARIOSUSU);
            var id=data.IDUSUARIOSUSU;

            $("#orgejecutora").val(data.IDORGEJE);
		    $("#orgejecutora").selectpicker('refresh');	
           
           //ocultando campos de clave 
                $("#labelclave").hide();
                $("#clave").hide();
                $('#clave').removeAttr("required");
            
            
        });
}

function clave(idusuariosusu) {
    $.post(
        "../ajax/usuario.php?op=mostrar", { idusuariosusu: idusuariosusu },
        function(data, status) {
            data = JSON.parse(data);

            cambiarclave(true);

            $("#usu").val(data.NOMBREUSU);
            $("#usu").prop("disabled", true);
            $("#idusuario").val(data.IDUSUARIOSUSU);
            
        }); 
             
}        

//Función para desactivar registros
function desactivar(idusuariosusu) {
    bootbox.confirm("Está Seguro de desactivar el usuario?", function(result) {
        if (result) {
            $.post(
                "../ajax/usuario.php?op=desactivar", { idusuariosusu: idusuariosusu },
                function(e) {
                    bootbox.alert(e);
                    tabla.ajax.reload();
                }
            );
        }
    });
}

//Función para activar registros
function activar(idusuariosusu) {
    bootbox.confirm("Está Seguro de activar el usuario", function(result) {
        if (result) {
            $.post(
                "../ajax/usuario.php?op=activar", { idusuariosusu: idusuariosusu },
                function(e) {
                    bootbox.alert(e);
                    tabla.ajax.reload();
                }
            );
        }
    });
}

init();
