<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['029JOR']) && $_SESSION['029JOR']==1){
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
        <li><a href="#">Actividades</a></li>
        <li class="active">Jornada</li>
      </ol>
    </section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
                        <h1 class="box-title">Jornadas 
							<?php
								if(isset($_SESSION['078AGR29']) && $_SESSION['078AGR29']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>  Agregar</button>
							<?php } ?>
						</h1>
						<div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->
					<!-- centro -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
						<div class="row">
							<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<label>Fecha Inicio:</label> 
								<i class="fa fa-calendar"></i>
								<!--<input type="date" class="form-control pull-right" id="fechainicio" name="fechainicio" value="<?php //echo "2000-01-01";?>"/>-->
								<input type="date" class="form-control pull-right" id="fechainicio" name="fechainicio" min='1890-01-01' max='9999-12-31' value=""/>
							</div>
							<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<label>Fecha Fin:</label>
								<i class="fa fa-calendar"></i>
                                <input type="date" class="form-control pull-right" id="fechafin" name="fechafin" min='1890-01-01' max='9999-12-31' value=""/>									
							</div>
							<div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">
								<label>Acciones:</label><br>
								<div class="btn-group">
									<button class="btn btn-primary" onclick="listar()"><i class="fa fa-search"></i> Buscar</button>
									<button class="btn btn-danger" type="button" onclick="limpiar()" id="btnCancelar" name="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Limpiar</button>
								</div>
							</div>
						</div>
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							<th>Id Act.</th> <!--Se agregó el id -->
							<th>Actividad</th>
							<th>Id Jor.</th>
							<th>Jornada</th>
							<th>Fecha</th>
							<th>Hora Inicio</th>
							<th>Hora Fin</th>
							<th>Usuario</th>
							<th>Estado</th>
							<th>Opciones</th>
						</thead>
						<tbody></tbody>
						<tfoot>
							<th>Id Act.</th> <!--Se agregó el id -->
							<th>Actividad</th>
							<th>Id Jor</th>
							<th>Jornada</th>
							<th>Fecha</th>
							<th>Hora Inicio</th>
							<th>Hora Fin</th>
							<th>Usuario</th>
							<th>Estado</th>
							<th>Opciones</th>
						</tfoot>
						</table>
					</div>
					<div class="panel-body" style="height: auto;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label><strong class="obligado">*</strong>Actividad:</label>
                                <input type="hidden" id="idjornadas" name="idjornadas">
								<select id="idactividades" name="idactividades" class="form-control selectpicker" data-live-search="true" required>
								</select>
								<input type="hidden" id="fechaactividad" name="fechaactividad"> 
                            </div>
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label><strong class="obligado">*</strong>Nombre de jornada según nomenclatura: </label>
                                <span style="display: inline-flex; flex-direction: row; align-items: center; font-size: 15px;">
                                    Ejemplo: 
                                    <p style="color:red; margin: 0; margin-right: 3px; margin-left: 3px;"><b> J2 </b></p> 
                                    <p style="color:blue; margin: 0; margin-right: 3px;"><b> 01 </b></p> 
                                    <p style="color:green; margin: 0; margin-right: 3px;"><b> Reconocimiento de las emociones </b></p> 
                                    <p style="color:purple; margin: 0; margin-right: 3px;"><b> 01-09-2021 </b></p>
                                </span>
                                <input type="text" class="form-control" id="nombrejor" name="nombrejor" maxlength="500" placeholder="Nombre" required>
                            </div>
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Información de la jornada:</label>
								<textarea class="form-control" id="objetivojor" name="objetivojor" cols="20" rows="3" placeholder="Información de la jornada" ></textarea>                                
                            </div>
													
							<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label><strong class="obligado">*</strong>Facilitador:</label>
								<select id="responsablejor" name="responsablejor[]" class="form-control selectpicker" title="Seleccione facilitador" multiple required>
								</select>
                            </div>

							<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label><strong class="obligado">*</strong>Lugar en el que se desarrolló la jornada:</label>
                                <input type="text" class="form-control" id="lugarjor" name="lugarjor" placeholder="lugar de la jornada" required/>
                            </div>
                           
							<div class="form-group col-lg-3 col-md-3 col-sm-4 col-xs-12">							
                                <label><strong class="obligado">*</strong>Fecha: <i class="fa fa-calendar"></i> </label>                                                                                       
                                <input type="text" class="form-control" id="fechajor" name="fechajor" readonly/>
                            </div>                          
                            <div  class="form-group col-lg-3 col-md-3 col-sm-4 col-xs-6">
			                  <label><strong class="obligado">*</strong>Hora Inicio:  <i class="fa fa-clock-o"></i></label>
							   <input type="time" class="form-control" name="horainijor" id="horainijor" required>
							   <!--<input id="horainijor" name="horainijor" type="text" class="form-control time" data-timepicker required/>-->
			                 </div>
			                <div  class="form-group col-lg-3 col-md-3 col-sm-4 col-xs-6">
			                  <label><strong class="obligado">*</strong>Hora Fin: <i class="fa fa-clock-o"></i></label>
							  <input type="time" class="form-control" name="horafinjor" id="horafinjor" required>
							  <!--<input id="horafinjor" name="horafinjor" type="text" class="form-control time" data-timepicker required/>-->
						    </div>
							<div  class="form-group col-lg-3 col-md-3 col-sm-4 col-xs-6">
			                  <label>Duración: <i class="fa fa-clock-o"></i></label>
							  <input type="time" class="form-control" name="duracion" id="duracion" readonly>
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
<script type="text/javascript" src="../librerias/bootstrap-select/js/bootstrap-select.js"></script>
<script type="text/javascript" src="../librerias/plugins/jQueryUI/jquery-ui.js"></script>
<script type="text/javascript" src="scripts/jornadas.js" ></script>
<?php
}
ob_end_flush();
?>

