<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_helpers.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$session_usuario = $_SESSION['usuario'] ?? null;

if (!$session_usuario) {
    redirect_to('../frontend/login.php', ['error' => 'Debes iniciar sesión para reservar.']);
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../frontend/buscar-cancha.php');
}

$cancha_id = (int)($_POST['cancha_id'] ?? 0);
$fecha     = trim($_POST['fecha'] ?? '');
$hora_ini  = trim($_POST['hora'] ?? '');

if ($cancha_id <= 0 || $fecha === '' || $hora_ini === '') {
    redirect_to('../frontend/detalle-cancha.php?id='.$cancha_id, ['error' => 'Datos incompletos.']);
}

// Parsear hora (formato "7:00 PM" o "19:00")
function parse_hora_reserva(string $h): string {
    $t = DateTime::createFromFormat('g:i A', strtoupper(trim($h)));
    if (!$t) $t = DateTime::createFromFormat('H:i', $h);
    return $t ? $t->format('H:i:s') : '00:00:00';
}

$hora_inicio = parse_hora_reserva($hora_ini);
// Asumir 1 hora de duración
$dt = DateTime::createFromFormat('H:i:s', $hora_inicio);
$dt->modify('+1 hour');
$hora_fin = $dt->format('H:i:s');

$pdo = db();

// Verificar que no hay traslape en esa fecha/hora
$check = $pdo->prepare('
    SELECT id FROM reservas
    WHERE cancha_id=? AND fecha=? AND estado NOT IN ("cancelada")
    AND NOT (hora_fin <= ? OR hora_inicio >= ?)
    LIMIT 1
');
$check->execute([$cancha_id, $fecha, $hora_inicio, $hora_fin]);
if ($check->fetch()) {
    redirect_to('../frontend/detalle-cancha.php?id='.$cancha_id, ['error' => 'Ese horario ya fue reservado. Elige otro.']);
}

// Verificar que la cancha tiene ese día/hora habilitado
$ts = strtotime($fecha);
$dias_map = [1=>'lunes',2=>'martes',3=>'miercoles',4=>'jueves',5=>'viernes',6=>'sabado',7=>'domingo'];
$dia = $dias_map[(int)date('N', $ts)] ?? '';

$hor_check = $pdo->prepare('
    SELECT id FROM horarios
    WHERE cancha_id=? AND dia_semana=? AND hora_inicio <= ? AND hora_fin >= ?
    LIMIT 1
');
$hor_check->execute([$cancha_id, $dia, $hora_inicio, $hora_fin]);
if (!$hor_check->fetch()) {
    redirect_to('../frontend/detalle-cancha.php?id='.$cancha_id, ['error' => 'La cancha no está disponible en ese horario.']);
}

// Obtener precio
$precio_row = $pdo->prepare('SELECT precio_por_hora FROM canchas WHERE id=?');
$precio_row->execute([$cancha_id]);
$cancha_data = $precio_row->fetch();
$monto = $cancha_data ? (float)$cancha_data['precio_por_hora'] : 0;

// Guardar reserva
$pdo->beginTransaction();
try {
    $ins = $pdo->prepare('INSERT INTO reservas (usuario_id,cancha_id,fecha,hora_inicio,hora_fin,estado) VALUES (?,?,?,?,?,?)');
    $ins->execute([(int)$session_usuario['id'], $cancha_id, $fecha, $hora_inicio, $hora_fin, 'pendiente']);
    $reserva_id = (int)$pdo->lastInsertId();

    // Crear pago pendiente
    $pago = $pdo->prepare('INSERT INTO pagos (reserva_id,monto,estado) VALUES (?,?,?)');
    $pago->execute([$reserva_id, $monto, 'pendiente']);

    $pdo->commit();
    redirect_to('../frontend/usuario/mis-reservas.php', ['success' => '¡Reserva registrada! Pendiente de confirmación.']);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    redirect_to('../frontend/detalle-cancha.php?id='.$cancha_id, ['error' => 'No se pudo registrar la reserva.']);
}
