<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['005PAR']) && $_SESSION['005PAR']==1){
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
					<h1 class="welcome-title">Bienvenido al área de Participantes</h1>    
					<p class="welcome-subtitle">Aquí puedes registrar todos los datos del participante.</p>   
				</div>
					
				<!-- centro -->
				<div class="panel-primary" >					
					<ul class="list-group">
						<li class="list-group-item" style="font-size: 20px;"><a href="participantes.php"><i class="fa fa-user-plus"></i> Participante</a> <i class="fa fa-long-arrow-right"></i> Registro de participantes al proyecto.</li>
						<li class="list-group-item" style="font-size: 20px;"><a href="grupoasignado.php"><i class="fa fa-users"></i> Grupo Asignado</a> <i class="fa fa-long-arrow-right"></i> Asociar participantes a un grupo.</li>
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