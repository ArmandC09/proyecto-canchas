<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_helpers.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$session_usuario = $_SESSION['usuario'] ?? null;

if (!$session_usuario) {
    redirect_to('../frontend/login.php', ['error' => 'Debes iniciar sesión.']);
}

$reserva_id = (int)($_GET['id'] ?? 0);
if ($reserva_id <= 0) {
    $back = ($session_usuario['rol'] === 'propietario')
        ? '../frontend/propietario/gestion-reservas.php'
        : '../frontend/usuario/mis-reservas.php';
    redirect_to($back, ['error' => 'ID inválido.']);
}

$pdo = db();

if ($session_usuario['rol'] === 'propietario') {
    $propietario_id = get_propietario_id((int)$session_usuario['id']);
    $check = $pdo->prepare('
        SELECT r.id FROM reservas r JOIN canchas c ON r.cancha_id=c.id
        WHERE r.id=? AND c.propietario_id=? AND r.estado NOT IN ("cancelada","completada")
    ');
    $check->execute([$reserva_id, $propietario_id]);
    $back = '../frontend/propietario/gestion-reservas.php';
} else {
    $check = $pdo->prepare('
        SELECT id FROM reservas WHERE id=? AND usuario_id=? AND estado NOT IN ("cancelada","completada")
    ');
    $check->execute([$reserva_id, (int)$session_usuario['id']]);
    $back = '../frontend/usuario/mis-reservas.php';
}

if (!$check->fetch()) {
    redirect_to($back, ['error' => 'No puedes cancelar esta reserva.']);
}

$pdo->prepare('UPDATE reservas SET estado="cancelada" WHERE id=?')->execute([$reserva_id]);
redirect_to($back, ['success' => 'Reserva cancelada.']);
