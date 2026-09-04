<?php
require_once "../modelos/Entregas.php";
require_once ("../public/tcpdf/tcpdf.php");

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setPrintHeader(false); 
$pdf->setPrintFooter(false);
$pdf->SetMargins(20, 20, 20, false); 
$pdf->SetAutoPageBreak(true, 20); 
$pdf->SetFont('Helvetica', '', 10);
$pdf->addPage();

$content = '';
$entrega= new Entregas();

$id=$_GET['id'];
#$id=1;       
$rspta = $entrega->cabeceraActa($id);


$content.='<table width="676" border="0">
<tr>
  <th width="143" scope="col"><img src="../public/images/FEPADELOGOS-04.png" width="150" height="75" /></th>
  
</tr>
</table>';

$content .='<br><br><br>
<table WIDTH="110">
<thead>
<tr><th style=" width="50"">Fecha Acta:</th><td><b><u>'.$rspta['FECHA'].'</u></b></td></tr>
</thead>

</table>
<br>
<table>
    <tr><th width="17%">Persona Entrega:</th><td><b><u>'.$rspta['NOMBREENTREGAAEN'].'</u></b></td></tr>
 </table>
 <br>
 <table>
     <tr><th width="17%">Persona Recibe:</th><td><b><u>'.$rspta['NOMBRERECIBEAEN'].'</u></b></td></tr>
 </table>

<br><br>
<table border="1">
 <thead>
 <tr><th style="background-color: #CCCBCA;"><h4 align="center">Centro Educativo</h4></th>
 <th style="background-color: #CCCBCA;" WIDTH="60"><h4 align="center">Código</h4></th></tr>
 </thead>
 <tbody>
 <tr><td><h4 align="center">'.$rspta['NOMBREINS'].'</h4></td><td WIDTH="60"><h4 align="center">'.$rspta['CODIGOINS'].'</h4></td></tr>
 </tbody>
 </table>
 <br>

 <br><br>
 <table border="1">
 <thead>
      <tr><th style="background-color: #CCCBCA;"><h4 align="center">Material</h4></th><th style="background-color: #CCCBCA;" WIDTH="60"><h4 align="center">Cantidad</h4></th></tr>
 </thead>
 <tbody>';
    $rsptad = $entrega->detalleActa($id);
    
    while($regd = $rsptad->fetch_object()){
      $content.=' <tr><td>'.$regd->NOMBREMAT.'</td><td WIDTH="60"><h4 align="center">'.$regd->CANTIDADACD.'</h4></td></tr>';

    }
    $content.='</table>';

    $pdf->writeHTML($content, true, 0, true, 0);

	$pdf->lastPage();
	$pdf->output('Reporte.pdf', 'I');
 ?>