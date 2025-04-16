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

//Consulta SQL donde ontendran los datos de los folios y de las OT´S
$query_daily = "SELECT 
    DATE_FORMAT(fecha_de_emision, '%Y-%m') AS month,
    DATE_FORMAT(fecha_de_emision, '%Y-%m-%d') AS day,
    GROUP_CONCAT(Folio_cc ORDER BY Folio_cc SEPARATOR ', ') AS folios,
    GROUP_CONCAT(OT_ginp ORDER BY OT_ginp SEPARATOR ', ') AS ot_ginp_list,
    COUNT(*) AS count 
FROM principal
GROUP BY month, day 
ORDER BY month DESC, day DESC";

$result_daily = $mysqli->query($query_daily);

if (!$result_daily) {
    die("Error en la consulta SQL: " . $mysqli->error);
}

$daily_data = [];
if ($result_daily->num_rows > 0) {
    while ($row = $result_daily->fetch_assoc()) {
        $month = $row['month'];
        $day = $row['day'];
        $count = $row['count'];
        $folios = $row['folios'];

        if (!isset($daily_data[$month])) {
            $daily_data[$month] = [
                'days' => [],
                'total' => 0,
                'day_count' => 0,
                'folios' => [],
                'ot_ginp' => []
            ];
        }

        $daily_data[$month]['days'][$day] = $count;
        $daily_data[$month]['folios'][$day] = $folios;

        // Procesar OT_ginp válidas
        $ots_raw = explode(', ', $row['ot_ginp_list']);
        $valid_ots = array_filter($ots_raw, function ($ot) {
            return trim($ot) !== '' && trim($ot) !== '0' && strtolower(trim($ot)) !== 'null';
        });

        if (!empty($valid_ots)) {
            $daily_data[$month]['ot_ginp'][$day] = implode(', ', $valid_ots);
        }

        $daily_data[$month]['total'] += $count;
        $daily_data[$month]['day_count']++;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>CALENDARIO</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.min.js'></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
                    
                    <div class="col-lg-10 mx-auto">
                        <div class="card mb-2">
                            <div class="card-header">Calendario de Folios y OT´S</div>
                                <div class="card-body p-3">
                                    <div class="stats-card bg-light p-12 mb-8">
                                        <div class="row text-center">
                                            <div class="col-md-4 stat-item">
                                                <div class="stat-value" id="calTotalFolios">0</div>
                                                <div class="stat-label">Total de Folios</div>
                                            </div>
                                            <div class="col-md-4 stat-item">
                                                <div class="stat-value" id="calDailyAverage">0</div>
                                                <div class="stat-label">Promedio Diario</div>
                                            </div>
                                            <div class="col-md-4 stat-item">
                                                <div class="stat-value" id="calDaysWithData">0</div>
                                                <div class="stat-label">Días con Registros</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="calendar"></div>
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
    <script>
    var calendarEvents = [];
    var calendarDailyData = {};

    <?php foreach ($daily_data as $month => $data): ?>
        <?php foreach ($data['days'] as $day => $count): ?>
            calendarEvents.push({
                title: 'Folios: <?= $count ?>',
                start: '<?= $day ?>',
                allDay: true,
                backgroundColor: '#007bff',
                borderColor: '#007bff',
                extendedProps: {
                    folios: "Folios: <?= addslashes($data['folios'][$day]) ?>"
                }
            });
        <?php endforeach; ?>

        <?php if (isset($data['ot_ginp'])): ?>
            <?php foreach ($data['ot_ginp'] as $day => $ots): ?>
                calendarEvents.push({
                    title: 'OT GINP: <?= count(explode(",", $ots)) ?>',
                    start: '<?= $day ?>',
                    allDay: true,
                    backgroundColor: '#dc3545',
                    borderColor: '#dc3545',
                    extendedProps: {
                        folios: "OT GINP: <?= addslashes($ots) ?>"
                    }
                });
            <?php endforeach; ?>
        <?php endif; ?>

        calendarDailyData['<?= $month ?>'] = {
            total: <?= $data['total'] ?>,
            day_count: <?= $data['day_count'] ?>
        };
    <?php endforeach; ?>

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            events: calendarEvents,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek'
            },
            datesSet: function(info) {
                const currentDate = calendar.getDate();
                updateCalendarStats(currentDate);
            },
            eventDidMount: function(info) {
                var folios = info.event.extendedProps.folios || 'Sin detalles';
                new bootstrap.Tooltip(info.el, {
                    title: folios,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
                calendar.render();

                // Redibujar el calendario cuando se cambie el tamaño del sidebar
                document.getElementById('sidebarToggle').addEventListener('click', function() {
                    setTimeout(() => {
                        calendar.updateSize();
                    }, 300); // Espera a que termine la animación del sidebar
                });
            }
        });
        calendar.render();

        function updateCalendarStats(currentDate) {
            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, '0');
            const monthKey = `${year}-${month}`;
            const monthData = calendarDailyData[monthKey] || { total: 0, day_count: 0 };

            document.getElementById('calTotalFolios').textContent = monthData.total;
            document.getElementById('calDailyAverage').textContent =
                monthData.day_count > 0 ? (monthData.total / monthData.day_count).toFixed(2) : '0';
            document.getElementById('calDaysWithData').textContent = monthData.day_count;
        }
    });
    </script>
</body>
</html>