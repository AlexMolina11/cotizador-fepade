var tabla;
//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });


    //Cargamos los items al select institución
    $.post("../ajax/institucion.php?op=selectTipoInstitucion", function(r) {
        $("#idtipoinstitucion").html(r);
        $("#idtipoinstitucion").selectpicker('refresh');
    });

    //Cargamos los items al select corredor
    $.post("../ajax/institucion.php?op=selectCorredor", function(r) {
        $("#idcorredor").html(r);
        $("#idcorredor").selectpicker('refresh');
    });

    //Cargamos los items al select corredor
    $.post("../ajax/institucion.php?op=selectCantoncaserio", function(r) {
        $("#idcantoncaserio").html(r);
        $("#idcantoncaserio").selectpicker('refresh');
    });

    //Cargamos los items al select Distritos
    $.post("../ajax/institucion.php?op=selectDistrito", function(r) {
        $("#idmunicipio").html(r);
        $("#idmunicipio").selectpicker('refresh');
    });

    //Cargamos los items al select Nuevos Municipio
    $.post("../ajax/institucion.php?op=selectNvoMunicipio", function(r) {
        $("#idnvomunicipio").html(r);
        $("#idnvomunicipio").selectpicker('refresh');
    });

    //Cargamos los items al select Departamento
    $.post("../ajax/institucion.php?op=selectDepartamento", function(r) {
        $("#iddepartamento").html(r);
        $("#iddepartamento").selectpicker('refresh');
    });

    //Se agregó validación, si se selecciona un departamento mostrar lo siguiente
    $("#iddepartamento").change(function(){
        var iddepartamento = $("#iddepartamento").val();  
        if(iddepartamento == 0) {
            $.post("../ajax/institucion.php?op=selectDistrito", function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });

            $.post("../ajax/institucion.php?op=selectNvoMunicipio", function(r) {
                $("#idnvomunicipio").html(r);
                $("#idnvomunicipio").selectpicker('refresh');
            });

            $.post("../ajax/institucion.php?op=selectCorredor", function(r) {
                $("#idcorredor").html(r);
                $("#idcorredor").selectpicker('refresh');
            });

            $.post("../ajax/institucion.php?op=selectCantoncaserio", function(r) {
                $("#idcantoncaserio").html(r);
                $("#idcantoncaserio").selectpicker('refresh');
            });
        } else {
            $.post("../ajax/institucion.php?op=selectDist", {iddepartamento: iddepartamento}, function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });
            
            $.post("../ajax/institucion.php?op=selectnvoMuni", {iddepartamento: iddepartamento}, function(r) {
                $("#idnvomunicipio").html(r);
                $("#idnvomunicipio").selectpicker('refresh');
            });

            $.post("../ajax/institucion.php?op=selectCorredorDep",{iddepartamento:iddepartamento},function(r) {             
                $("#idcorredor").html(r);
                $("#idcorredor").selectpicker('refresh');
            });

            $.post("../ajax/institucion.php?op=selectCantoncaserioDep",{iddepartamento:iddepartamento},function(r) {             
                $("#idcantoncaserio").html(r);
                $("#idcantoncaserio").selectpicker('refresh');
            });
        }
    });

    //Se agregó validación, si se selecciona un municipio mostrar lo siguiente
    $("#idmunicipio").change(function(){
        var idmunicipio = $("#idmunicipio").val();
        if(idmunicipio == 0) {
            $.post("../ajax/institucion.php?op=selectDepartamento", function(r) {
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');
            });
            $.post("../ajax/institucion.php?op=selectNvoMunicipio", function(r) {
                $("#idnvomunicipio").html(r);
                $("#idnvomunicipio").selectpicker('refresh');
            });
            $.post("../ajax/institucion.php?op=selectCorredor", function(r) {
                $("#idcorredor").html(r);
                $("#idcorredor").selectpicker('refresh');
            });
            $.post("../ajax/institucion.php?op=selectCantoncaserio", function(r) {
                $("#idcantoncaserio").html(r);
                $("#idcantoncaserio").selectpicker('refresh');
            });
        } else {  
            $.post("../ajax/institucion.php?op=selectDepa", {idmunicipio: idmunicipio}, function(r) {
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');

                var iddepartamento = $("#iddepartamento").val(); 
                $.post("../ajax/institucion.php?op=selectCorredorDep",{iddepartamento:iddepartamento},function(r) {             
                    $("#idcorredor").html(r);
                    $("#idcorredor").selectpicker('refresh');
                });
                $.post("../ajax/institucion.php?op=selectCantoncaserioDep",{iddepartamento:iddepartamento},function(r) {             
                    $("#idcantoncaserio").html(r);
                    $("#idcantoncaserio").selectpicker('refresh');
                });
            });
            $.post("../ajax/institucion.php?op=selectnvoMuniByDist", {idmunicipio: idmunicipio}, function(r) {
                $("#idnvomunicipio").html(r);
                $("#idnvomunicipio").selectpicker('refresh');
            });
        }
    });

    //Cuando se selecciona un distrito
    $("#idnvomunicipio").change(function(){
        var idnvomunicipio = $("#idnvomunicipio").val();
        if(idnvomunicipio == 0) {
            $.post("../ajax/institucion.php?op=selectDepartamento", function(r) {
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');
            });
            $.post("../ajax/institucion.php?op=selectDistrito", function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });
            $.post("../ajax/institucion.php?op=selectCorredor", function(r) {
                $("#idcorredor").html(r);
                $("#idcorredor").selectpicker('refresh');
            });
            $.post("../ajax/institucion.php?op=selectCantoncaserio", function(r) {
                $("#idcantoncaserio").html(r);
                $("#idcantoncaserio").selectpicker('refresh');
            });
        } else { 
            $.post("../ajax/institucion.php?op=selectDepaByNvoMuni", {idnvomunicipio: idnvomunicipio}, function(r) {
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');

                var iddepartamento = $("#iddepartamento").val(); 
                $.post("../ajax/institucion.php?op=selectCorredorDep",{iddepartamento:iddepartamento},function(r) {             
                    $("#idcorredor").html(r);
                    $("#idcorredor").selectpicker('refresh');
                });
                $.post("../ajax/institucion.php?op=selectCantoncaserioDep",{iddepartamento:iddepartamento},function(r) {             
                    $("#idcantoncaserio").html(r);
                    $("#idcantoncaserio").selectpicker('refresh');
                });
            });
            $.post("../ajax/institucion.php?op=selectDistByNvoMuni", {idnvomunicipio: idnvomunicipio}, function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });
        }
    });
    
    $('.solo-numero').keyup(function() {
        this.value = (this.value + '').replace(/[^0-9]/g, '');
    });
    
    $('#fechaingresoins').datepicker({
        language: 'es',
        dateFormat: 'dd/mm/yy',
        orientation: "bottom left"
      });

      $('#fechasalidains').datepicker({
        language: 'es',
        dateFormat: 'dd/mm/yy',
        orientation: "bottom left"
      });
      
    function validarLongitudMaxima(input) {
        if (input.value.length > 10) {
            input.value = input.value.slice(0, 10); // Limita a 10 caracteres
        }
    }

    function validarPuntoFinal(input) {
        // No permitir que el campo termine con un punto
        if (input.value[input.value.length - 1] === '.' && input.value.split('.').length > 2) {
            input.value = input.value.slice(0, -1);  // Elimina el punto si ya hay uno
        }
    }

    document.getElementById('latitud').addEventListener('input', function (e) {
        e.target.value = e.target.value.replace(/[^0-9.-]/g, ''); // Permite solo números, guion (-) y punto (.)
        validarLongitudMaxima(e.target);
        validarPuntoFinal(e.target);
    });

    document.getElementById('longitud').addEventListener('input', function (e) {
        e.target.value = e.target.value.replace(/[^0-9.-]/g, ''); // Permite solo números, guion (-) y punto (.)
        validarLongitudMaxima(e.target);
        validarPuntoFinal(e.target);
    });
}

//Función limpiar
function limpiar() {
    $("#idinstitucion").val("");
    $("#nombreins").val("");
    $("#codigoins").val("");
    $("#idtipoinstitucion").val("");
    $("#idtipoinstitucion").selectpicker('refresh');
    $("#idcorredor").val("");
    $("#idcorredor").selectpicker('refresh');
    $("#idcantoncaserio").val(""); //Se agregó cantón/caserío
    $("#idcantoncaserio").selectpicker('refresh');
    $("#idmunicipio").val(""); //Ahora son distritos
    $("#idmunicipio").selectpicker('refresh'); 
    $("#idnvomunicipio").val(""); //Se agregó nuevo municipio
    $("#idnvomunicipio").selectpicker('refresh'); 
    $("#iddepartamento").val(""); 
    $("#iddepartamento").selectpicker('refresh'); 
    $("#ubicacionins").val("");
    $("#latitud").val(""); //se agregó Latitud
    $("#longitud").val(""); //se agregó Longitud
    $("#zonains").val("");
    $("#zonains").selectpicker('refresh');
    $("#fechaingresoins").val("");
    $("#fechasalidains").val("");
    $("#nombredirectorins").val("");
    $("#correodir").val(""); //Se agregó correo director
    $("#telefonoins").val("");
    $("#telefonodirins").val(""); 
    $("#mat2018ins").val("");
    $("#mat2019ins").val("");
    $("#mat2020ins").val("");
    $("#mat2021ins").val("");
    $("#mat2022ins").val("");
    $("#mat2023ins").val("");
    $("#mat2024ins").val("");
    $("#mat2025ins").val("");
    $("#mat2026ins").val("");
    $("#numerodocfemeninoins").val("");
    $("#numerodocmasculinoins").val("");
    $("#turnoins").val("");
    $("#turnoins").selectpicker('refresh');
    $("#niveles_educativos").val([]); // Limpia la selección múltiple
    $("#niveles_educativos").selectpicker('refresh'); // Refresca el select para mostrar que está vacío
    $("#fechamodins").val("");
    $("#mat18inshom").val("");
    $("#mat18insmuj").val("");
    $("#mat19inshom").val("");
    $("#mat19insmuj").val("");
    $("#mat20inshom").val("");
    $("#mat20insmuj").val("");
    $("#mat21inshom").val("");
    $("#mat21insmuj").val("");
    $("#mat22inshom").val("");
    $("#mat22insmuj").val("");
    $("#mat23inshom").val("");
    $("#mat23insmuj").val("");
    $("#mat24inshom").val("");
    $("#mat24insmuj").val("");
    $("#mat25inshom").val("");
    $("#mat25insmuj").val("");
    $("#mat26inshom").val("");
    $("#mat26insmuj").val("");

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
    location.reload(); //Recargamos la pagina cada que cancelamos el formulario.
}

//Función cancelarform
function regresarform() {
    limpiar();
    mostrarform(false);
    //Mostramos boton Guardar y Cancelar
    $("#btnGuardar").css('display', 'inline');
    $("#btnCancelar").css('display', 'inline');
    //ocultamos el boton regresar
    $("#btnRegresar").css('display', 'none');
    //Regresamos los campos como estaban
    $("#nombreins").prop("disabled", false)
    $("#codigoins").prop("disabled", false)
    $("#idtipoinstitucion").prop("disabled", false)
    $("#idcorredor").prop("disabled", false)
    $("#idcantoncaserio").prop("disabled", false) //se agregó cantón/caserío
    $("#idmunicipio").prop("disabled", false) 
    $("#idnvomunicipio").prop("disabled", false) //Se agregó nuevo municipio
    $("#iddepartamento").prop("disabled", false) 
    $("#zonains").prop("disabled", false)
    $("#zonains").selectpicker('refresh');
    $("#ubicacionins").prop("disabled", false)
    $("#latitud").prop("disabled", false) //Se agregó latitud
    $("#longitud").prop("disabled", false) //se agregó longitud
    $("#fechaingresoins").prop("disabled", false)
    $("#fechasalidains").prop("disabled", false)
    $("#nombredirectorins").prop("disabled", false)
    $("#correodir").prop("disabled", false) //se agregó Correo director
    $("#telefonoins").prop("disabled", false)
    $("#telefonodirins").prop("disabled", false) 
    $("#mat2018ins").prop("disabled", false)
    $("#mat2019ins").prop("disabled", false)
    $("#mat2020ins").prop("disabled", false)
    $("#mat2021ins").prop("disabled", false)
    $("#mat2022ins").prop("disabled", false)
    $("#mat2023ins").prop("disabled", false)
    $("#mat2024ins").prop("disabled", false)
    $("#mat2025ins").prop("disabled", false)
    $("#mat2026ins").prop("disabled", false)
    $("#numerodocfemeninoins").prop("disabled", false)
    $("#numerodocmasculinoins").prop("disabled", false)
    $("#turnoins").prop("disabled", false)
    $("#niveles_educativos").prop("disabled", false); 
    $("#niveles_educativos").selectpicker('refresh'); // Refresca el select para mostrar que está habilitado
    $("#idinstitucion").prop("disabled", false)
    $("#mat18inshom").prop("disabled", false)
    $("#mat18insmuj").prop("disabled", false)
    $("#mat19inshom").prop("disabled", false)
    $("#mat19insmuj").prop("disabled", false)
    $("#mat20inshom").prop("disabled", false)
    $("#mat20insmuj").prop("disabled", false)
    $("#mat21inshom").prop("disabled", false)
    $("#mat21insmuj").prop("disabled", false)
    $("#mat22inshom").prop("disabled", false)
    $("#mat22insmuj").prop("disabled", false)
    $("#mat23inshom").prop("disabled", false)
    $("#mat23insmuj").prop("disabled", false)
    $("#mat24inshom").prop("disabled", false)
    $("#mat24insmuj").prop("disabled", false)
    $("#mat25inshom").prop("disabled", false)
    $("#mat25insmuj").prop("disabled", false)
    $("#mat26inshom").prop("disabled", false)
    $("#mat26insmuj").prop("disabled", false)
}

//Función listar
function listar() {

    tabla = $("#tbllistado")
        .dataTable({
            responsive:true,
            aProcessing: true, //Activamos el procesamiento del datatables
            aServerSide: true, //Paginación y filtrado realizados por el servidor
            dom: "Bfrtip", //Definimos los elementos del control de tabla
            buttons: ["copy", "excel", "csv", "pdf", "print"],
            ajax: {
                url: "../ajax/institucion.php?op=listar",
                type: "get",
                dataType: "json",
                error: function(e) {
                    console.log(e.responseText);
                }
            },
            bDestroy: true,
            iDisplayLength: 5, //paginación
            order: [ [0, "desc"] ], // Ordenar(columna,orden)
            "language":{
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
        url: "../ajax/institucion.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos) {
            bootbox.alert({
                message: datos,
                callback: function (result) {
                    tabla.ajax.reload();
                    location.reload();
                }
            });
        }
    });
}

function mostrar(idinstitucion) {
    //alert(idinstitucion);
    $.post(
        "../ajax/institucion.php?op=mostrar", { idinstitucion: idinstitucion },
        function(data, status) {
            data = JSON.parse(data);
            mostrarform(true);
            
            $("#nombreins").val(data.NOMBREINS);
            $("#codigoins").val(data.CODIGOINS);
            $("#idtipoinstitucion").val(data.IDTIPOINSTITUCION);
            $("#idtipoinstitucion").selectpicker('refresh');
            $("#idcorredor").val(data.IDCORREDOR); 
            $("#idcorredor").selectpicker('refresh');
            $("#idcantoncaserio").val(data.IDCANTONCASERIO); //Se agregó Cantón/Caserío
            $("#idcantoncaserio").selectpicker('refresh');
            $("#idmunicipio").val(data.IDMUNICIPIO); 
            $("#idmunicipio").selectpicker('refresh'); 
            $("#idnvomunicipio").val(data.IDNVOMUN); //Se agregó nuevo municipio
            $("#idnvomunicipio").selectpicker('refresh'); 
            $("#iddepartamento").val(data.IDDEPARTAMENTO); 
            $("#iddepartamento").selectpicker('refresh'); 
            $("#zonains").val(data.ZONAINS);
            $("#zonains").selectpicker('refresh');
            $("#ubicacionins").val(data.UBICACIONINS);
            $("#latitud").val(data.LATITUDINS); //se agregó latitud
            $("#longitud").val(data.LONGITUDINS); //se agregó longitud
            $("#fechaingresoins").val(data.FECHAINGRESOINS);
            $("#fechasalidains").val(data.FECHASALIDAINS);
            $("#nombredirectorins").val(data.NOMBREDIRECTORINS);
            $("#correodir").val(data.CORREODIRINS); //Se agregó correo del director
            $("#telefonoins").val(data.TELEFONOINS);
            $("#telefonodirins").val(data.TELEFONODIRINS); 
            $("#mat2018ins").val(data.MAT2018INS);
            $("#mat2019ins").val(data.MAT2019INS);
            $("#mat2020ins").val(data.MAT2020INS);
            $("#mat2021ins").val(data.MAT2021INS);
            $("#mat2022ins").val(data.MAT2022INS);
            $("#mat2023ins").val(data.MAT2023INS);
            $("#mat2024ins").val(data.MAT2024INS);
            $("#mat2025ins").val(data.MAT2025INS);
            $("#mat2026ins").val(data.MAT2026INS);
            $("#numerodocfemeninoins").val(data.NUMERODOCFEMENINOINS);
            $("#numerodocmasculinoins").val(data.NUMERODOCMASCULINOINS);
            $("#anterior_turno").html(data.TURNOINS); 
            $("#turnoins").val(data.TURNOINS); 
            $("#turnoins").selectpicker('refresh'); 
            // Configura los niveles educativos seleccionados
            $("#niveles_educativos").val(data.niveles_educativos);
            $("#niveles_educativos").selectpicker('refresh');
            $("#idinstitucion").val(data.IDINSTITUCION);
            $("#fechamodins").val(data.FECHAMODINS);
            $("#mat18inshom").val(data.MAT2018INSHOM);
            $("#mat18insmuj").val(data.MAT2018INSMUJ);
            $("#mat19inshom").val(data.MAT2019INSHOM);
            $("#mat19insmuj").val(data.MAT2019INSMUJ);
            $("#mat20inshom").val(data.MAT2020INSHOM);
            $("#mat20insmuj").val(data.MAT2020INSMUJ);
            $("#mat21inshom").val(data.MAT2021INSHOM);
            $("#mat21insmuj").val(data.MAT2021INSMUJ);
            $("#mat22inshom").val(data.MAT2022INSHOM);
            $("#mat22insmuj").val(data.MAT2022INSMUJ);
            $("#mat23inshom").val(data.MAT2023INSHOM);
            $("#mat23insmuj").val(data.MAT2023INSMUJ);
            $("#mat24inshom").val(data.MAT2024INSHOM);
            $("#mat24insmuj").val(data.MAT2024INSMUJ);
            $("#mat25inshom").val(data.MAT2025INSHOM);
            $("#mat25insmuj").val(data.MAT2025INSMUJ);
            $("#mat26inshom").val(data.MAT2026INSHOM);
            $("#mat26insmuj").val(data.MAT2026INSMUJ);

        }
    );
}

function ver(idinstitucion) {
    //alert(idinstitucion);
    $.post(
        "../ajax/institucion.php?op=mostrar", { idinstitucion: idinstitucion },
        function(data, status) {
            data = JSON.parse(data);
            mostrarform(true);
            //Ocultamos boton Guardar y Cancelar
            $("#btnGuardar").css('display', 'none');
            $("#btnCancelar").css('display', 'none');
            //Mostramos el boton regresar
            $("#btnRegresar").css('display', 'inline'); 
            
            $("#nombreins").val(data.NOMBREINS);
            $("#codigoins").val(data.CODIGOINS);
            $("#idtipoinstitucion").val(data.IDTIPOINSTITUCION);
            $("#idtipoinstitucion").selectpicker('refresh');
            $("#idcorredor").val(data.IDCORREDOR);
            $("#idcorredor").selectpicker('refresh');
            $("#idcantoncaserio").val(data.IDCANTONCASERIO); //Se agregó Cantón/Caserío
            $("#idcantoncaserio").selectpicker('refresh');
            $("#idmunicipio").val(data.IDMUNICIPIO); 
            $("#idmunicipio").selectpicker('refresh'); 
            $("#idnvomunicipio").val(data.IDNVOMUN); //Se agregó nuevo municipio
            $("#idnvomunicipio").selectpicker('refresh'); 
            $("#iddepartamento").val(data.IDDEPARTAMENTO); 
            $("#iddepartamento").selectpicker('refresh'); 
            $("#zonains").val(data.ZONAINS);
            $("#zonains").selectpicker('refresh');
            $("#ubicacionins").val(data.UBICACIONINS);
            $("#latitud").val(data.LATITUDINS); //se agregó latitud
            $("#longitud").val(data.LONGITUDINS); //se agregó longitud
            $("#fechaingresoins").val(data.FECHAINGRESOINS);
            $("#fechasalidains").val(data.FECHASALIDAINS);
            $("#nombredirectorins").val(data.NOMBREDIRECTORINS);
            $("#correodir").val(data.CORREODIRINS); //Se agregó correo del director
            $("#telefonoins").val(data.TELEFONOINS);
            $("#telefonodirins").val(data.TELEFONODIRINS); 
            $("#mat2018ins").val(data.MAT2018INS);
            $("#mat2019ins").val(data.MAT2019INS);
            $("#mat2020ins").val(data.MAT2020INS);
            $("#mat2021ins").val(data.MAT2021INS);
            $("#mat2022ins").val(data.MAT2022INS);
            $("#mat2023ins").val(data.MAT2023INS);
            $("#mat2024ins").val(data.MAT2024INS);
            $("#mat2025ins").val(data.MAT2025INS);
            $("#mat2026ins").val(data.MAT2026INS);
            $("#numerodocfemeninoins").val(data.NUMERODOCFEMENINOINS);
            $("#numerodocmasculinoins").val(data.NUMERODOCMASCULINOINS);
            $("#anterior_turno").html(data.TURNOINS); 
            $("#turnoins").val(data.TURNOINS); 
            $("#turnoins").selectpicker('refresh'); 
            // Cargar los niveles educativos seleccionados
            $("#niveles_educativos").val(data.niveles_educativos);
            $("#niveles_educativos").selectpicker('refresh');
            $("#idinstitucion").val(data.IDINSTITUCION);
            $("#fechamodins").val(data.FECHAMODINS);
            $("#mat18inshom").val(data.MAT2018INSHOM);
            $("#mat18insmuj").val(data.MAT2018INSMUJ);
            $("#mat19inshom").val(data.MAT2019INSHOM);
            $("#mat19insmuj").val(data.MAT2019INSMUJ);
            $("#mat20inshom").val(data.MAT2020INSHOM);
            $("#mat20insmuj").val(data.MAT2020INSMUJ);
            $("#mat21inshom").val(data.MAT2021INSHOM);
            $("#mat21insmuj").val(data.MAT2021INSMUJ);
            $("#mat22inshom").val(data.MAT2022INSHOM);
            $("#mat22insmuj").val(data.MAT2022INSMUJ);
            $("#mat23inshom").val(data.MAT2023INSHOM);
            $("#mat23insmuj").val(data.MAT2023INSMUJ);
            $("#mat24inshom").val(data.MAT2024INSHOM);
            $("#mat24insmuj").val(data.MAT2024INSMUJ);
            $("#mat25inshom").val(data.MAT2025INSHOM);
            $("#mat25insmuj").val(data.MAT2025INSMUJ);
            $("#mat26inshom").val(data.MAT2026INSHOM);
            $("#mat26insmuj").val(data.MAT2026INSMUJ);

            $("#nombreins").prop("disabled", true);
            $("#codigoins").prop("disabled", true);
            $("#idtipoinstitucion").prop("disabled", true);
            $("#idcorredor").prop("disabled", true);
            $("#idcantoncaserio").prop("disabled", true); //se agregó cantón/caserío
            $("#idmunicipio").prop("disabled", true); 
            $("#idnvomunicipio").prop("disabled", true); //se agregó nuevo municipio
            $("#iddepartamento").prop("disabled", true); 
            $("#zonains").prop("disabled", true);
            $("#zonains").selectpicker('refresh');
            $("#ubicacionins").prop("disabled", true);
            $("#latitud").prop("disabled", true); //se agregó latitud
            $("#longitud").prop("disabled", true); //se agregó longitud
            $("#fechaingresoins").prop("disabled", true);
            $("#fechasalidains").prop("disabled", true);
            $("#nombredirectorins").prop("disabled", true);
            $("#correodir").prop("disabled", true); //se agregó correo director
            $("#telefonoins").prop("disabled", true);
            $("#telefonodirins").prop("disabled", true); 
            $("#mat2018ins").prop("disabled", true);
            $("#mat2019ins").prop("disabled", true);
            $("#mat2020ins").prop("disabled", true);
            $("#mat2021ins").prop("disabled", true);
            $("#mat2022ins").prop("disabled", true);
            $("#mat2023ins").prop("disabled", true);
            $("#mat2024ins").prop("disabled", true);
            $("#mat2025ins").prop("disabled", true);
            $("#mat2026ins").prop("disabled", true);
            $("#numerodocfemeninoins").prop("disabled", true);
            $("#numerodocmasculinoins").prop("disabled", true);
            $("#turnoins").prop("disabled", true);
            $("#niveles_educativos").prop("disabled", true); 
            $("#niveles_educativos").selectpicker('refresh'); // Refresca el select para mostrar que está habilitado
            $("#idinstitucion").prop("disabled", true);
            $("#mat18inshom").prop("disabled", true);
            $("#mat18insmuj").prop("disabled", true);
            $("#mat19inshom").prop("disabled", true);
            $("#mat19insmuj").prop("disabled", true);
            $("#mat20inshom").prop("disabled", true);
            $("#mat20insmuj").prop("disabled", true);
            $("#mat21inshom").prop("disabled", true);
            $("#mat21insmuj").prop("disabled", true);
            $("#mat22inshom").prop("disabled", true);
            $("#mat22insmuj").prop("disabled", true);
            $("#mat23inshom").prop("disabled", true);
            $("#mat23insmuj").prop("disabled", true);
            $("#mat24inshom").prop("disabled", true);
            $("#mat24insmuj").prop("disabled", true);
            $("#mat25inshom").prop("disabled", true);
            $("#mat25insmuj").prop("disabled", true);
            $("#mat26inshom").prop("disabled", true);
            $("#mat26insmuj").prop("disabled", true);
        }
    );
}

//Función para desactivar registros
function desactivar(idinstitucion) {
    bootbox.confirm("Está Seguro de desactivar el Centro Educativo?", function(result) {
        if (result) {
            $.post("../ajax/institucion.php?op=desactivar", { idinstitucion: idinstitucion }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para activar registros
function activar(idinstitucion) {
    bootbox.confirm("Está Seguro de activar el Centro Educativo?", function(result) {
        if (result) {
            $.post("../ajax/institucion.php?op=activar", { idinstitucion: idinstitucion }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

// Función que suma los valores de hombres y mujeres y muestra el total
function calculateTotal(homId, mujId, totalId) {
    const hombres = document.getElementById(homId);
    const mujeres = document.getElementById(mujId);
    const total = document.getElementById(totalId);

    // Validación para asegurarse de que los campos contienen números
    hombres.addEventListener('input', updateTotal);
    mujeres.addEventListener('input', updateTotal);

    function updateTotal() {
        const numHombres = parseInt(hombres.value) || 0;
        const numMujeres = parseInt(mujeres.value) || 0;
        total.value = numHombres + numMujeres;
    }
}

// Llama a la función para cada año
calculateTotal('mat18inshom', 'mat18insmuj', 'mat2018ins');
calculateTotal('mat19inshom', 'mat19insmuj', 'mat2019ins');
calculateTotal('mat20inshom', 'mat20insmuj', 'mat2020ins');
calculateTotal('mat21inshom', 'mat21insmuj', 'mat2021ins');
calculateTotal('mat22inshom', 'mat22insmuj', 'mat2022ins');
calculateTotal('mat23inshom', 'mat23insmuj', 'mat2023ins');
calculateTotal('mat24inshom', 'mat24insmuj', 'mat2024ins');

init();