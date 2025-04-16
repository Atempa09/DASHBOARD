<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['id'])) {
    http_response_code(403);
    exit("Acceso denegado");
}

$mi_id = $_GET['mi_id'] ?? null;

if (!$mi_id) {
    http_response_code(400);
    exit("Falta ID de usuario");
}

$stmt = $mysqli->prepare("SELECT COUNT(*) AS no_leidos FROM mensajes WHERE receptor_id = ? AND leido = 0");
$stmt->bind_param("i", $mi_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

echo json_encode(["no_leidos" => $data['no_leidos']]);