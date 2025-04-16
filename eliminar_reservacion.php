<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] != 1) {
    die("Acceso denegado");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM reservaciones WHERE Id = $id";
    if (mysqli_query($mysqli, $query)) {
        header("Location: reservaciones.php?mensaje=eliminado");
    } else {
        echo "Error al eliminar: " . mysqli_error($mysqli);
    }
} else {
    echo "ID inválido.";
}
?>