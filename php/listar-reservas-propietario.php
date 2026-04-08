<?php
declare(strict_types=1);
require_once __DIR__ . '/conexion.php';

function listar_reservas_propietario(int $propietario_id, string $estado = 'pendiente', int $cancha_id = 0): array
{
    $estados_validos = ['pendiente','confirmada','cancelada','completada'];
    if (!in_array($estado, $estados_validos)) $estado = 'pendiente';

    $sql = '
        SELECT r.id, r.fecha, r.hora_inicio, r.hora_fin, r.estado,
               u.nombre AS usuario_nombre, u.telefono AS usuario_telefono,
               c.nombre AS cancha_nombre, c.id AS cancha_id,
               p.monto, p.estado AS pago_estado
        FROM reservas r
        JOIN canchas c ON r.cancha_id = c.id
        JOIN usuarios u ON r.usuario_id = u.id
        LEFT JOIN pagos p ON p.reserva_id = r.id
        WHERE c.propietario_id = ? AND r.estado = ?
    ';
    $params = [$propietario_id, $estado];

    if ($cancha_id > 0) {
        $sql .= ' AND c.id = ?';
        $params[] = $cancha_id;
    }
    $sql .= ' ORDER BY r.fecha ASC, r.hora_inicio ASC';

    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $dias_es = ['Monday'=>'Lunes','Tuesday'=>'Martes','Wednesday'=>'Miércoles',
                'Thursday'=>'Jueves','Friday'=>'Viernes','Saturday'=>'Sábado','Sunday'=>'Domingo'];
    foreach ($rows as &$r) {
        $ts = strtotime($r['fecha']);
        $r['fecha_display'] = ($dias_es[date('l',$ts)] ?? date('l',$ts)) . ', ' . date('j/m/Y',$ts);
        $r['hora'] = date('g:i A', strtotime($r['hora_inicio'])) . ' – ' . date('g:i A', strtotime($r['hora_fin']));
    }
    return $rows;
}

function contar_reservas_propietario(int $propietario_id): array
{
    $stmt = db()->prepare('
        SELECT r.estado, COUNT(*) as total
        FROM reservas r
        JOIN canchas c ON r.cancha_id = c.id
        WHERE c.propietario_id = ?
        GROUP BY r.estado
    ');
    $stmt->execute([$propietario_id]);
    $conteos = ['pendiente'=>0,'confirmada'=>0,'cancelada'=>0,'completada'=>0];
    foreach ($stmt->fetchAll() as $row) {
        $conteos[$row['estado']] = (int)$row['total'];
    }
    return $conteos;
}

function stats_propietario(int $propietario_id): array
{
    $pdo = db();
    $canchas = $pdo->prepare('SELECT COUNT(*) FROM canchas WHERE propietario_id=?');
    $canchas->execute([$propietario_id]);

    $pendientes = $pdo->prepare('
        SELECT COUNT(*) FROM reservas r
        JOIN canchas c ON r.cancha_id=c.id
        WHERE c.propietario_id=? AND r.estado="pendiente"
    ');
    $pendientes->execute([$propietario_id]);

    $ingresos = $pdo->prepare('
        SELECT COALESCE(SUM(p.monto),0) FROM pagos p
        JOIN reservas r ON p.reserva_id=r.id
        JOIN canchas c ON r.cancha_id=c.id
        WHERE c.propietario_id=? AND p.estado="pagado"
        AND MONTH(p.fecha_pago)=MONTH(NOW()) AND YEAR(p.fecha_pago)=YEAR(NOW())
    ');
    $ingresos->execute([$propietario_id]);

    return [
        'canchas'             => (int)$canchas->fetchColumn(),
        'reservas_pendientes' => (int)$pendientes->fetchColumn(),
        'ingresos_mes'        => (float)$ingresos->fetchColumn(),
    ];
}

function listar_canchas_propietario(int $propietario_id): array
{
    $stmt = db()->prepare('
        SELECT c.id, c.nombre, c.direccion, c.precio_por_hora, c.imagen_url, c.estado,
               GROUP_CONCAT(d.nombre ORDER BY d.nombre SEPARATOR ", ") AS deportes_str,
               (SELECT COUNT(*) FROM reservas r WHERE r.cancha_id=c.id AND r.estado="pendiente") AS reservas_pendientes
        FROM canchas c
        LEFT JOIN cancha_deporte cd ON cd.cancha_id = c.id
        LEFT JOIN deportes d ON cd.deporte_id = d.id
        WHERE c.propietario_id = ?
        GROUP BY c.id
        ORDER BY c.nombre
    ');
    $stmt->execute([$propietario_id]);
    $rows = $stmt->fetchAll();
    foreach ($rows as &$r) {
        $r['activa'] = ($r['estado'] === 'disponible');
        $r['imagen_principal'] = $r['imagen_url'] ?: null;
    }
    return $rows;
}
