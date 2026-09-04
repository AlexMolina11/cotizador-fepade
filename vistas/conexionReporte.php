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

while ($dato =mysqli_fetch_array($Listados)) {

  echo "<tr>";
  echo "<td>".$dato['IDACTIVIDADES']."</td>";
  echo "<td>".utf8_decode($dato['NOMBREACT'])."</td>";
  // echo "<td>".utf8_decode($dato['grupos'])."</td>";
  echo "<td>".utf8_decode($dato['LUGARACT'])."</td>";
  echo "<td>".utf8_decode($dato['OBJETIVOACT'])."</td>";
  echo "<td>".utf8_decode($dato['NOMBRERES'])."</td>";   
  echo "<td>".$dato['fechaini']."</td>";
  echo "<td>".$dato['fechafin']."</td>";
  echo "<td>".$dato['horario']."</td>";
  echo "<td>".$dato['horas']."</td>";
  echo "<td>".$dato['IDJORNADAS']."</td>";
  echo "<td>".utf8_decode($dato['NOMBREJOR'])."</td>";
  echo "<td>".$dato['IDPARTICIPANTE']."</td>";
  echo "<td>".utf8_decode($dato['nombrepar'])."</td>";
  echo "<td>".$dato['SEXOPAR']."</td>";
  echo "<td>".$dato['EDADPAR']."</td>";
  echo "<td>".utf8_decode($dato['OCUPACIONPAR'])."</td>";
  echo "<td>".utf8_decode($dato['participantetipo'])."</td>";
  echo "<td>".utf8_decode($dato['tipoactividad'])."</td>";
  echo "<td>".utf8_decode($dato['nombremun'])."</td>";
  echo "<td>".utf8_decode($dato['NOMBRECOR'])."</td>";
  echo "<td>".utf8_decode($dato['nombrecomunidad'])."</td>";
  echo "<td>".utf8_decode($dato['nombreinstitucion'])."</td>";
  echo "<td>".utf8_decode($dato['tipoinstitu'])."</td>";
  echo "<td>".utf8_decode($dato['ESCUELAPAR'])."</td>";
  echo "<td>".utf8_decode($dato['zona'])."</td>";
  echo "</tr>";
}
?>   
</table>
