<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['id'])) {
    die("Acceso denegado");
}

$mi_id = $_SESSION['id'];

// Actualiza el estado a "en línea" cada vez que el usuario interactúa
$query = "UPDATE usuarios SET online = 1 WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $mi_id);
$stmt->execute();
$stmt->close();

// Devuelve una respuesta exitosa
echo json_encode(['success' => true]);
