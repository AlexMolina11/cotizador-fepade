<?php

//Activamos el almacenamiento en el buffer

ob_start();

session_start();

if(!isset($_SESSION["nombre"])){

    header("Location: login.html");

}else{

require 'header.php';



if(isset($_SESSION['045DET']) && $_SESSION['045DET']==1){

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

        <li><a href="#">Cotización</a></li>

        <li class="active">Detalle</li>

      </ol>

    </section>

	<section class="content">

		<div class="row">

			<div class="col-md-12">

				<div class="box">

					<div class="box-header with-border">

                        <h1 class="box-title">Detalle de Cotizaciones</h1>

						<div class="box-tools pull-right">

                            <?php

								if(isset($_SESSION['132AGR45']) && $_SESSION['132AGR45']==1){ ?>

									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>  Agregar</button>

							<?php } ?>

                        </div>

					</div>

					<!-- /.box-header-->

					<!-- centro -->

					<div class="panel-body table-reponsive"  id="listadoregistros">

						<div class="row">

							<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">

								<label>Fecha Inicio:</label> 

								<i class="fa fa-calendar" style="color:#0ce98b8c;"></i>

								<input type="date" class="form-control pull-right" id="bsc_fecha" name="bsc_fecha" min='1890-01-01' max='9999-12-31' value=""/>

							</div>

							<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">

								<label>Cotizacion:</label>

                                <i class="fa fa-id-card" style="color:#0ce98b8c;"></i>

								<select class="form-control select-picker" name="bsc_cotizacion" id="bsc_cotizacion"  data-live-search="true" required></select>

							</div>

							<div class="form-group col-lg-5 col-md-5 col-sm-12 col-xs-12">

								<label>Acciones:</label><br>

								<div class="btn-group">

									<button class="btn btn-primary" onclick="listar()"><i class="fa fa-search"></i> Buscar</button>

									<button class="btn btn-danger" type="button" onclick="limpiar()" id="btnCancelar" name="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Limpiar</button>

								</div>

							</div>

						</div>

						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">

						<thead>

							<th>#</th>

							<th>Referencia</th>

							<th>Fecha</th>

							<th>Hora Inicio</th>

							<th>Hora Fin</th>

							<th>Cantidad Participantes</th>

							<th>Estado</th>

							<th>Opciones</th>

						</thead>

						<tbody></tbody>

						<tfoot>

							<th>#</th>

							<th>Referencia</th>

							<th>Fecha</th>

							<th>Hora Inicio</th>

							<th>Hora Fin</th>

							<th>Cantidad Participantes</th>

							<th>Estado</th>

							<th>Opciones</th>

						</tfoot>

						</table>

					</div>

					<div class="panel-body" style="height: auto;" id="formularioregistros">

                        <form name="formulario" id="formulario" method="POST">

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <label><strong class="obligado">*</strong>Cotización:</label>

                                <input type="hidden" id="iddetallecot" name="iddetallecot">

								<select id="idcotizacion" name="idcotizacion" class="form-control selectpicker" data-live-search="true" required>

								</select>

                            </div>



                            <!-- Información del Cliente -->

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <h2>Información del evento</h2>                             

                            </div>



							<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                <label><strong class="obligado">*</strong>Cantidad de participantes:</label>

                                <input type="number" class="form-control" id="cantpar" name="cantpar" placeholder="Cantidad de personas esperadas" required/>

                            </div>

                           

							<div class="form-group col-lg-6 col-md-6 col-sm-4 col-xs-12">							

                                <label><strong class="obligado">*</strong>Fecha: <i class="fa fa-calendar"></i> </label>                                                                                       

                                <input type="date" class="form-control pull-right" id="fechacot" name="fechacot" placeholder="dd/mm/aaaa" required/>

                            </div>                          

                            <div  class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-6">

			                  <label><strong class="obligado">*</strong>Hora Inicio:  <i class="fa fa-clock-o"></i></label>

							   <input type="time" class="form-control" name="horainicot" id="horainicot" required>

			                 </div>

			                <div  class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-6">

			                  <label><strong class="obligado">*</strong>Hora Fin: <i class="fa fa-clock-o"></i></label>

							  <input type="time" class="form-control" name="horafincot" id="horafincot" required>

						    </div>

							<div  class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-6">

			                  <label>Duración: <i class="fa fa-clock-o"></i></label>

							  <input type="time" class="form-control" name="duracion" id="duracion" readonly>

						    </div>

                            

                            <!-- Insumos del evento -->

							 <div id="seccion_insumos" style="display:none;">

								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

									<h2>Insumos del evento</h2>                             

								</div>



								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

									<div class="row">

										<div class="col-md-2">

											<h4>Tipos de Insumo</h4>

											<table id="tablaTiposInsumos" class="table table-striped table-bordered table-condensed table-hover">

											<thead><tr><th>Nombre Tipo</th></tr></thead>

											<tbody></tbody>

											</table>

										</div>

										<div class="col-md-10">

											<h4>Insumos</h4>

											<table id="tablaInsumos" class="table table-striped table-bordered table-condensed table-hover">

											<thead></thead>

											<tbody></tbody>

											</table>

										</div>

									</div>                           

								</div>

							</div>



                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

								<button class="btn btn-primary" type="submit" id="btnGuardar" name="btnGuardar">

									<i class="fa fa-save"></i> Guardar

								</button>

								<button class="btn btn-danger" type="button" onclick="cancelarform()" id="btnCancelar" name="btnCancelar">

									<i class="fa fa-arrow-circle-left"></i> Cancelar

								</button>

								<div class="box-tools pull-right" id="seccion_preview">

									<button class="btn btn-info" type="button" onclick="vistaPrevia()" id="btnVistaPrevia" name="btnVistaPrevia" >

										<i class="fa fa-eye"></i> Vista previa

									</button>

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

<?php

}else{

    require 'noacceso.php';

}

require 'footer.php';

?>

<script>

    // Captura el idcotizacion de la URL si existe

    var idCotizacionSeleccionada = "<?php echo isset($_GET['idcotizacionnew']) ? $_GET['idcotizacionnew'] : ''; ?>";

</script>

<script type="text/javascript" src="../librerias/bootstrap-select/js/bootstrap-select.js"></script>

<script type="text/javascript" src="../librerias/plugins/jQueryUI/jquery-ui.js"></script>

<script type="text/javascript" src="scripts/cotizacion_detalle.js" ></script>

<?php

}

ob_end_flush();

?>



