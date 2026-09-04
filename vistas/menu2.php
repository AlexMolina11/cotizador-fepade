<?php
require_once '../config/global.php';
ob_start();
session_start();
?>
<!DOCTYPE html>
<html>
    <head><meta charset="gb18030">
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo PRO_NOMBRE ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="../public/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../librerias/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <!-- Se puso el archivo AdminLTE.min.css para web -->
        <!--<link rel="stylesheet" href="../librerias/Adminlte/css/adminLTE.min.css">-->
        <link rel="stylesheet" href="../librerias/Adminlte/css/AdminLTE.min.css">
        <style>
        .imagen:hover {filter: opacity(.5);}
        </style>
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <style>
            .center {
                margin-left: auto;
                margin-right: auto;
                display: block;
            }
            #contenedor {height: 100px;margin:0;}
            #col_der, #col_izq, #col_cen {height: 100%;}
            #col_der {float: right; width: 200px;/*background-color: #fff;*/}
            #col_izq {float: left; width: 200px;/*background-color: #ccc;*/}
            #col_cen {/*background-color: #ccc;*/}

            .logout-btn {
                background: rgba(255, 255, 255, 0.1);
                border: 2px solid rgba(255, 255, 255, 0.2);
                color: var(--white);
                padding: 0.5rem 1.5rem;
                border-radius: 25px;
                font-weight: 500;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                backdrop-filter: blur(10px);
            }

            .logout-btn:hover {
                background: rgba(255, 255, 255, 0.2);
                border-color: rgba(255, 255, 255, 0.3);
                color: var(--white);
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
            }

            .logout-btn i {
                font-size: 0.9rem;
            }
            
             /* Responsive Design */
            @media (max-width: 768px) {
                .logout-btn {
                    padding: 0.4rem 1rem;
                    font-size: 0.9rem;
                }
            }

            .logout-btn:focus {
                outline: 2px solid var(--white);
                outline-offset: 2px;
            }


        </style>
    </head>
    <body>
        <div class="wrapper">
            <header>
                <nav class="navbar navbar-expand-lg navbar-light" style="border-bottom: solid; border-bottom-color: #CC8E00; background-color: #B0291C;">
                    <a class="navbar-brand" href="#">
                        <img src="../public/images/logosfepade/Logo_FEPADE_horizontal_ISO_Blanco.png" width="auto" height="40px" alt="" ALIGN="LEFT" />
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent" style="text-align: center;">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active"></li>
                        </ul>
                        <div class="col-lg-1 col-md-2 col-sm-6 col-xs-6" >
                            <a href="../ajax/usuario.php?op=salir" class="logout-btn" aria-label="Cerrar Sesión">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Cerrar Sesión</span>
                            </a>
                        </div>
                    </div>
                </nav>
            </header>
            <!-- <div class="content-wrapper">-->
            <!-- Main content -->
            <section class="content" style="padding-top: 0px;">
                <div class="card">
                    <div class="card-header">
                        <h3 style="margin-bottom: 0px;" class="card-title">Bienvenid@s al sistema de Proyectos de FEPADE</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                             if(isset($_SESSION["008CAT"])&&$_SESSION["008CAT"]==1){ ?>
                             
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="card btn">
                                        <a href="catalogos.php" class="small-box-footer">
                                            <!-- small card -->
                                            <div class="small-box bg-black" style="border-radius:8px;">
                                                <div class="inner">
                                                    <h3>Catálogos</h3>
                                                    <p>Mantenimientos</p>
                                                </div>
                                                <div class="icon">
                                                    <!--<i class="fa fa-book" style="color:white; font-size: xxx-large; font-size: xxx-large;"></i>-->
                                                    <img src="https://educaryconvivir.fepade.org.sv/EducarConvivir/public/iconos/catalogo.png" alt="Cat��logos" style="width: 50px; height: 50px;">
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            <?php } else { } ?>
                            <?php if(isset($_SESSION["005PAR"])&&$_SESSION["005PAR"]==1){ ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="card btn">
                                        <a href="participante.php" class="small-box-footer">
                                            <!-- small card -->
                                            <div class="small-box bg-navy" style="border-radius:8px;">
                                                <div class="inner">
                                                    <h3>Participantes</h3>
                                                    <p>Asignar a Grupos</p>
                                                </div>
                                                <div class="icon">
                                                    <!--<i class="fa fa-users" style="color:white; font-size: xxx-large;"></i>-->
                                                    <img src="https://educaryconvivir.fepade.org.sv/EducarConvivir/public/iconos/participe.png" alt="Cat��logos" style="width: 50px; height: 50px;">
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            <?php } else { } ?>
                            <?php if(isset($_SESSION["004ACT"])&&$_SESSION["004ACT"]==1){ ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="card btn">  
                                        <a href="actividades.php" class="small-box-footer">
                                            <!-- small card -->
                                            <div class="small-box bg-blue" style="border-radius:8px;">
                                                <div class="inner">
                                                    <h3>Actividades</h3>
                                                    <p>Jornadas y Asistencias</p>
                                                </div>
                                                <div class="icon">
                                                    <!--<i class="fa fa-list-alt" style="color:white; font-size: xxx-large;"></i>-->
                                                    <img src="https://educaryconvivir.fepade.org.sv/EducarConvivir/public/iconos/actividades.png" alt="Cat��logos" style="width: 50px; height: 50px;">
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            <?php } else { } ?>
                            <?php if(isset($_SESSION["002PLA"])&&$_SESSION["002PLA"]==1){ ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="card btn">  
                                        <a href="plan.php" class="small-box-footer">
                                            <!-- small card -->
                                            <div class="small-box bg-light-blue" style="border-radius:8px;">
                                                <div class="inner">
                                                    <h3>Plan</h3>
                                                    <p>Registro</p>
                                                </div>
                                                <div class="icon">
                                                    <!--<i class="fa fa-dot-circle-o" style="color:white; font-size: xxx-large;"></i>-->
                                                    <img src="https://educaryconvivir.fepade.org.sv/EducarConvivir/public/iconos/plan2.png" alt="Cat��logos" style="width: 50px; height: 50px;">
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            <?php } else { } ?>
                            <?php if(isset($_SESSION["006ALI"])&&$_SESSION["006ALI"]==1){ ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="card btn">  
                                        <a href="alianza.php" class="small-box-footer">
                                            <!-- small card -->
                                            <div class="small-box bg-teal" style="border-radius:8px;">
                                                <div class="inner">
                                                    <h3>Alianza</h3>
                                                    <p>y Apalancamiento</p>
                                                </div>
                                                <div class="icon">
                                                    <!--<i class="fa fa-exchange" style="color:white; font-size: xxx-large;"></i>-->
                                                    <img src="https://educaryconvivir.fepade.org.sv/EducarConvivir/public/iconos/alianzas3.png" alt="Cat��logos" style="width: 50px; height: 50px;">
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            <?php } else { } ?>
                            <?php if(isset($_SESSION["007ACT"])&&$_SESSION["007ACT"]==1){ ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="card btn">  
                                        <a href="entregas.php" class="small-box-footer">
                                            <!-- small card -->
                                            <div class="small-box bg-olive" style="border-radius:8px;">
                                                <div class="inner">
                                                    <h3>Actas</h3>
                                                    <p>Registro</p>
                                                </div>
                                                <div class="icon">
                                                    <!--<i class="fa fa-bookmark" style="color:white; font-size: xxx-large;"></i>-->
                                                    <img src="https://educaryconvivir.fepade.org.sv/EducarConvivir/public/iconos/actas.png" alt="Cat��logos" style="width: 50px; height: 50px;">
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            <?php } else { } ?>
                            <?php if(isset($_SESSION["009REP"])&&$_SESSION["009REP"]==1){ ?>
                                <!--<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="card btn">  
                                        <a href="reporte.php" class="small-box-footer">-->
                                            <!-- small card -->
                                            <!--<div class="small-box bg-green" style="border-radius:8px;">
                                                <div class="inner">
                                                    <h3>Reportes</h3>
                                                    <p>Exportar datos</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fa fa-table" style="color:white; font-size: xxx-large;"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>-->
                                <!-- ./col -->
                            <?php } else { } ?>
                            <?php if(isset($_SESSION["010USU"])&&$_SESSION["010USU"]==1){ ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="card btn">  
                                        <a href="usuarios.php" class="small-box-footer">
                                            <!-- small card -->
                                            <div class="small-box bg-purple" style="border-radius:8px;">
                                                <div class="inner">
                                                    <h3>Usuarios</h3>
                                                    <p>Registro</p>                                                    
                                                </div>
                                                <div class="icon">
                                                    <!--<i class="fa fa-users" style="color:white; font-size: xxx-large;"></i>-->
                                                    <img src="https://educaryconvivir.fepade.org.sv/EducarConvivir/public/iconos/usuario.png" alt="Cat��logos" style="width: 50px; height: 50px;">
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            <?php } else { } ?>
                            <?php if(isset($_SESSION["035ENL"])&&$_SESSION["035ENL"]==1){ ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="card btn">  
                                        <a href="https://fepadeedu-my.sharepoint.com/:o:/r/personal/manuel_cuellar_fepade_edu_sv/Documents/Educar%20y%20Convivir/Repositorio/Enlaces?d=w8c210f3171f246b5b3bf7e0528600ccf&csf=1&web=1" target="_blank" class="small-box-footer">
                                            <!-- small card -->
                                            <div class="small-box bg-red" style="border-radius:8px;">
                                                <div class="inner">
                                                    <h3>Enlaces</h3>
                                                    <p>Ir ahora</p>
                                                </div>
                                                <div class="icon">
                                                    <!--<i class="fa fa-link" style="color:white; font-size: xxx-large;"></i>-->
                                                    <img src="https://educaryconvivir.fepade.org.sv/EducarConvivir/public/iconos/enlaces2.png" alt="Cat��logos" style="width: 50px; height: 50px;">
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            <?php } else { } ?>
                            <?php if(isset($_SESSION["041DAS"])&&$_SESSION["041DAS"]==1){ ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="card btn">  
                                        <a href="dashboards.php" class="small-box-footer">
                                            <!-- small card -->
                                            <div class="small-box bg-orange" style="border-radius:8px;">
                                                <div class="inner">
                                                    <h3>Dashboard</h3>
                                                    <p>Ver estadisticas</p>
                                                </div>
                                                <div class="icon">
                                                    <!--<i class="fa fa-link" style="color:white; font-size: xxx-large;"></i>-->
                                                    <img src="https://educaryconvivir.fepade.org.sv/EducarConvivir/public/iconos/analisis-de-los-datos.png" alt="Cat��logos" style="width: 50px; height: 50px;">
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            <?php } else { } ?>
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <hr>
                <!--<div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-0 col-xs-0">

                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                        <div class="card" style="border:none;">
                            <div class="card-body">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6" style="text-align: -webkit-center;">
                                    <img src="../public/images/logo-FEPADE-flat.png" width="50%" style="margin-top:10px; margin-bottom: 10px;"/>
                                </div>    
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6" style="text-align: -webkit-center;">
                                    <img class="center-td" src="../public/images/logo-USAID-flat.png" width="50%" style="margin-bottom: 10px; display:none;"/>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="text-align: -webkit-center;">
                                    <img src="../public/images/Logotipo-04.png" width="50%" alt="" style="margin-top:-10px; margin-bottom: 10px;"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-0 col-xs-0">
                        
                    </div>
                </div>-->
            </section><!-- /.content -->

        <!--</div>--><!-- /.content-wrapper -->
        </div>
        <!-- jQuery 3 -->
        <script src="../public/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="../public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <!-- jQuery -->
        <script src="../librerias/Adminlte/jquery/jquery.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../librerias/Adminlte/js/adminlte.min.js"></script>

    </body>
</html>
<?php

ob_end_flush();
?>