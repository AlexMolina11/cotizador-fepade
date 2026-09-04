<?php
require_once '../config/global.php';
if(strlen(session_id())<1)    
    session_start();
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- codigo para evitar el cache en HTML -->
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">

  <title><?php echo PRO_NOMBRE ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="../public/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../public/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../public/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../public/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="../public/dist/css/skins/skin-blue.min.css">
  <link rel="stylesheet" href="../public/dist/css/skins/skin-black-light.css">
  <link rel="stylesheet" href="../public/dist/css/skins/skin-purple.min.css">
  <link rel="stylesheet" href="../public/dist/css/skins/skin-red.min.css">
  <link rel="stylesheet" href="../public/dist/css/skins/skin-green.min.css">
  <link rel="stylesheet" href="../public/dist/css/skins/skin-yellow.min.css">
  <!--CSS PERSONALIZADO-->
  <!--<link rel="stylesheet" href="../public/dist/css/personalizado.css">-->
  <!--DATATABLES-->
  <link rel="stylesheet" href="../public/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="../public/DataTables/Buttons-1.5.4/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="../public/DataTables/Responsive-2.2.2/css/responsive.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="../public/bootstrap-select-1.12.2/dist/css/bootstrap-select.css">
  <link rel="stylesheet" type="text/css" href="../public/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
  <link rel="stylesheet" type="text/css" href="../public/css/jquery.timepicker.css"/>

  <!-- Alerts --> 
  <link rel="stylesheet" href="../public/css/sweetalert2.min.css?n=1">

  <!-- Jquery -->
  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>

  <!-- TextArea Editor -->
  <!--<script src="//cdn.ckeditor.com/4.20.0/basic/ckeditor.js"></script>-->
  <script src="//cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>

  <!-- CSS de intl-tel-input -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.min.css"/>

  <!-- JS de intl-tel-input -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>

  <!-- Utils (para formateo y validación) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>

</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="menu.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">
        <img src="../public/images/logosfepade/Isotipo_FEPADE_Blanco.png" alt="" width="auto" height="20px">
        <!--<h3 width="40px" height="20px" style="color:white; font-size:15px; font-weight:900; margin-top:16px; font-family: unset;">EyC</h3>-->
      </span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">
        <img src="../public/images/logosfepade/Logo_FEPADE_horizontal_ISO_Blanco.png" alt="" width="auto" height="30px" style="margin-top: -5px;">
        <!--<h3 style="color:white; font-size:15px; font-weight:900; margin-top:16px; font-family: unset; text-transform: uppercase;">Educar y Convivir</h3>-->
      </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="color:white;">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="../public/images/user.png" width="50px" height="50px" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="../public/images/user.png" class="img-circle" alt="User Image">

                <p>
                <?php echo $_SESSION['nombre']; ?>
                  <!--<small><?php //echo $_SESSION['rol']; ?></small>-->
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <!--Solo los usuarios Administradores deben tener la opción de cambiar Clave-->
                  <?php if($_SESSION['idrol']==1){ ?>
                    <a href="#" class="btn btn-default btn-flat" data-toggle="modal" data-target="#modal-default">Cambiar Clave</a>
                  <?php } ?>
                </div>
                <div class="pull-right">
                  <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat">Cerrar</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  
  <!--MODAL PARA CAMBIAR CLAVE-->
  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Cambiar Clave</h4>
        </div>
        <div class="modal-body">
            
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <input type="hidden" id="usuariosusu" name="usuariosusu" value="<?php echo $_SESSION['idusuariosusu'];?>">
                      <input type="password" class="form-control" id="newclave" name="newclave" maxlength="100" placeholder="Nueva Clave" required>
                  </div>           
            
                <div class="modal-footer">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary" id="btnCambiar" name="btnCambiar" onclick="CambiarClave()">Cambiar Clave</button>
                </div>
              </div>
            
          
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
        
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
        <!-- Optionally, you can add icons to the links -->
        
        <li class="active"><a href="menu.php"><i class="fa fa-align-left"></i> <span>Inicio</span></a></li>

        <?php if(isset($_SESSION["043COT"])&&$_SESSION["043COT"]==1){ ?>
            <li class="treeview">
              <a href="#"><img src="../public/iconos/actividades.png" alt="Cotizaciones" style="width: 16px; height: 16px;"> <span>Cotizaciones</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
              </a>
              <ul class="treeview-menu">
                <?php if($_SESSION['044COT'] == 1){ ?> 
                  <li><a href="cotizacion.php"><i class="fa fa-circle-o"></i>Cotizaciones</a></li>
                <?php } else { } ?> 
                <?php if($_SESSION['045DET'] == 1){ ?> 
                  <li><a href="cotizacion_detalle.php"><i class="fa fa-circle-o"></i>Detalle de Cotizaciones</a></li>
                <?php } else { } ?> 
              </ul>
            </li>
        <?php } else { } ?>

        <?php if(isset($_SESSION["041DAS"])&&$_SESSION["041DAS"]==1){ ?>
            <li class="treeview">
              <a href="dashboards.php">
                <img src="../public/iconos/catalogo.png" alt="Dashboards" style="width: 16px; height: 16px;">
                <span>Dashboards</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                  <?php if($_SESSION['046DAS'] == 1){ ?> 
                    <li><a href="cot_dashboard.php"><i class="fa fa-circle-o"></i>Dashboard General</a></li>
                  <?php } else { } ?> 
                  <!--<li><a href="repActividad.php"><i class="fa fa-circle-o"></i>Reporte de Actividades</a></li>
                  <li><a href="repAlianzas.php"><i class="fa fa-circle-o"></i>Reporte de Alianzas</a></li>
                  <li><a href="repEntregas.php"><i class="fa fa-circle-o"></i>Reporte Entregas Realizadas</a></li>
                  <li><a href="repAsistencias.php"><i class="fa fa-circle-o"></i>Reporte de Asistencias</a></li>
                  <li><a href="repGeneral.php"><i class="fa fa-circle-o"></i>Reporte General</a></li>                
                  <li><a href="repVulnerables.php"><i class="fa fa-circle-o"></i>Reporte de Vulnerables</a></li>-->
              </ul>
            </li>
        <?php } else { } ?>

        <li class="active"><a><i class="fa fa-gear"></i> <span>Configuración</span></a></li>

        <?php if(isset($_SESSION["008CAT"])&&$_SESSION["008CAT"]==1){ ?>
            <li class="treeview">
              <a href="catalogos.php">
                <img src="../public/iconos/catalogo.png" alt="Catálogos" style="width: 16px; height: 16px;">
                <span>Catálogos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="active treeview-menu">
                <?php if($_SESSION['024ORG'] == 1){ ?> 
                  <li><a href="organizacionejecutora.php"><i class="fa fa-circle-o"></i>Organización Ejecutora</a></li>
                <?php } else { } ?> 
                <?php if($_SESSION['025ÁR'] == 1){ ?> 
                  <li><a href="arearesponsable.php"><i class="fa fa-circle-o"></i>Área Responsable</a></li>
                <?php } else { } ?>
              </ul>
            </li>
        <?php } else { } ?> 

        <?php if(isset($_SESSION["010USU"])&&$_SESSION["010USU"]==1){ ?>
          <li class="treeview">
            <a href="#"><img src="../public/iconos/usuario.png" alt="Usuarios" style="width: 16px; height: 16px;"> <span>Usuarios</span>
              <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu"> 
              <?php if($_SESSION['032ROL'] == 1){ ?> 
                <li><a href="roles.php"><i class="fa fa-circle-o"></i> <span>Roles</span></a></li>
              <?php } else { } ?> 
              <?php if($_SESSION['033USU'] == 1){ ?> 
                <li><a href="usuario.php"><i class="fa fa-circle-o"></i> <span>Usuarios</span></a></li>
              <?php } else { } ?> 
              <?php if($_SESSION['031MOD'] == 1){ ?> 
                <li><a href="modulos.php"><i class="fa fa-circle-o"></i> <span>Modulos</span></a></li>
              <?php } else { } ?>
              <?php if($_SESSION['034ACC'] == 1){ ?> 
                <li><a href="acciones.php"><i class="fa fa-circle-o"></i> <span>Acciones</span></a></li>
              <?php } else { } ?> 
            </ul>
          </li>
        <?php } else { } ?>  
        
        <!--<li class="active">
          <a href="../manual/Manual de Usuario Sistema ECO.pdf" target="_blank">
            <i class="fa fa-plus-square"></i> <span>Ayuda</span>
            <small class="label pull-right bg-red">PDF</small>
          </a>
        </li>-->
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>