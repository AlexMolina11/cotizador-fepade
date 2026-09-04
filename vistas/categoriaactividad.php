<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['015CAT']) && $_SESSION['015CAT']==1){
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
        <li class="active">Categoría Actividad</li>
      </ol>
    </section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h1 class="box-title">Categoria Actividad 
							<?php
								if(isset($_SESSION['020AGR15']) && $_SESSION['020AGR15']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar Categoria Actividad"><i class="fa fa-plus-circle"></i>  Agregar</button>
							<?php } ?>
						</h1>
                        <div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->
					<!-- centro -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
						<thead>
						    <th>Tipo Actividad</th>
							<th>Nombre Categoria</th>
							<th>Estado</th> <!--se agregó estado -->
							<th>Opciones</th>
						</thead>
						<tbody></tbody>
						<tfoot>
						    <th>Tipo Actividad</th>
							<th>Nombre Categoria</th>
							<th>Estado</th> <!--se agregó estado -->
							<th>Opciones</th>
						</tfoot>
						</table>
					</div>
					<div class="panel-body" style="height: 400px;" id="formularioregistros">
					    <span id="leyenda">(*) Campos obligatorios</span><br>
                        <form name="formulario" id="formulario" method="POST">
						    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
								    <label><strong class="obligado"></strong>Tipo de Actividad(*):</label>                                       
									<input type="hidden" id="idcategoriaactividad" name="idcategoriaactividad">
									<select id="idtipoactividad" name="idtipoactividad" class="form-control selectpicker" data-live-search="true" required>
								    </select>								
                             </div>
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label><strong class="obligado"></strong>Categoría(*):</label>
                                <input type="text" class="form-control" id="nombrecac" name="nombrecac" maxlength="40" placeholder="Categoría Actividad" required>
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
<script type="text/javascript" src="scripts/categoriaactividad.js" ></script>
<?php
}
ob_end_flush();
?>