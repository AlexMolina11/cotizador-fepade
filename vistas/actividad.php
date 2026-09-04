<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['028ACT']) && $_SESSION['028ACT']==1){
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
        <li class="active">Actividad</li>
      </ol>
    </section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h1 class="box-title">Actividad
							<?php
								if(isset($_SESSION['073AGR28']) && $_SESSION['073AGR28']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar Actividad"><i class="fa fa-plus-circle"></i>Agregar</button>
							<?php } ?>
						</h1>
						<div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->
					<!-- centro -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
						<div class="row">
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
								<div class="btn-group">
									<button class="btn btn-primary" onclick="listar()"><i class="fa fa-search"></i> Buscar</button>
									<button class="btn btn-danger" type="button" onclick="limpiar()" id="btnCancelar" name="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Limpiar</button>
								</div>
							</div>
						</div>
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							<th>N°</th> 
							<th>Nombre</th>
							<th>Fecha</th>
							<th>Departamento</th> 
							<th>Municipio</th>
							<th>Corredor</th>
							<th>Centro Educativo</th>
							<th>Área Res.</th>
							<th>Descripción</th> 
							<th>Usuario</th>
							<th>Estado</th>
                            <th>Opciones</th>
						</thead>
						<tbody></tbody>
						<tfoot>
							<th>N°</th> 
							<th>Nombre</th>
							<th>Fecha</th>
							<th>Departamento</th> 
							<th>Municipio</th>
							<th>Corredor</th>
							<th>Centro Educativo</th>
							<th>Área Res.</th>
							<th>Descripción</th> 
							<th>Usuario</th>
							<th>Estado</th>
                            <th>Opciones</th>
						</tfoot>
						</table>
					</div>
					<div class="panel-body" style="height: auto;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
							<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<label><strong class="obligado">*</strong>Área Responsable:</label>
								<select class="form-control select-picker" name="arearesponsable" id="arearesponsable"  data-live-search="true" required></select>
							</div>
						    <div class="form-group col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <label><strong class="obligado">*</strong>Nombre de la actividad según nomenclatura: </label>
                                <span style="display: inline-flex; flex-direction: row; align-items: center; font-size:15px;">
                                    Ejemplo: 
                                    <p style="color:red; margin: 0; margin-left: 3px;"><b>AR;</b></p>
                                    <p style="color:blue; margin: 0; margin-right: 3px;"><b>Habilidades socioemocionales</b></p> 
                                    <p style="color:green; margin: 0; margin-right: 3px;"><b>01</b></p> 
                                    <p style="color:purple; margin: 0; margin-right: 3px;"><b>CE Caserío Palo Verde</b></p> 
                                    <p style="margin: 0;"><b> 25-08-2021 </b></p>
                                </span>
								<input type="hidden" id="idactividad" name="idactividad">	
                                <input type="text" class="form-control" id="nombreact" name="nombreact" maxlength="150" placeholder="Nombre" required>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Información de la actividad:</label>
								<textarea class="form-control" id="descripcionact" name="descripcionact" cols="20" rows="3" maxlength="300" placeholder="Información de la actividad"></textarea>                              
                            </div>
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label><strong class="obligado">*</strong>Objetivo:</label>
                                <textarea class="form-control" id="objetivoact" name="objetivoact" cols="20" rows="3" maxlength="300" placeholder="Objetivo de la actividad" required></textarea>    
                            </div>
							<!--Se agregó tipo de participante -->
							<div class="form-group col-lg-9 col-md-7 col-sm-12 col-xs-12">
								<label><strong class="obligado">*</strong>Actividad dirigida a:</label>
								<select class="form-control select-picker" name="tipoparticipante" id="tipoparticipante"  data-live-search="true" required>															
								</select>
							</div>
                            <div class="form-group col-lg-3 col-md-5 col-sm-12 col-xs-12">
								<label><strong class="obligado">*</strong>Fecha:</label>
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>                                
									<input type="date" class="form-control pull-right" id="fechaact" name="fechaact" required/>
								</div>
                            </div>

							 <!-- Se agregó Departamento -->
							<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<label><strong class="obligado">*</strong>Departamento:</label>                                       
								<select id="departamentoact" name="departamentoact" class="form-control selectpicker" data-live-search="true" required>
								</select>								
							</div>						
							<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
								    <label><strong class="obligado">*</strong>Distrito:</label>                                       
									<select id="municipioact" name="municipioact" class="form-control selectpicker" data-live-search="true" required>
								    </select>								
                             </div>
							<div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                    <label><strong class="obligado">*</strong>Tipo Actividad:</label>                                         
									<select id="tipoact" name="tipoact" class="form-control selectpicker" data-live-search="true" required>
								    </select>
                                           
                             </div>
							  <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                    <label><strong class="obligado">*</strong>Categoria de Actividad:</label>                                         
									<select id="categoriaact" name="categoriaact" class="form-control selectpicker" data-live-search="true" required>
								    </select>                    
                             </div>			

							 <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <label><strong class="obligado">*</strong>Corredor:</label>
									<select id="corredoract" name="corredoract" class="form-control selectpicker" data-live-search="true" required>
								    </select>                                     
                             </div>
							<div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <label><strong class="obligado">*</strong>Centro Educativo:</label>
									<select id="institucionact" name="institucionact" class="form-control selectpicker" data-live-search="true" required>
								    </select>                                     
                             </div>

							 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label>Comunidades:</label>
								<select id="actividad_comunidades" name="actividad_comunidades[]" class="form-control selectpicker" multiple></select>
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
<script type="text/javascript" src="scripts/actividad.js"></script>
<?php
}
ob_end_flush();
?>