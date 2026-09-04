<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['041DAS']) && $_SESSION['041DAS']==1){
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
					<h1 class="welcome-title">Bienvenido al menu de dashboards y reportes</h1>    
					<p class="welcome-subtitle">Aquí están todas las opciones que invulucran a la reportería del sistema.</p>   
				</div>

				<!-- centro -->
				<div class="panel-primary" >
					<ul class="list-group">
						<?php if($_SESSION['046DAS']==1){ ?>
							<li class="list-group-item" style="font-size: 20px;"><a href="cot_dashboard.php"><i class="fa fa-id-card "></i> Dashboard General</a> <i class="fa fa-long-arrow-right"></i> Visualizar el dashboard general de cotizaciones.</li>
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