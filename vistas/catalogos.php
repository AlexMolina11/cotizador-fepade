<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION["nombre"])){
    header("Location: login.html");
}else{
require 'header.php';

if(isset($_SESSION['008CAT']) && $_SESSION['008CAT']==1){
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
            <h1 class="welcome-title">Bienvenido al área de catálogos</h1>    
            <p class="welcome-subtitle">Aquí puedes registrar todos los datos que complementarán el sistema.</p>   
          </div>
					<!-- centro -->
					<div class="panel-primary" >
						   
                        <ul class="list-group">
                          <?php if($_SESSION['011COM']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="comunidad.php"><i class="fa fa-users"></i> Comunidad</a> <i class="fa fa-long-arrow-right"></i> Registro de Comunidades participantes</li>
                          <?php } else { } ?>  
                          <?php if($_SESSION['040CAN']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="cantonesycaserios.php"><i class="fa fa-map-signs"></i> Cantones y caseríos</a> <i class="fa fa-long-arrow-right"></i> Registro de Cantones y caseríos</li>
                          <?php } else { } ?>  
                          <?php if($_SESSION['012COR']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="corredor.php"><i class="fa fa-home"></i> Corredores</a> <i class="fa fa-long-arrow-right"></i> Registro de Corredores</li>
                            <?php } else { } ?>
                          <?php if($_SESSION['013CEN']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="institucion.php"><i class="fa fa-university"></i> Centros Educativos</a> <i class="fa fa-long-arrow-right"></i> Registro de Centros Educativos</li>
                          <?php } else { } ?>
                          <?php if($_SESSION['014TIP']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="tipoactividad.php"><i class="fa fa-list-alt"></i> Tipo de actividad</a> <i class="fa fa-long-arrow-right"></i> Registro de Tipos de Actividad</li>
                          <?php } else { } ?>
                          <?php if($_SESSION['015CAT']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="categoriaactividad.php"><i class="fa fa-file-text-o"></i> Categoría de actividad</a> <i class="fa fa-long-arrow-right"></i> Registro de Categorías de Actividades</li>
                          <?php } else { } ?>
                          <?php if($_SESSION['016TIP']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="tipoorganizacion.php"><i class="fa fa-industry"></i> Tipo Organización</a> <i class="fa fa-long-arrow-right"></i> Registro de Tipo de Organización</li>  <!-- Se cambió Organización por Alianza -->
                          <?php } else { } ?>
                          <?php if($_SESSION['020RES']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="responsables.php"><i class="fa fa-user"></i> Facilitadores</a> <i class="fa fa-long-arrow-right"></i> Registro de Facilitadores</li>
                          <?php } else { } ?>
                          <?php if($_SESSION['021GRU']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="grupo.php"><i class="fa fa-th-large"></i> Grupos</a> <i class="fa fa-long-arrow-right"></i> Registro de Grupos</li>
                          <?php } else { } ?>
                          <?php if($_SESSION['022TIP']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="tipoparticipante.php"><i class="fa fa-users"></i> Tipo Participante</a> <i class="fa fa-long-arrow-right"></i> Tipo Participante</li>
                          <?php } else { } ?>
                          <?php if($_SESSION['023DIS']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="discapacidad.php"><i class="fa fa-wheelchair"></i> Discapacidad</a> <i class="fa fa-long-arrow-right"></i> Registro de Discapacidad</li>
                          <?php } else { } ?>
                          <?php if($_SESSION['024ORG']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="organizacionejecutora.php"><i class="fa fa-sitemap"></i> Organización Ejecutora</a> <i class="fa fa-long-arrow-right"></i> Registro de organizaciones ejecutoras</li>
                          <?php } else { } ?>
                          <?php if($_SESSION['025ÁR']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="arearesponsable.php"><i class="fa fa-puzzle-piece"></i> Área Responsable</a> <i class="fa fa-long-arrow-right"></i> Registro de áreas responsables</li>
                          <?php } else { } ?>
                          <?php if($_SESSION['037TIP']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="tipoasistenciatecnica.php"><i class="fa fa-list-alt"></i> Tipo de Asistencias Técnicas</a> <i class="fa fa-long-arrow-right"></i> Registro de tipos de asistencias técnicas</li>
                          <?php } else { } ?>
                          <?php if($_SESSION['038CAT']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="categoriaasistenciatecnica.php"><i class="fa fa-file-text-o"></i> Categorías de Asistencias Técnicas</a> <i class="fa fa-long-arrow-right"></i> Registro de categorías de asistencias técnicas</li>
                          <?php } else { } ?>
                          <?php if($_SESSION['039AVA']==1){ ?>
                            <li class="list-group-item" style="font-size: 20px;"><a href="avanceasistenciatecnica.php"><i class="fa fa-check-square"></i> Avances de Asistencias Técnicas</a> <i class="fa fa-long-arrow-right"></i> Registro de avances de asistencias técnicas</li>
                          <?php } else { } ?>
                        </ul>           
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