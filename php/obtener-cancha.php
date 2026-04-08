<?php
declare(strict_types=1);
require_once __DIR__ . '/conexion.php';

function obtener_cancha(int $id): ?array
{
    $stmt = db()->prepare('
        SELECT c.id, c.nombre, c.direccion, c.precio_por_hora,
               c.descripcion, c.imagen_url, c.tipo_superficie, c.estado,
               u.telefono,
               GROUP_CONCAT(d.nombre ORDER BY d.nombre SEPARATOR ",") AS deportes_str
        FROM canchas c
        LEFT JOIN cancha_deporte cd ON cd.cancha_id = c.id
        LEFT JOIN deportes d        ON cd.deporte_id = d.id
        LEFT JOIN propietarios p    ON p.id = c.propietario_id
        LEFT JOIN usuarios u        ON u.id = p.usuario_id
        WHERE c.id = ?
        GROUP BY c.id
    ');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) return null;

    $row['deportes'] = $row['deportes_str'] ? explode(',', $row['deportes_str']) : [];
    return $row;
}

function obtener_slots_disponibles(int $cancha_id, string $fecha): array
{
    $ts = strtotime($fecha);
    if ($ts === false) return [];

    $dias_map = [
        1 => 'lunes', 2 => 'martes', 3 => 'miercoles',
        4 => 'jueves', 5 => 'viernes', 6 => 'sabado', 7 => 'domingo',
    ];
    $dia = $dias_map[(int)date('N', $ts)] ?? '';

    $stmt = db()->prepare('SELECT hora_inicio, hora_fin FROM horarios WHERE cancha_id=? AND dia_semana=?');
    $stmt->execute([$cancha_id, $dia]);
    $horarios = $stmt->fetchAll();
    if (empty($horarios)) return [];

    $res = db()->prepare('
        SELECT hora_inicio, hora_fin FROM reservas
        WHERE cancha_id=? AND fecha=? AND estado NOT IN ("cancelada")
    ');
    $res->execute([$cancha_id, $fecha]);
    $reservadas = $res->fetchAll();

    $slots = [];
    foreach ($horarios as $h) {
        $start = strtotime($fecha . ' ' . $h['hora_inicio']);
        $end   = strtotime($fecha . ' ' . $h['hora_fin']);
        $cur   = $start;
        while ($cur + 3600 <= $end) {
            $slot_ini = date('H:i:s', $cur);
            $slot_fin = date('H:i:s', $cur + 3600);
            $libre    = true;
            foreach ($reservadas as $r) {
                if (!($r['hora_fin'] <= $slot_ini || $r['hora_inicio'] >= $slot_fin)) {
                    $libre = false;
                    break;
                }
            }
            $slots[] = [
                'hora'       => date('g:i A', $cur),
                'hora_raw'   => $slot_ini,
                'disponible' => $libre,
            ];
            $cur += 3600;
        }
    }
    return $slots;
}
