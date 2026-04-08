<?php
/**
 * imagen_url()
 * Devuelve la URL pública completa de una imagen guardada en BD.
 *
 * La BD guarda rutas relativas a la raíz del proyecto:
 *   "uploads/canchas/cancha_abc123.jpg"
 *
 * Esta función construye:
 *   LOCAL:  http://localhost/proyecto-canchas/uploads/canchas/cancha_abc123.jpg
 *   AWS:    http://mi-dominio.com/uploads/canchas/cancha_abc123.jpg
 *
 * En producción define la variable de entorno APP_URL:
 *   APP_URL=http://mi-app.elasticbeanstalk.com
 */
function imagen_url(?string $imagen_url, string $fallback = 'imagenes/buscar-cancha.png'): string
{
    $app_url = rtrim((string)(getenv('APP_URL') ?: ''), '/');

    if ($app_url === '') {
        // ── Auto-detección local (XAMPP / desarrollo) ──────────────────────
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';

        // SCRIPT_NAME ejemplo: /proyecto-canchas/frontend/propietario/mis-canchas.php
        // Queremos quedarnos solo con /proyecto-canchas
        $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $parts  = array_filter(explode('/', $script));   // ['proyecto-canchas','frontend','propietario','mis-canchas.php']

        $skip = ['frontend', 'propietario', 'usuario', 'php'];
        $base_parts = [];
        foreach ($parts as $part) {
            if (in_array($part, $skip, true) || str_ends_with($part, '.php')) {
                break;
            }
            $base_parts[] = $part;
        }

        $base_path = $base_parts ? '/' . implode('/', $base_parts) : '';
        $app_url   = $scheme . '://' . $host . $base_path;
    }

    // ── Construir URL final ────────────────────────────────────────────────
    $path = $imagen_url ?: $fallback;
    // Eliminar ../ residuales que puedan quedar de versiones viejas en BD
    $path = ltrim(preg_replace('#^(\.\./)+#', '', $path), '/');

    return rtrim($app_url, '/') . '/' . $path;
}
