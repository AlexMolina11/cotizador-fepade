<?php

require "../modelos/Reportegeneral.php";

  if(isset($_POST['fechaini'])){
 $fechaini =$_POST['fechaini'];
 }else{
	 $fechaini =NULL; 
	 }

   if(isset($_POST['fechafin'])){
 $fechafin=$_POST['fechafin'];
 }else{
	$fechafin=NULL; 
	 }
 
	  if(isset($_POST['desplegar'])){
  $desplegar=$_POST['desplegar'];
 }else{
	 $desplegar=NULL; 
	 }  
header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
header('Content-Disposition: attachment; filename=bdgeneral_educonv.xls');
?>
      <table width="600" border="1">
        <tr>
          <td width="30">ida</td>
		  <td width="100">Actividad</td>
          <!--<td width="125">Grupo</td>-->
          <td width="100">Lugar</td>
          <td width="300">Objetivo</td>
          <td width="300">Responsable</td>
          <td width="75">Fecha inicio</td>
          <td width="75">Fecha final</td>
          <td width="75">Horario</td>
          <td width="50">No horas</td>
		  <td width="30">idj</td>
          <td width="100">Jornadas</td>
		  <td width="30">idp</td>
          <td width="300">Nombre</td>
          <td width="30">Sexo</td>
          <td width="30">Edad</td>
          <td width="100">Cargo/Ocupaci&oacute;n</td>
          <td width="100">Tipo Participante</td>
          <td width="100">Tipo de Actividad</td>
          <td width="100">Municipio</td>
          <td width="100">Corredor</td>
          <td width="100">Comunidad</td>
		  <td width="125">Centro Educativo</td>
		  <td width="75">Tipo Centro Educativo</td>
          <td width="125">Escuela Participante </td>
          <td width="100">Zona</td>
        </tr>
        <?php 
   $id=0;
   $Listados=RepReporteGeneral($fechaini,  $fechafin); 
  //echo $Listados; 
   	while($dato =mysqli_fetch_array($Listados)){
			 
   ?>    
        <tr>
		  <td><?php echo $dato['IDACTIVIDADES']; ?></td>
          <td><?php echo utf8_decode($dato['NOMBREACT']); ?></td>
          <!--<td><?php /*echo utf8_decode($dato['grupos']);*/ ?></td>-->
          <td><?php echo utf8_decode($dato['LUGARACT']); ?></td>
          <td><?php echo utf8_decode($dato['OBJETIVOACT']); ?></td>
          <td><?php echo utf8_decode($dato['NOMBRERES']); ?></td>          
          <td><?php echo $dato['fechaini']; ?></td>
          <td><?php echo $dato['fechafin']; ?></td>
          <td><?php echo $dato['horario']; ?></td>
          <td><?php echo $dato['horas']; ?></td>
		  <td><?php echo $dato['IDJORNADAS']; ?></td>
          <td><?php echo utf8_decode($dato['NOMBREJOR']); ?></td>
		  <td><?php echo $dato['IDPARTICIPANTE']; ?></td>
          <td><?php echo utf8_decode($dato['nombrepar']); ?></td>
          <td><?php echo $dato['SEXOPAR']; ?></td>
          <td><?php echo $dato['EDADPAR']; ?></td>
          <td><?php echo utf8_decode($dato['OCUPACIONPAR']); ?></td>
          <td><?php echo utf8_decode($dato['participantetipo']); ?></td>
          <td><?php echo utf8_decode($dato['tipoactividad']); ?></td>
          <td><?php echo utf8_decode($dato['nombremun']); ?></td>
          <td><?php echo utf8_decode($dato['NOMBRECOR']); ?></td>
          <td><?php echo utf8_decode($dato['nombrecomunidad']); ?></td>
		  <td><?php echo utf8_decode($dato['nombreinstitucion']); ?></td>
		  <td><?php echo utf8_decode($dato['tipoinstitu']); ?></td>
          <td><?php echo utf8_decode($dato['ESCUELAPAR']); ?></td>
          <td><?php echo utf8_decode($dato['zona']); ?></td>
           
        </tr>
         <?php } ?>   
</table>
