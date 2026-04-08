<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_helpers.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$session_usuario = $_SESSION['usuario'] ?? null;

if (!$session_usuario || $session_usuario['rol'] !== 'propietario') {
    redirect_to('../frontend/login.php', ['error' => 'Acceso denegado.']);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../frontend/propietario/gestion-reservas.php');
}

$cancha_id = (int)($_POST['cancha_id'] ?? 0);
$fecha     = trim($_POST['fecha'] ?? '');
$propietario_id = get_propietario_id((int)$session_usuario['id']);

$check = db()->prepare('SELECT id FROM canchas WHERE id=? AND propietario_id=?');
$check->execute([$cancha_id, $propietario_id]);
if (!$check->fetch()) {
    redirect_to('../frontend/propietario/gestion-reservas.php', ['error' => 'Cancha no válida.']);
}

// Insertar reserva de bloqueo con usuario_id del propietario mismo
$cancha_data = db()->prepare('SELECT precio_por_hora FROM canchas WHERE id=?');
$cancha_data->execute([$cancha_id]);
$c = $cancha_data->fetch();

$stmt = db()->prepare('INSERT INTO reservas (usuario_id,cancha_id,fecha,hora_inicio,hora_fin,estado) VALUES (?,?,?,?,?,?)');
$stmt->execute([(int)$session_usuario['id'], $cancha_id, $fecha, '00:00:00', '23:59:59', 'confirmada']);
redirect_to('../frontend/propietario/gestion-reservas.php', ['success' => 'Fecha bloqueada correctamente.']);
