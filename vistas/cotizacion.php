<?php

//Activamos el almacenamiento en el buffer

ob_start();

session_start();

/*if (!isset($_SESSION['idusuariosusu'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: ../index.php");
    exit;
}*/

if(!isset($_SESSION["nombre"])){

    header("Location: login.html");

}else{

require 'header.php';



if(isset($_SESSION['044COT']) && $_SESSION['044COT']==1){

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

        <li><a href="menu.php"><i class="fa fa-dashboard"></i> Menú</a></li>

        <li><a href="#">Actividades</a></li>

        <li class="active">Cotizaciones</li>

      </ol>

    </section>

	<section class="content">

		<div class="row">

			<div class="col-md-12">

				<div class="box">

					<div class="box-header with-border">

						<h1 class="box-title">Cotizaciones</h1>

						<div class="box-tools pull-right">

							<?php

								if(isset($_SESSION['127AGR44']) && $_SESSION['127AGR44']==1){ ?>

									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar Cotización"><i class="fa fa-plus-circle"></i> Agregar Cotización</button>

							<?php } ?>

                        </div>

					</div>

					<!-- /.box-header-->

					<!-- centro -->

					<div class="panel-body table-reponsive"  id="listadoregistros">

						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">

						<thead>

							<th>N°</th> 

							<th>Referencia</th>

							<th>Empresa</th>

							<th>Contacto</th> 

							<th>Teléfono</th> 

							<th>Correo</th> 

							<th>Tipo Evento</th>

							<th>Tipo Alquiler</th>

							<th>Usuario</th>

							<th>Fecha Registro</th>

							<th>Estado</th>

                            <th>Opciones</th>

						</thead>

						<tbody></tbody>

						<tfoot>

							<th>N°</th> 

							<th>Referencia</th>

							<th>Empresa</th>

							<th>Contacto</th> 

							<th>Teléfono</th>  

							<th>Correo</th>

							<th>Tipo Evento</th>

							<th>Tipo Alquilero</th>

							<th>Usuario</th>

							<th>Fecha Registro</th>

							<th>Estado</th>

                            <th>Opciones</th>

						</tfoot>

						</table>

					</div>

					<div class="panel-body" style="height: auto;" id="formularioregistros">

                        <form name="formulario" id="formulario" method="POST">

							<!-- Información del Cliente -->

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <h2>Información de la cotización</h2>                             

                            </div>

							<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">

								<label>Tipo de alquiler:</label>

								<select class="form-control select-picker" name="tipoalquiler" id="tipoalquiler"  data-live-search="true">															

								</select>

							</div>



						    <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">

                                <label><strong class="obligado">*</strong>Código de Referencia: </label>

								<input type="hidden" id="idcotizacion" name="idcotizacion">	

                                <input type="text" class="form-control" id="codreferencia" name="codreferencia" maxlength="150" placeholder="Referencia" readonly required>

                            </div>

							<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<label>Exenta de IVA:</label><br>

								<label class="radio-inline">
									<input type="radio" name="exentacot" value="0" checked> Con IVA (13%)
								</label>

								<label class="radio-inline" style="margin-left: 20px;">
									<input type="radio" name="exentacot" value="1"> Exenta de IVA
								</label>
							</div>



                            <br>

			

                            <!-- Información del Cliente -->

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <h2>Información del cliente</h2>                             

                            </div>



                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                <label>NRC:</label>

                                <input type="text" class="form-control" id="cli_nrc" name="cli_nrc" maxlength="20" placeholder="NRC">                             

                            </div>

							<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                <label><strong class="obligado">*</strong>Empresa:</label>

                                <input type="text" class="form-control" id="cli_empresa" name="cli_empresa" maxlength="255" placeholder="Nombre de la empresa" required>                             

                            </div>

							<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                <label><strong class="obligado">*</strong>Contácto:</label>

                                <input type="text" class="form-control" id="cli_contacto" name="cli_contacto" maxlength="255" placeholder="Nombre de contácto" required>                             

                            </div>

							<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label><strong class="obligado">*</strong>Teléfono:</label>

								<input 
									type="tel" 
									class="form-control" 
									id="cli_telefono" 
									name="cli_telefono" 
									inputmode="tel"
									autocomplete="tel"
									placeholder="+50371234567"
									maxlength="25"
									required
									oninput="this.value = this.value.replace(/(?!^\+)[^\d]/g,'').replace(/^00/,'+')"
								/>

								<div id="telefonoHelp" class="text-danger" style="display:none; font-size: 13px;">
									Debe iniciar con + y código de país. Ejemplo: +50371234567
								</div>

								<small class="form-text text-muted">
									Ingrese el número en formato internacional (con código de país).  
									Ejemplo: +50371234567, +14155552671, +34911234567
								</small>
							</div>

							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <label><strong class="obligado">*</strong>Correo de contácto:</label>

                                <input 

									type="email" 

									class="form-control" 

									id="cli_email" 

									name="cli_email" 

									maxlength="255" 

									placeholder="Email de contacto" 

									required

									title="Ingrese un correo válido, por ejemplo usuario@dominio.com o usuario@dominio.edu.sv"

								>

                            </div>



							<!-- Información del evento -->

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <h2>Información del evento</h2>                             

                            </div>



							<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

								<label><strong class="obligado">*</strong>Tipo de evento:</label>

								<select class="form-control select-picker" name="tipoevento" id="tipoevento"  data-live-search="true" required>															

								</select>

							</div>



							<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                <label>Descripción:</label>

                                <input type="text" class="form-control" id="descripcion_cot" name="descripcion_cot" maxlength="255" placeholder="Breve descripción de la cotización">                             
								<br>
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

<script>
    const IDROL_USUARIO = <?= (int)$_SESSION['idrol']; ?>;

	//Función para leer el párametro antes de iniciar DataTable
	function getParametro(nombre) {
		const urlParams = new URLSearchParams(window.location.search);
		return urlParams.get(nombre);
	}
</script>

<!--Fin-Contenido-->

<?php

}else{

    require 'noacceso.php';

}

require 'footer.php';

?>

<script type="text/javascript" src="scripts/cotizacion.js?v=20260727-1"></script>

<?php

}

ob_end_flush();

?>