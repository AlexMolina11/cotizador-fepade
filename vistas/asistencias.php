<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['030ASI']) && $_SESSION['030ASI']==1){
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
        <li><a href="#">Asistencias</a></li>
        <li class="active">Asistencia</li>
      </ol>
    </section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
                        <h1 class="box-title">Asistencia 
                            <!--<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>  Nueva Jornada</button>-->
                        </h1>
						<div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->
					<!-- centro -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
                        <div class="form-inline col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                            <label>Actividad:</label>
                            <select id="actividad" name="actividad" class="form-control selectpicker" data-live-search="true"></select>									
						</div>
                        <div class="form-inline col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                            <label>Corredor:</label>
                            <select id="corredor" name="corredor" class="form-control selectpicker" data-live-search="true"></select>									
						</div>
                        <div class="form-group col-lg-5 col-md-4 col-sm-6 col-xs-12">
                            <label>Fecha Inicio:</label> 
                            <i class="fa fa-calendar"></i>
                            <input type="date" class="form-control pull-right" id="fechainicio" name="fechainicio" min='1890-01-01' max='9999-12-31' value=""/>									
                        </div>
                        <div class="form-group col-lg-5 col-md-4 col-sm-6 col-xs-12">
                            <label>Fecha Fin:</label>
                            <i class="fa fa-calendar"></i>
                            <input type="date" class="form-control pull-right" id="fechafin" name="fechafin" min='1890-01-01' max='9999-12-31' value=""/>									
                        </div>
                        <div class="form-group col-lg-2 col-md-4 col-sm-12 col-xs-12">
						    <label>Acciones:</label><br>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-primary" onclick="listar()"  data-toggle="tooltip" data-placement="top" title="Buscar actividad"><i class="fa fa-search"></i> Buscar</button>
                                <button class="btn btn-danger" type="button" onclick="limpiarBusqueda()" data-toggle="tooltip" data-placement="top" title="Limpiar Busqueda"><i class="fa fa-arrow-circle-left"></i> Limpiar</button>
                            </div>
				    	</div>
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
						<thead>							
							<th>Id Act.</th>							
							<th>Actividad</th>
							<th>Id Jor.</th>
							<th>Jornada</th>
                            <th>Descripción</th>
							<th>Fecha</th>
							<th>Hora Inicio</th>
							<th>Hora Fin</th>
                            <th>Asistencia</th>
							<th>Usuario</th> 
							<th>Estado</th>                            
							<th>Opciones</th>
						</thead>
						<tbody></tbody>
						<tfoot>							
							<th>Id Act.</th>							
							<th>Actividad</th>
							<th>Id Jor.</th>
							<th>Jornada</th>
                            <th>Descripción</th>
							<th>Fecha</th>
							<th>Hora Inicio</th>
							<th>Hora Fin</th>
                            <th>Asistencia</th>
							<th>Usuario</th> 
							<th>Estado</th>                            
							<th>Opciones</th>
						</tfoot>
						</table>
					</div>
				      <div class="panel-body" style="height: auto;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4 style="text-align: center;">
                                            Actividad: <b id="nombreact_txt"></b> /
                                            Participantes: <b id="total_par"></b> /
                                            Participantes por primera vez: <b id="primeravez_par"></b>
                                        </h4>
                                        <hr style="margin-bottom: 0px;">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <a data-toggle="modal" href="#agregarParticipantes">
                                            <button type="button" id="btnAgregarParticipantes" class="btn btn-success" style="min-width: -webkit-fill-available; border-radius: 0px;"><span class="fa fa-plus"></span> Agregar Asistencia</button>
                                        </a>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control pull-right" id="search" placeholder="Buscar participante...">
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                            <thead style="background-color:#A9D0F5">
                                                <th>Opciones</th>
                                                <th><input id="nombre" value="nombre" type="hidden"> Nombre Participante</th>									
                                            </thead>                                    
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4>Información de la actividad: </h4>
                                        <hr style="margin-bottom: 0px;">
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4><strong class="obligado">*</strong>Actividad:</h4>
                                        <input type="hidden" id="idjornadas" name="idjornadas">
                                        <select id="idactividades" name="idactividades" class="form-control selectpicker" data-live-search="true" required disabled>
                                        </select>
                                        <input type="hidden" id="fechaactividad" name="fechaactividad"> 
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4><strong class="obligado">*</strong>Nombre de Jornada: </h4> 
                                        <input type="text" class="form-control" id="nombrejor" name="nombrejor"  placeholder="Nombre" required readonly>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4>Objetivo de Jornada: </h4>
                                        <textarea class="form-control" id="objetivojor" name="objetivojor" cols="20" rows="3" placeholder="Objetivo" readonly></textarea>                                
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">							
                                        <h4><strong class="obligado">*</strong><i class="fa fa-calendar"></i> Fecha: <b id="fechajor_txt"></b> </h4>
                                        <input type="text" class="form-control" id="fechajorn" name="fechajorn" style="display:none;" readonly/>
                                    </div>             
                                    <div  class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4><strong class="obligado">*</strong><i class="fa fa-clock-o"></i> Hora Inicio: <b id="horainijor_txt"></b></h4>		            
                                        <!--<input type="time" class="form-control" id="horainijor" name="horainijor" required>-->
                                        <input id="horainijor" name="horainijor" type="text" class="form-control time" style="display:none;" data-timepicker readonly/>
                                    </div>
                                    <div  class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4><strong class="obligado">*</strong><i class="fa fa-clock-o"></i> Hora Fin: <b id="horafinjor_txt"></b></h4>
                                        <!--<input type="time" class="form-control" id="horafinjor" name="horafinjor" onChange="ValidarHoras();" required>-->
                                        <input id="horafinjor" name="horafinjor" type="text" class="form-control time" style="display:none;"  data-timepicker readonly/>								
                                    </div>
                                    <div id="guardar" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-primary" type="submit" id="btnGuardar" name="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                                        <button class="btn btn-danger" type="button" onclick="cancelarform()" id="btnCancelar" name="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
<!-- Modal -->
<div class="modal fade" id="agregarParticipantes" tabindex="-1" role="dialog" aria-labelledby="myMOdalLabel" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog" style="width: 75%;">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Seleccione Participantes</h4>
            </div>
            <div class="modal-body ">
                <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			        <label>Departamento:</label>
					<select id="iddepartamento" name="iddepartamento" class="form-control selectpicker" data-live-search="true" required>
					</select>
                </div>
			    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			        <label>Municipio:</label>
					<select id="idmunicipio" name="idmunicipio" class="form-control selectpicker" data-live-search="true" required>
					</select>
                </div>
                <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
			        <label>Centro Educativo:</label>
					<select id="idcentroeducativo" name="idcentroeducativo" class="form-control selectpicker" data-live-search="true" required>
					</select>
                </div>
				<div class="form-group col-lg-3 col-md-12 col-sm-6 col-xs-12">
					<label>Grupo:</label>
					<select id="idgrupo" name="idgrupo" class="form-control selectpicker" data-live-search="true" required>
					</select>
                </div>				
                <table id="tblparticipantes" class="table table-striped table-bordered table-condensed table-hover table-responsive" style="width: 100%;">
                    <thead>
                        <th>Opciones</th>
                        <th>No</th>
                        <th>Nombre Participante</th>
                        <th>Edad</th>
                        <th>Sexo</th>
                        <th>Centro Educativo</th>
                        <th>Comunidad</th>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <th>Opciones</th>
                        <th>No</th>
                        <th>Nombre Participante</th>
                        <th>Edad</th>
                        <th>Sexo</th>
                        <th>Centro Educativo</th>
                        <th>Comunidad</th>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--Fin Modal-->
<?php
}else{
    require 'noacceso.php';
}
require 'footer.php';
?>

<script type="text/javascript" src="scripts/asistencias.js" ></script>
<script type="text/javascript" src="scripts/consultas_asistencia.js" ></script>
<script type="text/javascript" src="../librerias/bootstrap-select/js/bootstrap-select.js"></script>
<!--<script type="text/javascript" src="../librerias/jQueryUI/jquery-ui.js"></script>-->
<?php
}
ob_end_flush();
?>

