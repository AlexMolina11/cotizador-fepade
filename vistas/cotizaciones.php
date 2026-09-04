<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['043COT']) && $_SESSION['043COT']==1){
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
					<h1 class="welcome-title">Bienvenido al área de Cotizaciones</h1>    
					<p class="welcome-subtitle">Aquí están todas las opciones que invulucran a la gestión de las cotizaciones.</p>   
				</div>

				<!-- centro -->
				<div class="panel-primary" >
					<ul class="list-group">
						<?php if($_SESSION['044COT']==1){ ?>
							<li class="list-group-item" style="font-size: 20px;"><a href="cotizacion.php"><i class="fa fa-id-card "></i> Cotización</a> <i class="fa fa-long-arrow-right"></i> Registro de cotizaciones para clientes.</li>
						<?php } else { } ?>
						<?php if($_SESSION['045DET']==1){ ?>	
							<li class="list-group-item" style="font-size: 20px;"><a href="cotizacion_detalle.php"><i class="fa fa-book"></i> Detalle de cotizaciones</a> <i class="fa fa-long-arrow-right"></i> Registro del detalle de las cotizaciones registradas.</li>
						<?php } else { } ?>
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