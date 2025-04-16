<?php
session_start();

$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];

require 'conexion.php';

// Verificar si la sesión está activa
if(!isset($_SESSION['id'])){
    header("Location: index.php");
}

// Obtener las reservaciones
$query = "SELECT * FROM reservaciones ORDER BY Fecha_evento DESC";
$result = mysqli_query($mysqli, $query);
if (!$result) {
    die("Error al obtener los datos: " . mysqli_error($mysqli));
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
        <title>Reservaciones</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <style>
        table {
            width: 90%; /* Ajustar el ancho de la tabla */
            margin-left: auto;
            margin-right: auto;
        }

        table th, table td {
            padding: 8px 10px; /* Reducir el padding entre las celdas */
            font-size: 14px; /* Reducir el tamaño de la fuente */
        }

        .container {
            max-width: 1200px; /* Limitar el ancho del contenedor */
            padding-left: 20px;
            padding-right: 20px;
        }
    </style>
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

        <!-- Contenido principal -->
        <div id="layoutSidenav_content">
            <div class="container-fluid">
                <h2>Lista de Reservaciones</h2>
                <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#modalReservacion">Nueva Reservación</button>

                <!-- Tabla de Reservaciones -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha Evento</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                            <th>Expediente</th>
                            <th>Área</th>
                            <th>Evento</th>
                            <th>Solicitante</th>
                            <th>Contacto</th>
                            <th>Comentarios</th>
                            <?php if ($tipo_usuario == 1) { ?>
                                <th>Movimiento</th>
                                <th>Usuario</th>
                                <th>Fecha Movimiento</th>
                                <th>Acciones</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= $row['Id'] ?></td>
                                <td><?= $row['Fecha_evento'] ?></td>
                                <td><?= $row['Hora_inicio'] ?></td>
                                <td><?= $row['Hora_fin'] ?></td>
                                <td><?= $row['Expediente_solicitante'] ?></td>
                                <td><?= $row['Area_solicitante'] ?></td>
                                <td><?= $row['Nombre_evento'] ?></td>
                                <td><?= $row['Nombre_solicitante'] ?></td>
                                <td><?= $row['Contacto_solicitante'] ?></td>
                                <td><?= $row['Comentarios'] ?></td>
                                <?php if ($tipo_usuario == 1) { ?>
                                    <td><?= $row['Movimiento'] ?></td>
                                    <td><?= $row['Usuario'] ?></td>
                                    <td><?= $row['FechaMovimiento'] ?></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-primary btn-sm" 
                                                data-toggle="modal" data-target="#modalEditarReservacion" 
                                                onclick="llenarModalEditar(<?= $row['Id'] ?>, '<?= $row['Fecha_evento'] ?>', '<?= $row['Hora_inicio'] ?>', '<?= $row['Hora_fin'] ?>', '<?= $row['Area_solicitante'] ?>', '<?= $row['Nombre_evento'] ?>', '<?= $row['Nombre_solicitante'] ?>', '<?= $row['Contacto_solicitante'] ?>', '<?= $row['Comentarios'] ?>')">Editar</button>
                                        <a href="eliminar_reservacion.php?id=<?= $row['Id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta reservación?');">Eliminar</a>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para Nueva Reservación -->
    <div class="modal fade" id="modalReservacion" tabindex="-1" role="dialog" aria-labelledby="modalReservacionLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalReservacionLabel">Nueva Reservación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="guardar_reservacion.php" method="POST">
                        <div class="form-group">
                            <label for="fechaEvento">Fecha del Evento</label>
                            <input type="date" class="form-control" id="fechaEvento" name="Fecha_evento" required>
                        </div>
                        <div class="form-group">
                            <label for="horaInicio">Hora de Inicio</label>
                            <input type="time" class="form-control" id="horaInicio" name="Hora_inicio" required>
                        </div>
                        <div class="form-group">
                            <label for="horaFin">Hora de Fin</label>
                            <input type="time" class="form-control" id="horaFin" name="Hora_fin" required>
                        </div>
                        <div class="form-group">
                            <label for="areaSolicitante">Área Solicitante</label>
                            <select class="form-control" id="areaSolicitante" name="Area_solicitante">
                                <option value="OP">---------- SELECCIONA UNA OPCIÓN ----------</option>
                                <option value="CL">CL</option>
                                <option value="CDT">CDT</option>
                                <option value="CAC">CAC</option>
                                <option value="GINP">GINP</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombreEvento">Nombre del Evento</label>
                            <input type="text" class="form-control" id="nombreEvento" name="Nombre_evento" required>
                        </div>
                        <div class="form-group">
                            <label for="nombreSolicitante">Nombre del Solicitante</label>
                            <input type="text" class="form-control" id="nombreSolicitante" name="Nombre_solicitante" required>
                        </div>
                        <div class="form-group">
                            <label for="contactoSolicitante">Contacto del Solicitante</label>
                            <input type="text" name="Contacto_solicitante" class="form-control" 
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" 
                                maxlength="10"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="comentarios">Comentarios</label>
                            <textarea class="form-control" id="comentarios" name="Comentarios" rows="3"></textarea>
                        </div>
                        <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 1) { ?>
                            <div class="form-group">
                                <label for="movimiento">Movimiento</label>
                                <select class="form-control" id="movimiento" name="Movimiento">
                                    <option value="ALTA">ALTA</option>
                                    <option value="BAJA">BAJA</option>
                                    <option value="CAMBIO">CAMBIO</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fechaMovimiento">Fecha Movimiento</label>
                                <input type="date" class="form-control" id="fechaMovimiento" name="FechaMovimiento">
                            </div>
                        <?php } ?>
                        <button type="submit" class="btn btn-primary">Guardar Reservación</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edición de Reservación -->
    <div class="modal fade" id="modalEditarReservacion" tabindex="-1" role="dialog" aria-labelledby="modalEditarReservacionLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarReservacionLabel">Editar Reservación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="editar_reservacion.php" method="POST" id="formEditarReservacion">
                        <input type="hidden" id="editId" name="Id">
                        <div class="form-group">
                            <label for="editFechaEvento">Fecha del Evento</label>
                            <input type="date" class="form-control" id="editFechaEvento" name="Fecha_evento" required>
                        </div>
                        <div class="form-group">
                            <label for="editHoraInicio">Hora de Inicio</label>
                            <input type="time" class="form-control" id="editHoraInicio" name="Hora_inicio" required>
                        </div>
                        <div class="form-group">
                            <label for="editHoraFin">Hora de Fin</label>
                            <input type="time" class="form-control" id="editHoraFin" name="Hora_fin" required>
                        </div>
                        <div class="form-group">
                            <label for="editAreaSolicitante">Área Solicitante</label>
                            <select class="form-control" id="editAreaSolicitante" name="Area_solicitante">
                                <option value="CL">CL</option>
                                <option value="CDT">CDT</option>
                                <option value="CAC">CAC</option>
                                <option value="GINP">GINP</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editNombreEvento">Nombre del Evento</label>
                            <input type="text" class="form-control" id="editNombreEvento" name="Nombre_evento" required>
                        </div>
                        <div class="form-group">
                            <label for="editNombreSolicitante">Nombre del Solicitante</label>
                            <input type="text" class="form-control" id="editNombreSolicitante" name="Nombre_solicitante" required>
                        </div>
                        <div class="form-group">
                            <label for="editContactoSolicitante">Contacto del Solicitante</label>
                            <input type="text" class="form-control" id="editContactoSolicitante" name="Contacto_solicitante" required>
                        </div>
                        <div class="form-group">
                            <label for="editComentarios">Comentarios</label>
                            <textarea class="form-control" id="editComentarios" name="Comentarios" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para datos de edición -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="js/reservacion.js"></script>
</body>
</html>