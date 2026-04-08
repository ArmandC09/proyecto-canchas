<?php
declare(strict_types=1);

require_once __DIR__ . '/auth_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../frontend/registro-propietario.php');
}

$nombre = clean_string($_POST['nombre'] ?? '');
$direccion = clean_string($_POST['direccion'] ?? '');
$correo = validate_email_or_redirect($_POST['correo'] ?? '', '../frontend/registro-propietario.php');
$telefono = clean_string($_POST['telefono'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirmPassword'] ?? '';

if ($nombre === '' || $telefono === '') {
    redirect_to('../frontend/registro-propietario.php', ['error' => 'Completa todos los campos obligatorios.']);
}

validate_passwords_or_redirect($password, $confirm, '../frontend/registro-propietario.php');

if (find_user_by_email($correo)) {
    redirect_to('../frontend/registro-propietario.php', ['error' => 'Ese correo ya está registrado.']);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$pdo = db();
$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, correo, telefono, password_hash, rol) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$nombre, $correo, $telefono, $hash, 'propietario']);

    $usuarioId = (int)$pdo->lastInsertId();

    $stmt2 = $pdo->prepare('INSERT INTO propietarios (usuario_id, direccion_referencia) VALUES (?, ?)');
    $stmt2->execute([$usuarioId, $direccion !== '' ? $direccion : null]);

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    redirect_to('../frontend/registro-propietario.php', ['error' => 'No se pudo crear la cuenta. Inténtalo de nuevo.']);
}

redirect_to('../frontend/login.php', ['success' => 'Cuenta de propietario creada correctamente.']);
