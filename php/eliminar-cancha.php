<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_helpers.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$session_usuario = $_SESSION['usuario'] ?? null;

if (!$session_usuario || $session_usuario['rol'] !== 'propietario') {
    redirect_to('../frontend/login.php', ['error' => 'Acceso denegado.']);
}

$cancha_id = (int)($_GET['id'] ?? 0);
if ($cancha_id <= 0) {
    redirect_to('../frontend/propietario/mis-canchas.php', ['error' => 'ID inválido.']);
}

$propietario_id = get_propietario_id((int)$session_usuario['id']);

$check = db()->prepare('SELECT id FROM canchas WHERE id=? AND propietario_id=?');
$check->execute([$cancha_id, $propietario_id]);
if (!$check->fetch()) {
    redirect_to('../frontend/propietario/mis-canchas.php', ['error' => 'No puedes eliminar esta cancha.']);
}

db()->prepare('DELETE FROM canchas WHERE id=?')->execute([$cancha_id]);
redirect_to('../frontend/propietario/mis-canchas.php', ['success' => 'Cancha eliminada correctamente.']);
