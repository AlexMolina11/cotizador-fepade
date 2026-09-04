<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['033USU']) && $_SESSION['033USU']==1){
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
        <li><a href="#">Usuarios</a></li>
        <li class="active">Usuarios</li>
      </ol>
    </section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h1 class="box-title">Usuario 
							<?php
								if(isset($_SESSION['092AGR33']) && $_SESSION['092AGR33']==1){ ?>
									<button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)" data-toggle="tooltip" data-placement="top" title="Agregar Usuario"> <i class="fa fa-plus-circle"></i> Agregar</button></h1>
							<?php } ?>
						</h1>
                    	<div class="box-tools pull-right"></div>
					</div>
					<!-- /.box-header-->
					<!-- centro -->
					<div class="panel-body table-reponsive"  id="listadoregistros">
						<table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							
							<th>Nombre</th>
                            <th>Email</th>
                            <th>Login</th>
                            <th>Estado</th>
                            <th>Opciones</th>
						</thead>
						<tbody></tbody>
						<tfoot>
							
							<th>Nombre</th>
                            <th>Email</th>
                            <th>Login</th>
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
								<input type="hidden" id="idusuariosusu" name="idusuariosusu">
								<input type="text" class="form-control" id="nombreusu" name="nombreusu" maxlength="100" placeholder="Nombre" required>
							</div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Email:</label>
								<input type="text" class="form-control" id="email" name="email" maxlength="50" placeholder="Email">
							</div>
							<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Teléfono:</label>
								<input type="text" class="form-control" id="telusu" name="telusu" maxlength="9" placeholder="Número de celular">
							</div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Extensión fijo:</label>
								<input type="text" class="form-control" id="extensionusu" name="extensionusu" maxlength="9" placeholder="Número de extensión fijo">
							</div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label>Login(*):</label>
                                <input type="text" class="form-control" id="login" name="login" maxlength="20" placeholder="Login" required>
							</div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label id="labelclave">Clave(*):</label>
                                <input type="password" class="form-control" id="clave" name="clave" maxlength="64" placeholder="Clave" required>
							</div>
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h4 style="background-color:#339FFF; padding:10px; border-radius:5px; color:white; font-weight:500;">Organización y Roles <span id="leyenda" style="font-size: 12px;">(*) Campos obligatorios</span></h4><hr style="margin-bottom: 0px;">
							</div>
							<div class="form-group col-lg-2 col-md-4 col-sm-12 col-xs-12">
								<label>Organización Ejecutora:</label>
								<select class="form-control select-picker" name="orgejecutora" id="orgejecutora"  data-live-search="true" required></select>
							</div>
							<div class="form-group col-lg-2 col-md-6 col-sm-6 col-xs-12">
								<label>Rol(*):</label>
								<select id="idrol" name="idrol" class="form-control selectpicker" data-live-search="true" required>
								</select>
							</div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<button class="btn btn-primary" type="submit" id="btnGuardar" name="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
								<button class="btn btn-danger" type="button" onclick="cancelarform()" id="btnCancelar" name="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
							</div>
						</form>
                                            
					</div>
					<div class="panel-body" id="frmnuevaclave">
					<form name="actclave" id="actclave" method="POST">
					<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label>Nombre de usuario:</label>
						<input type="text" class="form-control" id="usu" name="usu" maxlength="100" placeholder="Nombre">
					</div>	
                	<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<label>Nueva Clave(*):</label>
						<input type="hidden" id="idusuario" name="idusuario">
						<input type="password" class="form-control" id="passw" name="passw" maxlenght="200" placeholder="Nueva Clave">
					</div>
					<div id="guardar" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<button class="btn btn-primary" type="submit" id="btnCambio" name="btnCambio"><i class="fa fa-save"></i> Cambiar</button>
						<button class="btn btn-danger" type="button" onclick="cancelaform()" id="btnCancel" name="btnCancel"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
    function checkSession() {
        fetch('../config/session_check.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'timeout' || data.status === 'no_session') {
                    // Si la sesi��n ha expirado, redirige al usuario al login
                    window.location.href = 'login.html';
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Verificar la sesi��n cada 20 minutos (120,000 milisegundos)
    setInterval(checkSession, 1200000); 

    // Verificar la sesi��n cada 10 Minuto (60,000 milisegundos)
    //setInterval(checkSession, 60000); 
</script>
<!--Fin-Contenido-->
<?php
}else{
    require 'noacceso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/usuario.js" ></script>
<?php
}
ob_end_flush();
?>