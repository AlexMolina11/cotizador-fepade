//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
	$("#formularioregistros").hide();    
    $("#formulario").on("submit",function(e)
    {  
        guardaryeditar(e);
    });

    //Cargamos los items al select tipo participantes
    $.post("../ajax/participantes.php?op=selectTipoParticipante", function(r) {
        $("#tipoparticipante").html(r);
        $("#tipoparticipante").selectpicker('refresh');
    });

    //Cargamos los items al select comunidad
    $.post("../ajax/participantes.php?op=selectComunidad", function(r) {
        $("#idcomunidad").html(r);
        $("#idcomunidad").selectpicker('refresh');
    });

    //Cargamos los items al select Corredor 
    $.post("../ajax/participantes.php?op=selectCorredor", function(r) {
        $("#corredorpar").html(r);
        $("#corredorpar").selectpicker('refresh');
        
        var idcorredor = $("#corredorpar").val();
        if(idcorredor == "") {
            console.log("corredor vacio");
        } else {
            $.post("../ajax/participantes.php?op=mostrarCorredor",{idcorredor:idcorredor}, function(r) {
                res = JSON.parse(r); 
                idcorredor = res.IDCORREDOR;
                $("#idcorredorpar").val(idcorredor);
            });
        }
    });

    //Cargamos los items al select Distritos
    $.post("../ajax/participantes.php?op=selectDistrito", function(r) {
        $("#idmunicipio").html(r);
        $("#idmunicipio").selectpicker('refresh');
    });

    //Cargamos los items al select Nuevos Municipio
    $.post("../ajax/participantes.php?op=selectNvoMunicipio", function(r) {
        $("#idnvomunicipio").html(r);
        $("#idnvomunicipio").selectpicker('refresh');
    });

    //Cargamos los items al select Departamento
    $.post("../ajax/participantes.php?op=selectDepartamento", function(r) {
        $("#iddepartamento").html(r);
        $("#iddepartamento").selectpicker('refresh');
    });

    //Se agregó validación, si se selecciona un departamento mostrar lo siguiente
    $("#iddepartamento").change(function(){
        var iddepartamento = $("#iddepartamento").val();  
        if(iddepartamento == 0) {
            $.post("../ajax/participantes.php?op=selectDistrito", function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });

            $.post("../ajax/participantes.php?op=selectNvoMunicipio", function(r) {
                $("#idnvomunicipio").html(r);
                $("#idnvomunicipio").selectpicker('refresh');
            });

            //Cargamos los items al select Corredor 
            $.post("../ajax/participantes.php?op=selectCorredor", function(r) {
                $("#corredorpar").html(r);
                $("#corredorpar").selectpicker('refresh');
                
                var idcorredor = $("#corredorpar").val();
                if(idcorredor == "") {
                    console.log("corredor vacio");
                } else {
                    $.post("../ajax/participantes.php?op=mostrarCorredor",{idcorredor:idcorredor}, function(r) {
                        res = JSON.parse(r); 
                        idcorredor = res.IDCORREDOR;
                        $("#idcorredorpar").val(idcorredor);
                    });
                }
            });
        } else {
            $.post("../ajax/participantes.php?op=selectDist", {iddepartamento: iddepartamento}, function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });
            
            $.post("../ajax/participantes.php?op=selectnvoMuni", {iddepartamento: iddepartamento}, function(r) {
                $("#idnvomunicipio").html(r);
                $("#idnvomunicipio").selectpicker('refresh');
            });

            $.post("../ajax/participantes.php?op=selectCorr",{iddepartamento:iddepartamento},function(r) {             
                $("#corredorpar").html(r);
                $("#corredorpar").selectpicker('refresh');

                var idcorredor = $("#corredorpar").val();
                if(idcorredor == "") {
                    console.log("corredor vacio");
                } else {
                    $.post("../ajax/participantes.php?op=mostrarCorredor",{idcorredor:idcorredor}, function(r) {
                        res = JSON.parse(r); 
                        idcorredor = res.IDCORREDOR;
                        $("#idcorredorpar").val(idcorredor);
                    });
                }
             }); 
        }
    });

    //Se agregó validación, si se selecciona un municipio mostrar lo siguiente
    $("#idmunicipio").change(function(){
        var idmunicipio = $("#idmunicipio").val();
        if(idmunicipio == 0) {
            $.post("../ajax/participantes.php?op=selectDepartamento", function(r) {
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');
            });
            $.post("../ajax/participantes.php?op=selectNvoMunicipio", function(r) {
                $("#idnvomunicipio").html(r);
                $("#idnvomunicipio").selectpicker('refresh');
            });
            //Cargamos los items al select Corredor 
            $.post("../ajax/participantes.php?op=selectCorredor", function(r) {
                $("#corredorpar").html(r);
                $("#corredorpar").selectpicker('refresh');
                
                var idcorredor = $("#corredorpar").val();
                if(idcorredor == "") {
                    console.log("corredor vacio");
                } else {
                    $.post("../ajax/participantes.php?op=mostrarCorredor",{idcorredor:idcorredor}, function(r) {
                        res = JSON.parse(r); 
                        idcorredor = res.IDCORREDOR;
                        $("#idcorredorpar").val(idcorredor);
                    });
                }
            });
        } else {  
            $.post("../ajax/participantes.php?op=selectDepa",{idmunicipio:idmunicipio},function(r) {             
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');

                //Luego de seleccionar Departamento, limita los corredores
                var iddepartamento = $("#iddepartamento").val();
                $.post("../ajax/participantes.php?op=selectCorr",{iddepartamento:iddepartamento},function(r) {             
                    $("#corredorpar").html(r);
                    $("#corredorpar").selectpicker('refresh');

                    var idcorredor = $("#corredorpar").val();
                    if(idcorredor == "") {
                        console.log("corredor vacio");
                    } else {
                        $.post("../ajax/participantes.php?op=mostrarCorredor",{idcorredor:idcorredor}, function(r) {
                            res = JSON.parse(r); 
                            idcorredor = res.IDCORREDOR;
                            $("#idcorredorpar").val(idcorredor);
                        });
                    }
                });
            });
            $.post("../ajax/participantes.php?op=selectnvoMuniByDist", {idmunicipio: idmunicipio}, function(r) {
                $("#idnvomunicipio").html(r);
                $("#idnvomunicipio").selectpicker('refresh');
            });
        }
    });

    //Cuando se selecciona un distrito
    $("#idnvomunicipio").change(function(){
        var idnvomunicipio = $("#idnvomunicipio").val();
        if(idnvomunicipio == 0) {
            $.post("../ajax/participantes.php?op=selectDepartamento", function(r) {
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');
            });
            $.post("../ajax/participantes.php?op=selectDistrito", function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });
            //Cargamos los items al select Corredor 
            $.post("../ajax/participantes.php?op=selectCorredor", function(r) {
                $("#corredorpar").html(r);
                $("#corredorpar").selectpicker('refresh');
                
                var idcorredor = $("#corredorpar").val();
                if(idcorredor == "") {
                    console.log("corredor vacio");
                } else {
                    $.post("../ajax/participantes.php?op=mostrarCorredor",{idcorredor:idcorredor}, function(r) {
                        res = JSON.parse(r); 
                        idcorredor = res.IDCORREDOR;
                        $("#idcorredorpar").val(idcorredor);
                    });
                }
            });
        } else { 
            $.post("../ajax/participantes.php?op=selectDepaByNvoMuni", {idnvomunicipio: idnvomunicipio}, function(r) {
                $("#iddepartamento").html(r);
                $("#iddepartamento").selectpicker('refresh');

                var iddepartamento = $("#iddepartamento").val(); 
                $.post("../ajax/participantes.php?op=selectCorr",{iddepartamento:iddepartamento},function(r) {             
                    $("#corredorpar").html(r);
                    $("#corredorpar").selectpicker('refresh');

                    var idcorredor = $("#corredorpar").val();
                    if(idcorredor == "") {
                        console.log("corredor vacio");
                    } else {
                        $.post("../ajax/participantes.php?op=mostrarCorredor",{idcorredor:idcorredor}, function(r) {
                            res = JSON.parse(r); 
                            idcorredor = res.IDCORREDOR;
                            $("#idcorredorpar").val(idcorredor);
                        });
                    }
                });
            });
            $.post("../ajax/participantes.php?op=selectDistByNvoMuni", {idnvomunicipio: idnvomunicipio}, function(r) {
                $("#idmunicipio").html(r);
                $("#idmunicipio").selectpicker('refresh');
            });
        }
    });
    
    //Se agregó validación, si se selecciona un corredor
    $("#corredorpar").change(function() {
        var idcorredor = $("#corredorpar").val();
        console.log("el nuevo corredor es:" +idcorredor);
        if(idcorredor == ""){
            console.log("No se escogio corredor");
            //Cargamos todos los items al select Centro Educativo
            $.post("../ajax/participantes.php?op=selectInstitucion", function(r) {
                $("#idinstitucion").html(r);
                $("#idinstitucion").selectpicker('refresh');
            });
        } else {
            console.log(idcorredor);
            $.post("../ajax/participantes.php?op=mostrarCorredor",{idcorredor:idcorredor}, function(r) {
                res = JSON.parse(r); 
                idcorredor = res.IDCORREDOR;
                $("#idcorredorpar").val(idcorredor);
            });
            console.log("El corredor para instituciones es: " +idcorredor);
            //Cargamos los items al select Centro Educativo
            $.post("../ajax/participantes.php?op=selectInstituciones",{idcorredor:idcorredor}, function(r) {
                $("#idinstitucion").html(r);
                $("#idinstitucion").selectpicker('refresh');
            });
        }
    });

    //Cargamos los items al select Centro Educativo
    $.post("../ajax/participantes.php?op=selectInstitucion", function(r) {
        $("#idinstitucion").html(r);
        $("#idinstitucion").selectpicker('refresh');
    });

	//Cargamos los items al select Grupo
    $.post("../ajax/participantes.php?op=selectGrupo", function(r) {
        $("#grupoparticipante").html(r);
        $("#grupoparticipante").selectpicker('refresh');
    });

	//Cargamos los items al select Discapacidad
	 $.post("../ajax/participantes.php?op=selectDiscapacidad", function(r) {
        $("#iddiscapacidad").html(r);
        $("#iddiscapacidad").selectpicker('refresh');
    });


    $('.solo-numero').keyup(function() {
        this.value = (this.value + '').replace(/[^0-9]/g, '');
    });

    $("#fechanacpar").change(function() { //Agregamos función Change para cuando se eliga una fecha este haga el calculo de la edad.
        var fechanac = new Date($(this).val());
        var fechahoy = new Date();

        var edad = fechahoy.getFullYear() - fechanac.getFullYear();
        var m = fechahoy.getMonth() - fechanac.getMonth();

        if(m < 0 || (m === 0 && fechahoy.getDate() < fechanac.getDate())) {
            edad--;
        }
        $('#edadpar').val(edad);
    });
}

//Función limpiar
function limpiar(){  
    $("#idparticipante").val("");
    $("#iddiscapacidad").val("0");
    $("#iddiscapacidad").selectpicker('refresh');
    $("#iddepartamento").val("0"); 
    $("#iddepartamento").selectpicker('refresh'); 
    $("#idnvomunicipio").val("0"); //Se agregó nuevo municipio
    $("#idnvomunicipio").selectpicker('refresh');
    $("#idmunicipio").val("0");
    $("#idmunicipio").selectpicker('refresh');
    $("#idinstitucion").val("0");
    $("#idinstitucion").selectpicker('refresh');
    $("#idcomunidad").val("0");
    $("#idcomunidad").selectpicker('refresh');
    $("#codigopar").val("");
    $("#primernombre").val("");
    $("#segundonombre").val("");
    $("#primerapellido").val("");
    $("#segundoapellido").val("");
    $("#fechanacpar").val("");
    $("#sexopar").val("0");
    $("#sexopar").selectpicker('refresh');
    $("#edadpar").val("");
    $("#ocupacionpar").val("");
    $("#concentimeinto").prop("checked", false); //reseteamos 
    $("#estudiopar").val("");
    $("#escuelapar").val("");
    $("#seccion").val("");
    $("#turno").val("");
    $("#profesor").val("");
    $("#nombreresponsablepar").val("");
    $("#telresponsablepar").val("");
    $("#especialidadpar").val("");
    $("#corredorpar").val("0");
    $("#corredorpar").selectpicker('refresh');
    $("#idcorredor").val(""); //Se agregó idcorredor
    $("#niveleducativopar").val("");		
    $("#directorcepar").val("");
    $("#tipoparticipante").val("0");
    $("#tipoparticipante").selectpicker('refresh');	
    
    //Lipio los campos de grupo
    $("#idgrupo").val("");
    $("#grupoparticipante").val("0");
    $("#grupoparticipante").selectpicker('refresh');
}

function limpiarotropar(){
    //Asignamos valores predeterminados
    var tipopar_pred = $("#tipopar_nvopar").val();
    $("#tipoparticipante").val(tipopar_pred);
    $("#tipoparticipante").selectpicker('refresh');
    var comunidad_pred = $("#comunidad_nvopar").val();
    $("#idcomunidad").val(comunidad_pred);
    $("#idcomunidad").selectpicker('refresh');
    var depa_pred = $("#depa_nvopar").val();
    $("#iddepartamento").val(depa_pred); //Se agregó el departamento
    $("#iddepartamento").selectpicker('refresh'); //se agregó el departamento
    var muni_pred = $("#muni_nvopar").val();
    $("#idmunicipio").val(muni_pred);
    $("#idmunicipio").selectpicker('refresh');
    var corr_pred = $("#corr_nvopar_2").val();
    $("#corredorpar").val(corr_pred);
    $("#corredorpar").selectpicker('refresh');
    var centroed_pred = $("#centeduc_nvopar").val();
    $("#idinstitucion").val(centroed_pred);
    $("#idinstitucion").selectpicker('refresh');
    
    //Limpiamos los demás campos
    $("#idparticipante").val("");
    $("#iddiscapacidad").val("0");
    $("#iddiscapacidad").selectpicker('refresh');
    $("#codigopar").val("");
    $("#primernombre").val("");
    $("#segundonombre").val("");
    $("#primerapellido").val("");
    $("#segundoapellido").val("");
    $("#fechanacpar").val("");
    $("#sexopar").val("0");
    $("#sexopar").selectpicker('refresh');
    $("#edadpar").val("");
    $("#ocupacionpar").val("");
    $("#concentimeinto").prop("checked", false); //reseteamos 
    $("#estudiopar").val("");
    $("#escuelapar").val("");
    $("#seccion").val("");
    $("#turno").val("");
    $("#profesor").val("");
    $("#nombreresponsablepar").val("");
    $("#telresponsablepar").val("");
    $("#especialidadpar").val("");
    $("#niveleducativopar").val("");		
    $("#directorcepar").val("");
    
    //Lipio los campos de grupo
    $("#idgrupo").val("");
    $("#grupoparticipante").val("0");
    $("#grupoparticipante").selectpicker('refresh');
}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //Ocultamos los campos de los distintos tipos de participantes
        $("#estudiante").hide();
        $("#docente").hide();
        $("#comunidad").hide();
        $("#padredefamilia").hide();
        $("#voluntario").hide();
        $("#voluntariocoorporativo").hide();
        $("#director").hide();
        //mostramos el grupo
        document.getElementById("seccion_grupo").style.display = "block";
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide()
    }else{
        $("#listadoregistros").show();
        $("#formularioregistros").hide(); 
        $("#btnagregar").show() 
    }
}

//Función mostrar formulario
function mostrarformotropar(flag) {
    limpiarotropar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //mostramos u ocultamos dependiendo del tipo de participante
        var tipoP = $("#tipoparticipante").val();
    
        if (tipoP == 1) { //Docente
            $("#estudiante").hide();
            $("#docente").show(300);
            $("#comunidad").hide();
            $("#padredefamilia").hide();
            $("#voluntario").hide();
            $("#voluntariocoorporativo").hide();
            $("#director").hide(); //sección del director
        } else if (tipoP == 2) { //Estudiante
            $("#estudiante").show(300);
            $("#docente").hide();
            $("#comunidad").hide();
            $("#padredefamilia").hide();
            $("#voluntario").hide();
            $("#voluntariocoorporativo").hide();
            $("#director").hide(); //sección del director
        } else if (tipoP == 3) { //Miembro de Comunidad
            $("#estudiante").hide();
            $("#docente").hide();
            $("#comunidad").show(300);
            $("#padredefamilia").hide();
            $("#voluntario").hide();
            $("#voluntariocoorporativo").hide();
            $("#director").hide(); //sección del director
        } else if (tipoP == 4) { //Madre/Padre de Familia
            $("#estudiante").hide();
            $("#docente").hide();
            $("#comunidad").hide();
            $("#padredefamilia").show(300);
            $("#voluntario").hide();
            $("#voluntariocoorporativo").hide();
            $("#director").hide(); //sección del director
        } else if (tipoP == 5) { //Voluntario
            $("#estudiante").hide();
            $("#docente").hide();
            $("#comunidad").hide();
            $("#padredefamilia").hide();
            $("#voluntario").show(300);
            $("#voluntariocoorporativo").hide();
            $("#director").hide(); //sección del director
        } else if (tipoP == 6) { //Voluntario
            $("#estudiante").hide();
            $("#docente").hide();
            $("#comunidad").hide();
            $("#padredefamilia").hide();
            $("#voluntario").hide();
            $("#voluntariocoorporativo").show(300);
            $("#director").hide(); //sección del director
        } else if (tipoP == 7) { //Voluntario
            $("#estudiante").hide();
            $("#docente").hide();
            $("#comunidad").hide();
            $("#padredefamilia").hide();
            $("#voluntario").hide();
            $("#voluntariocoorporativo").hide();
            $("#director").show(300); //sección del director
        } else {
            $("#estudiante").hide();
            $("#docente").hide();
            $("#comunidad").hide();
            $("#padredefamilia").hide();
            $("#voluntario").hide();
            $("#voluntariocoorporativo").hide();
            $("#director").hide(); //sección del director
            limpiar(); //Al no seleccionar ningun tipo de participante este limpiará todos los campos. 
        }
        //mostramos el grupo
        document.getElementById("seccion_grupo").style.display = "block";
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
    //limpiar();
    //mostrarform(false);
    //Recargamos la pagina
    location.reload();
    
}

function ver(idparticipante) {
    //alert(idinstitucion);
    $.post(
        "../ajax/participantes.php?op=mostrar", { idparticipante: idparticipante },
        function(data, status) {
            data = JSON.parse(data);
            mostrarform(true);
            //Ocultamos boton Guardar y Cancelar
            $("#btnGuardar").css('display', 'none');
            $("#btnCancelar").css('display', 'none');
            //Mostramos el boton regresar
            $("#btnRegresar").css('display', 'inline'); 
            
            //Mostraremos los datos en base al tipo de participante que se ha registrado.
            var tipoPar = data.IDTIPOPARTICIPANTE;
            if (tipoPar == 1) { //Docente
                $("#estudiante").hide();
                $("#docente").show(300);
                $("#comunidad").hide();
                $("#padredefamilia").hide();
                $("#voluntario").hide();
                $("#voluntariocoorporativo").hide();
                $("#director").hide(); //sección del director
            } else if (tipoPar == 2) { //Estudiante
                $("#estudiante").show(300);
                $("#docente").hide();
                $("#comunidad").hide();
                $("#padredefamilia").hide();
                $("#voluntario").hide();
                $("#voluntariocoorporativo").hide();
                $("#director").hide(); //sección del director
            } else if (tipoPar == 3) { //Miembro de Comunidad
                $("#estudiante").hide();
                $("#docente").hide();
                $("#comunidad").show(300);
                $("#padredefamilia").hide();
                $("#voluntario").hide();
                $("#voluntariocoorporativo").hide();
                $("#director").hide(); //sección del director
            } else if (tipoPar == 4) { //Madre/Padre de Familia
                $("#estudiante").hide();
                $("#docente").hide();
                $("#comunidad").hide();
                $("#padredefamilia").show(300);
                $("#voluntario").hide();
                $("#voluntariocoorporativo").hide();
                $("#director").hide(); //sección del director
                $("#turno").prop('readonly', true);
            } else if (tipoPar == 5) { //Voluntario
                $("#estudiante").hide();
                $("#docente").hide();
                $("#comunidad").hide();
                $("#padredefamilia").hide();
                $("#voluntario").show(300);
                $("#voluntariocoorporativo").hide();
                $("#director").hide(); //sección del director
                $("#turno").prop('readonly', true);
            } else if (tipoPar == 6) { //Voluntario Coorporativo
                $("#estudiante").hide();
                $("#docente").hide();
                $("#comunidad").hide();
                $("#padredefamilia").hide();
                $("#voluntario").hide();
                $("#voluntariocoorporativo").show(300);
                $("#director").hide(); //sección del director
                $("#turno").prop('readonly', true);
            } else if (tipoPar == 7) { //Voluntario Coorporativo
                $("#estudiante").hide();
                $("#docente").hide();
                $("#comunidad").hide();
                $("#padredefamilia").hide();
                $("#voluntario").hide();
                $("#voluntariocoorporativo").hide();
                $("#director").show(300); //sección del director
                $("#turno").prop('readonly', true);
            } else {
                $("#estudiante").show();
                $("#docente").show();
                $("#comunidad").show();
                $("#padredefamilia").show();
                $("#voluntario").hide();
                $("#voluntariocoorporativo").hide();
                $("#director").hide(); //sección del director
            }

            $("#idparticipante").val(data.IDPARTICIPANTE);
            $("#tipoparticipante").val(data.IDTIPOPARTICIPANTE);
            $("#tipoparticipante").selectpicker('refresh');		
            $("#primernombre").val(data.PRIMERNOMBRE);
            $("#segundonombre").val(data.SEGUNDOSNOMBRE);
            $("#primerapellido").val(data.PRIMERAPELLIDO);
            $("#segundoapellido").val(data.SEGUNDOSAPELLIDO);
            $("#sexopar").val(data.SEXOPAR);
            $("#sexopar").selectpicker('refresh');			
            $("#iddiscapacidad").val(data.IDDISCAPACIDAD);
            $("#iddiscapacidad").selectpicker('refresh');		
            //$("#fechanacpar").val(data.fecha_dmy);		
            $("#fechanacpar").val(data.FECHANACPAR);
            $("#edadpar").val(data.EDADPAR);
            $("#idmunicipio").val(data.IDMUNICIPIO);
            $("#idmunicipio").selectpicker('refresh');
            $("#idnvomunicipio").val(data.IDNVOMUN); //Se agregó nuevo municipio
            $("#idnvomunicipio").selectpicker('refresh');
            $("#iddepartamento").val(data.IDDEPARTAMENTO); 
            $("#iddepartamento").selectpicker('refresh'); 
            $("#concentimeinto").val(data.CONCENTIMEINTOPARAFOTOPAR);
            if(data.CONCENTIMEINTOPARAFOTOPAR == 1) { //Si el dato concentimiento viene 1
                $("#concentimeinto").prop("checked",true); //el checkbox se activará
            } else { 
                $("#concentimeinto").prop("checked",false); //el checbox se desactivará
            }
            $("#codigopar").val(data.CODIGOPAR);			
            $("#idinstitucion").val(data.IDINSTITUCION);
            $("#idinstitucion").selectpicker('refresh');		
            $("#idcomunidad").val(data.IDCOMUNIDAD);
            $("#idcomunidad").selectpicker('refresh');		
            $("#corredorpar").val(data.CORREDORPAR);
            $("#corredorpar").selectpicker('refresh');		
            $("#ocupacionpar").val(data.OCUPACIONPAR);
            $("#estudiopar").val(data.ESTUDIOACTUALPAR);
            $("#nombreresponsablepar").val(data.NOMBRERESPONSABLEPAR);
            $("#telresponsablepar").val(data.TELRESPONSABLEPAR);
            $("#turno").val(data.TURNO);
            $("#seccion").val(data.SECCION);
            $("#directorcepar").val(data.DIRECTOR);
            $("#especialidadpar").val(data.ESPECIALIDADPAR);
            $("#niveleducativopar").val(data.NIVELEDUCATIVOPAR);		
            $("#profesor").val(data.PROFESOR);	
            $("#escuelapar").val(data.ESCUELAPAR);
            // Verifica si la variable "primeravez" está vacía o nula
            if (!data.FECHAPRIMERAVEZ) {
               $("#primeravez").html("No ha hecho primera jornada");
            } else {
              $("#primeravez").html(data.FECHAPRIMERAVEZ);
            }

            //ver grupo
            $("#idgrupo").val(data.IDGRUPO);
            $("#grupoparticipante").val(data.IDGRUPO);
            $("#grupoparticipante").selectpicker('refresh');

            //Desabilitamos los inputs
            $("#idparticipante").prop("disabled", true);
            $("#tipoparticipante").prop("disabled", true);	
            $("#primernombre").prop("disabled", true);
            $("#segundonombre").prop("disabled", true);
            $("#primerapellido").prop("disabled", true);
            $("#segundoapellido").prop("disabled", true);
            $("#sexopar").prop("disabled", true);			
            $("#iddiscapacidad").prop("disabled", true);			
            $("#fechanacpar").prop("disabled", true);
            $("#edadpar").prop("disabled", true);
            $("#idmunicipio").prop("disabled", true);	
            $("#idnvomunicipio").prop("disabled", true); //Se agregó nuevo municipio
            $("#iddepartamento").prop("disabled", true); 
            $("#concentimeinto").prop("disabled", true);
            $("#codigopar").prop("disabled", true);			
            $("#idinstitucion").prop("disabled", true);		
            $("#idcomunidad").prop("disabled", true);		
            $("#corredorpar").prop("disabled", true);	
            $("#ocupacionpar").prop("disabled", true);
            $("#estudiopar").prop("disabled", true);
            $("#nombreresponsablepar").prop("disabled", true);
            $("#telresponsablepar").prop("disabled", true);
            $("#turno").prop("disabled", true);
            $("#seccion").prop("disabled", true);
            $("#directorcepar").prop("disabled", true);
            $("#especialidadpar").prop("disabled", true);
            $("#niveleducativopar").prop("disabled", true);		
            $("#profesor").prop("disabled", true);	
            $("#escuelapar").prop("disabled", true);

            //Deshabilitamos los de grupo
            $("#idgrupo").prop("disabled", true);
            $("#grupoparticipante").prop("disabled", true);
        }
    );
}

//Función regresarform
function regresarform() {
    limpiar();
    mostrarform(false);
    //Mostramos boton Guardar y Cancelar
    $("#btnGuardar").css('display', 'inline');
    $("#btnCancelar").css('display', 'inline');
    //ocultamos el boton regresar
    $("#btnRegresar").css('display', 'none');
    //Regresamos los campos como estaban
    $("#idparticipante").prop("disabled", false);
    $("#tipoparticipante").prop("disabled", false);	
    $("#primernombre").prop("disabled", false);
    $("#segundonombre").prop("disabled", false);
    $("#primerapellido").prop("disabled", false);
    $("#segundoapellido").prop("disabled", false);
    $("#sexopar").prop("disabled", false);			
    $("#iddiscapacidad").prop("disabled", false);			
    $("#fechanacpar").prop("disabled", false);
    $("#edadpar").prop("disabled", false);
    $("#idmunicipio").prop("disabled", false);	
    $("#idnvomunicipio").prop("disabled", false);	//se agregó nuevo municipio
    $("#iddepartamento").prop("disabled", false);
    $("#concentimeinto").prop("disabled", false);
    $("#codigopar").prop("disabled", false);			
    $("#idinstitucion").prop("disabled", false);		
    $("#idcomunidad").prop("disabled", false);		
    $("#corredorpar").prop("disabled", false);	
    $("#ocupacionpar").prop("disabled", false);
    $("#estudiopar").prop("disabled", false);
    $("#nombreresponsablepar").prop("disabled", false);
    $("#telresponsablepar").prop("disabled", false);
    $("#turno").prop("disabled", false);
    $("#seccion").prop("disabled", false);
    $("#directorcepar").prop("disabled", false);
    $("#especialidadpar").prop("disabled", false);
    $("#niveleducativopar").prop("disabled", false);		
    $("#profesor").prop("disabled", false);	
    $("#escuelapar").prop("disabled", false);

    //Habilitamos los de grupo
    $("#idgrupo").prop("disabled", false);
    $("#grupoparticipante").prop("disabled", false);
}

//Función para guardar o editar
function guardaryeditar(e){
  e.preventDefault();//No se activará la acción predeterminada del evento
  $("#btnGuardar").prop("disabled",true);
  var formData = new FormData($("#formulario")[0]);
  
  $.ajax({
      url:"../ajax/participantes.php?op=guardaryeditar",
      type:"POST",
      data:formData,
      contentType:false,
      processData:false,
      
      success: function(datos){ 
        if(datos == "Existente"){
            bootbox.alert({ //Se modificó el bootbox para ejecutar una acción al poner ok
                message: "Participante ya existente, favor verificar los datos.",
                callback: function (result) {
                    $("#btnGuardar").prop("disabled",false);
                }
            });
        } else {
            if($("#idparticipante").val() == "") {
                //Asignamos variables con los datos que se acaban de ingresar del participante
                var idgrupoactual = $("#grupoparticipante").val();
                var TipoPar = $("#tipoparticipante").val();
                var ComunidadPar = $("#idcomunidad").val();
                var DepartamentoPar = $("#iddepartamento").val();
                var MunicipioPar = $("#idmunicipio").val();
                var CentroEducPar = $("#idinstitucion").val();
                var PrimerNombrePar = $("#primernombre").val();
                var PrimerApellidoPar = $("#primerapellido").val();
                var edadPar2 = $("#edadpar").val();
                var FechaNacPar = $("#fechanacpar").val();
                var SexoPar2 = $("#sexopar").val();
                //Obtenemos los datos del participante que acabamos de crear
                $.post("../ajax/participantes.php?op=mostrarPar",{tipopar:TipoPar, comunidadpar:ComunidadPar, departamentopar:DepartamentoPar, municipiopar:MunicipioPar, centroeducpar:CentroEducPar, primernombrepar:PrimerNombrePar, primerapellidopar:PrimerApellidoPar, edadpar2:edadPar2, fechanacpar:FechaNacPar, sexopar2:SexoPar2},function(data,status)
                {
                    data2 = JSON.parse(data); 
                    //Obtenemos Datos del participante
                    idparticipante_res = data2.IDPARTICIPANTE;
                    idgrupoasignadores = idgrupoactual;
    
                    idtipoparticipante_res = data2.IDTIPOPARTICIPANTE;
                    idcomunidad_res = data2.IDCOMUNIDAD;
                    iddepartamento_res = data2.IDDEPARTAMENTO;
                    idmunicipio_res = data2.IDMUNICIPIO;
                    idcorredor_res = data2.IDCORREDOR;
                    corredor_res = data2.CORREDORPAR;
                    idcentroeduc_res = data2.IDINSTITUCION
                    
                    //Asignamos a los input hidden para reutilizarlos
                    $("#grupoasignar").val(idgrupoasignadores);
                    $("#participanteasignar").val(idparticipante_res);
                    //Asignamos para nuevo participante
                    $("#tipopar_nvopar").val(idtipoparticipante_res);
                    $("#comunidad_nvopar").val(idcomunidad_res);
                    $("#depa_nvopar").val(iddepartamento_res);
                    $("#muni_nvopar").val(idmunicipio_res);
                    $("#corr_nvopar").val(idcorredor_res);
                    $("#corr_nvopar_2").val(corredor_res);
                    $("#centeduc_nvopar").val(idcentroeduc_res);
    
                    //Activamos el boton de formulario para Asignar participante
                    $("#btnGuardar2").trigger("click");
                });
            } else {
                bootbox.alert({
                    message: datos,
                    callback: function () {
                        location.reload();
                    }
                });
                //mostrarform(false);
                //tabla.ajax.reload();
            }
        }  
      }
  });
}

$("#formularioasignar").on("submit",function(e)
{  
    e.preventDefault();//No se activará la acción predeterminada del evento
    var formData2 = new FormData($("#formularioasignar")[0]);
    
    $.ajax({
        url:"../ajax/asignargrupo.php?op=asignaragrupo",
        type:"POST",
        data:formData2,
        contentType:false,
        processData:false,
        
        success: function(datos)
        { 
            //bootbox.alert(datos);
            bootbox.confirm({
                title: datos,
                message: "¿Desea agregar otro participante?",
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> No'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Si'
                    }
                },
                closeButton: false,
                callback: function (result) {
                    if(result == true) {
                        mostrarformotropar(true);
                    } else {
                        //Si el usuario responde No
                        //Recargamos la pagina
                        location.reload();
                        //tabla.ajax.reload();
                        //mostrarform(false);
                    }
                }
            });
        }
    });
});

function mostrar(idparticipante){
    $.post("../ajax/participantes.php?op=mostrar",{idparticipante:idparticipante},function(data,status){
        data = JSON.parse(data); 
        mostrarform(true); 
        
        //Mostraremos los datos en base al tipo de participante que se ha registrado.
        var tipoPar = data.IDTIPOPARTICIPANTE;
        if (tipoPar == 1) { //Docente
            $("#estudiante").hide();
            $("#docente").show(300);
            $("#comunidad").hide();
            $("#padredefamilia").hide();
            $("#voluntario").hide();
            $("#voluntariocoorporativo").hide();
            $("#director").hide(); //sección del director
        } else if (tipoPar == 2) { //Estudiante
            $("#estudiante").show(300);
            $("#docente").hide();
            $("#comunidad").hide();
            $("#padredefamilia").hide();
            $("#voluntario").hide();
            $("#voluntariocoorporativo").hide();
            $("#director").hide(); //sección del director
        } else if (tipoPar == 3) { //Miembro de Comunidad
            $("#estudiante").hide();
            $("#docente").hide();
            $("#comunidad").show(300);
            $("#padredefamilia").hide();
            $("#voluntario").hide();
            $("#voluntariocoorporativo").hide();
            $("#director").hide(); //sección del director
        } else if (tipoPar == 4) { //Madre/Padre de Familia
            $("#estudiante").hide();
            $("#docente").hide();
            $("#comunidad").hide();
            $("#padredefamilia").show(300);
            $("#voluntario").hide();
            $("#voluntariocoorporativo").hide();
            $("#director").hide(); //sección del director
            $("#turno").prop('readonly', true);
        }  else if (tipoPar == 5) { //voluntario
            $("#estudiante").hide();
            $("#docente").hide();
            $("#comunidad").hide();
            $("#padredefamilia").hide();
            $("#voluntario").show(300);
            $("#voluntariocoorporativo").hide();
            $("#director").hide(); //sección del director
            $("#turno").prop('readonly', true);
        }  else if (tipoPar == 6) { //voluntario Coorporativo
            $("#estudiante").hide();
            $("#docente").hide();
            $("#comunidad").hide();
            $("#padredefamilia").hide();
            $("#voluntario").hide();
            $("#voluntariocoorporativo").show(300);
            $("#director").hide(); //sección del director
            $("#turno").prop('readonly', true);
        } else if (tipoPar == 7) { //voluntario Coorporativo
            $("#estudiante").hide();
            $("#docente").hide();
            $("#comunidad").hide();
            $("#padredefamilia").hide();
            $("#voluntario").hide();
            $("#voluntariocoorporativo").hide();
            $("#director").show(300); //sección del director
            $("#turno").prop('readonly', true);
        } else {
            $("#estudiante").show();
            $("#docente").show();
            $("#comunidad").show();
            $("#padredefamilia").show();
            $("#voluntario").hide();
            $("#voluntariocoorporativo").hide();
            $("#director").hide(); //sección del director
        }

        //Asignamos los datos del participante a cada input 
	    $("#idparticipante").val(data.IDPARTICIPANTE);
		$("#tipoparticipante").val(data.IDTIPOPARTICIPANTE);
		$("#tipoparticipante").selectpicker('refresh');		
        $("#primernombre").val(data.PRIMERNOMBRE);
        $("#segundonombre").val(data.SEGUNDOSNOMBRE);
        $("#primerapellido").val(data.PRIMERAPELLIDO);
        $("#segundoapellido").val(data.SEGUNDOSAPELLIDO);
	    $("#sexopar").val(data.SEXOPAR);
		$("#sexopar").selectpicker('refresh');			
		$("#iddiscapacidad").val(data.IDDISCAPACIDAD);
        $("#iddiscapacidad").selectpicker('refresh');		
		//$("#fechanacpar").val(data.fecha_dmy);		
		$("#fechanacpar").val(data.FECHANACPAR);
		$("#edadpar").val(data.EDADPAR);

        $("#iddepartamento").val(data.IDDEPARTAMENTO); 
		$("#iddepartamento").selectpicker('refresh');	

		$("#idnvomunicipio").val(data.IDNVOMUN); //Se agregó nuevo municipio
		$("#idnvomunicipio").selectpicker('refresh');	

		$("#idmunicipio").val(data.IDMUNICIPIO);
		$("#idmunicipio").selectpicker('refresh');	

        $("#idcorredorpar").val(data.IDCORREDOR);
        $("#idcorredorpar").selectpicker('refresh');

        $("#idinstitucion").val(data.IDINSTITUCION);
		$("#idinstitucion").selectpicker('refresh');

		$("#concentimeinto").val(data.CONCENTIMEINTOPARAFOTOPAR);
        if(data.CONCENTIMEINTOPARAFOTOPAR == 1) { //Si el dato concentimiento viene 1
            $("#concentimeinto").prop("checked",true); //el checkbox se activará
        } else { 
            $("#concentimeinto").prop("checked",false); //el checbox se desactivará
        }
        $("#codigopar").val(data.CODIGOPAR);					
        $("#idcomunidad").val(data.IDCOMUNIDAD);
		$("#idcomunidad").selectpicker('refresh');	

		$("#corredorpar").val(data.CORREDORPAR);
		$("#corredorpar").selectpicker('refresh');
        
		$("#ocupacionpar").val(data.OCUPACIONPAR);
		$("#estudiopar").val(data.ESTUDIOACTUALPAR);
		$("#nombreresponsablepar").val(data.NOMBRERESPONSABLEPAR);
		$("#telresponsablepar").val(data.TELRESPONSABLEPAR);
        $("#anterior_turno").html(data.TURNO); //Se agrego el turno que se había ingresado antes de implementar el select
	    $("#turno").val(data.TURNO);
		$("#seccion").val(data.SECCION);
		$("#directorcepar").val(data.DIRECTOR);
		$("#especialidadpar").val(data.ESPECIALIDADPAR);
		$("#niveleducativopar").val(data.NIVELEDUCATIVOPAR);		
		$("#profesor").val(data.PROFESOR);	
        $("#escuelapar").val(data.ESCUELAPAR);
        
        //Mostrar grupo
        if($("#idparticipante").val() != "") {
            document.getElementById("seccion_grupo").style.display = "none";
        }
        $("#idgrupo").val(data.IDGRUPO);
        $("#grupoparticipante").val(data.IDGRUPO);
        $("#grupoparticipante").selectpicker('refresh');

        var iddepartamento = $("#iddepartamento").val();
        var idmunicipio = $("#idmunicipio").val();
        var idcorredor = $("#idcorredorpar").val();
        var idinstitucion = $("#idinstitucion").val();
        console.log('Nuevo CE: ' +idinstitucion);

        $.post("../ajax/participantes.php?op=selectMunicipio_mostrar",{iddepartamento:iddepartamento, idmunicipio:idmunicipio},function(r) {             
            $("#idmunicipio").html(r);
            $("#idmunicipio").selectpicker('refresh');
        });

        $.post("../ajax/participantes.php?op=selectCorredor_mostrar",{iddepartamento:iddepartamento, idcorredor:idcorredor},function(r) {             
            $("#corredorpar").html(r);
            $("#corredorpar").selectpicker('refresh');
        });

        $.post("../ajax/participantes.php?op=selectCentroEducativo_mostrar",{idcorredor:idcorredor, idinstitucion:idinstitucion},function(r) {             
            $("#idinstitucion").html(r);
            $("#idinstitucion").selectpicker('refresh');
        });
	});

}

//Agregamos validación para eliminar
function eliminar(idparticipante){
    bootbox.confirm("¿Está Seguro de eliminar el participante?",function (result) {
        if(result){
            $.post("../ajax/participantes.php?op=eliminar",{idparticipante:idparticipante},function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}


function desactivar(idparticipante) {
    bootbox.confirm("Está Seguro de desactivar el participante", function(result) {
        if (result) {
            $.post("../ajax/participantes.php?op=desactivar", { idparticipante: idparticipante }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para activar registros
function activar(idparticipante) {
    bootbox.confirm("Está Seguro de activar el participante", function(result) {
        if (result) {
            $.post("../ajax/participantes.php?op=activar", { idparticipante: idparticipante }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

$("#estudiante").hide();
$("#docente").hide();
$("#comunidad").hide();
$("#padredefamilia").hide();
$("#voluntario").hide();
$("#voluntariocoorporativo").hide();
$("#director").hide(); //sección del director
$("#tipoparticipante").change(function() {
    var tipoP = $(this).val();
    
    if (tipoP == 1) { //Docente
		$("#estudiante").hide();
		$("#docente").show(300);
        $("#comunidad").hide();
        $("#padredefamilia").hide();
        $("#voluntario").hide();
        $("#voluntariocoorporativo").hide();
        $("#director").hide(); //sección del director
    } else if (tipoP == 2) { //Estudiante
		$("#estudiante").show(300);
        $("#docente").hide();
        $("#comunidad").hide();
        $("#padredefamilia").hide();
        $("#voluntario").hide();
        $("#voluntariocoorporativo").hide();
        $("#director").hide(); //sección del director
    } else if (tipoP == 3) { //Miembro de Comunidad
	    $("#estudiante").hide();
		$("#docente").hide();
        $("#comunidad").show(300);
        $("#padredefamilia").hide();
        $("#voluntario").hide();
        $("#voluntariocoorporativo").hide();
        $("#director").hide(); //sección del director
    } else if (tipoP == 4) { //Madre/Padre de Familia
		$("#estudiante").hide();
		$("#docente").hide();
        $("#comunidad").hide();
        $("#padredefamilia").show(300);
        $("#voluntario").hide();
        $("#voluntariocoorporativo").hide();
        $("#director").hide(); //sección del director
    } else if (tipoP == 5) { //Voluntario
		$("#estudiante").hide();
		$("#docente").hide();
        $("#comunidad").hide();
        $("#padredefamilia").hide();
        $("#voluntario").show(300);
        $("#voluntariocoorporativo").hide();
        $("#director").hide(); //sección del director
    } else if (tipoP == 6) { //Voluntario
		$("#estudiante").hide();
		$("#docente").hide();
        $("#comunidad").hide();
        $("#padredefamilia").hide();
        $("#voluntario").hide();
        $("#voluntariocoorporativo").show(300);
        $("#director").hide(); //sección del director
    } else if (tipoP == 7) { //Voluntario
		$("#estudiante").hide();
		$("#docente").hide();
        $("#comunidad").hide();
        $("#padredefamilia").hide();
        $("#voluntario").hide();
        $("#voluntariocoorporativo").hide();
        $("#director").show(300); //sección del director
    } else {
		$("#estudiante").hide();
		$("#docente").hide();
        $("#comunidad").hide();
        $("#padredefamilia").hide();
        $("#voluntario").hide();
        $("#voluntariocoorporativo").hide();
        $("#director").hide(); //sección del director
        limpiar(); //Al no seleccionar ningun tipo de participante este limpiará todos los campos. 
    }
});

//Al ingresar Edad, calcula la el año que nació y pone día y mes 01/01.
$("#edadpar").change(function() {
    var edad = $(this).val();
    var fechahoy = new Date();

    var año_edad = fechahoy.getFullYear() - edad; //Obtenemos el año actual y le restamos la edad para obtener el año de nacimiento.
    var dia_mes_stnd = "-12-31"; //Se cambió de -01-01 a -12-31
    var fecha_nac = año_edad + dia_mes_stnd;

    $("#fechanacpar").val(fecha_nac);
});

init();

