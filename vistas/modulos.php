<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['031MOD']) && $_SESSION['031MOD']==1){
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
        <li class="active">Modulos</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title">Modulos 
							<?php
								if(isset($_SESSION['086AGR31']) && $_SESSION['086AGR31']==1){ ?>
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
                                <th>Modulo</th>   
                                <th>Descripcion</th>                           
                                <th>Opciones</th>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <th>No</th>
                                <th>Modulo</th>   
                                <th>Descripcion</th>                               
                                <th>Opciones</th>
                            </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4 style="background-color:#339FFF; padding:10px; border-radius:5px; color:white; font-weight:500;">Datos Generales <span id="leyenda" style="font-size: 12px;">(*) Campos obligatorios</span></h4><hr style="margin-bottom: 0px;">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input type="hidden" id="idmodulo" name="idmodulo">
                                        <label>Nombre(*):</label>
                                        <input type="text" class="form-control" id="nombremod" name="nombremod" maxlength="255" placeholder="Nombre del Módulo" required>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Descripcion(*):</label>
                                        <input type="text" class="form-control" id="descripcionmod" name="descripcionmod" maxlength="255" placeholder="Breve Descripción del Módulo" required>
                                    </div>
                                </div> 
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="border-left-style: double; border-color: darkblue;">  
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4 style="background-color:#339FFF; padding:10px; border-radius:5px; color:white; font-weight:500;">Tipo de Módulo <span id="leyenda" style="font-size: 12px;">(*) Campos obligatorios</span></h4><hr style="margin-bottom: 0px;">
                                    </div>
                                    <!--Se agrega el input Modulo Padre o hijo -->
                                    <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                        <label>Tipo de Módulo(*):</label>
                                        <select id="padreohijo" name="padreohijo" class="form-control selectpicker" data-live-search="true" required>
                                            <option value="">-- Seleccionar Tipo --</option>
                                            <option value="1">Padre</option>
                                            <option value="0">Hijo</option>
                                        </select>
                                    </div>
                                    <!--Se agrega el select Modulo Padre -->
                                    <div class="form-group col-lg-9 col-md-6 col-sm-6 col-xs-12">
                                        <label>Modulo Padre(*):</label>
                                        <select id="idmodpadre" name="idmodpadre" class="form-control selectpicker" data-live-search="true" >
                                        </select>
                                    </div>
                                </div>      
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
<script type="text/javascript" src="scripts/modulos.js"></script>
<?php
}
ob_end_flush();
?>