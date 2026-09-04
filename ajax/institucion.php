<?php
session_start();
require_once "../modelos/Institucion.php";

$institucion= new Institucion();

$idinstitucion=isset($_POST["idinstitucion"])?limpiarCadena($_POST["idinstitucion"]):"";
$idtipoinstitucion=isset($_POST["idtipoinstitucion"])?limpiarCadena($_POST["idtipoinstitucion"]):""; 
$idcorredor=isset($_POST["idcorredor"])?limpiarCadena($_POST["idcorredor"]):""; 
$idcantoncaserio=isset($_POST["idcantoncaserio"])?limpiarCadena($_POST["idcantoncaserio"]):""; //se agregó cantón/caserio
$idmunicipio=isset($_POST["idmunicipio"])?limpiarCadena($_POST["idmunicipio"]):""; 
$idnvomunicipio=isset($_POST["idnvomunicipio"])?limpiarCadena($_POST["idnvomunicipio"]):""; //se agregó nuevo municipio
$iddepartamento=isset($_POST["iddepartamento"])?limpiarCadena($_POST["iddepartamento"]):""; 
$codigoins=isset($_POST["codigoins"])?limpiarCadena($_POST["codigoins"]):"";
$nombreins=isset($_POST["nombreins"])?limpiarCadena($_POST["nombreins"]):""; 
$ubicacionins=isset($_POST["ubicacionins"])?limpiarCadena($_POST["ubicacionins"]):""; 
$latitud=isset($_POST["latitud"])?limpiarCadena($_POST["latitud"]):""; //se agregó latitud
$longitud=isset($_POST["longitud"])?limpiarCadena($_POST["longitud"]):""; //Se agregó longitud
$fechaingresoins=isset($_POST["fechaingresoins"])?limpiarCadena($_POST["fechaingresoins"]):""; 
$fechasalidains=isset($_POST["fechasalidains"])?limpiarCadena($_POST["fechasalidains"]):""; 
$nombredirectorins=isset($_POST["nombredirectorins"])?limpiarCadena($_POST["nombredirectorins"]):""; 
$correodir=isset($_POST["correodir"])?limpiarCadena($_POST["correodir"]):""; //seagregó correo del director
$telefonoins=isset($_POST["telefonoins"])?limpiarCadena($_POST["telefonoins"]):""; 
$telefonodirins=isset($_POST["telefonodirins"])?limpiarCadena($_POST["telefonodirins"]):""; 
//Matriculas año 2018-2026
$mat2018ins=isset($_POST["mat2018ins"])?limpiarCadena($_POST["mat2018ins"]):""; 
$mat2019ins=isset($_POST["mat2019ins"])?limpiarCadena($_POST["mat2019ins"]):""; 
$mat2020ins=isset($_POST["mat2020ins"])?limpiarCadena($_POST["mat2020ins"]):""; 
$mat2021ins=isset($_POST["mat2021ins"])?limpiarCadena($_POST["mat2021ins"]):""; 
$mat2022ins=isset($_POST["mat2022ins"])?limpiarCadena($_POST["mat2022ins"]):""; 
$mat2023ins=isset($_POST["mat2023ins"])?limpiarCadena($_POST["mat2023ins"]):""; 
$mat2024ins=isset($_POST["mat2024ins"])?limpiarCadena($_POST["mat2024ins"]):""; 
$mat2025ins=isset($_POST["mat2025ins"])?limpiarCadena($_POST["mat2025ins"]):""; 
$mat2026ins=isset($_POST["mat2026ins"])?limpiarCadena($_POST["mat2026ins"]):""; 

$mat18inshom=isset($_POST["mat18inshom"])?limpiarCadena($_POST["mat18inshom"]):"";
$mat18insmuj=isset($_POST["mat18insmuj"])?limpiarCadena($_POST["mat18insmuj"]):""; 
$mat19inshom=isset($_POST["mat19inshom"])?limpiarCadena($_POST["mat19inshom"]):""; 
$mat19insmuj=isset($_POST["mat19insmuj"])?limpiarCadena($_POST["mat19insmuj"]):"";  
$mat20inshom=isset($_POST["mat20inshom"])?limpiarCadena($_POST["mat20inshom"]):"";  
$mat20insmuj=isset($_POST["mat20insmuj"])?limpiarCadena($_POST["mat20insmuj"]):""; 
$mat21inshom=isset($_POST["mat21inshom"])?limpiarCadena($_POST["mat21inshom"]):""; 
$mat21insmuj=isset($_POST["mat21insmuj"])?limpiarCadena($_POST["mat21insmuj"]):""; 
$mat22inshom=isset($_POST["mat22inshom"])?limpiarCadena($_POST["mat22inshom"]):""; 
$mat22insmuj=isset($_POST["mat22insmuj"])?limpiarCadena($_POST["mat22insmuj"]):""; 
$mat23inshom=isset($_POST["mat23inshom"])?limpiarCadena($_POST["mat23inshom"]):""; 
$mat23insmuj=isset($_POST["mat23insmuj"])?limpiarCadena($_POST["mat23insmuj"]):""; 
$mat24inshom=isset($_POST["mat24inshom"])?limpiarCadena($_POST["mat24inshom"]):""; 
$mat24insmuj=isset($_POST["mat24insmuj"])?limpiarCadena($_POST["mat24insmuj"]):""; 
$mat25inshom=isset($_POST["mat25inshom"])?limpiarCadena($_POST["mat25inshom"]):"";  
$mat25insmuj=isset($_POST["mat25insmuj"])?limpiarCadena($_POST["mat25insmuj"]):"";
$mat26inshom=isset($_POST["mat26inshom"])?limpiarCadena($_POST["mat26inshom"]):"";
$mat26insmuj=isset($_POST["mat26insmuj"])?limpiarCadena($_POST["mat26insmuj"]):""; 

$numerodocfemeninoins=isset($_POST["numerodocfemeninoins"])?limpiarCadena($_POST["numerodocfemeninoins"]):""; 
$numerodocmasculinoins=isset($_POST["numerodocmasculinoins"])?limpiarCadena($_POST["numerodocmasculinoins"]):"";
$numerodocentes = 0;
$turnoins=isset($_POST["turnoins"])?limpiarCadena($_POST["turnoins"]):"";
$nivelesEducativos = isset($_POST["niveles_educativos"]) ? array_map('limpiarCadena', $_POST["niveles_educativos"]) : []; // se agregó niveles educativos
$zonains=isset($_POST["zonains"])?limpiarCadena($_POST["zonains"]):"";
$ipusu = $_SERVER["REMOTE_ADDR"];
$regusu = $_SESSION['login'];

if(empty($numerodocfemeninoins)){$numerodocfemeninoins=0;}else{$numerodocfemeninoins=$numerodocfemeninoins;}
if(empty($numerodocmasculinoins)){$numerodocmasculinoins=0;}else{$numerodocmasculinoins=$numerodocmasculinoins;}

//validando Acción Ver
if(isset($_SESSION["010VER13"])&&$_SESSION["010VER13"]==1){
    $btn_stl_ver = "style='display: block;'"; 
} else {
    $btn_stl_ver = "style='display: none;'"; 
}

//validando Acción Editar
if(isset($_SESSION["012EDI13"])&&$_SESSION["012EDI13"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["013ACT13"])&&$_SESSION["013ACT13"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["014DES13"])&&$_SESSION["014DES13"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    /*case 'guardaryeditar':
       
        if (empty($idinstitucion)) {
            $rspta=$institucion->insertar($idtipoinstitucion, $idcorredor, $idcantoncaserio, $idmunicipio, $idnvomunicipio, $iddepartamento, $codigoins,$nombreins, $ubicacionins, $latitud, $longitud, $zonains, $fechaingresoins, $fechasalidains, $nombredirectorins, $correodir, $telefonoins, $telefonodirins, $mat2018ins, $mat2019ins, $mat2020ins, $mat2021ins, $mat2022ins, $mat2023ins, $mat2024ins, $mat2025ins, $mat2026ins, $numerodocfemeninoins, $numerodocmasculinoins, $turnoins,$regusu,$ipusu, $mat18inshom, $mat18insmuj, $mat19inshom, $mat19insmuj, $mat20inshom, $mat20insmuj, $mat21inshom, $mat21insmuj, $mat22inshom, $mat22insmuj, $mat23inshom, $mat23insmuj, $mat24inshom, $mat24insmuj, $mat25inshom, $mat25insmuj, $mat26inshom, $mat26insmuj);
           echo $rspta? "Centro Educativo Registrado":"Centro Educativo no se pudo registrar";
        }else {
            $rspta=$institucion->editar($idinstitucion,$idtipoinstitucion, $idcorredor, $idcantoncaserio, $idmunicipio, $idnvomunicipio, $iddepartamento, $codigoins,$nombreins, $ubicacionins, $latitud, $longitud, $zonains, $fechaingresoins, $fechasalidains, $nombredirectorins, $correodir, $telefonoins, $telefonodirins, $mat2018ins, $mat2019ins, $mat2020ins, $mat2021ins, $mat2022ins, $mat2023ins, $mat2024ins, $mat2025ins, $mat2026ins, $numerodocfemeninoins, $numerodocmasculinoins, $turnoins, $regusu, $mat18inshom, $mat18insmuj, $mat19inshom, $mat19insmuj, $mat20inshom, $mat20insmuj, $mat21inshom, $mat21insmuj, $mat22inshom, $mat22insmuj, $mat23inshom, $mat23insmuj, $mat24inshom, $mat24insmuj, $mat25inshom, $mat25insmuj, $mat26inshom, $mat26insmuj);
           echo $rspta? "Centro Educativo Actualizado":"Centro Educativo no se pudo actualizar";
        }
        break; */
        
    case 'guardaryeditar':
        if (empty($idinstitucion)) {
            // Insertar nuevo centro educativo
            $rspta = $institucion->insertar($idtipoinstitucion, $idcorredor, $idcantoncaserio, $idmunicipio, $idnvomunicipio, $iddepartamento, $codigoins, $nombreins, $ubicacionins, $latitud, $longitud, $zonains, $fechaingresoins, $fechasalidains, $nombredirectorins, $correodir, $telefonoins, $telefonodirins, $mat2018ins, $mat2019ins, $mat2020ins, $mat2021ins, $mat2022ins, $mat2023ins, $mat2024ins, $mat2025ins, $mat2026ins, $numerodocfemeninoins, $numerodocmasculinoins, $turnoins, $regusu, $ipusu, $mat18inshom, $mat18insmuj, $mat19inshom, $mat19insmuj, $mat20inshom, $mat20insmuj, $mat21inshom, $mat21insmuj, $mat22inshom, $mat22insmuj, $mat23inshom, $mat23insmuj, $mat24inshom, $mat24insmuj, $mat25inshom, $mat25insmuj, $mat26inshom, $mat26insmuj);
            
            if ($rspta) {
                // Insertar los niveles educativos asociados
                $idinstitucionNuevo = $institucion->ultimoID(); 
                $institucion->insertarNivelesEducativos($idinstitucionNuevo, $nivelesEducativos);
                echo "Centro Educativo Registrado";
            } else {
                echo "Centro Educativo no se pudo registrar";
            }
        } else {
            // Actualizar centro educativo existente
            $rspta = $institucion->editar($idinstitucion, $idtipoinstitucion, $idcorredor, $idcantoncaserio, $idmunicipio, $idnvomunicipio, $iddepartamento, $codigoins, $nombreins, $ubicacionins, $latitud, $longitud, $zonains, $fechaingresoins, $fechasalidains, $nombredirectorins, $correodir, $telefonoins, $telefonodirins, $mat2018ins, $mat2019ins, $mat2020ins, $mat2021ins, $mat2022ins, $mat2023ins, $mat2024ins, $mat2025ins, $mat2026ins, $numerodocfemeninoins, $numerodocmasculinoins, $turnoins, $regusu, $mat18inshom, $mat18insmuj, $mat19inshom, $mat19insmuj, $mat20inshom, $mat20insmuj, $mat21inshom, $mat21insmuj, $mat22inshom, $mat22insmuj, $mat23inshom, $mat23insmuj, $mat24inshom, $mat24insmuj, $mat25inshom, $mat25insmuj, $mat26inshom, $mat26insmuj);
            
            if ($rspta) {
                // Eliminar los niveles educativos actuales
                $institucion->eliminarNivelesEducativos($idinstitucion);
                // Insertar los nuevos niveles educativos asociados
                $institucion->insertarNivelesEducativos($idinstitucion, $nivelesEducativos);
                echo "Centro Educativo Actualizado";
            } else {
                echo "Centro Educativo no se pudo actualizar";
            }
        }
        break;

    case 'desactivar':
        $rspta=$institucion->desactivar($idinstitucion);
        echo $rspta?"Centro Educativo Desactivado":"Centro Educativo no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$institucion->activar($idinstitucion);
        echo $rspta?"Centro Educativo Activado":"Centro Educativo no se pudo activar";
    break;
        
    case 'mostrar':
        $rspta=$institucion->mostrar($idinstitucion);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$institucion->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            //calculo de Docentes
            $docentes =$reg->NUMERODOCFEMENINOINS+$reg->NUMERODOCMASCULINOINS;
            $data[]= array(
               
                "0"=>$reg->IDINSTITUCION,
                "1"=>$reg->CODIGOINS,
                "2"=>$reg->NOMBREINS,
                "3"=>$reg->NOMBRETIN,
                "4"=>$reg->NOMBRECOR,
                "5"=>$reg->UBICACIONINS,
                "6"=>$reg->ZONAINS,
                "7"=>$reg->TELEFONOINS,
                "8"=>$reg->MAT2024INS,
                "9"=>$reg->NOMBREDIRECTORINS,
                "10"=>$reg->TELEFONODIRINS, //Se agregó el tel. Director
                "11"=>$reg->NUMERODOCFEMENINOINS,
                "12"=>$reg->NUMERODOCMASCULINOINS,
                "13"=>$docentes, //Mostrar el calculo de docentes
                "14"=>($reg->ESTADOINS)?'<span class="label bg-green">Activada</span>':'<span class="label bg-red">Desactivada</span>',
                "15"=>($reg->ESTADOINS)?'<div class="btn-group btn-group-sm" style="display:flex;">
                  <button '.$btn_stl_ver.' class="btn btn-info" onclick="ver('.$reg->IDINSTITUCION.')" data-toggle="tooltip" data-placement="top" title="Ver Centro Educativo"><i class="fa fa-eye"></i></button>'.
                ' <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDINSTITUCION.')" data-toggle="tooltip" data-placement="top" title="Editar Centro Educativo"><i class="fa fa-pencil"></i></button>'.
                ' <button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDINSTITUCION.')" data-toggle="tooltip" data-placement="top" title="Desactivar Centro Educativo"><i class="fa fa-close"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;"><button class="btn btn-info" onclick="ver('.$reg->IDINSTITUCION.')" data-toggle="tooltip" data-placement="top" title="Ver Centro Educativo"><i class="fa fa-eye"></i></button>'.
                '<button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDINSTITUCION.')" data-toggle="tooltip" data-placement="top" title="Editar Centro Educativo"><i class="fa fa-pencil"></i></button>'.
                ' <button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDINSTITUCION.')" data-toggle="tooltip" data-placement="top" title="Activar Centro Educativo"><i class="fa fa-check"></i></button></div>'
                );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;

    case 'selectTipoInstitucion':
        $rspta=$institucion->selectTipoIns();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDTIPOINSTITUCION.'>'.$reg->NOMBRETIN.'</option>';
        }
    break;

    case 'selectCorredor':
        $rspta=$institucion->selectCorr();
        //echo '<option value="">--Seleccione Municipio--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCORREDOR.'>'.$reg->NOMBRECOR.'</option>';
        }
    break;

    case 'selectCorredorDep':
        $rspta=$institucion->selectCorredorDep($iddepartamento);
        echo '<option value="">--Seleccione Corredor--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCORREDOR.'>'.$reg->NOMBRECOR.'</option>';
        }
    break;

    case 'selectCantoncaserio':
        $rspta=$institucion->selectCantoncaserio();
        //echo '<option value="">--Seleccione Cantón/caserio--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCANTONCASERIO.'>'.$reg->NOMBRECANTONCASERIO.'</option>';
        }
    break;

    case 'selectCantoncaserioDep':
        $rspta=$institucion->selectCantoncaserioDep($iddepartamento);
        echo '<option value="1">--Seleccione Cantón/caserío--</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDCANTONCASERIO.'>'.$reg->NOMBRECANTONCASERIO.'</option>';
        }
    break;

    //Se agregó Distrito
    case 'selectDistrito':
        $rspta=$institucion->selectDistrito();
        echo '<option value="">--Seleccione Distrito--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Select distrito para cuando se selecciona un Departamento
    case 'selectDist':
        $rspta=$institucion->selectDist($_POST['iddepartamento']);
        echo '<option value="">--Seleccione Distrito --</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Select distrito para cuando se selecciona un municipio
    case 'selectDistByNvoMuni':
        $rspta=$institucion->selectDistByNvoMuni($_POST['idnvomunicipio']);
        echo '<option value="">--Seleccione Distrito --</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDMUNICIPIO.'>'.$reg->NOMBREMUN.'</option>';
        }
    break;

    //Select Todos los municipios
    case 'selectNvoMunicipio':
        $rspta=$institucion->selectNvoMunicipio();
        echo '<option value="">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDNVOMUN.'>'.$reg->NOMBRENVOMUN.'</option>';
        }
    break;

    //Select municipios pertenecientes a un departamento
    case 'selectnvoMuni':
        $rspta=$institucion->selectnvoMuni($iddepartamento);
        echo '<option value="">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDNVOMUN.'>'.$reg->NOMBRENVOMUN.'</option>';
        }
    break;

    //Select municipios pertenecientes a un distrito
    case 'selectnvoMuniByDist':
        $rspta=$institucion->selectnvoMuniByDist($idmunicipio);
		//echo '<option value="0">--Seleccione Municipio--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDNVOMUN.'>'.$reg->NOMBRENVOMUN.'</option>';
        }
	break;

    //Select todos los departamentos
    case 'selectDepartamento':
        $rspta=$institucion->selectDepartamento();
        echo '<option value="">--Seleccione Departamento--</option>'; 
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;

    //Select los departamentos pertenecientes a un distrito
    case 'selectDepa':
        $rspta=$institucion->selectDepa($_POST['idmunicipio']);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;

    //Select departamento perteneciente a un municipio
    case 'selectDepaByNvoMuni':
        $rspta=$institucion->selectDepaByNvoMuni($_POST['idnvomunicipio']);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDDEPARTAMENTO.'>'.$reg->NOMBREDEP.'</option>';
        }
    break;
}
?>