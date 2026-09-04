<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';
require_once("../modelos/RepParticipantes.php");
//Pendiende de crear el submodulo - Fase 2
//Por mientras el límite será por Modulo General
if($_SESSION['009REP']==1){
    $fechaini = NULL;
    $fechafin = NULL; 
if(isset( $_POST['fechaini'])){
	$fechaini = $_POST['fechaini'];
}


if(isset( $_POST['fechafin'])){
	$fechafin = $_POST['fechafin'];
}


if(isset( $_POST['pasa'])){
	$pasa = $_POST['pasa'];
}else{
    $pasa =0;

}
if(isset($_POST['corredor'])){
  $corredor =$_POST['corredor'];
}

if(isset($_POST['tipoparticipante'])){
  $tipoparticipante =$_POST['tipoparticipante'];
}
?>
<!--CONTENIDO-->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content-header">
      <h1>        
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Menu</a></li>
        <li><a href="#">Reportes</a></li>
        <li class="active">Reporte de Participantes</li>
      </ol>
    </section>
	<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h1 class="box-title">Reporte de Participantes</h1>
        <div class="box-tools pull-right"></div>
	</div>
    <div class="panel-body table-reponsive"  id="listadoregistros">
    <form name="form1" method="post" action="" return false>
    <input id="pasa" name="pasa" type="hidden" value="1">
    <table border="1" id="tbllistadoregistros" class="table table-striped table-bordered table-condensed table-hover">
    <tr>
        <td width="145">Fecha Inicio: <label for="fechaini"></label>
        <input name="fechaini" type="date" id="fechaini" value="<?php echo $fechaini; ?>">&nbsp;</td>
        <td width="174">Fecha Fin: <input name="fechafin" type="date" id="fechafin" value="<?php echo $fechafin; ?>"></td>
    </tr>
    <tr>
    <td> <input type="hidden" id="idparticipante" name="idparticipante">
        <label>Tipo de Participante:</label>
        <select class="form-control select-picker" name="tipoparticipante" id="tipoparticipante" >															
        <?php
            $rspta=selectTipoParticipante();
            echo '<option value="0">--Seleccione un tipo--</option>';
            while ($reg = mysqli_fetch_array($rspta)) {
              echo '<option value='.$reg['IDTIPOPARTICIPANTE'].'>'.$reg['NOMBRETIP'].'</option>';
            }
        ?>
        </select>
    </td>
    <td> 
        <label>Corredor:</label>
         <select class="form-control select-picker" id="corredor" name="corredor">
																					
        <?php          
    
            $rspta=selectCorredor();
            echo '<option value="0">--Seleccione un corredor--</option>';
            while ($reg = mysqli_fetch_array($rspta)) {
               echo '<option value='.$reg['IDCORREDOR'].'>'.$reg['NOMBRECOR'].'</option>';
            }
        ?>
        </select>
    </td>
    </tr>
    <tr>
        <td width="31" rowspan="2"><input class="btn btn-primary" type="submit" name="BUSCAR" id="BUSCAR" value="Generar"></td> 
       
        </tr>
    </table>
    </form>  
    <br> 
    <table border="2" id="tblreporte" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
    <tr>
        <th colspan="2"><center>Domicilio</center></th>
        <th colspan="2"><center>Sexo</center></th>
        <th colspan="7"><center>Edad</center></th>
        <th colspan="2"><center>Total</center></th>
   </tr>   
   <tr>
     <th>Municipio</th>
     <th>Comunidad</th>
     <th>Masculino</th>
     <th>Femenino</th>
     <th>0</th>
     <th>1-9</th>
     <th>10-14</th>
     <th>15-19</th>
     <th>20-24</th>
     <th>25-29</th>
     <th>30+</th>
     <th>Total</th>
     <th>Nuevos</th>
 
   </tr>
   </thead>
   <tbody>
   <?php 
   if($pasa==1){
  $trimestre = getReporte_Participantes($fechaini,$fechafin,$corredor,$tipoparticipante); 
 //echo  $trimestre; 
   while($resultado=mysqli_fetch_array($trimestre )){
	$masculino=        getReporte_ParticipantesDetalle($fechaini,$fechafin,$resultado['IDMUNICIPIO'], $resultado['IDCOMUNIDAD'], "M",$corredor,$tipoparticipante);
	$femenino=      getReporte_ParticipantesDetalle($fechaini,$fechafin,$resultado['IDMUNICIPIO'], $resultado['IDCOMUNIDAD'], "F",$corredor,$tipoparticipante);
   $A0 =       getReporte_ParticipantesDetalle($fechaini,$fechafin,$resultado['IDMUNICIPIO'], $resultado['IDCOMUNIDAD'], "A0",$corredor,$tipoparticipante);
   $A1_9=       getReporte_ParticipantesDetalle($fechaini,$fechafin,$resultado['IDMUNICIPIO'], $resultado['IDCOMUNIDAD'], "A1_9",$corredor,$tipoparticipante);
   $A10_14=       getReporte_ParticipantesDetalle($fechaini,$fechafin,$resultado['IDMUNICIPIO'], $resultado['IDCOMUNIDAD'], "A10_14",$corredor,$tipoparticipante);
   $A15_19=       getReporte_ParticipantesDetalle($fechaini,$fechafin,$resultado['IDMUNICIPIO'], $resultado['IDCOMUNIDAD'], "A15_19",$corredor,$tipoparticipante);
   $A20_24=    getReporte_ParticipantesDetalle($fechaini,$fechafin,$resultado['IDMUNICIPIO'], $resultado['IDCOMUNIDAD'], "A20_24",$corredor,$tipoparticipante);
   $A25_29=       getReporte_ParticipantesDetalle($fechaini,$fechafin,$resultado['IDMUNICIPIO'], $resultado['IDCOMUNIDAD'], "A25_29",$corredor,$tipoparticipante);
   $A30MAS=       getReporte_ParticipantesDetalle($fechaini,$fechafin,$resultado['IDMUNICIPIO'], $resultado['IDCOMUNIDAD'], "A30MAS",$corredor,$tipoparticipante);
   $total=$masculino+$femenino;
	
   echo '  
   <tr>
     <td>'.$resultado['nombremun'].'</td>
     <td>'.$resultado['nombrecom'].'</td>
     <td>'.$masculino.'</td>
      <td>'.$femenino.'</td>
     <td>'.$A0.'</td>
     <td>'.$A1_9.'</td>
     <td>'.$A10_14.'</td>
     <td>'.$A15_19.'</td>
     <td>'.$A20_24.'</td>
     <td>'. $A25_29.'</td>
     <td>'.$A30MAS.'</td>
     <td>'.$total.'</td>
     <td>'.$resultado['nuevos'].'</td>
    </tr>'; 
      } 
    }
      ?> 
</tbody>
 </table>
 </div>
 </div>
	</section><!-- /.content -->

</div><!-- /.content-wrapper -->
<!--Fin-Contenido-->
<?php
}else{
    require 'noacceso.php';
}
require 'footer.php';
?>
<script>
  $( document ).ready(function() {
        $('#tblreporte').dataTable({
        "aProcessing": true, //Activamos el procesamiento del datatables
        "aServerSide": true, //Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', //Definimos los elementos del control de tabla
        buttons: [
            'copy',
            'excel',
            'csv',
            'pdf',
            'print'
        ],
        "bDestroy": true,
        "iDisplayLength": 5, //paginación
        "order": [
                [0, "desc"]
            ],
		"scrollX": true,
		"scrollCollapse": false,
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
        });
        
     
  });
</script>
<?php
}
ob_end_flush();
?>