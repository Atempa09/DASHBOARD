<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['id'])) {
    http_response_code(403);
    exit("Acceso denegado");
}

$mi_id = $_GET['mi_id'] ?? null;
$receptor_id = $_GET['receptor_id'] ?? null;

if (!$mi_id || !$receptor_id) {
    http_response_code(400);
    exit("Faltan parámetros");
}

$stmt = $mysqli->prepare("
    SELECT emisor_id, mensaje, fecha 
    FROM mensajes 
    WHERE (emisor_id = ? AND receptor_id = ?) OR (emisor_id = ? AND receptor_id = ?)
    ORDER BY fecha ASC
");
$stmt->bind_param("iiii", $mi_id, $receptor_id, $receptor_id, $mi_id);
$stmt->execute();
$result = $stmt->get_result();

$mensajes = [];
while ($row = $result->fetch_assoc()) {
    $mensajes[] = $row;
}
$stmt->close();

echo json_encode(["mensajes" => $mensajes]);