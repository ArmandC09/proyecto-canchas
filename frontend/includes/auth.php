<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar helpers de base de datos si aún no están cargados
if (!function_exists('db')) {
    $auth_db_path = __DIR__ . '/../../php/conexion.php';
    if (file_exists($auth_db_path)) {
        require_once $auth_db_path;
    }
}

if (!function_exists('get_propietario_id')) {
    $auth_helpers_path = __DIR__ . '/../../php/auth_helpers.php';
    if (file_exists($auth_helpers_path)) {
        // Solo cargar las funciones utilitarias sin re-iniciar sesión
        require_once $auth_helpers_path;
    }
}

$session_usuario = $_SESSION['usuario'] ?? null;

function require_login(?string $rol = null): void
{
    global $session_usuario;

    if (!$session_usuario) {
        header('Location: ../login.php?error=' . urlencode('Debes iniciar sesión para continuar.'));
        exit;
    }

    if ($rol !== null && (($session_usuario['rol'] ?? '') !== $rol)) {
        header('Location: ../login.php?error=' . urlencode('No tienes permiso para acceder a esa sección.'));
        exit;
    }
}
