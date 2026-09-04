<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['013CEN']) && $_SESSION['013CEN']==1){
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
        <li class="active">Centros Educativos</li>
      </ol>
    </section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h1 class="box-title">Centro Educativo 
							<?php
								if(isset($_SESSION['011AGR13']) && $_SESSION['011AGR13']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar Centro Educativo"> <i class="fa fa-plus-circle"></i> Agregar</button>
							<?php } ?>
						</h1>
						<div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->
					<!-- centro -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							<th>ID</th>
							<th>Código</th>
                            <th>Centro Educativo</th>
                            <th>Tipo</th>
                            <th>Corredor</th>
                            <th>Dirección</th>
                            <th>Zona</th>
                            <th>Tel. CE</th>
                            <th>Matrícula 2024</th>
                            <th>Director/a</th>
							<th>Tel.Dir.</th>
                            <th>Profesoras</th>
                            <th>Profesores</th>
							<th>Docentes</th>
							<th>Estado</th>
							<th>Opciones</th>
						</thead>
						<tbody></tbody>
						<tfoot>
							<th>ID</th>
                        	<th>Código</th>
                            <th>Centro Educativo</th>
                            <th>Tipo</th>
                            <th>Corredor</th>
                            <th>Dirección</th>
                            <th>Zona</th>
                            <th>Tel. CE</th>
                            <th>Matrícula 2023</th>
                            <th>Director/a</th>
							<th>Tel.Dir.</th>
                            <th>Profesoras</th>
                            <th>Profesores</th>
							<th>Docentes</th>
							<th>Estado</th>
							<th>Opciones</th>
						</tfoot>
						</table>
					</div>
					<div class="panel-body"  id="formularioregistros">
						<form name="formulario" id="formulario" method="POST">
						    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h4 style="background-color:#339FFF; padding:10px; border-radius:5px; color:white; font-weight:500;">Datos Generales <span id="leyenda" style="font-size: 12px;">(*) Campos obligatorios</span></h4><hr style="margin-bottom: 0px;">
							</div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Nombre(*):</label>
								<input type="hidden" id="idinstitucion" name="idinstitucion">
								<input type="text" class="form-control" id="nombreins" name="nombreins" maxlength="150" placeholder="Nombre Centro Educativo" required>
							</div>
                            <div class="form-group col-lg-2 col-md-3 col-sm-4 col-xs-12">
								<label>Código(*):</label>
								<input type="text" class="form-control" id="codigoins" name="codigoins" maxlength="10" placeholder="Código Centro Educativo" pattern="[0-9]{0,10}" title="Únicamente números, no letras ni caracteres especiales" required>
							</div>    
                            <div class="form-group col-lg-2 col-md-3 col-sm-4 col-xs-12">
								<label>Tipo(*):</label>
								<select id="idtipoinstitucion" name="idtipoinstitucion" class="form-control selectpicker" data-live-search="true" required>
								</select>
							</div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">
								<label>Zona(*):</label>
								<select id="zonains" name="zonains" class="form-control selectpicker" required>
									<option value="">--Seleccione Zona--</option>
									<option value='Rural'>Rural</option>
									<option value='Urbano'>Urbano</option>
								</select>
							</div>
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h4 style="background-color:#339FFF; padding:10px; border-radius:5px; color:white; font-weight:500;">Ubicación <span id="leyenda" style="font-size: 12px;">(*) Campos obligatorios</span></h4><hr style="margin-bottom: 0px;">
							</div>
							<!-- Se agregan inputs municipio y departamento -->
							<div class="form-group col-lg-2 col-md-4 col-sm-6 col-xs-12">
								<label>Departamento(*):</label>
								<select id="iddepartamento" name="iddepartamento" class="form-control selectpicker" data-live-search="true" required>
								</select>
							</div>
							<div class="form-group col-lg-2 col-md-6 col-sm-6 col-xs-12">
								<label>Municipio(*):</label>
								<select id="idnvomunicipio" name="idnvomunicipio" class="form-control selectpicker" data-live-search="true" required>
								</select>
							</div>
							<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
								<label>Distrito(*):</label>
								<select id="idmunicipio" name="idmunicipio" class="form-control selectpicker" data-live-search="true" required>
								</select>
							</div>
							<div class="form-group col-lg-3 col-md-4 col-sm-4 col-xs-12">
								<label>Corredor(*):</label>
								<select id="idcorredor" name="idcorredor" class="form-control selectpicker" data-live-search="true" required>
								</select>
							</div>
							<div class="form-group col-lg-2 col-md-4 col-sm-4 col-xs-12">
								<label>Cantón/caserío:</label>
								<select id="idcantoncaserio" name="idcantoncaserio" class="form-control selectpicker" data-live-search="true">
								</select>
							</div>
                            <div class="form-group col-lg-10 col-md-10 col-sm-12 col-xs-12">
								<label>Dirección(*):</label>
								<input type="text" class="form-control" id="ubicacionins" name="ubicacionins" maxlength="255" placeholder="Dirección Centro Educativo" required>
							</div>
							<div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label>Latitud:</label>
                                <input type="text" class="form-control" id="latitud" name="latitud" maxlength="10" 
                                       placeholder="Ingrese la latitud." pattern="^-?\d+(\.\d+)?$" 
                                       title="Ingrese un número válido con un punto decimal opcional, y hasta 10 caracteres en total. El punto no puede estar al final.">
                            </div>
                            <div class="form-group col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label>Longitud:</label>
                                <input type="text" class="form-control" id="longitud" name="longitud" maxlength="10" 
                                       placeholder="Ingrese la longitud." pattern="^-?\d+(\.\d+)?$" 
                                       title="Ingrese un número válido con un punto decimal opcional, y hasta 10 caracteres en total. El punto no puede estar al final.">
                            </div>
                            <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <label>Fecha Ingreso:</label>
                                <input type="text" class="form-control" id="fechaingresoins" name="fechaingresoins" readonly>
                            </div>
                            <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <label>Fecha fin de Proyecto:</label>
                                <input type="text" class="form-control" id="fechasalidains" name="fechasalidains" readonly>
                            </div>
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h4 style="background-color:#339FFF; padding:10px; border-radius:5px; color:white; font-weight:500;">Contácto <span id="leyenda" style="font-size: 12px;">(*) Campos obligatorios</span></h4><hr style="margin-bottom: 0px;">
							</div>   
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Director/a:</label>
								<input type="text" class="form-control" id="nombredirectorins" name="nombredirectorins" maxlength="100" placeholder="Nombre Director/a">
							</div>
							<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label>Correo electrónico Director/a:</label>
                                <input type="email" class="form-control" id="correodir" name="correodir" maxlength="100" 
                                       placeholder="Correo electrónico Director/a" pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$"
                                       title="Ingrese un correo electrónico válido. Ejemplo: ejemplo@dominio.com">
                            </div>
							<!-- Se agregó input telefono director -->
							<div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
								<label>Teléfono Director/a:</label>
								<input type="tel" class="form-control" id="telefonodirins" name="telefonodirins" minlength="8" maxlength="8" placeholder="Teléfono" pattern="[0-9]*" title="Digitar Números sin guiones ni espacios">
							</div>
                            <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
								<label>Teléfono Institución:</label>
								<input type="tel" class="form-control" id="telefonoins" name="telefonoins" minlength="8" maxlength="8" placeholder="Teléfono" pattern="[0-9]*" title="Digitar Números sin guiones ni espacios">
							</div>
							<div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
								<label>Turnos Laborales(*): </label>
								<select id="turnoins" name="turnoins" class="form-control selectpicker" required>
									<option value="">--Seleccione Turno--</option>
									<option value='Matutino'>Matutino</option>
									<option value='Vespetino'>Vespetino</option>
									<option value='Nocturno'>Nocturno</option>
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
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label>Niveles educativos:</label>
								<select id="niveles_educativos" name="niveles_educativos[]" class="form-control selectpicker" multiple>
									<option value="1">Kinder o parvularia</option>
									<option value="2">I ciclo</option>
									<option value="3">II ciclo</option>
									<option value="4">III ciclo</option>
									<option value="5">Bachillerato general</option>
									<option value="6">Bachillerato técnico</option>
								</select>
							</div>
                            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
								<label>Num. Doc. Femeninos(*):</label>
								<input type="text" class="form-control" id="numerodocfemeninoins" name="numerodocfemeninoins" maxlength="4" placeholder="Doc. Femeninos" pattern="[0-9]*" title="Digitar unicamente Números enteros." required>
							</div>
                            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
								<label>Num. Doc. Masculinos(*):</label>
								<input type="text" class="form-control" id="numerodocmasculinoins" name="numerodocmasculinoins" maxlength="4" placeholder="Doc. Masculinos" pattern="[0-9]*" title="Digitar unicamente Números enteros." required>
							</div>
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h4 style="background-color:#339FFF; padding:10px; border-radius:5px; color:white; font-weight:500;">Matrícula General</h4><hr style="margin-bottom: 0px;">
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p style="background-color:#4a4848; padding:2px; border-radius:5px; color:#ffffff; font-weight:200; text-align:center;">Matrícula 2018</p>
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Hombres:</label>
									<input type="text" class="form-control" id="mat18inshom" name="mat18inshom" maxlength="10" placeholder="Matrícula 2018" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Mujeres:</label>
									<input type="text" class="form-control" id="mat18insmuj" name="mat18insmuj" maxlength="10" placeholder="Matrícula 2018" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Total:</label>
									<input type="text" class="form-control" id="mat2018ins" name="mat2018ins" maxlength="10" placeholder="Matrícula 2018" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones" readonly>
								</div> 
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p style="background-color:#4a4848; padding:2px; border-radius:5px; color:#ffffff; font-weight:200; text-align:center;">Matrícula 2019</p>
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Hombres:</label>
									<input type="text" class="form-control" id="mat19inshom" name="mat19inshom" maxlength="10" placeholder="Matrícula 2019" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Mujeres:</label>
									<input type="text" class="form-control" id="mat19insmuj" name="mat19insmuj" maxlength="10" placeholder="Matrícula 2019" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            		<label>Total:</label>
                            		<input type="text" class="form-control" id="mat2019ins" name="mat2019ins" maxlength="10" placeholder="Matrícula 2019" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones" readonly>
                            	</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p style="background-color:#4a4848; padding:2px; border-radius:5px; color:#ffffff; font-weight:200; text-align:center;">Matrícula 2020</p>
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Hombres:</label>
									<input type="text" class="form-control" id="mat20inshom" name="mat20inshom" maxlength="10" placeholder="Matrícula 2020" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Mujeres:</label>
									<input type="text" class="form-control" id="mat20insmuj" name="mat20insmuj" maxlength="10" placeholder="Matrícula 2020" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            		<label>2020:</label>
                            		<input type="text" class="form-control" id="mat2020ins" name="mat2020ins" maxlength="10" placeholder="Matrícula 2020" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones" readonly>
                            	</div>	
							</div>
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<hr style="margin-bottom: 0px;">
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p style="background-color:#4a4848; padding:2px; border-radius:5px; color:#ffffff; font-weight:200; text-align:center;">Matrícula 2021</p>
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Hombres:</label>
									<input type="text" class="form-control" id="mat21inshom" name="mat21inshom" maxlength="10" placeholder="Matrícula 2021" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Mujeres:</label>
									<input type="text" class="form-control" id="mat21insmuj" name="mat21insmuj" maxlength="10" placeholder="Matrícula 2021" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            		<label>2021:</label>
                            		<input type="text" class="form-control" id="mat2021ins" name="mat2021ins" maxlength="10" placeholder="Matrícula 2021" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones" readonly>
                            	</div> 
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p style="background-color:#4a4848; padding:2px; border-radius:5px; color:#ffffff; font-weight:200; text-align:center;">Matrícula 2022</p>
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Hombres:</label>
									<input type="text" class="form-control" id="mat22inshom" name="mat22inshom" maxlength="10" placeholder="Matrícula 2022" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Mujeres:</label>
									<input type="text" class="form-control" id="mat22insmuj" name="mat22insmuj" maxlength="10" placeholder="Matrícula 2022" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>2022:</label>
									<input type="text" class="form-control" id="mat2022ins" name="mat2022ins" maxlength="10" placeholder="Matrícula 2022" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones" readonly>
								</div> 
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p style="background-color:#4a4848; padding:2px; border-radius:5px; color:#ffffff; font-weight:200; text-align:center;">Matrícula 2023</p>
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Hombres:</label>
									<input type="text" class="form-control" id="mat23inshom" name="mat23inshom" maxlength="10" placeholder="Matrícula 2023" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Mujeres:</label>
									<input type="text" class="form-control" id="mat23insmuj" name="mat23insmuj" maxlength="10" placeholder="Matrícula 2023" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<label>2023:</label>
									<input type="text" class="form-control" id="mat2023ins" name="mat2023ins" maxlength="10" placeholder="Matrícula 2023"  pattern="[0-9]*" title="Digitar Números Enteros sin espacios, ni comas, ni guiones" readonly>
								</div>  
							</div>
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<hr style="margin-bottom: 0px;">
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p style="background-color:#4a4848; padding:2px; border-radius:5px; color:#ffffff; font-weight:200; text-align:center;">Matrícula 2024</p>
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Hombres:</label>
									<input type="text" class="form-control" id="mat24inshom" name="mat24inshom" maxlength="10" placeholder="Matrícula 2024" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>Mujeres:</label>
									<input type="text" class="form-control" id="mat24insmuj" name="mat24insmuj" maxlength="10" placeholder="Matrícula 2024" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label>2024:</label>
									<input type="text" class="form-control" id="mat2024ins" name="mat2024ins" maxlength="10" placeholder="Matrícula 2024" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones" readonly>
								</div> 
							</div>
                             
                            
                            
                            <!--<div class="form-group col-lg-2 col-md-3 col-sm-4 col-xs-12">
                            	<label>2025:</label>
                            	<input type="text" class="form-control" id="mat2025ins" name="mat2025ins" maxlength="10" placeholder="Matrícula 2025" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
                            </div> 
                            <div class="form-group col-lg-2 col-md-3 col-sm-4 col-xs-12">
                            	<label>2026:</label>
                            	<input type="text" class="form-control" id="mat2026ins" name="mat2026ins" maxlength="10" placeholder="Matrícula 2026" pattern="[0-9]{0,10}" title="Digitar Números Enteros sin espacios, ni comas, ni guiones">
                            </div> -->
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <hr style="margin-bottom: 0px;">
                            </div>
							<div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                <label>Ultima Modificación: </label>
                                <input type="text" class="form-control" id="fechamodins" name="fechamodins" disabled>
                            </div>
							
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<button class="btn btn-primary" type="submit" id="btnGuardar" name="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
								<button class="btn btn-danger" type="button" onclick="cancelarform()" id="btnCancelar" name="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
								<button class="btn btn-default" type="button" onclick="regresarform()" id="btnRegresar" name="btnRegresar" style="display:none;"><i class="fa fa-arrow-circle-left"></i> Regresar</button> <!--Agregamos btn Regresar -->
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
}
ob_end_flush();
?>
<script type="text/javascript" src="scripts/institucion.js" ></script>
<script src="https://cdn.datatables.net/fixedheader/3.2.0/js/dataTables.fixedHeader.min.js" ></script>