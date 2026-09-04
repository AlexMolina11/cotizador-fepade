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
        <li class="active">Reporte de Alianzas</li>
      </ol>
    </section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
                          <h1 class="box-title">Reporte de Alianzas</h1>
						<div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->				
					<div class="panel-body table-reponsive"  id="listadoregistros">
					       <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<div class="input-group date">
							<div class="input-group-addon">
                                <label><strong class="obligado">*</strong>Fecha Inicio:</label> 
                                <i class="fa fa-calendar"></i>
                            </div>                                
                                <input type="date" class="form-control pull-right" value="<?php echo date("Y-m-d");?>" id="fechainicio" name="fechainicio" />
                            </div>
                            </div>
						     <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<div class="input-group date">
							<div class="input-group-addon">
                                <label><strong class="obligado">*</strong>Fecha Fin:</label>
                                <i class="fa fa-calendar"></i>
                            </div>                                
                                <input type="date" class="form-control pull-right" value="<?php echo date("Y-m-d");?>" id="fechafin" name="fechafin" />
                            </div>
                            </div>
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <label>Tipo de Organizacion:</label>                                         
									<select id="tipoorganizacion" name="tipoorganizacion" class="form-control selectpicker" data-live-search="true" required>
								    </select>                                           
                             </div>													
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-primary" onclick="listar()"><i class="fa fa-save"></i> Generar</button>
                                <button class="btn btn-danger" type="button" onclick="limpiar()" id="btnCancelar" name="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Limpiar</button>
                            </div>
						<table id="tblreporte" class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							  <td>Codigo</td>
						      <td>Nombre Alianza</td>
							  <td>Corredor</td>
							  <td>Trimestre</td>
							  <td>Estado</td>
							  <td>Responsable</td>
							  <td>Organizaciones</td>
							  <td>Inversion Propuesta</td>
							  <td>Especie</td>
							  <td>Efectivo</td>
							  <td>Inversión Total</td>							
						</thead>
						<tbody></tbody>						
						</table>
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
<script type="text/javascript" src="scripts/repAlianzas.js"></script>
<?php
}
ob_end_flush();
?>