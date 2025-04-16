<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['Id'];
    $fechaEvento = $_POST['Fecha_evento'];
    $horaInicio = $_POST['Hora_inicio'];
    $horaFin = $_POST['Hora_fin'];
    $areaSolicitante = $_POST['Area_solicitante'];
    $nombreEvento = $_POST['Nombre_evento'];
    $nombreSolicitante = $_POST['Nombre_solicitante'];
    $contactoSolicitante = $_POST['Contacto_solicitante'];
    $comentarios = $_POST['Comentarios'];

    $query = "UPDATE reservaciones SET Fecha_evento='$fechaEvento', Hora_inicio='$horaInicio', Hora_fin='$horaFin', Area_solicitante='$areaSolicitante', Nombre_evento='$nombreEvento', Nombre_solicitante='$nombreSolicitante', Contacto_solicitante='$contactoSolicitante', Comentarios='$comentarios' WHERE Id='$id'";

    if (mysqli_query($mysqli, $query)) {
        header('Location: reservaciones.php');
    } else {
        echo "Error al actualizar: " . mysqli_error($mysqli);
    }
}
?>