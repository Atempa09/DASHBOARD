<?php
session_start();

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];

// Verifica si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos (una sola vez)
$mysqli = mysqli_connect("localhost", "root", "", "sistema");
if (!$mysqli) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Consultas para obtener los valores para la gráfica de pastel
$query_normal = "SELECT COUNT(Normal) AS Normales FROM principal";
$resultado_normal = $mysqli->query($query_normal);
$row_normal = $resultado_normal->fetch_row();
$normales = $row_normal[0];

$query_urgente = "SELECT COUNT(Urgente) AS Urgentes FROM principal";
$resultado_urgente = $mysqli->query($query_urgente);
$row_urgente = $resultado_urgente->fetch_row();
$urgentes = $row_urgente[0];

$query_extraurgente = "SELECT COUNT(extraurgente) AS Extras FROM principal";
$resultado_extraurgente = $mysqli->query($query_extraurgente);
$row_extraurgente = $resultado_extraurgente->fetch_row();
$extraurgentes = $row_extraurgente[0];

// Total de folios asignados
$sql_total = "SELECT * FROM `principal`";  
$query_run_total = mysqli_query($mysqli, $sql_total);
$total = mysqli_num_rows($query_run_total);

// Consultas por tipo de OT (Normal, Urgente, ExtraUrgente, OT, CL, CDC.CAC, JEF, CONCLUIDO, NO CONCLUIDO)
$sql_normal = "SELECT COUNT(*) AS NORMAL FROM principal WHERE Normal = 1";
$query_normal = mysqli_query($mysqli, $sql_normal);
$row_normal = mysqli_fetch_assoc($query_normal);
$normal_count = $row_normal['NORMAL'];

$sql_urgente = "SELECT COUNT(*) AS URGENTES FROM principal WHERE Urgente = 1";
$query_urgente = mysqli_query($mysqli, $sql_urgente);
$row_urgente = mysqli_fetch_assoc($query_urgente);
$urgente_count = $row_urgente['URGENTES'];

$sql_extraurgente = "SELECT COUNT(*) AS EXTRAURGENTES FROM principal WHERE extraurgente = 1";
$query_extraurgente = mysqli_query($mysqli, $sql_extraurgente);
$row_extraurgente = mysqli_fetch_assoc($query_extraurgente);
$extraurgente_count = $row_extraurgente['EXTRAURGENTES'];

$sql_ots = "SELECT COUNT(DISTINCT OT_ginp) AS OT_ginp FROM principal WHERE OT_ginp AND (OT_ginp != 0 AND OT_ginp IS NOT NULL)";
$query_ots = mysqli_query($mysqli, $sql_ots);
$row_ots = mysqli_fetch_assoc($query_ots);
$ots_count = $row_ots['OT_ginp'];

$sql_cl_asignado = "SELECT COUNT(*) AS CL_ASIGNADOS FROM principal WHERE CL_asignado = 1";
$query_cl_asignado = mysqli_query($mysqli, $sql_cl_asignado);
$row_cl_asignado = mysqli_fetch_assoc($query_cl_asignado);
$cl_asignado_count = $row_cl_asignado['CL_ASIGNADOS'];

$sql_cac_asignado = "SELECT COUNT(*) AS CAC_ASIGNADOS FROM principal WHERE CAC_asignado = 1";
$query_cac_asignado = mysqli_query($mysqli, $sql_cac_asignado);
$row_cac_asignado = mysqli_fetch_assoc($query_cac_asignado);
$cac_asignado_count = $row_cac_asignado['CAC_ASIGNADOS'];

$sql_cdt_asignado = "SELECT COUNT(*) AS CDT_ASIGNADOS FROM principal WHERE CDT_asignado = 1";
$query_cdt_asignado = mysqli_query($mysqli, $sql_cdt_asignado);
$row_cdt_asignado = mysqli_fetch_assoc($query_cdt_asignado);
$cdt_asignado_count = $row_cdt_asignado['CDT_ASIGNADOS'];

$sql_jef_asignado = "SELECT COUNT(*) AS JEF_ASIGNADOS FROM principal WHERE JEF_asignado = 1";
$query_jef_asignado = mysqli_query($mysqli, $sql_jef_asignado);
$row_jef_asignado = mysqli_fetch_assoc($query_jef_asignado);
$jef_asignado_count = $row_jef_asignado['JEF_ASIGNADOS'];

$sql_proceso_concluido = "SELECT COUNT(*) AS PROCESOS_CONCLUIDOS FROM principal WHERE ProcesoConcluido = 1";
$query_proceso_concluido = mysqli_query($mysqli, $sql_proceso_concluido);
$row_proceso_concluido = mysqli_fetch_assoc($query_proceso_concluido);
$proceso_concluido_count = $row_proceso_concluido['PROCESOS_CONCLUIDOS'];

$sql_proceso_no_concluido = "SELECT COUNT(*) AS PROCESOS_NO_CONCLUIDOS FROM principal WHERE ProcesoConcluido = 0";
$query_proceso_no_concluido = mysqli_query($mysqli, $sql_proceso_no_concluido);
$row_proceso_no_concluido = mysqli_fetch_assoc($query_proceso_no_concluido);
$proceso_no_concluido_count = $row_proceso_no_concluido['PROCESOS_NO_CONCLUIDOS'];

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta http-equiv="refresh" content="60">
        <title>Sistema Web</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="principal.php">SISTEMAS WEB GINP</a><button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <ul class="navbar-nav ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo $nombre; ?> <i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="logout.php">Cerrar Sesión</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="principal.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>Principal
                        </a>

                        <?php 
                            if ($tipo_usuario == 2) {
                                // Código para el tipo de usuario 2
                            } elseif ($tipo_usuario == 3) {
                                // Código para el tipo de usuario 3
                            } elseif ($tipo_usuario == 4) {
                                // Código para el tipo de usuario 4
                            } elseif ($tipo_usuario == 5) {
                                // Código para el tipo de usuario 5
                            } elseif ($tipo_usuario == 1) {
                                // Còdigo para el tipo de usuario 1
                        ?>

                    <div class="sb-sidenav-menu-heading">Interfaz</div>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAsignacion" aria-expanded="true" aria-controls="collapseAsignacion">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>Asignación
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                        <div class="collapse" id="collapseAsignacion" aria-labelledby="headingOne">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="normal.php"><div class="sb-nav-link-icon"><i class="fas fa-check-circle"></i></div>Normal</a>
                                <a class="nav-link" href="urgentes.php"><div class="sb-nav-link-icon"><i class="fas fa-exclamation-circle"></i></div>Urgentes</a>
                                <a class="nav-link" href="extraurgentes.php"><div class="sb-nav-link-icon"><i class="fas fa-bomb"></i></div>ExtraUrgentes</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsedetalles" aria-expanded="true" aria-controls="collapsedetalles">
                            <div class="sb-nav-link-icon"><i class="fas fa-info-circle"></i></div>Más Detalles
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsedetalles" aria-labelledby="headingOne">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsefolios" aria-expanded="false" aria-controls="collapsefolios">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>FOLIOS
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsefolios" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <a class="nav-link" href="tablaf.php"><div class="sb-nav-link-icon"><i class="fa fa-search"></i></div>TOTAL</a>
                                <a class="nav-link" href="tablan.php"><div class="sb-nav-link-icon"><i class="fas fa-check-circle"></i></div>NORMAL</a>
                                <a class="nav-link" href="tablau.php"><div class="sb-nav-link-icon"><i class="fas fa-exclamation-circle"></i></div>URGENTE</a>
                                <a class="nav-link" href="tablae.php"><div class="sb-nav-link-icon"><i class="fas fa-bomb"></i></div>EXTRAURGENTE</a>
                                <a class="nav-link" href="tablaot.php"><div class="sb-nav-link-icon"><i class="fa fa-desktop"></i></div>OT`S ASIGNADAS</a>
                                <a class="nav-link" href="tablani.php"><div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>NOTAS INFORMATIVAS</a>
                            </div>

                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseprocesos" aria-expanded="false" aria-controls="collapseprocesos">
                                <div class="sb-nav-link-icon"><i class="fa fa-inbox"></i></div>
                                PROCESOS
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseprocesos" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <a class="nav-link" href="tablapc.php"><div class="sb-nav-link-icon"><i class="fa fa-check"></i></div>CONCLUIDO</a>
                                <a class="nav-link" href="tablapnc.php"><div class="sb-nav-link-icon"><i class="fa fa-times"></i></div>NO CONCLUIDO</a>
                            </div>

                                <a class="nav-link" href="charts.php"><div class="sb-nav-link-icon"><i class="fas fa-chart-pie mr-1"></i></div>Gráficas Generales</a>
                                <a class="nav-link" href="calendario.php"><div class="sb-nav-link-icon"><i class="fa fa-calendar"></i></div>Calendario</a>
                            </nav>
                        </div>

                        <?php 
                        } 
                        else {
                                // Si el tipo de usuario no es válido
                                echo "";
                            }
                        ?>

                        <div class="sb-sidenav-menu-heading">Tablas Relacionadas</div>
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTablas" aria-expanded="true" aria-controls="collapseGraficas">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div> Tablas
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseTablas" aria-labelledby="headingOne">
                            <nav class="sb-sidenav-menu-nested nav">
                                <!-- Este enlace solo se muestra si el tipo de usuario es 1 o 2 -->
                                <?php if ($tipo_usuario == 1 || $tipo_usuario == 2): ?>
                                    <a class="nav-link" href="tablaPCAC.php">
                                        <div class="sb-nav-link-icon"><i class="fa fa-cubes"></i></div> RELACION DE OT´S DE PRINCIPAL - CAC
                                    </a>
                                <?php endif; ?>
        
                                <!-- Este enlace solo se muestra si el tipo de usuario es 1 o 4 -->
                                <?php if ($tipo_usuario == 1 || $tipo_usuario == 4): ?>
                                    <a class="nav-link" href="tablaPCDT.php">
                                        <div class="sb-nav-link-icon"><i class="fa fa-cogs"></i></div> RELACION DE OT´S DE PRINCIPAL - CDT
                                    </a>
                                <?php endif; ?>
        
                                <!-- Este enlace solo se muestra si el tipo de usuario es 1 o 3 -->
                                <?php if ($tipo_usuario == 1 || $tipo_usuario == 3): ?>
                                    <a class="nav-link" href="tablaPCL.php">
                                        <div class="sb-nav-link-icon"><i class="fa fa-flask"></i></div> RELACION DE OT´S DE PRINCIPAL - CL
                                    </a>
                                <?php endif; ?>
        
                                <!-- Este enlace solo se muestra si el tipo de usuario es 1 o 5 -->
                                <?php if ($tipo_usuario == 1 || $tipo_usuario == 5): ?>
                                    <a class="nav-link" href="tablaPJEF.php">
                                        <div class="sb-nav-link-icon"><i class="fa fa-user"></i></div> RELACION DE OT´S DE PRINCIPAL - JEF
                                    </a>
                                <?php endif; ?>
                            </nav>
                        </div>
                            <a class="nav-link" href="reservaciones.php"><i class="fa fa-clock"></i><div class="sb-nav-link-icon"></div>Reservaciones</a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">GINP</h1>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body"><i class="fas fa-book-open"></i> FOLIOS ASIGNADOS<h4><?php echo $total; ?></h4></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body"><i class="fas fa-check-circle"></i> NORMAL<h4><?php echo $normal_count; ?></h4></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #ffbf00; color: white;">
                                <div class="card-body"><i class="fas fa-exclamation-circle"></i> URGENTE<h4><?php echo $urgente_count; ?></h4></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #0033ff; color: white;">
                                <div class="card-body"><i class="fas fa-bomb"></i> EXTRA URGENTE<h4><?php echo $extraurgente_count; ?></h4></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #33ffff; color: black;">
                                <div class="card-body"><i class="fa fa-desktop"></i> OT´S ASIGNADAS<h4><?php echo $ots_count; ?></h4></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #62002b; color: white;">
                                <div class="card-body"><i class="fa fa-flask"></i> CL<h4><?php echo $cl_asignado_count; ?></h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #ff4500; color: white;">
                                <div class="card-body"><i class="fa fa-cubes"></i> CAC<h4><?php echo $cac_asignado_count; ?></h4></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #00b100; color: white;">
                                <div class="card-body"><i class="fa fa-cogs"></i> CDT<h4><?php echo $cdt_asignado_count; ?></h4></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #400080; color: white;">
                                <div class="card-body"><i class="fa fa-user"></i> JEF<h4><?php echo $jef_asignado_count; ?></h4></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #66b3ff; color: black;">
                                <div class="card-body"><i class="fa fa-check"></i> PROCESO CONCLUIDO<h4><?php echo $proceso_concluido_count; ?></h4></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #ff1493; color: white;">
                                <div class="card-body"><i class="fa fa-times-circle"></i> PROCESO NO CONCLUIDO<h4><?php echo $proceso_no_concluido_count; ?></h4></div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <?php include 'chat_flotante.php'; ?>
</body>
</html>

<?php
// Cierra la conexión
mysqli_close($mysqli);
?>