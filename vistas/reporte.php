<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

//Pendiende de crear el submodulo - Fase 2
//Por mientras el límite será por Modulo General
if($_SESSION['009REP']==1){
?>
<!--CONTENIDO-->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<br>
				<!-- /.box-header-->
				<div class="panel-primary" style="margin-bottom: 10px;">
					<h1 class="welcome-title">Bienvenido al área de Reportes</h1>    
					<p class="welcome-subtitle">Aquí están todos los reportes que el sistema brinda.</p>   
				</div>

				<!-- centro -->
				<div class="panel-primary" >
					<ul class="list-group">
						<li class="list-group-item" style="font-size: 20px;"><a href="repActividad.php"><i class="fa fa-file-text-o"></i> Reporte de Actividades <i class="fa fa-long-arrow-right"></i></a> Reporte de Actividades realizadas</li>
						<li class="list-group-item" style="font-size: 20px;"><a href="repAlianzas.php"><i class="fa fa-handshake-o"></i> Reporte de Alianzas</a> <i class="fa fa-long-arrow-right"></i> Reporte de las Alianzas realizadas</li>
						<li class="list-group-item" style="font-size: 20px;"><a href="repEntregas.php"><i class="fa fa-truck"></i> Reporte de Entregas Realizadas</a> <i class="fa fa-long-arrow-right"></i> Reporte de las Entregas de material hechos a centros educativos</li>                           
						<li class="list-group-item" style="font-size: 20px;"><a href="repAsistencias.php"><i class="fa fa-address-book"></i> Reporte de Asistencias</a> <i class="fa fa-long-arrow-right"></i> Reporte de listados de asistencias</li>
						<li class="list-group-item" style="font-size: 20px;"><a href="repGeneral.php"><i class="fa fa-line-chart"></i> Reporte General</a> <i class="fa fa-long-arrow-right"></i> Reporte de consolidado de un periodo determinado</li>
						<li class="list-group-item" style="font-size: 20px;"><a href="repVulnerables.php"><i class="fa fa-user-plus"></i> Reporte Vulnerables</a> <i class="fa fa-long-arrow-right"></i> Reporte de participantes vulnerables</li>							
					</ul>
				</div>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</section><!-- /.content -->

</div><!-- /.content-wrapper -->
<!--Fin-Contenido-->
<?php
}else{
    require 'noacceso.php';
}
require 'footer.php';
}
ob_end_flush();
?>