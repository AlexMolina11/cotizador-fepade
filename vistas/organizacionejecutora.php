<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['024ORG']) && $_SESSION['024ORG']==1){
?>
<!--CONTENIDO-->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
        <li><a href="#">Catálogos</a></li>
        <li class="active">Organizaciones Ejecutoras</li>
      </ol>
    </section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
				    
				    <!-- box-header-->
					<div class="box-header with-border">
						<h1 class="box-title">Organizaciones Ejecutoras</h1>
                        <div class="box-tools pull-right">
							<?php
								if(isset($_SESSION['055AGR24']) && $_SESSION['055AGR24']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar Organización Ejecutora"><i class="fa fa-plus-circle"></i>  Agregar</button>
							<?php } ?>
                        </div>
					</div><!-- /.Box.header -->
					
					<!-- Centro / Tabla -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    						<thead>
    							<th>Nombre</th>
    							<th>Estado</th>
    							<th>Opciones</th>
    						</thead>
    						<tbody></tbody>
    						<tfoot>
    							<th>Nombre</th>
    							<th>Estado</th>
    							<th>Opciones</th>
    						</tfoot>
						</table>
					</div><!-- /.centro/Tabla -->
					
					<!-- Formulario -->
					<div class="panel-body" style="height: 400px;" id="formularioregistros">
					    
					    <span id="leyenda"><b>Indicaciones: </b>(*) Campos obligatorios</span><hr>
					    
						<form name="formulario" id="formulario" method="POST">
						    
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label>Nombre Organización(*):</label>
								<input type="hidden" id="codigororgeje" name="codigororgeje">
								<input type="text" class="form-control" id="nombreorgeje" name="nombreorgeje" maxlength="255" placeholder="Nombre Organización Ejecutora" title="Ingrese el nombre" required>
							</div>
							
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<button class="btn btn-primary" type="submit" id="btnGuardar" name="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
								<button class="btn btn-danger" type="button" onclick="cancelarform()" id="btnCancelar" name="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
							</div>
						</form>
                                            
					</div><!-- /. Formulario -->
				
				</div><!-- /.box -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	</section><!-- /. Main content -->

</div><!-- /.content-wrapper -->
<!--Fin-Contenido-->
<?php
}else{
    require 'noacceso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/organizacionejecutora.js" ></script>
<?php
}
ob_end_flush();
?>