<?php

require_once '../config/global.php';

ob_start();

session_start();

require_once "../config/Conexion.php";

$sql = "
    SELECT 
        e.IDESTADOCOT,
        e.NOMBREESTADOCOT,
        COUNT(c.IDCOTIZACION) AS total
    FROM cot_estados e
    LEFT JOIN cot_cotizaciones c 
        ON c.ESTADOCOT = e.IDESTADOCOT
    WHERE e.ESTADOESTCOT = 1
    GROUP BY e.IDESTADOCOT, e.NOMBREESTADOCOT
    ORDER BY e.IDESTADOCOT;
";

$result = $conexion->query($sql);

$estados = [];

while ($row = $result->fetch_assoc()) {
    $estados[] = $row;
}

$totalCotizaciones = 0;
foreach ($estados as $e) {
    $totalCotizaciones += (int)$e['total'];
}

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

        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    

    <!-- Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    

    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    

    <style>

        :root {

            --primary-red: #B0291C;

            --primary-gold: #CC8E00;

            --primary-green: #0ce98b8c;

            --background-bk-green: radial-gradient(circle at 100% 70.9666633605957%, rgba(12, 233, 139, 0.55) 0%, 10.5%, rgba(12, 233, 139, 0) 35%), radial-gradient(circle at 52.266674041748054% 38.43336423238119%, rgba(0, 0, 0, 0.99) 0%, 25%, rgba(0, 0, 0, 0) 50%), radial-gradient(circle at 0% 66.70000076293945%, rgba(12, 233, 139, 0.55) 0%, 11.4%, rgba(12, 233, 139, 0) 38%), radial-gradient(circle at 48.9013671875% 49.521484375%, #000000 0%, 100%, rgba(0, 0, 0, 0) 100%);

            --white: #ffffff;

            --black: #000000;

            --glass-bg: rgba(255, 255, 255, 0.08);

            --glass-border: rgba(255, 255, 255, 0.15);

            --text-primary: #ffffff;

            --text-secondary: rgba(255, 255, 255, 0.8);

            --text-muted: rgba(255, 255, 255, 0.6);

            --shadow-glow: 0 0 40px rgba(12, 233, 139, 0.15);

            --card-hover: rgba(255, 255, 255, 0.12);

        }



        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

        }



        body {

            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

            background: var(--background-bk-green);

            min-height: 100vh;

            overflow-x: hidden;

            position: relative;

        }



        /* Enhanced animated background elements */

        .bg-decoration {

            position: fixed;

            top: 0;

            left: 0;

            width: 100%;

            height: 100%;

            pointer-events: none;

            z-index: 1;

        }



        .floating-orb {

            position: absolute;

            border-radius: 50%;

            background: linear-gradient(45deg, rgba(12, 233, 139, 0.3), rgba(204, 142, 0, 0.2));

            filter: blur(1px);

            animation: float 12s ease-in-out infinite;

        }



        .orb-1 {

            width: 150px;

            height: 150px;

            top: 10%;

            right: 10%;

            animation-delay: 0s;

        }



        .orb-2 {

            width: 100px;

            height: 100px;

            bottom: 15%;

            left: 8%;

            animation-delay: 3s;

        }



        .orb-3 {

            width: 80px;

            height: 80px;

            top: 70%;

            right: 5%;

            animation-delay: 6s;

        }



        .orb-4 {

            width: 120px;

            height: 120px;

            top: 30%;

            left: 15%;

            animation-delay: 9s;

        }



        @keyframes float {

            0%, 100% { 

                transform: translateY(0px) translateX(0px) scale(1);

                opacity: 0.4;

            }

            25% { 

                transform: translateY(-30px) translateX(20px) scale(1.1);

                opacity: 0.6;

            }

            50% { 

                transform: translateY(-10px) translateX(-25px) scale(0.9);

                opacity: 0.5;

            }

            75% { 

                transform: translateY(20px) translateX(15px) scale(1.05);

                opacity: 0.7;

            }

        }



        /* Header Navigation */

        .header-nav {

            position: relative;

            z-index: 100;

            background: var(--glass-bg);

            backdrop-filter: blur(20px);

            border-bottom: 2px solid var(--glass-border);

            padding: 1rem 0;

            box-shadow: var(--shadow-glow);

        }



        .nav-container {

            max-width: 1200px;

            margin: 0 auto;

            padding: 0 2rem;

            display: flex;

            justify-content: space-between;

            align-items: center;

        }



        .logo-header {

            height: 45px;

            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));

        }



        .logout-btn {

            background: rgba(255, 255, 255, 0.1);

            border: 2px solid rgba(255, 255, 255, 0.2);

            color: var(--white);

            padding: 0.75rem 1.5rem;

            border-radius: 25px;

            font-weight: 600;

            transition: all 0.3s ease;

            text-decoration: none;

            display: inline-flex;

            align-items: center;

            gap: 0.5rem;

            backdrop-filter: blur(10px);

            font-size: 0.95rem;

        }



        .logout-btn:hover {

            background: rgba(255, 255, 255, 0.2);

            border-color: rgba(255, 255, 255, 0.3);

            color: var(--white);

            transform: translateY(-2px);

            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);

            text-decoration: none;

        }



        /* Main Content */

        .main-content {

            position: relative;

            z-index: 10;

            padding: 2rem;

            animation: slideUp 1s ease-out;

        }



        @keyframes slideUp {

            from {

                opacity: 0;

                transform: translateY(50px) scale(0.95);

            }

            to {

                opacity: 1;

                transform: translateY(0) scale(1);

            }

        }



        .welcome-section {

            max-width: 1200px;

            margin: 3rem auto 3rem;

            text-align: center;

        }



        .welcome-card {

            background: var(--glass-bg);

            backdrop-filter: blur(20px);

            border: 2px solid var(--glass-border);

            border-radius: 24px;

            padding: 2.5rem;

            box-shadow: var(--shadow-glow);

            position: relative;

            overflow: hidden;

        }



        .welcome-card::before {

            content: '';

            position: absolute;

            top: 0;

            left: 0;

            right: 0;

            height: 1px;

            background: linear-gradient(90deg, transparent, var(--primary-green), var(--primary-gold), transparent);

            animation: shimmer 4s linear infinite;

        }



        @keyframes shimmer {

            0% { transform: translateX(-100%); }

            100% { transform: translateX(100%); }

        }



        .welcome-title {

            color: var(--text-primary);

            font-size: 2.2rem;

            font-weight: 700;

            margin-bottom: 0.5rem;

            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);

            letter-spacing: -0.025em;

        }



        .welcome-subtitle {

            color: var(--text-secondary);

            font-size: 1.1rem;

            font-weight: 400;

            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);

        }



        /* Menu Grid */

        .menu-container {

            max-width: 1200px;

            margin: 0 auto;

        }



        .menu-grid {

            display: grid;

            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));

            gap: 2rem;

            padding: 1rem;

        }



        .menu-card {

            background: var(--glass-bg);

            backdrop-filter: blur(20px);

            border: 2px solid var(--glass-border);

            border-radius: 20px;

            padding: 0;

            transition: all 0.4s ease;

            position: relative;

            overflow: hidden;

            box-shadow: var(--shadow-glow);

            text-decoration: none;

            display: block;

            group: hover;

        }



        .menu-card:hover {

            transform: translateY(-8px) scale(1.02);

            background: var(--card-hover);

            border-color: rgba(255, 255, 255, 0.25);

            box-shadow: 

                var(--shadow-glow),

                0 20px 40px rgba(0, 0, 0, 0.4),

                0 0 50px rgba(12, 233, 139, 0.3);

            text-decoration: none;

        }



        .menu-card-content {

            padding: 2rem;

            display: flex;

            align-items: center;

            gap: 1.5rem;

            position: relative;

            z-index: 2;

        }



        .menu-icon {

            width: 60px;

            height: 60px;

            border-radius: 16px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 1.8rem;

            transition: all 0.3s ease;

            flex-shrink: 0;

        }



        .menu-icon img {

            width: 50px;

            height: 50px;

            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));

            transition: all 0.3s ease;

        }



        .menu-card:hover .menu-icon img {

            transform: scale(1.1);

            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.4));

        }



        .menu-text {

            flex: 1;

        }



        .menu-title {

            color: var(--text-primary);

            font-size: 1.4rem;

            font-weight: 700;

            margin-bottom: 0.25rem;

            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);

            transition: color 0.3s ease;

        }



        .menu-description {

            color: var(--text-secondary);

            font-size: 0.95rem;

            font-weight: 400;

            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);

            transition: color 0.3s ease;

        }



        .menu-card:hover .menu-title {

            color: var(--primary-green);

        }



        .menu-card:hover .menu-description {

            color: var(--text-primary);

        }



        /* Color themes for different modules */

        .menu-card.bg-black { --module-color: #000000; }

        .menu-card.bg-navy { --module-color: #001f3f; }

        .menu-card.bg-blue { --module-color: #0074D9; }

        .menu-card.bg-light-blue { --module-color: #7FDBFF; }

        .menu-card.bg-teal { --module-color: #39CCCC; }

        .menu-card.bg-olive { --module-color: #3D9970; }

        .menu-card.bg-purple { --module-color: #B10DC9; }

        .menu-card.bg-red { --module-color: #FF4136; }

        .menu-card.bg-orange { --module-color: #FF851B; }



        .menu-card::after {

            content: '';

            position: absolute;

            top: 0;

            left: 0;

            right: 0;

            bottom: 0;

            background: linear-gradient(135deg, var(--module-color, transparent) 0%, transparent 50%);

            opacity: 0.05;

            transition: opacity 0.3s ease;

            z-index: 1;

        }



        .menu-card:hover::after {

            opacity: 0.15;

        }



        /* Responsive Design */

        @media (max-width: 768px) {

            .nav-container {

                padding: 0 1rem;

            }

            

            .main-content {

                padding: 1rem;

            }



            .menu-grid {

                grid-template-columns: 1fr;

                gap: 1.5rem;

                padding: 0;

            }



            .welcome-card {

                padding: 2rem;

            }



            .welcome-title {

                font-size: 1.8rem;

            }



            .menu-card-content {

                padding: 1.5rem;

                gap: 1rem;

            }



            .menu-icon {

                width: 50px;

                height: 50px;

            }



            .menu-icon img {

                width: 40px;

                height: 40px;

            }



            .menu-title {

                font-size: 1.2rem;

            }



            .logout-btn {

                padding: 0.6rem 1.2rem;

                font-size: 0.9rem;

            }

        }



        @media (max-width: 480px) {

            .welcome-title {

                font-size: 1.6rem;

            }



            .welcome-subtitle {

                font-size: 1rem;

            }



            .menu-card-content {

                flex-direction: column;

                text-align: center;

                gap: 1rem;

            }



            .menu-icon {

                width: 60px;

                height: 60px;

            }



            .menu-icon img {

                width: 45px;

                height: 45px;

            }

        }



        /* Reduce motion for accessibility */

        @media (prefers-reduced-motion: reduce) {

            * {

                animation-duration: 0.01ms !important;

                animation-iteration-count: 1 !important;

                transition-duration: 0.01ms !important;

            }

        }



        /* Focus styles for accessibility */

        .menu-card:focus,

        .logout-btn:focus {

            outline: 3px solid var(--primary-green);

            outline-offset: 4px;

        }

    </style>

</head>

<body>

    <div class="bg-decoration">

        <div class="floating-orb orb-1"></div>

        <div class="floating-orb orb-2"></div>

        <div class="floating-orb orb-3"></div>

        <div class="floating-orb orb-4"></div>

    </div>



    <!-- Header Navigation -->

    <nav class="header-nav">

        <div class="nav-container" style="margin-top: 10px; margin-bottom:10px;">

            <img src="../public/images/logosfepade/Logo_FEPADE_horizontal_ISO_Blanco.png" alt="FEPADE" class="logo-header">

            <a href="../ajax/usuario.php?op=salir" class="logout-btn" aria-label="Cerrar Sesión">

                <i class="fas fa-sign-out-alt"></i>

                <span>Cerrar Sesión</span>

            </a>

        </div>

    </nav>



    <!-- Main Content -->

    <main class="main-content">

        <!-- Welcome Section -->

        <section class="welcome-section">

            <div class="welcome-card">

                <h1 class="welcome-title">Sistema de Cotizaciones FEPADE</h1>

                <p class="welcome-subtitle">Seleccione el módulo al que desea acceder</p>

            </div>

        </section>


        <!-- Menu Grid -->

        <div class="menu-container">

            <div class="menu-grid">

                <!-- Cotizaciones -->

                <?php if(isset($_SESSION["043COT"])&&$_SESSION["043COT"]==1){ ?>

                <a href="cotizaciones.php" class="menu-card">

                    <div class="menu-card-content" style="background-color: black;">

                        <div class="menu-icon">

                            <img src="../public/iconos/actividades.png" alt="Cotizaciones">

                        </div>

                        <div class="menu-text">

                            <h3 class="menu-title">Cotizaciones</h3>

                            <p class="menu-description">Gestión de cotizaciones a clientes.</p>

                        </div>

                    </div>

                </a>

                <?php } else { } ?>



                <!-- Catálogos -->

                <?php if(isset($_SESSION["008CAT"])&&$_SESSION["008CAT"]==1){ ?>

                    <a href="catalogos.php" class="menu-card">

                        <div class="menu-card-content">

                            <div class="menu-icon">

                                <img src="../public/iconos/catalogo.png" alt="Catálogos">

                            </div>

                            <div class="menu-text">

                                <h3 class="menu-title">Catálogos</h3>

                                <p class="menu-description">Gestión de mantenimientos del sistema</p>

                            </div>

                        </div>

                    </a>

                <?php } else { } ?>

                

                <?php if(isset($_SESSION["041DAS"])&&$_SESSION["041DAS"]==1){ ?>

                <a href="dashboards.php" class="menu-card">

                    <div class="menu-card-content">

                        <div class="menu-icon">

                            <img src="../public/iconos/plan2.png" alt="Dashboards">

                        </div>

                        <div class="menu-text">

                            <h3 class="menu-title">Dashboards y reportes</h3>

                            <p class="menu-description">Visualización de información del sistema.</p>

                        </div>

                    </div>

                </a>

                <?php } else { } ?>



                <!-- Usuarios -->

                <?php if(isset($_SESSION["010USU"])&&$_SESSION["010USU"]==1){ ?>

                <a href="usuarios.php" class="menu-card">

                    <div class="menu-card-content">

                        <div class="menu-icon">

                            <img src="../public/iconos/usuario.png" alt="Control">

                        </div>

                        <div class="menu-text">

                            <h3 class="menu-title">Configuración y controles</h3>

                            <p class="menu-description">Administración toos los permisos del sistema.</p>

                        </div>

                    </div>

                </a>

                <?php } else { } ?>

            </div>

        </div>

        <br>

        <!-- Estados Section -->

        <section class="welcome-section">

            <div class="welcome-card">

                <h1 class="welcome-title">Dashboard de Cotizaciones</h1>

                <div class="menu-grid">

                <a 
                    href="cotizacion.php"
                    class="menu-card"
                    title="Ver todas las cotizaciones"
                    style="background-color: black;"
                >
                    <div class="menu-card-content">
                        <div class="menu-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>

                        <div class="menu-text">
                            <h3 class="menu-title"><?= $totalCotizaciones ?></h3>
                            <p class="menu-description">Total de cotizaciones</p>
                        </div>
                    </div>
                </a>

                <?php foreach ($estados as $estado): ?>

                    <a 
                        href="cotizacion.php?buscar=<?= urlencode($estado['NOMBREESTADOCOT']) ?>"
                        class="menu-card"
                        title="Ver cotizaciones: <?= $estado['NOMBREESTADOCOT'] ?>"
                    >
                        <div class="menu-card-content">
                            <div class="menu-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>

                            <div class="menu-text">
                                <h3 class="menu-title"><?= (int)$estado['total'] ?></h3>
                                <p class="menu-description"><?= htmlspecialchars($estado['NOMBREESTADOCOT']) ?></p>
                            </div>
                        </div>
                    </a>

                <?php endforeach; ?>

                </div>


            </div>

        </section>

    </main>



    <!-- Scripts -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    

    <script>

        document.addEventListener('DOMContentLoaded', function() {

            const menuCards = document.querySelectorAll('.menu-card');

            

            // Añadir efectos de parallax sutil al mouse

            document.addEventListener('mousemove', function(e) {

                const orbs = document.querySelectorAll('.floating-orb');

                const mouseX = e.clientX / window.innerWidth;

                const mouseY = e.clientY / window.innerHeight;

                

                orbs.forEach((orb, index) => {

                    const speed = (index + 1) * 0.3;

                    const x = (mouseX - 0.5) * speed;

                    const y = (mouseY - 0.5) * speed;

                    orb.style.transform = `translate(${x}px, ${y}px)`;

                });

            });



            // Añadir efectos de entrada escalonada para las tarjetas

            menuCards.forEach((card, index) => {

                card.style.opacity = '0';

                card.style.transform = 'translateY(30px) scale(0.9)';

                

                setTimeout(() => {

                    card.style.transition = 'all 0.6s ease';

                    card.style.opacity = '1';

                    card.style.transform = 'translateY(0) scale(1)';

                }, index * 100);

            });



            // Mejorar la accesibilidad con navegación por teclado

            menuCards.forEach(card => {

                card.addEventListener('keydown', function(e) {

                    if (e.key === 'Enter' || e.key === ' ') {

                        e.preventDefault();

                        this.click();

                    }

                });

            });



            // Añadir efecto de ondas al hacer click

            menuCards.forEach(card => {

                card.addEventListener('click', function(e) {

                    const ripple = document.createElement('div');

                    const rect = this.getBoundingClientRect();

                    const size = Math.max(rect.width, rect.height);

                    const x = e.clientX - rect.left - size / 2;

                    const y = e.clientY - rect.top - size / 2;

                    

                    ripple.style.position = 'absolute';

                    ripple.style.width = ripple.style.height = size + 'px';

                    ripple.style.left = x + 'px';

                    ripple.style.top = y + 'px';

                    ripple.style.background = 'rgba(12, 233, 139, 0.3)';

                    ripple.style.borderRadius = '50%';

                    ripple.style.pointerEvents = 'none';

                    ripple.style.animation = 'ripple 0.6s ease-out';

                    ripple.style.zIndex = '1';

                    

                    this.style.position = 'relative';

                    this.style.overflow = 'hidden';

                    this.appendChild(ripple);

                    

                    setTimeout(() => {

                        ripple.remove();

                    }, 600);

                });

            });

        });



        // Añadir animación de ondas CSS

        const style = document.createElement('style');

        style.textContent = `

            @keyframes ripple {

                0% {

                    transform: scale(0);

                    opacity: 0.8;

                }

                100% {

                    transform: scale(2);

                    opacity: 0;

                }

            }

        `;

        document.head.appendChild(style);

    </script>

</body>

</html>

<?php



ob_end_flush();

?>