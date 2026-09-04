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
	 
?>
<!--CONTENIDO-->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content-header">
      <h1>        
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
        <li><a href="#">Reportes</a></li>
        <li class="active">Reporte General</li>
      </ol>
    </section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
                          <h1 class="box-title">Reporte General</h1>
						<div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->
					
					   <div class="panel-body" style="height: 200px;" id="formularioregistros">
						   <form id="form1" name="form1" method="post" action="conexionReporte.php">
								<div style="border" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label>Fecha Inicio:</label>
								   <input name="fechaini" type="date" id="fechaini" value="<?php echo $fechaini; ?>" />
								   
								   <label>Fecha Fin:</label>
									<input name="fechafin" type="date" id="fechafin" value="<?php echo $fechafin; ?>" />
								</div>
								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								   <button class="btn btn-primary" type="submit" id="button" name="button" value="Buscar"><i class="fa fa-reload"></i> Generar</button>
								   <button class="btn btn-danger" type="reset"  id="btnCancelar" name="btnCancelar"><i class="fa fa-refresh"></i> Limpiar</button>
									<input name="desplegar" type="hidden" id="desplegar" value="1" />
								</div>
							</form>
                        </div>	                    					  
					
				</div><!-- /.box -->
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
?>
<script type="text/javascript" src="../librerias/bootstrap-select/js/bootstrap-select.js"></script>
<script type="text/javascript" src="../librerias/plugins/jQueryUI/jquery-ui.js"></script>
<?php
}
ob_end_flush();
?>