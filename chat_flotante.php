<?php
if (!isset($_SESSION['id'])) {
    die("Acceso denegado");
}
require_once 'conexion.php';

$mi_id = $_SESSION['id'];
$usuarios = [];
$notificaciones = 0;

// Actualiza el estado en línea del usuario
$mysqli->query("UPDATE usuarios SET online = 1 WHERE id = $mi_id");

$query = "
    SELECT u.id, u.usuario, u.online,
           (SELECT COUNT(*) FROM mensajes m WHERE m.emisor_id = u.id AND m.receptor_id = ? AND m.leido = 0) AS no_leidos,
           (SELECT mensaje FROM mensajes m2 WHERE (m2.emisor_id = u.id AND m2.receptor_id = ?) OR (m2.receptor_id = u.id AND m2.emisor_id = ?) ORDER BY m2.fecha DESC LIMIT 1) AS ultimo
    FROM usuarios u
    WHERE u.id != ?
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("iiii", $mi_id, $mi_id, $mi_id, $mi_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
    $notificaciones += $row['no_leidos'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat Flotante</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- Botón flotante -->
<button id="open-chat-btn" title="Abrir chat">
    <i class="fas fa-comment"></i>
    <span id="notificaciones-count" class="notificacion"><?= $notificaciones > 0 ? $notificaciones : '' ?></span>
</button>

<!-- Contenedor del chat -->
<div id="chat-container">
    <div id="chat-header">
        <span>Chat</span>
        <button id="close-btn"><i class="fas fa-times"></i></button>
    </div>

    <!-- Selector de usuarios -->
    <div id="user-selector">
        <label for="usuario-select">Selecciona un usuario:</label>
        <select id="usuario-select" onchange="abrirChat(this.value)">
            <option value="">-- Elegir usuario --</option>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id'] ?>">
                    <?= htmlspecialchars($u['usuario']) ?>
                    <?= $u['online'] ? '🟢' : '🔴' ?> <!-- Indica si está conectado o desconectado -->
                    <?php if ($u['no_leidos'] > 0): ?> (<?= $u['no_leidos'] ?>)<?php endif; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="chat-mensajes"></div>

    <form id="chat-form">
        <input type="text" id="mensaje" placeholder="Escribe un mensaje...">
        <button type="submit">Enviar</button>
    </form>
</div>

<!-- Este script pasa el ID del usuario al JS -->
<script>
    const mi_id = <?= json_encode($mi_id); ?>;
</script>

<!-- Carga el script externo -->
<script src="js/mensaje.js"></script>

</body>
</html>
