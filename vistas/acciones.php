<?php

//Activamos el almacenamiento en el buffer

ob_start();

session_start();

if(!isset($_SESSION["nombre"])){

    header("Location: login.html");

}else{

require 'header.php';



if(isset($_SESSION['034ACC']) && $_SESSION['034ACC']==1){

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

        <li><a href="#">Accesos</a></li>

        <li class="active">Acciones</li>

      </ol>

    </section>

    <!-- Main content -->

    <section class="content">

        <div class="row">

            <div class="col-md-12">

                <div class="box">

                    <div class="box-header with-border">

                        <h1 class="box-title">Acciones 

							<?php

								if(isset($_SESSION['097AGR34']) && $_SESSION['097AGR34']==1){ ?>

									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>

                            <?php } ?>

						</h1>

                        <div class="box-tools pull-right"></div>

                    </div>

                    <!-- /.box-header-->

                    <!-- centro -->

                    <div class="panel-body table-reponsive" id="listadoregistros">



                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">

                            <thead>

                                <th>No</th>

                                <th>Acción</th>

                                <th>Descripción</th>

                                <th>Módulo</th>                            

                                <th>Opciones</th>

                            </thead>

                            <tbody></tbody>

                            <tfoot>

                                <th>No</th>

                                <th>Acción</th>  

                                <th>Descripción</th>

                                <th>Módulo</th>                                 

                                <th>Opciones</th>

                            </tfoot>

                        </table>

                    </div>

                    <div class="panel-body" id="formularioregistros">

                        <form name="formulario" id="formulario" method="POST">

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

								<h4 style="background-color:#339FFF; padding:10px; border-radius:5px; color:white; font-weight:500;">Datos Generales <span id="leyenda" style="font-size: 12px;">(*) Campos obligatorios</span></h4><hr style="margin-bottom: 0px;">

							</div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                <input type="hidden" id="idaccion" name="idaccion">

                                <label>Acción(*):</label>

                                <!--<input type="text" class="form-control" id="nombreaccion" name="nombreaccion" maxlength="255" placeholder="Nombre de la Acción" required>-->

                                <select id="nombreaccion" name="nombreaccion" class="form-control selectpicker" data-live-search="true" required>

									<option value="">--Seleccione Acción--</option>

									<option value='VER'>VER</option>

                                    <option value='AGREGAR'>AGREGAR</option>

									<option value='EDITAR'>EDITAR</option>

                                    <option value='ELIMINAR'>ELIMINAR</option>

									<option value='ACTIVAR'>ACTIVAR</option>

                                    <option value='DESACTIVAR'>DESACTIVAR</option>

                                    <option value='CAMBIAR_CONTRASEÑA'>CAMBIAR CONTRASEÑA</option>

                                    <option value='IMPRIMIR'>IMPRIMIR</option>

                                    <option value='MATRICULA'>ASIGNAR MATRICULA</option>

                                    <option value='CAMBIAR_ESTADO'>CAMBIAR ESTADO</option>

                                    <option value='EXPORTAR'>EXPORTAR</option>

								</select>

                            </div> 

                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                <label>Descripcion:</label>

                                <input type="text" class="form-control" id="descripcionacc" name="descripcionacc" maxlength="255" placeholder="Breve Descripción de la Acción">

                            </div>    

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <h4 style="background-color:#339FFF; padding:10px; border-radius:5px; color:white; font-weight:500;">Módulo al que pertenece <span id="leyenda" style="font-size: 12px;">(*) Campos obligatorios</span></h4><hr style="margin-bottom: 0px;">

                            </div>

                            <!--Se agrega el select Modulo -->

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <label>Modulo:</label>

                                <select id="idmodulo" name="idmodulo" class="form-control selectpicker" data-live-search="true" ></select>

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

<script type="text/javascript" src="scripts/accion.js"></script>

<?php

}

ob_end_flush();

?>