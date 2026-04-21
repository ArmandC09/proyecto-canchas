<?php
require_once __DIR__ . '/includes/auth.php';
// UX fix: si ya está logueado, redirigir a su panel
if ($session_usuario) {
    if ($session_usuario['rol'] === 'propietario') {
        header('Location: propietario/inicio-propietario.php');
    } else {
        header('Location: usuario/inicio-usuario.php');
    }
    exit;
}
$base = '';
$php_base = '../php/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio | AlquilaTuCancha</title>
  <link rel="stylesheet" href="../styles/global.css">
  <link rel="stylesheet" href="../styles/index.css">
</head>
<body>
  <?php include 'includes/navbar-public.php'; ?>

  <section class="hero-home">
    <div class="home-content">
      <div class="home-badge">⚡ Reserva fácil, rápida y sin llamadas innecesarias</div>
      <h1>Tu próxima <span class="green">pichanga</span><br>empieza aquí</h1>
      <p class="hero-sub">
        Encuentra una cancha, revisa horarios disponibles y reserva en pocos pasos.
        Más simple para ti, más atractivo para quien quiere jugar hoy mismo.
      </p>
      <div class="home-actions">
        <a href="buscar-cancha.php" class="btn btn-green btn-lg">Buscar una cancha</a>
        <a href="registro-propietario.php" class="btn btn-light btn-lg">Quiero publicar mi cancha</a>
      </div>
    </div>
  </section>

  <script src="../js/nav.js"></script>
</body>
</html>
