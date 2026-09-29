<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['047INS']) && $_SESSION['047INS']==1){
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
                <h1 class="welcome-title">Bienvenido al área de Insumos</h1>    
                <p class="welcome-subtitle">Aquí puedes acceder a todos los submodulos para la gestión de insumos.</p>   
            </div>
            <!-- centro -->
            <div class="panel-primary" >
						   
                <ul class="list-group">
                    <?php if($_SESSION['048GES']==1){ ?>
                        <li class="list-group-item" style="font-size: 20px;"><a href="gestioninsumos.php"><i class="fa fa-list-alt"></i> Gestion de insumo</a> <i class="fa fa-long-arrow-right"></i> Registro de todos los insumos que requieran las cotizaciones.</li>
                    <?php } else { } ?>
                    <?php if($_SESSION['049TIP']==1){ ?>
                        <li class="list-group-item" style="font-size: 20px;"><a href="tipoinsumo.php"><i class="fa fa-file-text-o"></i> Tipos de insumo</a> <i class="fa fa-long-arrow-right"></i> Registro de todos los tipos de insumos.</li>
                    <?php } else { } ?>
                </ul>       
            </div>
            <!-- Fin centro -->
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