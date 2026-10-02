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



if(isset($_SESSION['047INS']) && $_SESSION['047INS']==1){

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

        <li><a href="#">Insumos</a></li>

        <li class="active">Gestión de insumos</li>

      </ol>

    </section>

	<section class="content">

		<div class="row">

			<div class="col-md-12">

				<div class="box">

					<div class="box-header with-border">

						<h1 class="box-title">Insumos</h1>

						<div class="box-tools pull-right">

							<?php

								if(isset($_SESSION['146AGR48']) && $_SESSION['146AGR48']==1){ ?>

									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar Gestión"><i class="fa fa-plus-circle"></i> Agregar Insumo</button>

							<?php } ?>

                        </div>

					</div>

					<!-- /.box-header-->

					<!-- centro -->

					<div class="panel-body table-reponsive"  id="listadoregistros">

						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">

						<thead>

							<th>N°</th> 

							<th>Tipo Insumo</th>

							<th>Nombre de Insumo</th>

							<th>Precio 8H</th> 

							<th>Precio 4H</th> 

							<th>Precio Sala Remodelada</th> 

							<th>Precio Unitario</th>

							<th>Precio de Venta</th>

							<th>Insumo Editable</th>

							<th>Fecha Registro</th>

							<th>Fecha Ultima Modificación</th>

							<th>Estado</th>

                            <th>Opciones</th>

						</thead>

						<tbody></tbody>

						<tfoot>

							<th>N°</th> 

							<th>Tipo Insumo</th>

							<th>Nombre de Insumo</th>

							<th>Precio 8H</th> 

							<th>Precio 4H</th> 

							<th>Precio Sala Remodelada</th> 

							<th>Precio Unitario</th>

							<th>Precio de Venta</th>

							<th>Insumo Editable</th>

							<th>Fecha Registro</th>

							<th>Fecha Ultima Modificación</th>

							<th>Estado</th>

                            <th>Opciones</th>

						</tfoot>

						</table>

					</div>

					<div class="panel-body" style="height: auto;" id="formularioregistros">

                        <form name="formulario" id="formulario" method="POST">

							<!-- Información del Cliente -->

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <h2>Información del insumo</h2>                             

                            </div>

							<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<label>Tipo de insumo:</label>
								<select class="form-control select-picker" name="tipoinsumo" id="tipoinsumo"  data-live-search="true" required>															
								</select>
							</div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label>Nombre de Insumo(*):</label>
								<input type="hidden" id="idinsumo" name="idinsumo">
								<input type="text" class="form-control" id="nombreinsumo" name="nombreinsumo" maxlength="255" placeholder="Nombre de Insumo" title="Ingrese el nombre" required>
							</div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label>Alías del Insumo(*):</label>
                                <span>Nombre del insumo que se verá reflejado en la cotización del cliente.</span>
								<input type="text" class="form-control" id="aliasinsumo" name="aliasinsumo" maxlength="255" placeholder="Alias del Insumo" title="Ingrese el alias" required>
							</div>

							<div class="form-group col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                <label>Descripción:</label>
                                <input type="text" class="form-control" id="descripcion_insumo" name="descripcion_insumo" maxlength="255" placeholder="Breve descripción del insumo">                             
								<br>
                            </div>
                            
                            <hr>

                            <!-- Precios por tipo de insumo -->
                            <div id="precios_x_tipo" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h1>Precios por tipo</h1>
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

<script type="text/javascript" src="scripts/gestioninsumo.js?v=20260727-1"></script>

<?php

}

ob_end_flush();

?>