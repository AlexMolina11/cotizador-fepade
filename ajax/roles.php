<?php
if(strlen(session_id())<1)
session_start();
require_once "../modelos/Rol.php";

$rol= new Rol();

$idrol=isset($_POST["idrol"])?limpiarCadena($_POST["idrol"]):"";
$nombrerol=isset($_POST["nombrerol"])?limpiarCadena($_POST["nombrerol"]):"";
$permiso=isset($_POST["permiso"])?limpiarCadena($_POST["permiso"]):"";
$arearesponsable=isset($_POST["permisoareares"])?limpiarCadena($_POST["permisoareares"]):""; //se agregó area responsable
$ipusu = $_SERVER["REMOTE_ADDR"];
$regusu = $_SESSION["login"];

//validando Acción Editar
if(isset($_SESSION["089EDI32"])&&$_SESSION["089EDI32"]==1){
    $btn_stl_edi = "style='display: block;'"; 
} else {
    $btn_stl_edi = "style='display: none;'"; 
}

//validando Acción Activar
if(isset($_SESSION["090ACT32"])&&$_SESSION["090ACT32"]==1){
    $btn_stl_act = "style='display: block;'"; 
} else {
    $btn_stl_act = "style='display: none;'"; 
}

//validando Acción Desactivar
if(isset($_SESSION["091DES32"])&&$_SESSION["091DES32"]==1){
    $btn_stl_desact = "style='display: block;'"; 
} else {
    $btn_stl_desact = "style='display: none;'"; 
}

switch ($_GET["op"]) {
    case 'guardaryeditar':  
        if (empty($idrol)) {
            //valida que el rol sea único
            $rs=$rol->rolunico($nombrerol);
            $reg = $rs->fetch_object();
            if($reg->cantidad ==0){
                $rspta=$rol->insertar($nombrerol,$regusu,$permiso,$ipusu,$_POST['acceso'],$_POST['accesoacciones'],$arearesponsable);
                echo $rspta? "Rol Registrado":"No se pudieron registrar todos los datos del Rol";
            }else{echo 'El Rol ya existe';}    
        }else {
            $rspta=$rol->editar($idrol,$nombrerol,$regusu,$permiso,$ipusu,$_POST['acceso'],$_POST['accesoacciones'],$arearesponsable);
            echo $rspta? "Rol Actualizado":"Rol no se pudo actualizar";
        }
    break;

    case 'desactivar':
        $rspta=$rol->desactivar($idrol);
        echo $rspta?"Rol Desactivado":"Rol no se pudo desactivar";
    break;

    case 'activar':
        $rspta=$rol->activar($idrol);
        echo $rspta?"Rol Activado":"Rol no se pudo activar";
    break;

    case 'mostrar':
        $rspta=$rol->mostrar($idrol);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta=$rol->listar();
        //Vamos a declarar un array
        $data= Array();

        while ($reg = $rspta->fetch_object()) {
            $data[]= array(
                
                "0"=>$reg->NOMBREROL,
                "1"=>$reg->MODULOS,
                "2"=>($reg->ESTADOROL)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desacativado</span>',
                "3"=>($reg->ESTADOROL)?'<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDROL.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                ' <button '.$btn_stl_desact.' class="btn btn-danger" onclick="desactivar('.$reg->IDROL.')" data-toggle="tooltip" data-placement="top" title="Desactivar Rol"><i class="fa fa-close"></i></button></div>':
                '<div class="btn-group btn-group-sm" style="display:flex;">
                <button '.$btn_stl_edi.' class="btn btn-warning" onclick="mostrar('.$reg->IDROL.')" data-toggle="tooltip" data-placement="top" title="Editar Información"><i class="fa fa-pencil"></i></button>'.
                ' <button '.$btn_stl_act.' class="btn btn-primary" onclick="activar('.$reg->IDROL.')" data-toggle="tooltip" data-placement="top" title="Activar Rol"><i class="fa fa-check"></i></button></div>'
            );
        }
        $results = array("sEcho" => 1, //Información para el datatables
                         "iTotalRecords" => count($data),//enviamos el total registros al datatable
                         "iTotalDisplayRecords" => count($data),//enviamos el total de registros a visualizar
                         "aaData" =>$data);
        echo json_encode($results);                 
    break;

    /*case 'selectRoles':
        $opciones='<option value="0">Seleccione rol</option>';
        $rspta=$Rol->roles();
        while ($reg = $rspta->fetch_object()) {
            $opciones.= '<option value='.$reg->IDROL.'>'.$reg->NOMBREROL.'</option>';
        }
        echo $opciones;
    break;*/

    
    case 'permisos':
        //Obtenemos todos los módulos
        $rspta = $rol->modulosLista();
        
        //Obtener los permisos asignados al Rol 
        $id = $_GET['id'];
        $marcados = $rol->listarmarcados($id);
        
        //Declaramos un array para almacenar los permisos marcados
        $valores = array();
        
        //Almacenar los permisos asignados al usuario en el array
        while ($per = $marcados->fetch_object()) {
            array_push($valores, $per->IDMODULO);
        }
        
        // Controles globales con estilo glass
        echo "<div class='' style='margin-bottom: 20px;'>";
        echo "<div class='menu-card-content' style='padding: 1.5rem; flex-wrap: wrap;'>";
        echo "<div style='display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap;'>";
        echo "<button type='button' class='btn btn-success' onclick='selectAllPermisos()'>Seleccionar Todos</button>";
        echo "<button type='button' class='btn btn-danger' onclick='deselectAllPermisos()'>Deseleccionar Todos</button>";
        echo "<button type='button' class='btn btn-primary' onclick='collapseAllPermisos()'>Colapsar Todos</button>";
        echo "<button type='button' class='btn btn-primary' onclick='expandAllPermisos()'>Expandir Todos</button>";
        echo "</div>";
        echo "<div style='display: flex; align-items: center; gap: 15px; flex-wrap: wrap;'>";
        echo "<span id='contadorPermisos' style='color: var(--text-primary); font-weight: bold; text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);'>Seleccionados: 0</span>";
        echo "</div>";
        echo "</div>";
        
        echo "<div id='listaPermisos'>";
        
        $totalModulos = 0;
        $modulosSeleccionados = 0;
        $ultimoPadre = false; // bandera para saber si hay un padre abierto
        
        //Mostramos la lista de permisos en la vista y si están o no marcados
        while ($mods = $rspta->fetch_object()) {
            $totalModulos++;
            
            //Validación de estilo Input Style
            $is = "style='outline:auto; margin-right: 10px!important;'";
            $sw = in_array($mods->IDMODULO, $valores) ? 'checked' : '';
            
            if ($sw) $modulosSeleccionados++;
            
            //Si es Módulo padre o hijo
            if ($mods->PADREOHIJO == 1) {
                // Si había un padre abierto antes, cerramos sus hijos
                if ($ultimoPadre) {
                    echo "</div></div>"; // cerrar hijos-container y modulo-padre
                }

                // Nuevo padre
                echo "<div class='modulo-padre' style='margin-bottom: 10px;'>";
                echo "<div style='padding: 8px; border-left: 4px solid #097245; cursor: pointer;' onclick='toggleHijos(this)'>";
                echo "<span class='toggle-icon' style='font-size: 14px; margin-right: 8px;'>▼</span>";
                echo "<input type='checkbox' " . $sw . " " . $is . " name='acceso[]' value='" . $mods->IDMODULO . "' onchange='updateCounter()'>";
                echo "<span style='font-size: 16px; text-transform: uppercase; font-weight: 600; color: #097245!important;'>";
                echo $mods->NOMBREMOD;
                echo "</span>";
                echo "</div>";
                echo "<div class='hijos-container' style='margin-left: 25px; display: block;'>";

                $ultimoPadre = true;
            } else if ($mods->PADREOHIJO == 0) {
                // Módulo hijo
                echo "<div style='padding: 5px 0; margin-left: 15px; color:black;'>";
                echo "<input type='checkbox' " . $sw . " " . $is . " name='acceso[]' value='" . $mods->IDMODULO . "' onchange='updateCounter()'>";
                echo "<span style='font-weight: 500;'>" . $mods->NOMBREMOD . "</span>";
                echo "</div>";
            }
        }
        
        // Al final, cerrar si quedó un padre abierto
        if ($ultimoPadre) {
            echo "</div></div>";
        }

        echo "</div></div>"; // Cerrar lista y panel
        
        // JavaScript para funcionalidades
        echo "<script>
            // Actualizar contador inicial
            document.addEventListener('DOMContentLoaded', function() {
                updateCounter();
            });
            
            function selectAllPermisos() {
                const checkboxes = document.querySelectorAll('input[name=\"acceso[]\"]');
                checkboxes.forEach(cb => cb.checked = true);
                updateCounter();
            }
            
            function deselectAllPermisos() {
                const checkboxes = document.querySelectorAll('input[name=\"acceso[]\"]');
                checkboxes.forEach(cb => cb.checked = false);
                updateCounter();
            }

            function collapseAllPermisos() {
                const containers = document.querySelectorAll('.hijos-container');
                const icons = document.querySelectorAll('.toggle-icon');
                containers.forEach(container => container.style.display = 'none');
                icons.forEach(icon => {
                    icon.textContent = '▶';
                    icon.style.transform = 'rotate(-90deg)';
                });
            }
            
            function expandAllPermisos() {
                const containers = document.querySelectorAll('.hijos-container');
                const icons = document.querySelectorAll('.toggle-icon');
                containers.forEach(container => container.style.display = 'block');
                icons.forEach(icon => {
                    icon.textContent = '▼';
                    icon.style.transform = 'rotate(0deg)';
                });
            }
            
            function updateCounter() {
                const checkboxes = document.querySelectorAll('input[name=\"acceso[]\"]:checked');
                document.getElementById('contadorPermisos').textContent = 'Seleccionados: ' + checkboxes.length;
            }
            
            function toggleHijos(elemento) {
                const icon = elemento.querySelector('.toggle-icon');
                const container = elemento.nextElementSibling;
                if (container.style.display === 'none') {
                    container.style.display = 'block';
                    icon.textContent = '▼';
                } else {
                    container.style.display = 'none';
                    icon.textContent = '▶';
                }
            }
        </script>";
        
    break;

    case 'permisosacciones':
        //Obtenemos todas las acciones
        $rspta = $rol->accionesLista();
        
        //Obtener los permisos asignados al Rol 
        $id = $_GET['id'];
        $accmarcados = $rol->listaraccionesmarcados($id);
        
        //Declaramos un array para almacenar los permisos marcados
        $valores = array();
        while ($per = $accmarcados->fetch_object()) {
            array_push($valores, $per->IDACCION);
        }
        
        // Agregar controles globales
        echo "<div class='' style='margin-bottom: 20px;'>
                <div class='menu-card-content' style='padding: 1.5rem; flex-wrap: wrap;'>
                    <div style='display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap;'>
                        <button type='button' class='btn btn-success' onclick='selectAllAcciones()'>Seleccionar Todas</button>
                        <button type='button' class='btn btn-danger' onclick='deselectAllAcciones()'>Deseleccionar Todas</button>
                        <button type='button' class='btn btn-primary' onclick='collapseAllAcciones()'>Colapsar Todos</button>
                        <button type='button' class='btn btn-primary' onclick='expandAllAcciones()'>Expandir Todos</button>
                    </div>
                    <div style='display: flex; align-items: center; gap: 15px; flex-wrap: wrap;'>
                        <input type='text' id='filtroAcciones' placeholder='Buscar en módulos y acciones...' 
                            style='background: rgba(255, 255, 255, 0.1); border: 2px solid rgba(255, 255, 255, 0.2);
                            color: var(--white); padding: 0.75rem 1rem; border-radius: 12px; backdrop-filter: blur(10px);
                            font-size: 0.95rem; flex: 1; min-width: 250px;' onkeyup='filtrarAcciones()'>
                        <span id='contadorAcciones' style='color: var(--text-primary); font-weight: bold; 
                            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);'>Seleccionadas: 0</span>
                    </div>
                </div>
            </div>";
        
        // Agrupar acciones por módulo
        $accionesPorModulo = array();
        $accionesSinModulo = array();
        while ($accs = $rspta->fetch_object()) {
            if ($accs->IDMODULO == null) {
                $accionesSinModulo[] = $accs;
            } else {
                if (!isset($accionesPorModulo[$accs->NOMBREMOD])) {
                    $accionesPorModulo[$accs->NOMBREMOD] = array();
                }
                $accionesPorModulo[$accs->NOMBREMOD][] = $accs;
            }
        }
        
        echo "<div id='listaAcciones'>";
        
        // Mostrar acciones agrupadas por módulo
        foreach ($accionesPorModulo as $nombreModulo => $acciones) {
            echo "<div class='modulo-acciones' data-modulo='" . strtolower($nombreModulo) . "' 
                    style='margin-bottom: 15px; border: 1px solid rgba(0, 31, 63, 0.9) !important; border-radius: 5px;'>
                    <div style='background-color: rgba(0, 31, 63, 0.9) !important; padding: 10px; cursor: pointer; font-weight: bold; color: White;' 
                        onclick='toggleAccionesModulo(this)'>
                        <span class='toggle-icon' style='margin-right: 8px;'>▼</span>
                        Módulo: " . $nombreModulo . " <small>(" . count($acciones) . " acciones)</small>
                    </div>
                    <div class='acciones-container' style='padding: 10px; display: block;'>";
            
            foreach ($acciones as $accs) {
                $sw = in_array($accs->IDACCION, $valores) ? 'checked' : '';
                echo "<div class='accion-item' style='margin-bottom: 8px; padding: 5px;' 
                        data-nombre='" . strtolower($accs->NOMBREACC) . "'>
                        <input type='checkbox' $sw style='outline:auto; margin-right: 5px;' 
                                name='accesoacciones[]' value='" . $accs->IDACCION . "' onchange='updateCounterAcciones()'>
                        <span style='font-weight: 500; color:#097245; margin-left: 10px;'>" . $accs->NOMBREACC . "</span>
                    </div>";
            }
            
            echo "</div></div>";
        }
        
        // Mostrar acciones sin módulo
        if (!empty($accionesSinModulo)) {
            echo "<div class='modulo-acciones' data-modulo='sin modulo'
                    style='margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px;'>
                    <div style='background-color: #fff3cd; padding: 10px; cursor: pointer; font-weight: bold; color: #856404;' 
                        onclick='toggleAccionesModulo(this)'>
                        <span class='toggle-icon' style='margin-right: 8px;'>▼</span>
                        Sin Módulo Asignado <small>(" . count($accionesSinModulo) . " acciones)</small>
                    </div>
                    <div class='acciones-container' style='padding: 10px; display: block;'>";
            
            foreach ($accionesSinModulo as $accs) {
                $sw = in_array($accs->IDACCION, $valores) ? 'checked' : '';
                echo "<div class='accion-item' style='margin-bottom: 8px; padding: 5px;' 
                        data-nombre='" . strtolower($accs->NOMBREACC) . "'>
                        <input type='checkbox' $sw style='outline:auto; margin-right: 5px;' 
                                name='accesoacciones[]' value='" . $accs->IDACCION . "' onchange='updateCounterAcciones()'>
                        <span style='font-weight: 500; color: darkred;'>" . $accs->NOMBREACC . "</span>
                    </div>";
            }
            
            echo "</div></div>";
        }
        
        echo "</div>";
        
        // JavaScript para funcionalidades de acciones
        echo "<script>
            // Actualizar contador inicial
            document.addEventListener('DOMContentLoaded', function() {
                updateCounterAcciones();
            });
            
            function selectAllAcciones() {
                const checkboxes = document.querySelectorAll('input[name=\"accesoacciones[]\"]');
                checkboxes.forEach(cb => cb.checked = true);
                updateCounterAcciones();
            }
            
            function deselectAllAcciones() {
                const checkboxes = document.querySelectorAll('input[name=\"accesoacciones[]\"]');
                checkboxes.forEach(cb => cb.checked = false);
                updateCounterAcciones();
            }

            function collapseAllAcciones() {
                const containers = document.querySelectorAll('.acciones-container');
                const icons = document.querySelectorAll('.modulo-acciones .toggle-icon');
                containers.forEach(container => container.style.display = 'none');
                icons.forEach(icon => {
                    icon.textContent = '▶';
                    icon.style.transform = 'rotate(-90deg)';
                });
            }
            
            function expandAllAcciones() {
                const containers = document.querySelectorAll('.acciones-container');
                const icons = document.querySelectorAll('.modulo-acciones .toggle-icon');
                containers.forEach(container => container.style.display = 'block');
                icons.forEach(icon => {
                    icon.textContent = '▼';
                    icon.style.transform = 'rotate(0deg)';
                });
            }
            
            function updateCounterAcciones() {
                const checkboxes = document.querySelectorAll('input[name=\"accesoacciones[]\"]:checked');
                document.getElementById('contadorAcciones').textContent = 'Seleccionadas: ' + checkboxes.length;
            }
            
            function toggleAccionesModulo(elemento) {
                const span = elemento.querySelector('span');
                const container = elemento.nextElementSibling;
                if (container.style.display === 'none') {
                    container.style.display = 'block';
                    span.textContent = '▼';
                } else {
                    container.style.display = 'none';
                    span.textContent = '▶';
                }
            }
            
            function filtrarAcciones() {
                const filtro = document.getElementById('filtroAcciones').value.toLowerCase();
                const modulos = document.querySelectorAll('.modulo-acciones');
                let modulosVisibles = 0;
                
                modulos.forEach(modulo => {
                    const nombreModulo = modulo.getAttribute('data-modulo') || '';
                    const acciones = modulo.querySelectorAll('.accion-item');
                    let accionesVisibles = 0;
                    
                    // Verificar si el filtro coincide con el nombre del módulo
                    const moduloCoincide = nombreModulo.includes(filtro);
                    
                    acciones.forEach(accion => {
                        const nombreAccion = accion.getAttribute('data-nombre') || '';
                        const accionCoincide = nombreAccion.includes(filtro);
                        
                        if (moduloCoincide || accionCoincide || filtro === '') {
                            accion.style.display = 'block';
                            accionesVisibles++;
                        } else {
                            accion.style.display = 'none';
                        }
                    });
                    
                    // Mostrar/ocultar módulo completo
                    if (accionesVisibles > 0 || filtro === '') {
                        modulo.style.display = 'block';
                        modulosVisibles++;
                    } else {
                        modulo.style.display = 'none';
                    }
                });
                
                // Si hay filtro activo, expandir automáticamente los módulos visibles
                if (filtro !== '') {
                    modulos.forEach(modulo => {
                        if (modulo.style.display !== 'none') {
                            const container = modulo.querySelector('.acciones-container');
                            const icon = modulo.querySelector('.toggle-icon');
                            if (container && icon) {
                                container.style.display = 'block';
                                icon.textContent = '▼';
                                icon.style.transform = 'rotate(0deg)';
                            }
                        }
                    });
                }
            }
        </script>";
        
    break;

    //Se agregó área responsable
    case 'selectAreaResponsable':
        $rspta=$rol->selectAreaResponsable();
		echo '<option value="">--Seleccione un área--</option>';
        echo '<option value="0"> 0 - Todas </option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->IDAREARES.'>'.$reg->IDAREARES.' - '.$reg->NOMBREAREARES.'</option>';
        }
	break;
    
    
}
?>