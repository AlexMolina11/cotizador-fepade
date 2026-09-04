<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['022TIP']) && $_SESSION['022TIP']==1){
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
        <li class="active">Tipo Participante</li>
      </ol>
    </section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h1 class="box-title">Tipo Participante
							<?php
								if(isset($_SESSION['049AGR22']) && $_SESSION['049AGR22']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar Tipo Participante"><i class="fa fa-plus-circle"></i>  Agregar</button>
							<?php } ?>
						</h1>
                        <div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->
					<!-- centro -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							<th>No</th>
							<th>Nombre Tipo</th>
							<th>Estado</th> <!-- Se agregó Estado -->
							<th>Opciones</th>
						</thead>
						<tbody></tbody>
						<tfoot>
                            <th>No</th>
							<th>Nombre Tipo</th>
							<th>Estado</th> <!-- Se agregó Estado -->
							<th>Opciones</th>
						</tfoot>
						</table>
					</div>
					<div class="panel-body" style="height: 400px;" id="formularioregistros">
					    <span id="leyenda">(*) Campos obligatorios</span><br>
						<form name="formulario" id="formulario" method="POST">
							<div class="form-group col-lg-8 col-md-8 col-sm-12 col-xs-12">
								<label>Nombre Tipo(*):</label>
								<input type="hidden" id="idtipoparticipante" name="idtipoparticipante">
								<input type="text" class="form-control" id="nombretip"  name="nombretip" maxlength="50" placeholder="Nombre Tipo" required>
							</div>
							<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
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
<script type="text/javascript" src="scripts/tipoparticipante.js" ></script>
<?php
}
ob_end_flush();
?>