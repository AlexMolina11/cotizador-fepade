<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['012COR']) && $_SESSION['012COR']==1){
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
        <li class="active">Corredores</li>
      </ol>
    </section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h1 class="box-title">Corredor 
							<?php
								if(isset($_SESSION['006AGR12']) && $_SESSION['006AGR12']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button>
							<?php } ?>
						</h1>
                        <div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->
					<!-- centro -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							<th>Plan</th>
							<th>Nombre</th>
							<th>Observación</th>
							<th>Alianza</th>
							<th>Departamento</th>
							<!--<th>Sector</th>--> <!-- Se agregó sector -->
							<th>Estado</th> <!-- Se agregó estado -->
							<th>Opciones</th>
						</thead>
						<tbody></tbody>
						<tfoot>
							<th>Plan</th>
							<th>Nombre</th>
							<th>Observación</th>
							<th>Alianza</th>
							<th>Departamento</th>
							<!--<th>Sector</th>--> <!-- Se agregó sector -->
							<th>Estado</th> <!-- Se agregó estado -->
							<th>Opciones</th>
						</tfoot>
						</table>
					</div>
					<div class="panel-body" id="formularioregistros">
					<span id="leyenda">(*) Campos obligatorios</span><br>
                        <form name="formulario" id="formulario" method="POST">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">							   
                                <label>Nombre Corredor(*):</label>
                                <input type="text" class="form-control" id="nombrecor" name="nombrecor" maxlength="255" placeholder="Nombre Corredor" required>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label>Observación:</label>
                                <input type="text" class="form-control" id="descripcioncor" name="descripcioncor" maxlength="256" placeholder="Observación">
                            </div>	
							<div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">							    
                                <label>Plan:</label>
								 <input type="hidden" id="idcorredor" name="idcorredor">
                                <select id="idplan" name="idplan" class="form-control selectpicker" data-live-search="true">
								</select>
                            </div>
							<div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
								<label>Departamento(*):</label>
								<select id="iddepartamento" name="iddepartamento" class="form-control selectpicker" data-live-search="true" required>
								</select>
							</div>
							<!--Se agrega el input alianza -->
							<div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                <label>Alianza(*):</label>
                                <select id="idalianza" name="idalianza[]" class="form-control selectpicker" data-live-search="true" multiple required></select>
                            </div>
                            <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                <label>Alianzas Seleccionadas:</label>
                                <div id="alianzasContainer"></div>
                            </div>	
							<!-- Se agregan input Sector -->
							<!--<div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
								<label>Sector:</label>
								<select id="sector" name="sector" class="form-control selectpicker">
									<option value='Ninguno' selected>Ninguno</option>
									<option value='Publico'>Publico</option>
									<option value='Privado'>Privado</option>
								</select>
							</div>-->
							<div class="form-group col-lg-12">
							    <hr style="margin-top: 0px; margin-bottom: 0px; ">
							</div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group col-md-6">
                                    <button class="btn btn-primary" type="submit" id="btnGuardar" name="btnGuardar">
                                        <i class="fa fa-save"></i> Guardar
                                    </button>
                                    <button class="btn btn-danger" type="button" onclick="cancelarform()" id="btnCancelar" name="btnCancelar">
                                        <i class="fa fa-arrow-circle-left"></i> Cancelar
                                    </button>
                                </div>
                                <div class="form-group col-md-3">
                                    
                                </div>
                                <div class="form-group col-md-3">
                                    <!-- Aquí puedes agregar el contenido de tu tarjeta (card) -->
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- Contenido de la tarjeta -->
                                            <p style="text-align: center; background: antiquewhite; padding: 5px;"><i class="fa fa-lg fa-exclamation"></i><b> Recordar seleccionar todas las alianzas.</b> <i class="fa fa-lg fa-exclamation"></i></p>
                                        </div>
                                    </div>
                                </div>
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
<script type="text/javascript" src="../public/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="../public/plugins/jQueryUI/jquery-ui.js"></script>
<script type="text/javascript" src="../public/plugins/jQueryUI/jquery-ui.min.js"></script>
<script type="text/javascript" src="scripts/corredor.js" ></script>
<?php
}
ob_end_flush();
?>