<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['032ROL']) && $_SESSION['032ROL']==1){
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
        <li><a href="#">Usuarios</a></li>
        <li class="active">Usuarios</li>
      </ol>
    </section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h1 class="box-title">Roles 
							<?php
								if(isset($_SESSION['088AGR32']) && $_SESSION['088AGR32']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar Roles"> <i class="fa fa-plus-circle"></i> Agregar</button></h1>
							<?php } ?>
						</h1>
                        <div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->
					<!-- centro -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							<th>Rol</th>
                            <th>Modulos</th>
							<th>Estado</th>
							<th>Opciones</th>
                        </thead>
						<tbody></tbody>
						<tfoot>
							<th>Rol</th>
                            <th>Modulos</th>
							<th>Estado</th>
							<th>Opciones</th>
                        </tfoot>
						</table>
					</div>
					<div class="panel-body"  id="formularioregistros">
						<form name="formulario" id="formulario" method="POST">
							<div class="row panel-permisos">
								<div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label style="font-size:20px; font-weight: 700px;">Nombre del Rol(*):</label>
										<input type="text" class="form-control" id="nombrerol" name="nombrerol"  placeholder="Rol" maxlength="255" required>
										<input type="hidden" id="idrol" name="idrol">
										<hr style="margin-bottom: 10px;">
									</div>
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label for="permiso" style="font-size:20px; font-weight: 700px;">Tipo de Acceso <span class="required">*</span></label>
										<select id="permiso" name="permiso" class="form-control selectpicker" data-live-search="true" required>
											<option value="">-- Seleccione Tipo de Acceso --</option>
											<option value="0">1 - Acceso Datos Usuario</option>
											<option value="2">2 - Acceso Datos Organización</option>
											<option value="1">3 - Acceso Datos Total</option>
										</select>
										<br>
										<div class="panel-permisos mt-3" style="padding: 15px; color:black;">
											<p><strong>Tenemos tipos de acceso:</strong></p>
											<ol style="margin-left:-20px;">
												<li><b class="highlight" style="color: #097245">Acceso Datos Usuario:</b> permite acceder solo a los registros propios.</li>
												<li><b class="highlight" style="color: #097245">Acceso Datos Organización:</b> permite acceder a todos los registros creados por usuarios de una misma organización.</li>
												<li><b class="highlight" style="color: #097245">Acceso Datos Total:</b> permite acceder a todos los registros creados por cualquier usuario y organización.</li>
											</ol>
										</div>
										<hr class="divider">
									</div>
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label style="font-size:20px; font-weight: 700px;">Área Responsable (*):</label>
										<select id="permisoareares" name="permisoareares" class="form-control selectpicker" data-live-search="true" required>
										</select>
										<hr style="margin-bottom: 10px;margin-top: 0px;"> 
									</div>
								</div>
								<div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12" style="border-left-style: double; border-color: darkred;">
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label style="font-size:20px; font-weight: 700px;">Permisos</label>
										<p><i>(Seleccione los módulos y submódulos que al rol se le asignen permisos.)</i></p>
										<hr style="margin-bottom: 10px;margin-top: 0px;"> 
										<ul style="list-style: none; display: inline;" id="acceso" name="acceso"></ul>
									</div>
								</div>
								<div class="form-group col-lg-4 col-md-12 col-sm-12 col-xs-12" style="border-left-style: double; border-color: darkred;">
									<label style="font-size:20px; font-weight: 700px;">Acciones</label>
									<p><i>(Seleccione acciones que el rol necesite de cada módulo y submódulo.)</i></p>
									<hr style="margin-bottom: 10px;margin-top: 0px;"> 
									<ul style="list-style: none; display: inline;" id="accesoacciones" name="accesoacciones"></ul>
								</div>
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
<script type="text/javascript" src="scripts/rol.js" ></script>
<?php
}
ob_end_flush();
?>