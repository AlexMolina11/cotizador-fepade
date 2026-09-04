<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['020RES']) && $_SESSION['020RES']==1){
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
        <li class="active">Facilitadores</li>
      </ol>
    </section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h1 class="box-title">Facilitadores
							<?php
								if(isset($_SESSION['039AGR20']) && $_SESSION['039AGR20']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar Facilitadores"><i class="fa fa-plus-circle"></i>  Agregar</button>
							<?php } ?>
						</h1>
                        <div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->
					<!-- centro -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							<th>Nombre</th>
							<th>Org. ejecutora</th> <!--se agregó org. Ejecutora-->
							<th>Estado</th>
							<th>Opciones</th>
						</thead>
						<tbody></tbody>
						<tfoot> 
							<th>Nombre</th>
							<th>Org. ejecutora</th> <!--se agregó org. Ejecutora-->
							<th>Estado</th>
							<th>Opciones</th>
						</tfoot>
						</table>
					</div>
					<div class="panel-body" style="height: 400px;" id="formularioregistros">
					<span id="leyenda">(*) Campos obligatorios</span><br>
						<form name="formulario" id="formulario" method="POST">
							<div class="form-group col-lg-8 col-md-8 col-sm-12 col-xs-12">
								<label>Nombre Facilitador/a(*):</label>
								<input type="hidden" id="codigores" name="codigores">
								<input type="text" class="form-control" id="nombreres" name="nombreres" maxlength="255" placeholder="Nombre Facilitador/a" title="Ingrese el nombre" required>
							</div>
							<!-- Se agregó Organización Ejecutora -->
							<div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label>Organización Ejecutora(*):</label>
                                <select id="idorgejecutora" name="idorgejecutora" class="form-control selectpicker" data-live-search="true" required>
                                </select>
                            </div>
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<button class="btn btn-primary" type="submit" id="btnGuardar" name="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
								<button class="btn btn-danger" type="button" onclick="cancelarform()" id="btnCancelar" name="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
							</div>
						</form>
                                            
					</div>
					<!-- Fin centro -->
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
<script type="text/javascript" src="scripts/responsables.js" ></script>
<?php
}
ob_end_flush();
?>