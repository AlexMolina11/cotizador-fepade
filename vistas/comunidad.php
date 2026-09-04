<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['011COM']) && $_SESSION['011COM']==1){
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
        <li class="active">Comunidad</li>
      </ol>
    </section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h1 class="box-title">Comunidad 
							<?php
								if(isset($_SESSION['001AGR11']) && $_SESSION['001AGR11']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar Comunidad"><i class="fa fa-plus-circle"></i>  Agregar</button></h1>
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
							<th>Distrito</th>
							<th>Municipio</th>
							<th>Departamento</th>
							<th>Estado</th>
							<th>Opciones</th>
						</thead>
						<tbody></tbody>
						<tfoot>
							<th>Nombre</th>
							<th>Distrito</th>
							<th>Municipio</th>
							<th>Departamento</th>
							<th>Estado</th>
							<th>Opciones</th>
						</tfoot>
						</table>
					</div>
					<div class="panel-body" style="height: 400px;" id="formularioregistros">
					    <span id="leyenda">(*) Campos obligatorios</span><br>
							<form name="formulario" id="formulario" method="POST">
								<div class="form-group col-lg-9 col-md-12 col-sm-12 col-xs-12">
									<label>Nombre Comunidad(*):</label>
									<input type="hidden" id="idcomunidad" name="idcomunidad">
									<input type="text" class="form-control" id="nombrecomunidad" name="nombrecomunidad" maxlength="150" placeholder="Nombre Comunidad" required>
								</div>
								<!-- Se agregan inputs municipio y departamento -->
								<div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
									<label>Departamento(*):</label>
									<select id="iddepartamento" name="iddepartamento" class="form-control selectpicker" data-live-search="true" required>
									</select>
								</div>
								<div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
									<label>Municipio(*):</label>
									<select id="idnvomunicipio" name="idnvomunicipio" class="form-control selectpicker" data-live-search="true" required>
									</select>
								</div>
								<div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
									<label>Distrito(*):</label>
									<select id="idmunicipio" name="idmunicipio" class="form-control selectpicker" data-live-search="true" required>
									</select>
								</div>
								<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Latitud:</label>
                                    <input type="text" class="form-control" id="latitud" name="latitud" maxlength="10" 
                                           placeholder="Ingrese la latitud." pattern="^-?\d+(\.\d+)?$" 
                                           title="Ingrese un número válido con un punto decimal opcional, y hasta 10 caracteres en total. El punto no puede estar al final.">
                                </div>
                                <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label>Longitud:</label>
                                    <input type="text" class="form-control" id="longitud" name="longitud" maxlength="10" 
                                           placeholder="Ingrese la longitud." pattern="^-?\d+(\.\d+)?$" 
                                           title="Ingrese un número válido con un punto decimal opcional, y hasta 10 caracteres en total. El punto no puede estar al final.">
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
<script type="text/javascript" src="scripts/comunidad.js" ></script>
<?php
}
ob_end_flush();
?>