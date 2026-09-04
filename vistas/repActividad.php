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
        <li class="active">Reporte de Actividades</li>
      </ol>
    </section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
                          <h1 class="box-title">Reporte de Actividades</h1>
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
                                <input type="date" class="form-control pull-right" id="fechainicio" name="fechainicio" value="<?php echo date("Y-m-d");?>"/>
                            </div>
                            </div>
						     <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<div class="input-group date">
							<div class="input-group-addon">
                                <label><strong class="obligado">*</strong>Fecha Fin:</label>
                                <i class="fa fa-calendar"></i>
                            </div>                                
                                <input type="date" class="form-control pull-right" id="fechafin" name="fechafin" value="<?php echo date("Y-m-d");?>"/>
                            </div>
                            </div>
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <label>Tipo Actividad:</label>                                         
									<select id="tipoact" name="tipoact" class="form-control selectpicker" data-live-search="true" required>
								    </select>                                           
                             </div>
							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <label>Corredor:</label>
									<select id="corredoract" name="corredoract" class="form-control selectpicker" data-live-search="true" required>
								    </select>                                     
                             </div>							
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-primary" onclick="listar()"><i class="fa fa-save"></i> Generar</button>
                                <button class="btn btn-danger" type="button" onclick="limpiar()" id="btnCancelar" name="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Limpiar</button>
                            </div>
						<table id="tblreporte" class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							<th>Nombre</th>
							<th>Lugar</th>
							<th>Fecha</th>
							<th>Jornadas</th>
							<th>Horas</th>
							<th>Masculino</th>
							<th>Femenino</th>
							<th>Total</th>                           
						</thead>
						<tbody></tbody>
						<tfoot>
							<th>Nombre</th>
							<th>Lugar</th>
							<th>Fecha</th>
							<th>Jornadas</th>
							<th>Horas</th>
							<th>Masculino</th>
							<th>Femenino</th>
							<th>Total</th>  
						</tfoot>
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
<script type="text/javascript" src="scripts/repActividad.js"></script>
<?php
}
ob_end_flush();
?>