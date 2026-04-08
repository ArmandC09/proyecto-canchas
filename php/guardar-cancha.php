<?php
declare(strict_types=1);
require_once __DIR__ . '/auth_helpers.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$session_usuario = $_SESSION['usuario'] ?? null;

if (!$session_usuario || $session_usuario['rol'] !== 'propietario') {
    redirect_to('../frontend/login.php', ['error' => 'Debes iniciar sesión como propietario.']);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../frontend/propietario/publicar-cancha.php');
}

$propietario_id = get_propietario_id((int)$session_usuario['id']);
if (!$propietario_id) {
    redirect_to('../frontend/propietario/publicar-cancha.php', ['error' => 'No se encontró tu perfil de propietario.']);
}

$cancha_id       = (int)($_POST['cancha_id'] ?? 0);
$nombre          = trim($_POST['nombre'] ?? '');
$direccion       = trim($_POST['direccion'] ?? '');
$precio          = (float)($_POST['precio'] ?? 0);
$descripcion     = trim($_POST['descripcion'] ?? '');
$hora_inicio_str = $_POST['hora_inicio'] ?? '06:00 AM';
$hora_fin_str    = $_POST['hora_fin']    ?? '10:00 PM';
$dias            = $_POST['dias']     ?? [];
$deportes        = $_POST['deportes'] ?? [];

if ($nombre === '' || $direccion === '' || $precio <= 0) {
    redirect_to('../frontend/propietario/publicar-cancha.php', ['error' => 'Completa los campos obligatorios.']);
}

function parse_hora(string $h): string {
    $t = DateTime::createFromFormat('g:i A', strtoupper(trim($h)));
    if (!$t) $t = DateTime::createFromFormat('H:i', $h);
    return $t ? $t->format('H:i:s') : '06:00:00';
}
$hora_inicio = parse_hora($hora_inicio_str);
$hora_fin    = parse_hora($hora_fin_str);

$dias_map = ['lun'=>'lunes','mar'=>'martes','mie'=>'miercoles','jue'=>'jueves','vie'=>'viernes','sab'=>'sabado','dom'=>'domingo'];

// ─── IMAGEN ──────────────────────────────────────────────────────────────────
// dirname(__DIR__) = raíz del proyecto (proyecto-canchas/)
// Funciona igual en Windows (XAMPP) y Linux (AWS)
$project_root = dirname(__DIR__);
$upload_dir   = $project_root . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'canchas' . DIRECTORY_SEPARATOR;

$imagen_url = null;

if (
    isset($_FILES['imagenes']['name'][0]) &&
    $_FILES['imagenes']['name'][0] !== '' &&
    $_FILES['imagenes']['error'][0] === UPLOAD_ERR_OK
) {
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file          = $_FILES['imagenes'];
    $ext           = strtolower(pathinfo($file['name'][0], PATHINFO_EXTENSION));
    $allowed_exts  = ['jpg', 'jpeg', 'png', 'webp'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];

    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimetype = $finfo->file($file['tmp_name'][0]);

    if (
        in_array($ext, $allowed_exts, true) &&
        in_array($mimetype, $allowed_types, true) &&
        $file['size'][0] <= 5 * 1024 * 1024
    ) {
        $filename = uniqid('cancha_', true) . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'][0], $upload_dir . $filename)) {
            // Siempre slash normal — funciona en URLs y en AWS Linux
            $imagen_url = 'uploads/canchas/' . $filename;
        }
    }
}
// ─────────────────────────────────────────────────────────────────────────────

$pdo = db();
$pdo->beginTransaction();
try {
    if ($cancha_id > 0) {
        $check = $pdo->prepare('SELECT id FROM canchas WHERE id=? AND propietario_id=?');
        $check->execute([$cancha_id, $propietario_id]);
        if (!$check->fetch()) throw new Exception('No tienes permiso para editar esta cancha.');

        $sql    = 'UPDATE canchas SET nombre=?, direccion=?, precio_por_hora=?, descripcion=?';
        $params = [$nombre, $direccion, $precio, $descripcion ?: null];
        if ($imagen_url) { $sql .= ', imagen_url=?'; $params[] = $imagen_url; }
        $sql   .= ' WHERE id=?';
        $params[] = $cancha_id;
        $pdo->prepare($sql)->execute($params);
    } else {
        $stmt = $pdo->prepare('INSERT INTO canchas (propietario_id,nombre,direccion,precio_por_hora,descripcion,imagen_url) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$propietario_id, $nombre, $direccion, $precio, $descripcion ?: null, $imagen_url]);
        $cancha_id = (int)$pdo->lastInsertId();
    }

    // Deportes
    $pdo->prepare('DELETE FROM cancha_deporte WHERE cancha_id=?')->execute([$cancha_id]);
    if (!empty($deportes)) {
        $dep_stmt = $pdo->prepare('SELECT id FROM deportes WHERE nombre=?');
        $ins_dep  = $pdo->prepare('INSERT IGNORE INTO cancha_deporte (cancha_id,deporte_id) VALUES (?,?)');
        foreach ($deportes as $dep) {
            $dep_stmt->execute([trim($dep)]);
            $row = $dep_stmt->fetch();
            if ($row) $ins_dep->execute([$cancha_id, $row['id']]);
        }
    }

    // Horarios
    $pdo->prepare('DELETE FROM horarios WHERE cancha_id=?')->execute([$cancha_id]);
    if (!empty($dias)) {
        $hor_stmt = $pdo->prepare('INSERT INTO horarios (cancha_id,dia_semana,hora_inicio,hora_fin) VALUES (?,?,?,?)');
        foreach ($dias as $dia_key) {
            $dia = $dias_map[$dia_key] ?? null;
            if ($dia) $hor_stmt->execute([$cancha_id, $dia, $hora_inicio, $hora_fin]);
        }
    }

    $pdo->commit();
    redirect_to('../frontend/propietario/mis-canchas.php', ['success' => 'Cancha guardada correctamente.']);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    redirect_to('../frontend/propietario/publicar-cancha.php', ['error' => 'Error al guardar: ' . $e->getMessage()]);
}
