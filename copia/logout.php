<?php
session_start();
require_once 'conexion.php';

if (isset($_SESSION['id'])) {
    $usuario_id = $_SESSION['id'];
    $mysqli->query("UPDATE usuarios SET online = 0 WHERE id = $usuario_id");
    session_destroy();
}

header("Location: index.php"); // Redirige a la página de login
exit();
