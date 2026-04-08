<?php
declare(strict_types=1);

require_once __DIR__ . '/auth_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../frontend/registro-usuario.php');
}

$nombre = clean_string($_POST['nombre'] ?? '');
$correo = validate_email_or_redirect($_POST['correo'] ?? '', '../frontend/registro-usuario.php');
$telefono = clean_string($_POST['telefono'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirmPassword'] ?? '';

if ($nombre === '' || $telefono === '') {
    redirect_to('../frontend/registro-usuario.php', ['error' => 'Completa todos los campos obligatorios.']);
}

validate_passwords_or_redirect($password, $confirm, '../frontend/registro-usuario.php');

if (find_user_by_email($correo)) {
    redirect_to('../frontend/registro-usuario.php', ['error' => 'Ese correo ya está registrado.']);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = db()->prepare('INSERT INTO usuarios (nombre, correo, telefono, password_hash, rol) VALUES (?, ?, ?, ?, ?)');
$stmt->execute([$nombre, $correo, $telefono, $hash, 'usuario']);

redirect_to('../frontend/login.php', ['success' => 'Cuenta creada correctamente. Ya puedes iniciar sesión.']);
