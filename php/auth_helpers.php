<?php
declare(strict_types=1);

require_once __DIR__ . '/conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirect_to(string $path, array $query = []): void
{
    $url = $path;
    if (!empty($query)) $url .= '?' . http_build_query($query);
    header('Location: ' . $url);
    exit;
}

function clean_string(string $value): string
{
    return trim($value);
}

function validate_email_or_redirect(string $email, string $redirectPath): string
{
    $email = trim($email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_to($redirectPath, ['error' => 'Correo inválido.']);
    }
    return mb_strtolower($email);
}

function validate_passwords_or_redirect(string $password, string $confirm, string $redirectPath): void
{
    if (strlen($password) < 6) {
        redirect_to($redirectPath, ['error' => 'La contraseña debe tener al menos 6 caracteres.']);
    }
    if ($password !== $confirm) {
        redirect_to($redirectPath, ['error' => 'Las contraseñas no coinciden.']);
    }
}

function find_user_by_email(string $email): ?array
{
    $stmt = db()->prepare('SELECT * FROM usuarios WHERE correo = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function create_session_from_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['usuario'] = [
        'id'     => (int)$user['id'],
        'nombre' => $user['nombre'],
        'correo' => $user['correo'],
        'rol'    => $user['rol'],
    ];
}

function redirect_after_login(array $user): void
{
    if (($user['rol'] ?? '') === 'propietario') {
        redirect_to('../frontend/propietario/inicio-propietario.php');
    }
    redirect_to('../frontend/usuario/inicio-usuario.php');
}

function get_propietario_id(int $usuario_id): ?int
{
    $stmt = db()->prepare('SELECT id FROM propietarios WHERE usuario_id = ? LIMIT 1');
    $stmt->execute([$usuario_id]);
    $row = $stmt->fetch();
    return $row ? (int)$row['id'] : null;
}
