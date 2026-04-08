<?php
declare(strict_types=1);
require_once __DIR__ . '/conexion.php';

function listar_reservas_usuario(int $usuario_id, string $estado = 'pendiente'): array
{
    $estados_validos = ['pendiente','confirmada','cancelada','completada'];
    if (!in_array($estado, $estados_validos)) $estado = 'pendiente';

    $stmt = db()->prepare('
        SELECT r.id, r.fecha, r.hora_inicio, r.hora_fin, r.estado,
               c.nombre AS cancha_nombre, c.direccion, c.imagen_url, c.id AS cancha_id,
               p.monto, p.estado AS pago_estado
        FROM reservas r
        JOIN canchas c ON r.cancha_id = c.id
        LEFT JOIN pagos p ON p.reserva_id = r.id
        WHERE r.usuario_id = ? AND r.estado = ?
        ORDER BY r.fecha DESC, r.hora_inicio DESC
    ');
    $stmt->execute([$usuario_id, $estado]);
    $rows = $stmt->fetchAll();

    // Formatear para vista
    $dias_es = ['Monday'=>'Lunes','Tuesday'=>'Martes','Wednesday'=>'Miércoles',
                'Thursday'=>'Jueves','Friday'=>'Viernes','Saturday'=>'Sábado','Sunday'=>'Domingo'];
    $meses_es = ['January'=>'enero','February'=>'febrero','March'=>'marzo','April'=>'abril',
                 'May'=>'mayo','June'=>'junio','July'=>'julio','August'=>'agosto',
                 'September'=>'septiembre','October'=>'octubre','November'=>'noviembre','December'=>'diciembre'];

    foreach ($rows as &$r) {
        $ts = strtotime($r['fecha']);
        $day   = date('l', $ts);
        $month = date('F', $ts);
        $r['fecha_display'] = ($dias_es[$day] ?? $day) . ', ' . date('j', $ts) . ' de ' . ($meses_es[$month] ?? $month);
        $r['hora'] = date('g:i A', strtotime($r['hora_inicio']));
    }
    return $rows;
}

function contar_reservas_usuario(int $usuario_id): array
{
    $stmt = db()->prepare('
        SELECT estado, COUNT(*) as total FROM reservas
        WHERE usuario_id=? GROUP BY estado
    ');
    $stmt->execute([$usuario_id]);
    $conteos = ['pendiente'=>0,'confirmada'=>0,'cancelada'=>0,'completada'=>0];
    foreach ($stmt->fetchAll() as $row) {
        $conteos[$row['estado']] = (int)$row['total'];
    }
    return $conteos;
}

function reservas_proximas_usuario(int $usuario_id): array
{
    $stmt = db()->prepare('
        SELECT r.id, r.fecha, r.hora_inicio, r.estado,
               c.nombre AS cancha_nombre
        FROM reservas r
        JOIN canchas c ON r.cancha_id = c.id
        WHERE r.usuario_id=? AND r.fecha >= CURDATE() AND r.estado IN ("pendiente","confirmada")
        ORDER BY r.fecha ASC, r.hora_inicio ASC
        LIMIT 5
    ');
    $stmt->execute([$usuario_id]);
    $rows = $stmt->fetchAll();
    $dias_es = ['Monday'=>'Lunes','Tuesday'=>'Martes','Wednesday'=>'Miércoles',
                'Thursday'=>'Jueves','Friday'=>'Viernes','Saturday'=>'Sábado','Sunday'=>'Domingo'];
    foreach ($rows as &$r) {
        $ts = strtotime($r['fecha']);
        $day = date('l', $ts);
        $r['fecha_display'] = ($dias_es[$day] ?? $day) . ' ' . date('j/m', $ts);
        $r['hora'] = date('g:i A', strtotime($r['hora_inicio']));
        $r['deporte_emoji'] = '⚽';
    }
    return $rows;
}
