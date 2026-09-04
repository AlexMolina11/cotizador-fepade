<?php

require_once("../modelos/RepVulnerables.php");

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
header('Content-Disposition: attachment; filename=reporte_vulnerables.xls');
?>
    <table width="600" border="1">
    <thead>
	<tr>
        <th colspan="3"><center>Generales</center></th>
        <th colspan="8"><center>Vulnerables</center></th>
        <th colspan="8"><center>Nuevos</center></th>       
   </tr>  
    <tr>
        <th colspan="3"><center></center></th>
		<th colspan="3"><center>Masculino</center></th>
		<th><center>Total</center></th> 
        <th colspan="3"><center>Femenino</center></th> 
		<th><center>Total</center></th> 
        <th colspan="3"><center>Masculino</center></th>
		<th><center>Total</center></th> 
        <th colspan="3"><center>Femenino</center></th> 		
		<th><center>Total</center></th> 
   </tr>     
     <tr>
     <th>Corredor</th>
     <th>Comunidad</th>
     <th>Centro Educativo</th>
     <th>0</th>
     <th>1a17</th>
     <th>18+</th>
	 <th>Total</th> 
	 <th>0</th>
     <th>1a17</th>
     <th>18+</th>
     <th>Total</th>     
     <th>0N</th>
     <th>1a17N</th>
     <th>18+N</th>
	 <th>TotalN</th>
	 <th>0N</th>
     <th>1a17N</th>
     <th>18+N</th>
     <th>TotalN</th>      
   </tr>
   </thead>
   <tbody>
   <?php 
  
  $trimestre = getReporteVulnerables($fechaini,$fechafin); 
 //echo  $trimestre; 
   while($resultado=mysqli_fetch_array($trimestre )){
    $A0M =  getReporteDetalle($fechaini,$fechafin,$resultado['IDCORREDOR'], $resultado['IDCOMUNIDAD'],$resultado['IDINSTITUCION'], "A0M",0);
   $A1_17M= getReporteDetalle($fechaini,$fechafin,$resultado['IDCORREDOR'], $resultado['IDCOMUNIDAD'],$resultado['IDINSTITUCION'], "A1_17M",0);
   $A18M= getReporteDetalle($fechaini,$fechafin,$resultado['IDCORREDOR'], $resultado['IDCOMUNIDAD'],$resultado['IDINSTITUCION'], "A18M",0);
   $totalM=$A0M+$A1_17M+$A18M;
   $A0F =  getReporteDetalle($fechaini,$fechafin,$resultado['IDCORREDOR'], $resultado['IDCOMUNIDAD'],$resultado['IDINSTITUCION'], "A0F",0);
   $A1_17F= getReporteDetalle($fechaini,$fechafin,$resultado['IDCORREDOR'], $resultado['IDCOMUNIDAD'],$resultado['IDINSTITUCION'], "A1_17F",0);
   $A18F= getReporteDetalle($fechaini,$fechafin,$resultado['IDCORREDOR'], $resultado['IDCOMUNIDAD'],$resultado['IDINSTITUCION'], "A18F",0);
   $totalF=$A0F+$A1_17F+$A18F;
   
   $A0MN =  getReporteDetalle($fechaini,$fechafin,$resultado['IDCORREDOR'], $resultado['IDCOMUNIDAD'],$resultado['IDINSTITUCION'], "A0M",1);
   $A1_17N= getReporteDetalle($fechaini,$fechafin,$resultado['IDCORREDOR'], $resultado['IDCOMUNIDAD'],$resultado['IDINSTITUCION'], "A1_17M",1);
   $A18MN= getReporteDetalle($fechaini,$fechafin,$resultado['IDCORREDOR'], $resultado['IDCOMUNIDAD'],$resultado['IDINSTITUCION'], "A18M",1);
   $totalMN=$A0MN+$A1_17N+$A18MN;
   $A0FN =  getReporteDetalle($fechaini,$fechafin,$resultado['IDCORREDOR'], $resultado['IDCOMUNIDAD'],$resultado['IDINSTITUCION'], "A0F",1);
   $A1_17FN= getReporteDetalle($fechaini,$fechafin,$resultado['IDCORREDOR'], $resultado['IDCOMUNIDAD'],$resultado['IDINSTITUCION'], "A1_17F",1);
   $A18FN= getReporteDetalle($fechaini,$fechafin,$resultado['IDCORREDOR'], $resultado['IDCOMUNIDAD'],$resultado['IDINSTITUCION'], "A18F",1);
   $totalFN=$A0FN+$A1_17FN+$A18FN;
   
  
   echo '  
   <tr>
     <td>'.utf8_decode($resultado['NOMBRECOR']).'</td>
     <td>'.utf8_decode($resultado['NOMBRECOMUNIDAD']).'</td>
     <td>'.utf8_decode($resultado['NOMBREINS']).'</td>
     <td>'.$A0M.'</td>
     <td>'.$A1_17M.'</td>
     <td>'.$A18M.'</td>
     <td>'.$totalM.'</td> 
     <td>'.$A0F.'</td>
     <td>'.$A1_17F.'</td>
     <td>'.$A18F.'</td>
     <td>'.$totalF.'</td>
     <td>'.$A0MN.'</td>
     <td>'.$A1_17N.'</td> 
     <td>'.$A18MN.'</td>
     <td>'.$totalMN.'</td>
     <td>'.$A0FN.'</td>
     <td>'.$A1_17FN.'</td>
     <td>'.$A18FN.'</td>
     <td>'.$totalFN.'</td>	 
    </tr>'; 
      } 
    
      ?> 
</tbody>
 </table>
