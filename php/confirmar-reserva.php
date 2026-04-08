<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_helpers.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$session_usuario = $_SESSION['usuario'] ?? null;

if (!$session_usuario || $session_usuario['rol'] !== 'propietario') {
    redirect_to('../frontend/login.php', ['error' => 'Acceso denegado.']);
}

$reserva_id = (int)($_GET['id'] ?? 0);
if ($reserva_id <= 0) redirect_to('../frontend/propietario/gestion-reservas.php', ['error' => 'ID inválido.']);

$propietario_id = get_propietario_id((int)$session_usuario['id']);

// Verificar que la reserva es de una cancha de este propietario
$check = db()->prepare('
    SELECT r.id FROM reservas r
    JOIN canchas c ON r.cancha_id = c.id
    WHERE r.id=? AND c.propietario_id=? AND r.estado="pendiente"
');
$check->execute([$reserva_id, $propietario_id]);
if (!$check->fetch()) {
    redirect_to('../frontend/propietario/gestion-reservas.php', ['error' => 'No puedes confirmar esta reserva.']);
}

db()->prepare('UPDATE reservas SET estado="confirmada" WHERE id=?')->execute([$reserva_id]);
redirect_to('../frontend/propietario/gestion-reservas.php', ['success' => 'Reserva confirmada correctamente.']);
