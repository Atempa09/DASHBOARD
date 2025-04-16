<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['id'])) {
    http_response_code(403);
    exit("Acceso denegado");
}

$mi_id = $_SESSION['id'];
$emisor_id = $_GET['emisor_id'] ?? null;

if (!$emisor_id) {
    http_response_code(400);
    exit("Falta emisor");
}

$stmt = $mysqli->prepare("UPDATE mensajes SET leido = 1 WHERE emisor_id = ? AND receptor_id = ?");
$stmt->bind_param("ii", $emisor_id, $mi_id);
$stmt->execute();
$stmt->close();

echo "Mensajes marcados como leídos";