<?php
    require('../../config/Conexion.php');
    $id = $_GET['id'];
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment; filename=Reporte_".$id.".doc");

    
    //Consulta a Base de Datos
    $consulta = "SELECT  a.IDASISTEC,
                    date_format(a.FECHAAT, '%d-%m-%Y') as FECHAAT,
                    a.CODIGOINSTAT,
                    a.IDINSTITUCIONAT,
                    i.NOMBREINS,
                    a.IDDEPARTAMENTOINSTAT,
                    d.NOMBREDEP,
                    a.IDMUNICIPIOINSTAT,
                    m.NOMBREMUN,
                    a.IDCORREDORINSTAT,
                    c.NOMBRECOR,
                    a.NOMBREDIRINSTAT,
                    a.TELEFONOINSTAT,
                    a.IDCOMUNIDADAT,
                    cm.NOMBRECOMUNIDAD,
                    a.IDUSUARIOAT,
                    u.NOMBREUSU,
                    a.IDAREARESAT,
                    ar.NOMBREAREARES,
                    a.IDTIPOAT,
                    t.NOMBRETAT,
                    a.IDCATEGORIAAT,
                    ca.NOMBRECATAT,
                    a.OBJETIVOAT,
                    a.ACCIONAT,
                    a.DESCRAVANCAT,
                    a.ACUERDOSAT
                FROM asistenciastecnicas a 
                LEFT OUTER JOIN institucion i ON a.IDINSTITUCIONAT = i.IDINSTITUCION
                LEFT OUTER JOIN departamento d ON a.IDDEPARTAMENTOINSTAT = d.IDDEPARTAMENTO
                LEFT OUTER JOIN municipio m ON a.IDMUNICIPIOINSTAT = m.IDMUNICIPIO
                LEFT OUTER JOIN corredor c ON a.IDCORREDORINSTAT = c.IDCORREDOR
                LEFT OUTER JOIN comunidad cm ON a.IDCOMUNIDADAT = cm.IDCOMUNIDAD
                LEFT OUTER JOIN usuarios u ON a.IDUSUARIOAT = u.IDUSUARIOSUSU
                LEFT OUTER JOIN arearesponsable ar ON a.IDAREARESAT = ar.IDAREARES
                LEFT OUTER JOIN tipoasistenciatecnica t ON a.IDTIPOAT = t.IDTIPOAT
                LEFT OUTER JOIN categoriaasistenciatecnica ca ON a.IDCATEGORIAAT = ca.IDCATEGORIAAT
                WHERE a.IDASISTEC='$id'";
    $resultado = ejecutarConsulta($consulta);
    $at = $resultado->fetch_assoc();

    //Consulta a participantes por asistencia técnica
    $consulta_tpar = "SELECT p.IDPARAT,p.IDASISTEC,p.IDTIPOPAR, p.ASISTENCIA, t.NOMBRETIP, 'Registrado' as TIPO
                FROM participantesasistenciastecnicas p
                INNER JOIN tipoparticipante t
                ON p.IDTIPOPAR = t.IDTIPOPARTICIPANTE
                WHERE p.IDASISTEC = '$id'";
    $resultado_tpar = ejecutarConsulta($consulta_tpar);
    $result = mysqli_num_rows($resultado_tpar);
    
    //Creando variables para calculos y detalles
    $detalleTabla = '';
    $sub_total = 0;
    $total = 0;
    $arrayData = array(); //Array de los datos de la tabla Detalle Temp

    if($result > 0) {
        while ($data = mysqli_fetch_assoc($resultado_tpar)){
            //Calculando datos 
            $precioTotal = round($data['ASISTENCIA'], 4);
            $sub_total = round($sub_total + $precioTotal, 4);
            $total = round($total + $precioTotal, 4);

            //Llenando tr de las tablas en html y php dentro del ajax
            $detalleTabla .= '<tr>
                                <td style="text-align:center;">'.$data['NOMBRETIP'].'</td>
                                <td style="text-align:center;">'.$data['ASISTENCIA'].'</td>
                            </tr>';
        }

        //Calculando todos los datos de la tabla Detalle Temp
        $tl = round($sub_total, 4); 
        $total = round($tl, 4);

        $detalleTotales = '<tr style="background-color: #fffff; color: black;">
                                <td colspan="1" style="text-align:center;"><b>TOTAL</b></td>
                                <td class="bg-info centrar-texto" style="text-align:center; color: black;"><b>'.$total.'</b></td>
                            </tr>';

        $arrayData['detalle'] = $detalleTabla;
        $arrayData['totales'] = $detalleTotales;

        //echo json_encode($arrayData);
    } else {
        echo 'error';
    }
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- codigo para evitar el cache en HTML -->
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">

  <title><?php echo PRO_NOMBRE ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!--Estilos -->
    <style>
    
        .big_title {
            font-family: Segoe UI light;
            font-size: 14px;
            text-align: center;
            font-weight: 200;
            margin-top: 0px;
            margin-bottom: 2px;
        }
    
        .title {
            font-family: Segoe UI light;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 0px;
        }
        .customers {
            font-family: Segoe UI light;
            border-collapse: collapse;
            width: 100%;
            font-size: 11px;
            display: inline-table; 
            border: none; 
            white-space: break-spaces;
            margin-top: 0px;
        }

        .customers td, .customers th {
        border: 1px solid #ddd;
        padding: 8px;
        margin-top: 0px;
        }

        .customers tr:nth-child(even){background-color: #f2f2f2;}

        .customers tr:hover {background-color: #ddd;}

        .customers th {
        /*padding-top: 12px;
        padding-bottom: 12px;*/
        text-align: left;
        /*background-color: #000000;
        color: white;*/
        }

        .nopadding th, td {
            padding: 0px!important;
        }
        
        .tbl table, th, td {
          border: 0px;
        }
        
        .tbl table {
            margin-top: -50px;
        }
        
        .lst_avances ul {
            list-style: none; 
            column-count:2; 
            padding-left:0px;
            margin-top: 0px;
        }
    </style>
</head>

<table class="customers tbl">
  <tr>
    <td><img src="https://eycdesarrollo.fepade.org.sv/EducarConvivir/librerias/images/logosoficiales/LogoUSAID.PNG" width="193.719" height="66.250" class="responsive-image" /></td>
    <td><img src="https://eycdesarrollo.fepade.org.sv/EducarConvivir/librerias/images/logosoficiales/LogotipoEyC.png" width="154.969" height="94.625" class="responsive-image"/></td>
    <td><img src="https://eycdesarrollo.fepade.org.sv/EducarConvivir/librerias/images/logosoficiales/LogoFEPADEfondoblanco.jpg" width="193.719" height="63.688" class="responsive-image"/></td>
  </tr>
</table>

<h1 class="big_title"><b>BITÁCORA DE ASISTENCIA TÉCNICA</b></h1>

<table class="customers">
  <tr>
    <td colspan="1"><b> Fecha: </b> <br> <?php echo $at['FECHAAT'];?></td>
    <td colspan="3"><b> Código CE: </b> <br> <?php echo $at['CODIGOINSTAT'];?></td>
    <td colspan="2"><b> Centro Educativo: </b> <br> <?php echo $at['NOMBREINS'];?></td>
  </tr>
  <tr>
    <td colspan="1"><b> Departamento: </b> <br> <?php echo $at['NOMBREDEP'];?></td>
    <td colspan="3"><b> Municipio: </b> <br> <?php echo $at['NOMBREMUN'];?></td>
    <td colspan="2"><b> Corredor: </b> <br> <?php echo $at['NOMBRECOR'];?></td>
  </tr>
  <tr>
    <td colspan="1"><b> Director/a: </b> <br> <?php echo $at['NOMBREDIRINSTAT'];?></td>
    <td colspan="3"><b> Teléfono Institución: </b> <br> <?php echo $at['TELEFONOINSTAT'];?></td>
    <td colspan="2"><b> Comunidad: </b> <br> <?php echo $at['NOMBRECOMUNIDAD'];?></td>
  </tr>
  <tr>
    <td colspan="1"><b> Técnico Responsable: </b> <br> <?php echo $at['NOMBREUSU'];?></td>
    <td colspan="3"><b> Área Responsable: </b> <br> <?php echo $at['NOMBREAREARES'];?></td>
    <td colspan="1"><b> Tipo Asistencia Técnica: </b> <br> <?php echo $at['NOMBRETAT'];?></td>
    <td colspan="1"><b> Categoría de AT: </b> <br> <?php echo $at['NOMBRECATAT'];?></td>
  </tr>
</table>
<br>
<table class="customers">
    <thead>
        <th style="text-align:center;">Tipo de Participante</th>
        <th style="text-align:center;">Asistencia</th>
    </thead>
    <tbody id="detalle_tipopar">
        <?php if(isset($arrayData['detalle'])) { 
            echo $arrayData['detalle']; 
        } else { ?> 
        <?php }  ?>
    </tbody>
    <tfoot id="detalle_totales">
    <?php if(isset($arrayData['totales'])) { 
            echo $arrayData['totales']; 
        } else { ?> 
            <tr style="background-color: #ffffff; color: black;">
                <td colspan="1" style="text-align:center;"><b>TOTAL</b></td>
                <td class="bg-info centrar-texto" style="text-align:center; color: black;"><b>0</b></td>
            </tr> 
        <?php }  ?>
    </tfoot>
</table>

<p class="title">1. Objetivo de la visita:</p>
<p class="customers"><?php echo nl2br($at['OBJETIVOAT']); ?></p>

<p class="title">2. Acción realizada:</p>
<p class="customers"><?php echo nl2br($at['ACCIONAT']); ?></p>

<p class="title">3. Áreas de avances encontrados:</p>
<ul class="customers lst_avances" id="avances" name="avances">
<?php 
    //Consulta de Avances
    $consulta_av = "SELECT * FROM avanceporat ap LEFT OUTER JOIN avancesasistenciatecnica ast ON ast.IDAVANCEAT=ap.IDAVANCEAT WHERE IDASISTEC='$id'";
    $resultado_av = ejecutarConsulta($consulta_av);

    $arrayAvances = array(); //Array de los datos de la tabla Detalle Temp

    //Mostramos la lista de avances en la vista y si están marcados
    while($mods = $resultado_av->fetch_object()) {
        echo "<li>".$mods->NOMBREAVANCEAT."</li>";
    }
?>
</ul>

<p class="title">4. Descripción de los avances encontrados:</p>
<p class="customers"><?php echo nl2br($at['DESCRAVANCAT']); ?></p>

<p class="title">5. Acuerdos tomados:</p>
<p class="customers"><?php echo nl2br($at['ACUERDOSAT']); ?></p>