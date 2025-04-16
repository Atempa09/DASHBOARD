<?php 
session_start(); // Asegúrate de que la sesión esté iniciada

require_once 'conexion.php'; // Inicia la conexión a la base de datos

// Verificar que los datos han sido enviados por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $fecha_evento = mysqli_real_escape_string($mysqli, $_POST['Fecha_evento']);
    $hora_inicio = mysqli_real_escape_string($mysqli, $_POST['Hora_inicio']);
    $hora_fin = mysqli_real_escape_string($mysqli, $_POST['Hora_fin']);
    $area_solicitante = mysqli_real_escape_string($mysqli, $_POST['Area_solicitante']);
    $nombre_evento = mysqli_real_escape_string($mysqli, $_POST['Nombre_evento']);
    $nombre_solicitante = mysqli_real_escape_string($mysqli, $_POST['Nombre_solicitante']);
    $contacto_solicitante = mysqli_real_escape_string($mysqli, $_POST['Contacto_solicitante']);
    $comentarios = mysqli_real_escape_string($mysqli, $_POST['Comentarios']);
    $movimiento = isset($_POST['Movimiento']) ? mysqli_real_escape_string($mysqli, $_POST['Movimiento']) : null;
    $fecha_movimiento = isset($_POST['FechaMovimiento']) ? mysqli_real_escape_string($mysqli, $_POST['FechaMovimiento']) : null;

    // Obtener el nombre del usuario en lugar del ID
    $usuario_id = $_SESSION['id'];
    $query_usuario = "SELECT nombre FROM usuarios WHERE id = '$usuario_id'";
    $result_usuario = mysqli_query($mysqli, $query_usuario);

    if ($result_usuario && mysqli_num_rows($result_usuario) > 0) {
        $usuario = mysqli_fetch_assoc($result_usuario)['nombre'];
    }

    // Preparar la consulta SQL para insertar los datos
    $query = "INSERT INTO reservaciones (
        Fecha_evento, Hora_inicio, Hora_fin, Area_solicitante, Nombre_evento, 
        Nombre_solicitante, Contacto_solicitante, Comentarios, Movimiento, Usuario, FechaMovimiento
    ) VALUES (
        '$fecha_evento', '$hora_inicio', '$hora_fin', '$area_solicitante', '$nombre_evento',
        '$nombre_solicitante', '$contacto_solicitante', '$comentarios', '$movimiento', '$usuario', '$fecha_movimiento'
    )";

    // Ejecutar la consulta
    mysqli_query($mysqli, $query);

    // Redirigir siempre a la página de reservaciones
    header("Location: reservaciones.php");
    exit();
} else {
    header("Location: reservaciones.php");
    exit();
}