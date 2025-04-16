<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['id'])) {
    http_response_code(403);
    exit("Acceso denegado");
}

$emisor_id = $_SESSION['id'];
$receptor_id = $_POST['receptor_id'] ?? null;
$mensaje = trim($_POST['mensaje'] ?? '');

if (!$receptor_id || !$mensaje) {
    http_response_code(400);
    exit("Datos incompletos");
}

$stmt = $mysqli->prepare("INSERT INTO mensajes (emisor_id, receptor_id, mensaje, fecha, leido) VALUES (?, ?, ?, NOW(), 0)");
$stmt->bind_param("iis", $emisor_id, $receptor_id, $mensaje);
$stmt->execute();
$stmt->close();

echo "Mensaje enviado";