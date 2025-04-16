<?php
session_start();

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];

// Verifica si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Conectar a la base de datos
$mysqli = mysqli_connect("localhost", "root", "", "sistema");
if (!$mysqli) {
    die("ERROR: No se pudo conectar a la base de datos." . mysqli_connect_error());
}

// Inicializar las variables para la primer tarjeta
$total = 0;
$rojos = 0;
$amarillos = 0;
$verdes = 0;
$porcentaje_rojos = 0;
$porcentaje_amarillos = 0;
$porcentaje_verdes = 0;

// Obtener datos para la gráfica
//Donde "color (V,A,R)" va a contar todos los CL, CAC, CDT, JEF donde sea igual mayor a 0 para hacer la grafica
$datos = [
    'verde' => ['CL' => 0, 'CAC' => 0, 'CDT' => 0, 'JEF' => 0],
    'amarillo' => ['CL' => 0, 'CAC' => 0, 'CDT' => 0, 'JEF' => 0],
    'rojo' => ['CL' => 0, 'CAC' => 0, 'CDT' => 0, 'JEF' => 0],
];

//
$sql = "SELECT Folio_cc, Asunto, referencia, fecha_de_emision, fecha_de_rececepcion, Fecha_requerida, OT_ginp, Urgente, CL_asignado, CAC_asignado, CDT_asignado, JEF_asignado, 
            CASE WHEN DATEDIFF(Fecha_requerida, fecha_de_rececepcion) > 3 AND DATEDIFF(Fecha_requerida, fecha_de_rececepcion) <= 5 THEN 'verde' WHEN DATEDIFF(Fecha_requerida, fecha_de_rececepcion) <= 3 THEN 'amarillo' ELSE 'rojo' END AS color
        FROM principal WHERE Urgente = 1 
        AND (OT_ginp != 0 AND OT_ginp IS NOT NULL)
        AND (referencia LIKE '%UT%' 
             OR referencia LIKE '%CIIR%' 
             OR referencia LIKE '%GSI%') 
        ORDER BY ID DESC";

$resultado = mysqli_query($mysqli, $sql);

// Recorrer los resultados de la consulta y contar las OT's
//Dependiendo del color de cada registro, se incrementa el contador específico de cada color (B,A,N,G)
while ($mostrar = mysqli_fetch_assoc($resultado)) {
    $color = $mostrar['color'];
    $datos[$color]['CL'] += $mostrar['CL_asignado'];
    $datos[$color]['CAC'] += $mostrar['CAC_asignado'];
    $datos[$color]['CDT'] += $mostrar['CDT_asignado'];
    $datos[$color]['JEF'] += $mostrar['JEF_asignado'];

    $total++; // Incrementamos el total de OT's

    if ($color == 'verde') {
        $verdes++;
    }
    elseif ($color == 'amarillo') {
        $amarillos++;
    }
    else{
        $rojos++;
    }
}

// Calcular los porcentajes de la primera tarjeta
if ($total > 0) {
    $porcentaje_rojos = ($rojos / $total) * 100;
    $porcentaje_amarillos = ($amarillos / $total) * 100;
    $porcentaje_verdes = ($verdes / $total) * 100;
}
else {
    $porcentaje_rojos = 0;
    $porcentaje_amarillos = 0;
    $porcentaje_verdes = 0;
}

// Consultas por tipo de tarjeta (CL, CDC.CAC, JEF)
$sql_cl_asignado = "SELECT COUNT(*) AS CL_ASIGNADOS FROM principal WHERE Urgente = 1 AND CL_asignado = 1 AND (referencia LIKE '%UT%' OR referencia LIKE '%CIIR%' OR referencia LIKE '%GSI%') AND (OT_ginp != 0 AND OT_ginp IS NOT NULL)";
$query_cl_asignado = mysqli_query($mysqli, $sql_cl_asignado);
$row_cl_asignado = mysqli_fetch_assoc($query_cl_asignado);
$cl_asignado_count = $row_cl_asignado['CL_ASIGNADOS'];

$sql_cac_asignado = "SELECT COUNT(*) AS CAC_ASIGNADOS FROM principal WHERE Urgente = 1 AND CAC_asignado = 1 AND (referencia LIKE '%UT%' OR referencia LIKE '%CIIR%' OR referencia LIKE '%GSI%') AND (OT_ginp != 0 AND OT_ginp IS NOT NULL)";
$query_cac_asignado = mysqli_query($mysqli, $sql_cac_asignado);
$row_cac_asignado = mysqli_fetch_assoc($query_cac_asignado);
$cac_asignado_count = $row_cac_asignado['CAC_ASIGNADOS'];

$sql_cdt_asignado = "SELECT COUNT(*) AS CDT_ASIGNADOS FROM principal WHERE Urgente = 1 AND CDT_asignado = 1 AND (referencia LIKE '%UT%' OR referencia LIKE '%CIIR%' OR referencia LIKE '%GSI%') AND (OT_ginp != 0 AND OT_ginp IS NOT NULL)";
$query_cdt_asignado = mysqli_query($mysqli, $sql_cdt_asignado);
$row_cdt_asignado = mysqli_fetch_assoc($query_cdt_asignado);
$cdt_asignado_count = $row_cdt_asignado['CDT_ASIGNADOS'];

$sql_jef_asignado = "SELECT COUNT(*) AS JEF_ASIGNADOS FROM principal WHERE Urgente = 1 AND JEF_asignado = 1 AND (referencia LIKE '%UT%' OR referencia LIKE '%CIIR%' OR referencia LIKE '%GSI%') AND (OT_ginp != 0 AND OT_ginp IS NOT NULL)";
$query_jef_asignado = mysqli_query($mysqli, $sql_jef_asignado);
$row_jef_asignado = mysqli_fetch_assoc($query_jef_asignado);
$jef_asignado_count = $row_jef_asignado['JEF_ASIGNADOS'];

// Cerrar la conexión
mysqli_close($mysqli);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>URGENTES</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
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
                <!-- SE CREA UNA TARJETA PARA MEJOR VISUALIZACIÓN --> 
                <div class="container-fluid"><h1 class="mt-4">ASIGNACIÓN URGENTES</h1>
                    <h5 style="text-align: center;">AMARILLO - TODOS AQUELLOS MENOS DE 3 DIAS<br>
                    VERDE - TODOS AQUELLOS DE 4 Ó 5 DIAS<br>
                    ROJO - TODOS AQUELLOS DE MAS DE 5 DIAS<br></h5>
                    <div class="row">
                        <!-- Tarjeta de información -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #000080; color: white;">
                                <div class="card-body">URGENTES<h4><?php echo $total; ?></h4>
                                    <p><strong>3 o menos días: </strong><?php echo $amarillos; ?> (<?php echo number_format($porcentaje_amarillos, 2); ?>%)</p>
                                    <p><strong>4 o 5 días: </strong><?php echo $verdes; ?> (<?php echo number_format($porcentaje_verdes, 2); ?>%)</p>
                                    <p><strong>Mas de 5 días: </strong><?php echo $rojos; ?> (<?php echo number_format($porcentaje_rojos, 2); ?>%)</p>
                                </div>
                            </div>
                        </div>
                        <!-- Gráfica de OT's por Color y Tipo -->
                        <div class="col-xl-9 col-md-6">
                            <div class="card mb-4">
                                <div class="card-header"><i class="fas fa-chart-bar mr-2"></i>Gráfica de OT's por Color y Tipo</div>
                                    <div class="card-body">
                                        <canvas id="myChart" width="250" height="100"></canvas>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SE CREA UNA TARJETA PARA MEJOR VISUALIZACIÓN --> 
                    
                    <div class="row">
                        <!-- Tarjeta de información -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #62002b; color: white;">
                                <div class="card-body"><i class="fa fa-flask" aria-hidden="true"></i> CL<h4><?php echo $cl_asignado_count; ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #ff4500; color: white;">
                                <div class="card-body"><i class="fa fa-cubes" aria-hidden="true"></i> CAC<h4><?php echo $cac_asignado_count; ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #00b100; color: white;">
                                <div class="card-body"><i class="fa fa-cogs" aria-hidden="true"></i> CDT<h4><?php echo $cdt_asignado_count; ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card" style="background-color: #400080; color: white;">
                                <div class="card-body"><i class="fa fa-user" aria-hidden="true"></i> JEF<h4><?php echo $jef_asignado_count; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="container-fluid">
                    <div class="card mb-4">
                        <div class="card-header"><i class="fas fa-table mr-1"></i>NUMEROS DE OT´S URGENTES</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="urgentesTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>FOLIO</th>
                                            <th>ASUNTO</th>
                                            <th>REFERENCIA</th>
                                            <th>FECHA DE EMISIÓN</th>
                                            <th>FECHA DE RECEPCIÓN</th>
                                            <th>FECHA REQUERIDA</th>
                                            <th>OT GINP</th>
                                            <th>URGENTE</th>
                                            <th>CL</th>
                                            <th>CAC</th>
                                            <th>CDT</th>
                                            <th>JEF</th>
                                            <th>DIAS</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>FOLIO</th>
                                            <th>ASUNTO</th>
                                            <th>REFERENCIA</th>
                                            <th>FECHA DE EMISIÓN</th>
                                            <th>FECHA DE RECEPCIÓN</th>
                                            <th>FECHA REQUERIDA</th>
                                            <th>OT GINP</th>
                                            <th>URGENTE</th>
                                            <th>CL</th>
                                            <th>CAC</th>
                                            <th>CDT</th>
                                            <th>JEF</th>
                                            <th>DIAS</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $mysqli = mysqli_connect("localhost", "root", "", "sistema");
                                        if (!$mysqli) {
                                            die("ERROR: No se pudo conectar a la base de datos." . mysqli_connect_error());
                                        }

                                        // Filtrar solo registros donde Urgente = 1 y haga la resta entre (Fecha requerida y Fecha de recepcion)
                                        $sql = "SELECT Folio_cc, Asunto, referencia, fecha_de_emision, fecha_de_rececepcion, Fecha_requerida, OT_ginp, Urgente, CL_asignado, CAC_asignado, CDT_asignado, JEF_asignado, DATEDIFF(Fecha_requerida, fecha_de_rececepcion) AS DIAS FROM principal WHERE Urgente = 1 AND (OT_ginp != 0 AND OT_ginp IS NOT NULL) AND ( referencia LIKE '%UT%' OR referencia LIKE '%CIIR%' OR referencia LIKE '%GSI%') ORDER BY ID DESC";
                                        //$resultado2 es para mandar a llamar la sentencia SQL de arriba y nos muestre los datos en la tabla
                                        $resultado2 = mysqli_query($mysqli, $sql);

                                        while ($mostrar = mysqli_fetch_assoc($resultado2)) {
                                            $dias = $mostrar['DIAS']; // Se almacena el valor de DIAS
                                            // Se asignar el color segun el valor de DIAS
                                            if ($dias > 3 && $dias <= 5) {
                                                $class = 'verde';
                                            }
                                            elseif ($dias <= 3) {
                                                $class = 'amarillo';
                                            }
                                            else{
                                                $class = 'rojo';
                                            }
                                        ?>
                                        <tr class="<?php echo $class; ?>"> <!--El apartado $class que se ejecuta en php es para mandar a llamar el color que esta en CSS dependiente la sentencia del resultado (ya sea azul, naranja o gris) -->
                                            <td><?php echo $mostrar['Folio_cc']; ?></td>
                                            <td><?php echo $mostrar['Asunto']; ?></td>
                                            <td><?php echo $mostrar['referencia']; ?></td>
                                            <td><?php echo $mostrar['fecha_de_emision']; ?></td>
                                            <td><?php echo $mostrar['fecha_de_rececepcion']; ?></td>
                                            <td><?php echo $mostrar['Fecha_requerida']; ?></td>
                                            <td><?php echo $mostrar['OT_ginp']; ?></td>
                                            <td><i class="fas fa-exclamation-circle"></i></td>  <!-- Ícono de precaucion -->
                                            <td>
                                                <?php if ($mostrar['CL_asignado'] == 1) { ?>
                                                    <i class="fa fa-flask"></i> 
                                                <?php } else { echo ""; } ?>
                                            </td>
                                            <td>
                                                <?php if ($mostrar['CAC_asignado'] == 1) { ?>
                                                    <i class="fa fa-cubes"></i> 
                                                <?php } else { echo ""; } ?>
                                            </td>
                                            <td>
                                                <?php if ($mostrar['CDT_asignado'] == 1) { ?>
                                                    <i class="fa fa-cogs"></i> 
                                                <?php } else { echo ""; } ?>
                                            </td>
                                            <td>
                                                <?php if ($mostrar['JEF_asignado'] == 1) { ?>
                                                    <i class="fa fa-user"></i> 
                                                <?php } else { echo ""; } ?>
                                            </td>
                                            <td><?php echo $mostrar['DIAS']; ?></td>
                                        </tr>  
                                        <?php
                                        }
                                        mysqli_close($mysqli);
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script><script src="js/scripts.js"></script>
    <script>
        $(document).ready(function () {
            $('#urgentesTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                },
                "paging": true, // Activar paginación
                "searching": true, // Activar búsqueda
                "lengthChange": true, // Activar el cambio de cantidad de registros por página
                "pageLength": 10, // Definir el número predeterminado de registros por página
            });
        });

        // Gráfica
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['3 o menos días', '4 o 5 días', 'Mas de 5 días'],
                datasets: [
                    {
                        label: 'CL',
                        data: [
                            <?php echo $datos['amarillo']['CL']; ?>,
                            <?php echo $datos['verde']['CL']; ?>,
                            <?php echo $datos['rojo']['CL']; ?>
                        ],
                        backgroundColor: '#0070ff'
                    },
                    {
                        label: 'CAC',
                        data: [
                            <?php echo $datos['amarillo']['CAC']; ?>,
                            <?php echo $datos['verde']['CAC']; ?>,
                            <?php echo $datos['rojo']['CAC']; ?>
                        ],
                        backgroundColor: '#ff4500'
                    },
                    {
                        label: 'CDT',
                        data: [
                            <?php echo $datos['amarillo']['CDT']; ?>,
                            <?php echo $datos['verde']['CDT']; ?>,
                            <?php echo $datos['rojo']['CDT']; ?>
                        ],
                        backgroundColor: '#00b100'
                    },
                    {
                        label: 'JEF',
                        data: [
                            <?php echo $datos['verde']['JEF']; ?>,
                            <?php echo $datos['amarillo']['JEF']; ?>,
                            <?php echo $datos['rojo']['JEF']; ?>
                        ],
                        backgroundColor: '#400080'
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>