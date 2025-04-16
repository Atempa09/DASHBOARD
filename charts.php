<?php
session_start();

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];

// Verifica si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos (orientada a objetos)
$mysqli = new mysqli("localhost", "root", "", "sistema");
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Obtener el total de folios registrados
$sql_total = "SELECT * FROM `principal`";  
$query_run_total = mysqli_query($mysqli, $sql_total);
$total = mysqli_num_rows($query_run_total);

// Consultas para obtener los valores de cada categoría
$query_normal = "SELECT COUNT(Normal) AS Normales FROM principal WHERE Normal = 1";
$resultado_normal = $mysqli->query($query_normal);
$row_normal = $resultado_normal->fetch_row();
$normales = $row_normal[0];

$query_urgente = "SELECT COUNT(Urgente) AS Urgentes FROM principal WHERE Urgente = 1";
$resultado_urgente = $mysqli->query($query_urgente);
$row_urgente = $resultado_urgente->fetch_row();
$urgentes = $row_urgente[0];

$query_extraurgente = "SELECT COUNT(extraurgente) AS Extras FROM principal WHERE extraurgente = 1";
$resultado_extraurgente = $mysqli->query($query_extraurgente);
$row_extraurgente = $resultado_extraurgente->fetch_row();
$extraurgentes = $row_extraurgente[0];

// Calcular el porcentaje para cada categoría
$normal_percentage = ($normales / $total) * 100;
$urgente_percentage = ($urgentes / $total) * 100;
$extraurgente_percentage = ($extraurgentes / $total) * 100;


if ($total > 0) {
    $normal_percentage = ($normales / $total) * 100;
    $urgente_percentage = ($urgentes / $total) * 100;
    $extraurgente_percentage = ($extraurgentes / $total) * 100;
}
else {
    $normal_percentage = $urgente_percentage = $extraurgente_percentage = 0;
}

// Consultas para obtener datos de folios asignadas por mes
$query_dates = "SELECT DATE_FORMAT(fecha_de_emision, '%Y-%m') AS month, COUNT(*) AS count FROM principal GROUP BY month ORDER BY month DESC LIMIT 12"; // Limitar a los últimos 12 meses
$result_dates = $mysqli->query($query_dates);

$months = [];
$counts = [];

if ($result_dates->num_rows > 0) {
    while ($row = $result_dates->fetch_assoc()) {
        $months[] = $row['month'];  // Almacenar el mes (año-mes)
        $counts[] = $row['count'];  // Almacenar el conteo de OT's para ese mes
    }
} 
else {
    // Asignar valores por defecto si no hay datos
    $months = ["No hay datos"];
    $counts = [0];
}

// Consultas para obtener los valores para la segunda gráfica de pastel
$sql = "SELECT ProcesoConcluido, COUNT(*) as total FROM principal GROUP BY ProcesoConcluido";
$result = $mysqli->query($sql);

// Inicializar los contadores
$concluido = 0;
$no_concluido = 0;

// Procesar los resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['ProcesoConcluido'] == 1) {
            $concluido = $row['total'];
        } 
        else {
            $no_concluido = $row['total'];
        }
    }
}

// Consultas a la base de datos
$sql_ots = "SELECT COUNT(DISTINCT OT_ginp) AS OT_ginp FROM principal WHERE OT_ginp AND (OT_ginp != 0 AND OT_ginp IS NOT NULL)";
$query_ots = mysqli_query($mysqli, $sql_ots);
$row_ots = mysqli_fetch_assoc($query_ots);
$ots_count = $row_ots['OT_ginp']; // Total de OT asignadas

$query_normales = "SELECT COUNT(DISTINCT OT_ginp) AS Normales FROM principal WHERE OT_ginp IS NOT NULL AND OT_ginp != 0 AND Normal > 0";
$resultado_normales = $mysqli->query($query_normales);
$row_normales = $resultado_normales->fetch_row();
$normalesot = $row_normales[0] ? $row_normales[0] : 0;

$query_urgentes = "SELECT COUNT(DISTINCT OT_ginp) AS Urgentes FROM principal WHERE OT_ginp IS NOT NULL AND OT_ginp != 0 AND Urgente > 0";
$resultado_urgentes = $mysqli->query($query_urgentes);
$row_urgentes = $resultado_urgentes->fetch_row();
$urgentesot = $row_urgentes[0] ? $row_urgentes[0] : 0;

$query_extraurgentes = "SELECT COUNT(DISTINCT OT_ginp) AS Extraurgente FROM principal WHERE OT_ginp IS NOT NULL AND OT_ginp != 0 AND extraurgente > 0";
$resultado_extraurgentes = $mysqli->query($query_extraurgentes);
$row_extraurgentes = $resultado_extraurgentes->fetch_row();
$extraurgentesot = $row_extraurgentes[0] ? $row_extraurgentes[0] : 0;

// Calcular porcentajes
$normal_percentageot = ($ots_count > 0) ? ($normalesot / $ots_count) * 100 : 0;
$urgente_percentageot = ($ots_count > 0) ? ($urgentesot / $ots_count) * 100 : 0;
$extraurgente_percentageot = ($ots_count > 0) ? ($extraurgentesot / $ots_count) * 100 : 0;

// Calcular porcentajes de las OTs
if ($ots_count > 0) {
    $normal_percentageot = ($normalesot / $ots_count) * 100;
    $urgente_percentageot = ($urgentesot / $ots_count) * 100;
    $extraurgente_percentageot = ($extraurgentesot / $ots_count) * 100;
}
else {
    $normal_percentageot = $urgente_percentageot = $extraurgente_percentageot = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Graficas</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
        <style>
        .chart-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .chart-card {
            width: 48%;
        }
        canvas {
            width: 100% !important;
            height: auto !important;
        }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="principal.php">Sistema Web GINP</a><button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
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
                        <h1 class="mt-4">Gráficas</h1>
                        <ol class="breadcrumb mb-4">
                        <!-- Contenedor de las gráficas -->
                            <li class="breadcrumb-item"><a href="principal.php">Principal</a> </li>
                            <li class="breadcrumb-item active">Gráficas</li>
                        </ol>
                        <div class="row">
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header"><i class="fas fa-chart-pie mr-1"></i>Asignación de Folios por Prioridad</div>
                                <div class="card-body">
                                    <canvas id="myPieChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header"><i class="fas fa-chart-pie mr-1"></i>Asignación de OT's por Prioridad</div>
                                <div class="card-body">
                                    <canvas id="myPieChart3"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header"><i class="fas fa-percent"></i> Porcentajes de Folios por Prioridad</div>
                                    <li class="list-group-item">NORMAL
                                        <span class="list-group-progress" style="width: <?php echo round($normal_percentage, 2); ?>%;"></span>
                                        <span class="percentage"><?php echo round($normal_percentage, 2); ?>%</span>
                                    </li>
                                    <li class="list-group-item">URGENTE
                                        <span class="list-group-progress" style="width: <?php echo round($urgente_percentage, 2); ?>%;"></span>
                                        <span class="percentage"><?php echo round($urgente_percentage, 2); ?>%</span>
                                    </li>
                                    <li class="list-group-item">EXTRAURGENTE
                                        <span class="list-group-progress" style="width: <?php echo round($extraurgente_percentage, 2); ?>%;"></span>
                                        <span class="percentage"><?php echo round($extraurgente_percentage, 2); ?>%</span>
                                    </li>    
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header"><i class="fas fa-percent"></i> Porcentajes de OT´S por Prioridad</div>
                                    <li class="list-group-item">NORMAL
                                        <span class="list-group-progress" style="width: <?php echo round($normal_percentage, 2); ?>%;"></span>
                                        <span class="percentage"><?php echo round($normal_percentageot, 2); ?>%</span>
                                    </li>
                                    <li class="list-group-item">URGENTE
                                        <span class="list-group-progress" style="width: <?php echo round($urgente_percentage, 2); ?>%;"></span>
                                        <span class="percentage"><?php echo round($urgente_percentageot, 2); ?>%</span>
                                    </li>
                                    <li class="list-group-item">EXTRAURGENTE
                                        <span class="list-group-progress" style="width: <?php echo round($extraurgente_percentage, 2); ?>%;"></span>
                                        <span class="percentage"><?php echo round($extraurgente_percentageot, 2); ?>%</span>
                                    </li>    
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Folios Asignados por Mes</div>
                                <div class="card-body">
                                    <canvas id="myBarChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header"><i class="fas fa-chart-pie mr-1"></i>Tipos de Procesos</div>
                                <div class="card-body">
                                    <canvas id="myPieChart2"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script>

        // Datos para la gráfica de pastel
        var xValues = <?php echo json_encode(["Normales", "Urgentes", "Extraurgentes"]) ?>;
        var yValues = <?php echo json_encode([$normales, $urgentes, $extraurgentes]) ?>;
        var barColors = ["#28a745", "#ffbf00", "#0033ff"]; 

        // Crear el gráfico de pastel
        new Chart("myPieChart", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                responsive: true
            }
        });

        // Datos para la gráfica de barras
        var dateLabels = <?php echo json_encode($months) ?>;
        var otCounts = <?php echo json_encode($counts) ?>;

        // Crear el gráfico de barras
        new Chart("myBarChart", {
            type: "bar",
            data: {
                labels: dateLabels,
                datasets: [{
                    label: "Folios Asignados",
                    data: otCounts,
                    backgroundColor: "#007bff"
                }]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true // Comienza el eje Y desde 0
                        }
                    }]
                }
            }
        });

        // Datos para el segundo gráfico de pastel
        var xValues = <?php echo json_encode(["Proceso Concluido", "Proceso No concluido"]) ?>;
        var yValues = <?php echo json_encode([$concluido, $no_concluido]) ?>;
        var barColors = ['#66b3ff', '#ff1493'];

        new Chart("myPieChart2", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                responsive: true,
            }
        });

        // Datos para la gráfica de pastel
        var xValues = <?php echo json_encode(["Normales", "Urgentes", "Extraurgentes"]) ?>;
        var yValues = <?php echo json_encode([$normalesot, $urgentesot, $extraurgentesot]) ?>;
        var barColors = ["#28a745", "#ffbf00", "#0033ff"]; 

        // Crear el gráfico de pastel
        new Chart("myPieChart3", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                responsive: true
            }
        });
        </script>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>

<?php
// Cierra la conexión
$mysqli->close();
?>