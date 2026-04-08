<?php
declare(strict_types=1);
require_once __DIR__ . '/conexion.php';

/**
 * Retorna array de canchas filtradas.
 * Usar: $canchas = listar_canchas($_GET);
 */
function listar_canchas(array $filtros = []): array
{
    $deporte   = trim($filtros['deporte']   ?? '');
    $ubicacion = trim($filtros['ubicacion'] ?? '');
    $fecha     = trim($filtros['fecha']     ?? '');

    $sql = '
        SELECT c.id, c.nombre, c.direccion, c.precio_por_hora, c.imagen_url,
               GROUP_CONCAT(d.nombre ORDER BY d.nombre SEPARATOR ",") AS deportes_str
        FROM canchas c
        LEFT JOIN cancha_deporte cd ON cd.cancha_id = c.id
        LEFT JOIN deportes d ON cd.deporte_id = d.id
        WHERE c.estado = "disponible"
    ';
    $params = [];

    if ($deporte !== '') {
        $sql .= ' AND c.id IN (
            SELECT cd2.cancha_id FROM cancha_deporte cd2
            JOIN deportes d2 ON cd2.deporte_id = d2.id
            WHERE d2.nombre = ?)';
        $params[] = $deporte;
    }

    if ($ubicacion !== '') {
        $sql .= ' AND c.direccion LIKE ?';
        $params[] = "%{$ubicacion}%";
    }

    if ($fecha !== '') {
        $ts = strtotime($fecha);
        if ($ts !== false) {
            $dias_map = [1=>'lunes',2=>'martes',3=>'miercoles',4=>'jueves',5=>'viernes',6=>'sabado',7=>'domingo'];
            $dia = $dias_map[(int)date('N', $ts)] ?? '';
            if ($dia) {
                $sql .= ' AND c.id IN (SELECT h.cancha_id FROM horarios h WHERE h.dia_semana = ?)';
                $params[] = $dia;
            }
        }
    }

    $sql .= ' GROUP BY c.id ORDER BY c.nombre';
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
