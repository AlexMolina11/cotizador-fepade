<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['026PAR']) && $_SESSION['026PAR']==1){
?>
<!--CONTENIDO-->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
      <h1>        
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
        <li><a href="#">Participantes</a></li>
        <li class="active">Participante</li>
      </ol>
    </section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h1 class="box-title">Participante 
							<?php
								if(isset($_SESSION['066AGR26']) && $_SESSION['066AGR26']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar Participante"><i class="fa fa-plus-circle"></i> Agregar</button>
							<?php } ?>
						</h1>
                  		<div class="box-tools pull-right"></div>
						<div class="box-tools pull-right"></div>
					</div>					
					<!-- /.box-header-->
					<!-- centro -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
						<div class="row">
							<div class="form-group col-lg-2 col-md-4 col-sm-12 col-xs-12">
								<label>Tipo de Participante:</label>
								<select class="form-control select-picker" name="tipopar" id="tipopar"  data-live-search="true" required></select>
							</div>
							<!-- Se agregó Select Departamento -->
							<div class="form-group col-lg-2 col-md-4 col-sm-6 col-xs-6">
								<label>Departamento:</label>
								<select id="departamento" name="departamento" class="form-control selectpicker" data-live-search="true" data-normalize-search="true">
								</select>									
							</div>
							<div class="form-group col-lg-2 col-md-4 col-sm-6 col-xs-6">
								<label>Distrito:</label>
								<select id="municipio" name="municipio" class="form-control selectpicker" data-live-search="true">
								</select>									
							</div>
							<div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
									<label>Centro Educativo:</label>
									<select id="institucion" name="institucion" class="form-control selectpicker" data-live-search="true">
									</select>
							</div>
							<!-- Se agregó Select Corredor -->
							<div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
								<label>Corredor:</label>
								<select id="corredor" name="corredor" class="form-control selectpicker" data-live-search="true">
								</select>
							</div>
						</div>
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<button class="btn btn-primary" onclick="listar()" data-toggle="tooltip" data-placement="top" title="Buscar Participante"><i class="fa fa-search"></i> Buscar</button>
								<button class="btn btn-primary" onclick="limpiarBusqueda()" data-toggle="tooltip" data-placement="top" title="Limpiar Busqueda"><i class="fa fa-clean"></i> Limpiar</button>
						</div>
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
							<thead>
								<th>No</th>
								<th>Nombre</th>
								<th>Sexo</th>
								<th>Edad</th>
								<th>Corredor</th>
								<th>Centro Educativo</th>							
								<th>Opciones</th>
							</thead>
							<tbody></tbody>
							<tfoot>
								<th>No</th>
								<th>Nombre</th>
								<th>Sexo</th>
								<th>Edad</th>
								<th>Corredor</th>
								<th>Centro Educativo</th>                           					
								<th>Opciones</th>
							</tfoot>
						</table>
					</div>				
					<!-- Fin centro -->
					<div class="panel-body" style="height: auto;" id="formularioregistros">
						<section>
							<form id="formulario" method="POST">
								<div> <!-- ASPECTOS GENERALES -->
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<h4 style="background-color:#339FFF; padding:10px; border-radius:5px; color:white; font-weight:500;">Aspectos Generales <span id="leyenda" style="font-size: 12px;">(*) Campos obligatorios</span></h4><hr style="margin-bottom: 0px;">
									</div>
									<div class="form-group col-lg-12 col-md-3 col-sm-12 col-xs-12">
										<input type="hidden" id="idparticipante" name="idparticipante">
										<label><strong class="obligado">*</strong>Tipo de Participante:</label>
										<select class="form-control select-picker" name="tipoparticipante" id="tipoparticipante"  data-live-search="true" required></select>
									</div>
									<div class="form-group col-lg-3 col-md-9 col-sm-12 col-xs-12">
										<label><strong class="obligado">*</strong>Comunidad:</label>
										<select id="idcomunidad" name="idcomunidad" class="form-control selectpicker" data-live-search="true" required></select>
									</div>		
									<div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
										<label><strong class="obligado">*</strong>Departamento:</label>
										<select id="iddepartamento" name="iddepartamento" class="form-control selectpicker" data-live-search="true" required></select>
									</div>
									<div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
										<label><strong class="obligado">*</strong>Municipio:</label>
										<select id="idnvomunicipio" name="idnvomunicipio" class="form-control selectpicker" data-live-search="true" required>
										</select>
									</div>
									<div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
										<label><strong class="obligado">*</strong>Distrito:</label>
										<select id="idmunicipio" name="idmunicipio" class="form-control selectpicker" data-live-search="true" required>
										</select>
									</div>
									<div class="form-group col-lg-6 col-md-4 col-sm-12 col-xs-12">
										<label><strong class="obligado">*</strong>Corredor:</label>
										<select id="corredorpar" name="corredorpar" class="form-control selectpicker" data-live-search="true" required></select>
										<input type="hidden" id="idcorredorpar" name="idcorredorpar">
									</div>
									<div class="form-group col-lg-6 col-md-8 col-sm-12 col-xs-12">
										<label><strong class=obligado">*</strong>Centro Educativo:</label>
										<select id="idinstitucion" name="idinstitucion" class="form-control selectpicker" data-live-search="true" required></select>
									</div>
								</div>
					
								<div> <!-- Grupo -->
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="seccion_grupo">
										<hr style="margin-top: 0px;"><h4 style="font-weight:700; color:#000000;">Grupo al que pertenece:</h4>
										<input type="hidden" id="idgrupo" name="idgrupo">
										<select class="form-control select-picker" name="grupoparticipante" id="grupoparticipante" data-live-search="true"></select><hr style="margin-bottom: 0px;">
									</div>
								</div>
								<div id="datospersonales" style="padding-top: 20px;"> <!-- DATOS PERSONALES -->
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<h4 style="background-color:#339FFF; padding:10px; border-radius:5px; color:white; font-weight:500;">Datos Personales</h4><hr style="margin-bottom: 0px;">
									</div>
									<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12"> 
										<label><strong class="obligado">*</strong>Primer Nombre:</label>									
										<input type="text" class="form-control" id="primernombre" name="primernombre" maxlength="20" placeholder="Primer Nombre" required>
									</div>
									<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<label>Segundo Nombre:</label>
										<input type="text" class="form-control" id="segundonombre" name="segundonombre" maxlength="20" placeholder="Segundo Nombre">
									</div>
									<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<label><strong class="obligado">*</strong>Primer Apellido:</label>
										<input type="text" class="form-control" id="primerapellido" name="primerapellido" maxlength="20" placeholder="Primer Apellido" required>
									</div>
									<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<label>Segundo Apellido:</label>
										<input type="text" class="form-control" id="segundoapellido" name="segundoapellido" maxlength="20" placeholder="Segundo Apellido">							
									</div>
									<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<label>Fecha de Nacimiento:</label>
										<i class="fa fa-calendar"></i>
										<input type="date" class="form-control" placeholder="dd/mm/yyyy" min='1890-01-01' max='9999-12-31' id="fechanacpar" name="fechanacpar" />
										<!--<input type="text" class="form-control" id="fechanacpar" name="fechanacpar" readonly/>-->
									</div>							
									<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12"> 
										<label><strong class="obligado">*</strong>Edad:</label>
										<input type="number" class="form-control" id="edadpar" name="edadpar" min="0" max="99" step="1" pattern="[0-9]*" title="Deben ser números enteros y positivos" required/>
									</div>
									<div class="form-group col-lg-3 col-md-3 col-sm-4 col-xs-12">
										<label><strong class="obligado">*</strong>Sexo:</label>
										<select class="form-control select-picker" name="sexopar" id="sexopar" required>
										<option value="" selected>--Seleccione--</option>
										<option value="F">Femenino</option>
										<option value="M">Masculino</option>														
										</select>								
									</div>
									<div class="form-group col-lg-6 col-md-3 col-sm-4 col-xs-12">
										<label><strong class="obligado">*</strong>Discapacidad:</label>
										<select class="form-control select-picker" name="iddiscapacidad" id="iddiscapacidad"  data-live-search="true" required>															
										</select>
									</div>	
									<div class="form-group col-lg-6 col-md-3 col-sm-4 col-xs-12">
										<label>Ocupacion:</label>
										<input type="text" class="form-control" id="ocupacionpar" name="ocupacionpar" maxlength="100" placeholder="Ocupacion">
									</div>		
								</div>
								<div id="extra"style="padding-top: 20px;"> <!-- EXTRA -->
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<h4 style="background-color:#339FFF; padding:10px; border-radius:5px; color:white; font-weight:500;">Datos Extra</h4><hr style="margin-bottom: 0px;">
									</div>
									<div class="estudiante" id="estudiante"> <!-- Sección Estudiantes -->				
										<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<label>Responsable participante:</label>
											<input type="text" class="form-control" id="nombreresponsablepar" name="nombreresponsablepar" maxlength="255" pattern="[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{0,255}" title="No poner Números" placeholder="Responsable">
										</div>
										<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<label>Telefono participante:</label>
											<input type="tel" id="telresponsablepar" name="telresponsablepar" class="form-control solo-numero" maxlength="8" pattern="[0-9]*" title="Deben ser números enteros y positivos" placeholder="Teléfono">
										</div>
										<div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
											<label>Grado:</label>
											<input type="text" class="form-control" id="estudiopar" name="estudiopar" maxlength="255" placeholder="Estudio">
										</div>
										<div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
											<label>Secci&oacute;n:</label>
											<input type="text" class="form-control" id="seccion" name="seccion" maxlength="1" placeholder="Seccion">
										</div>
										<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<label>Profesor:</label>
											<input type="text" class="form-control" id="profesor" name="profesor" maxlength="100" pattern="[a-zA-Z ]{0,100}" title="No poner Números" placeholder="Profesor">
										</div>
										<div class="form-group form-check col-lg-2 col-md-3 col-sm-12 col-xs-12">
											<label class="form-check-label" for="concentimeinto">Consentimiento</label>
											<input type="checkbox" class="form-check-input" id="concentimeinto" name="concentimeinto"/>									
										</div>
									</div>
									<div class="docente" id="docente" >	<!-- Sección Docentes -->																			
										<div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
											<label>NIP (Docente):</label>
											<input type="text" class="form-control" id="codigopar" name="codigopar" maxlength="10" placeholder="NIP">
										</div>
										<div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
											<label>Director de CE:</label>
											<input type="text" class="form-control" id="directorcepar" name="directorcepar" maxlength="100" placeholder="Director">
										</div>
										<div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12"> 
											<label>Especialidad participante:</label>
											<input type="text" class="form-control" id="especialidadpar" name="especialidadpar" maxlength="40" placeholder="Especialidad">
										</div>
										<div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
											<label>Turno Escolar:  <i id="anterior_turno"></i></label>
											<select class="form-control select-picker" name="turno" id="turno" data-live-search="true">	
												<option value="" selected>--Seleccione Turno--</option>		
												<option value="Ninguno">Ninguno</option>
												<option value="Matutino">Matutino</option>
												<option value="Vespertino">Vespertino</option>
												<option value="Nocturno">Nocturno</option>	
												<option value='A Distancia'>A Distancia</option>
												<option value='Matutino y Vespertino'>Matutino y Vespertino</option>
												<option value='Matutino y Nocturno'>Matutino y Nocturno</option>
												<option value='Matutino y a Distancia'>Matutino y a Distancia</option>
												<option value='Vespertino y Nocturno'>Vespertino y Nocturno</option>
												<option value='Vespertino y a Distancia'>Vespertino y a Distancia</option>
												<option value='Nocturno y a Distancia'>Nocturno y a Distancia</option>
												<option value='Matutino, Vespetino y Nocturno'>Matutino, Vespetino y Nocturno</option>
												<option value='Matutino, Vespetino y a Distancia'>Matutino, Vespetino y a Distancia</option>
												<option value='Matutino, Nocturno y a Distancia'>Matutino, Nocturno y a Distancia</option>
												<option value='Vespetino, Nocturno y a Distancia'>Vespetino, Nocturno y a Distancia</option>
												<option value='Matutino, Vespetino, Nocturno y a Distancia'>Matutino, Vespetino, Nocturno y a Distancia</option>																
											</select>							
										</div>
									</div>
									<div class="comunitario" id="comunidad">						
									</div>
									<div class="padrefamilia" id="padredefamilia">
										<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-6"> 
											<label>Escuela a que asiste hijo:</label>
											<input type="text" class="form-control" id="escuelapar" name="escuelapar" maxlength="255" placeholder="Escuela">
										</div>
									</div>
									<div class="voluntariado" id="voluntario">					
									</div>
									<div class="voluntariadocoor" id="voluntariocoorporativo">					
									</div>
									<div class="director" id="director">
									</div>
									<div class="todos" id="todos">	
									    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<span>Fecha de primera participación: <b id="primeravez"></b></span>
										</div>
									</div>
								</div>
								
								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<button class="btn btn-primary" type="submit" id="btnGuardar" name="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
									<button class="btn btn-danger" type="button" onclick="cancelarform()" id="btnCancelar" name="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
									<button class="btn btn-default" type="button" onclick="regresarform()" id="btnRegresar" name="btnRegresar" style="display:none;"><i class="fa fa-arrow-circle-left"></i> Regresar</button> <!--Agregamos btn Regresar -->
								</div>
							</form>
							<form name="formularioasignar" id="formularioasignar" method="POST">
								<input type="hidden" id="grupoasignar" name="grupoasignar">
								<input type="hidden" id="participanteasignar" name="participanteasignar">
								<!-- Datos para nuevo participante -->
								<input type="hidden" id="tipopar_nvopar" name="tipopar_nvopar">
								<input type="hidden" id="comunidad_nvopar" name="comunidad_nvopar">
								<input type="hidden" id="depa_nvopar" name="depa_nvopar">
								<input type="hidden" id="muni_nvopar" name="muni_nvopar">
								<input type="hidden" id="corr_nvopar" name="corr_nvopar">
								<input type="hidden" id="corr_nvopar_2" name="corr_nvopar_2">
								<input type="hidden" id="centeduc_nvopar" name="centeduc_nvopar">
								<button class="btn btn-primary" type="submit" id="btnGuardar2" name="btnGuardar2" style="display:none;"><i class="fa fa-save"></i> Guardar</button>
							</form>
						</section>	
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
<script type="text/javascript" src="scripts/consultas.js" ></script>
<script type="text/javascript" src="scripts/participantes.js" ></script>
<?php
}
ob_end_flush();
?>