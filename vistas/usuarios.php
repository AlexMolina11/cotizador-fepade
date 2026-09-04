<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['010USU']) && $_SESSION['010USU']==1){
?>
<!--CONTENIDO-->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<br>
				<!-- /.box-header-->
				<div class="panel-primary" style="margin-bottom: 10px;">
					<h1 class="welcome-title">Bienvenido al área de Control</h1>    
					<p class="welcome-subtitle">Aquí están todos las confuguraciones y controles del sistema.</p>   
				</div>

				<!-- centro -->
				<div class="panel-primary" >
					<h4>Bienvenido al área de administración de usuarios, aquí podra encontrar:</h4>
					
					<ul class="list-group"> 
					<?php if($_SESSION['032ROL'] == 1){ ?> 
						<li class="list-group-item" style="font-size: 20px;"><a href="roles.php"><i class="fa fa-male"></i> Roles</a> <i class="fa fa-long-arrow-right"></i> Creación de nuevos perfiles de usuarios.</li>
					<?php } else { } ?>
					<?php if($_SESSION['033USU'] == 1){ ?> 
						<li class="list-group-item" style="font-size: 20px;"><a href="usuario.php"><i class="fa fa-user"></i> Usuarios</a> <i class="fa fa-long-arrow-right"></i> Creación de nuevos usuarios.</li>
					<?php } else { } ?> 
					<?php if($_SESSION['031MOD'] == 1){ ?> 
						<li class="list-group-item" style="font-size: 20px;"><a href="modulos.php"><i class="fa fa-th"></i> Modulos</a> <i class="fa fa-long-arrow-right"></i> Creación de nuevos Modulos para el sistema.</li>	
					<?php } else { } ?> 
					<?php if($_SESSION['034ACC'] == 1){ ?> 
						<li class="list-group-item" style="font-size: 20px;"><a href="acciones.php"><i class="fa fa-edit"></i> Acciones</a> <i class="fa fa-long-arrow-right"></i> Creación de nuevas acciones para el usuario.</li>
					<?php } else { } ?> 
					</ul>
				</div>
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
                    // Si la sesión ha expirado, redirige al usuario al login
                    window.location.href = 'login.html';
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Verificar la sesión cada 20 minutos (120,000 milisegundos)
    setInterval(checkSession, 1200000); 

    // Verificar la sesión cada 10 Minuto (60,000 milisegundos)
    //setInterval(checkSession, 60000); 
</script>
<!--Fin-Contenido-->
<?php
}else{
    require 'noacceso.php';
}
require 'footer.php';
}
ob_end_flush();
?>