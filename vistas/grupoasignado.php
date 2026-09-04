<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['027ASI']) && $_SESSION['027ASI']==1){
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
        <li><a href="#">Participantes</a></li>
        <li class="active">Asignar a Grupo</li>
      </ol>
    </section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h1 class="box-title">Participante 
							<?php
								if(isset($_SESSION['071AGR27']) && $_SESSION['071AGR27']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Asignar a Grupo"><i class="fa fa-plus-circle"></i> Agregar</button>
							<?php } ?>
						</h1>
                        <div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->
					<!-- centro -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							<th>Grupo</th>
							<th>Nombre</th>
							<th>Edad</th>
							<th>Sexo</th>
							<th>Municipio</th>
							<th>Opciones</th>
                           
						</thead>
						<tbody></tbody>
						<tfoot>
							<th>Grupo</th>
							<th>Nombre</th>
							<th>Edad</th>
							<th>Sexo</th>
							<th>Municipio</th>
                            <th>Opciones</th>
						</tfoot>
						</table>
					</div>
					<div class="panel-body" style="height: 800px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
						     <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">							 
                                    <label><strong class="obligado">*</strong>Grupo:</label> 																	
									<select id="idgrupo" name="idgrupo" class="form-control selectpicker" data-live-search="true" required>
								    </select>                             
                             </div> 
							<div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
								    <label>Tipo Participante:</label>                                       
									<select id="tipoparticipante" name="tipoparticipante" class="form-control selectpicker" data-live-search="true">
								    </select>								
                             </div> 
							<div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
								    <label><strong class="obligado"></strong>Municipio:</label>                                       
									<select id="municipioact" name="municipioact" class="form-control selectpicker" data-live-search="true" required>
								    </select>								
                             </div>							 
							 <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								    <label><strong class="obligado">*</strong>Participante:</label>                                       
									<select multiple id="participante" name="participante[]" class="form-control selectpicker" data-live-search="true" required>
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
<script type="text/javascript" src="scripts/grupoasignado.js"></script>
<?php
}
ob_end_flush();
?>