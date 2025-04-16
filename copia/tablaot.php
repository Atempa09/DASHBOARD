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

// Consulta SQL con GROUP BY y MAX(ID) para obtener solo la fila más reciente por OT_ginp
$sql = "SELECT Folio_cc, OT_ginp, OT_didt, fecha_de_emision, fecha_de_rececepcion, Asunto, referencia, CDT_asignado, CL_asignado, CAC_asignado, JEF_asignado, Normal, Urgente, extraurgente FROM principal WHERE OT_ginp != 0 AND OT_ginp IS NOT NULL GROUP BY OT_ginp ORDER BY MAX(ID) DESC";
/*Se usó GROUP BY OT_ginp para agrupar las filas por OT_ginp
Se usó MAX(ID) para seleccionar la fila más reciente de cada grupo*/

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
        <title>OT´S ASIGNADOS</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="principal.php">SISTEMAS WEB GINP</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
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
                        <h1 class="mt-4">Folios Con OT´S Asignados</h1>
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-table mr-1"></i>TOTAL DE OT´S ASIGNADOS</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="foliosTable" width="100%" cellspacing="30">
                                        <thead>
                                            <tr>
                                                <th>FOLIO</th>
                                                <th>OT GINP</th>
                                                <th>OT DIDT</th>
                                                <th>FECHA DE EMISIÓN</th>
                                                <th>FECHA DE RECEPCIÓN</th>
                                                <th>ASUNTO</th>
                                                <th>REFERENCIA</th>
                                                <th>CDT</th>
                                                <th>CL</th>
                                                <th>CAC</th>
                                                <th>JEF</th>
                                                <th>NORMAL</th>
                                                <th>URGENTE</th>
                                                <th>EXTRAURGENTE</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>FOLIO</th>
                                                <th>OT GINP</th>
                                                <th>OT DIDT</th>
                                                <th>FECHA DE EMISIÓN</th>
                                                <th>FECHA DE RECEPCIÓN</th>
                                                <th>ASUNTO</th>
                                                <th>REFERENCIA</th>
                                                <th>CDT</th>
                                                <th>CL</th>
                                                <th>CAC</th>
                                                <th>JEF</th>
                                                <th>NORMAL</th>
                                                <th>URGENTE</th>
                                                <th>EXTRAURGENTE</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            $mysqli = mysqli_connect("localhost", "root", "", "sistema");
                                                if (!$mysqli) {
                                                    die("ERROR: No se pudo conectar a la base de datos." . mysqli_connect_error());
                                                }
                                            $resultado = mysqli_query($mysqli, $sql);
                                            while ($mostrar = mysqli_fetch_assoc($resultado)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $mostrar['Folio_cc']; ?></td>
                                                <td><?php echo $mostrar['OT_ginp']; ?></td>
                                                <td><?php echo $mostrar['OT_didt']; ?></td>
                                                <td><?php echo $mostrar['fecha_de_emision']; ?></td>
                                                <td><?php echo $mostrar['fecha_de_rececepcion']; ?></td>
                                                <td><?php echo $mostrar['Asunto']; ?></td>
                                                <td><?php echo $mostrar['referencia']; ?></td>
                                                <td>
                                                    <?php if ($mostrar['CDT_asignado'] == 1) { ?>
                                                        <i class="fa fa-cogs"></i> 
                                                    <?php } else { echo ""; } ?>
                                                </td>
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
                                                    <?php if ($mostrar['JEF_asignado'] == 1) { ?>
                                                        <i class="fa fa-user"></i> 
                                                    <?php } else { echo ""; } ?>
                                                </td>
                                                <td>
                                                    <?php if ($mostrar['Normal'] == 1) { ?>
                                                        <i class="fas fa-check-circle"></i>  <!-- Ícono de palomita -->
                                                    <?php } else { echo ""; } ?>
                                                </td>    
                                                <td>
                                                    <?php if ($mostrar['Urgente'] == 1) { ?>
                                                        <i class="fas fa-exclamation-circle"></i>  <!-- Ícono de precaucion -->
                                                    <?php } else { echo ""; } ?>
                                                </td>    
                                                <td>
                                                    <?php if ($mostrar['extraurgente'] == 1) { ?>
                                                    <i class="fas fa-bomb"></i>  <!-- Ícono de bomba -->
                                                    <?php } else { echo ""; } ?>
                                                </td>
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
            $('#foliosTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                },
                "paging": true, // Activar paginación
                "searching": true, // Activar búsqueda
                "lengthChange": true, // Activar el cambio de cantidad de registros por página
                "pageLength": 10, // Definir el número predeterminado de registros por página
                });
            });
        </script>
    </body>
</html>