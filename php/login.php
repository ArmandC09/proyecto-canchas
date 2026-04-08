<?php
declare(strict_types=1);

require_once __DIR__ . '/auth_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../frontend/login.php');
}

$correo = validate_email_or_redirect($_POST['correo'] ?? '', '../frontend/login.php');
$password = $_POST['password'] ?? '';

$user = find_user_by_email($correo);

if (!$user || !password_verify($password, $user['password_hash'])) {
    redirect_to('../frontend/login.php', ['error' => 'Correo o contraseña incorrectos.']);
}

create_session_from_user($user);
redirect_after_login($user);
