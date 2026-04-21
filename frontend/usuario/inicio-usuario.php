<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../php/listar-reservas-usuario.php';
$base = '../';
$php_base = '../../php/';
$nav_active = 'inicio';
require_login('usuario');

$reservas_proximas = reservas_proximas_usuario((int)$session_usuario['id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio | AlquilaTuCancha</title>
  <link rel="stylesheet" href="../../styles/global.css">
  <link rel="stylesheet" href="../../styles/inicio-usuario.css">
</head>
<body>
  <?php include '../includes/navbar-panel.php'; ?>

  <header class="hero-banner user-hero">
    <div class="hero-inner">
      <h1>¡Hola, <?= htmlspecialchars($session_usuario['nombre']) ?>!</h1>
      <p>Busca una cancha, reserva tu horario y a jugar sin complicaciones.</p>
    </div>
  </header>

  <main class="container page-pad">
    <?php if (!empty($_GET['success'])): ?>
      <div class="alert alert-success" style="margin-bottom:16px;"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <section class="quick-grid">
      <article class="quick-card">
        <div class="icon">🔎</div>
        <h3>Buscar Canchas</h3>
        <p>Encuentra la mejor cancha según ubicación, deporte y fecha.</p>
        <a href="../buscar-cancha.php" class="btn btn-green">Buscar Ahora</a>
      </article>
      <article class="quick-card">
        <div class="icon">📅</div>
        <h3>Mis Reservas</h3>
        <p>Revisa tus reservas activas, pendientes y tus partidos anteriores.</p>
        <a href="mis-reservas.php" class="btn btn-green">Ver Reservas</a>
      </article>
      <article class="quick-card">
        <div class="icon">🏟️</div>
        <h3>Reservar Rápido</h3>
        <p>Reserva rápido tu horario favorito y asegura tu partido de hoy.</p>
        <a href="../buscar-cancha.php?fecha=<?= date('Y-m-d') ?>" class="btn btn-green">Reservar Hoy</a>
      </article>
    </section>

    <?php if (!empty($reservas_proximas)): ?>
      <div class="section-title" style="margin-top:32px;">
        <h2>Tus Próximos Partidos</h2>
        <span></span>
      </div>
      <div class="proximas-list">
        <?php foreach ($reservas_proximas as $r): ?>
          <div class="proxima-card">
            <span class="proxima-deporte"><?= $r['deporte_emoji'] ?></span>
            <div class="proxima-info">
              <strong><?= htmlspecialchars($r['cancha_nombre']) ?></strong>
              <span><?= htmlspecialchars($r['fecha_display']) ?> · <?= htmlspecialchars($r['hora']) ?></span>
            </div>
            <span class="badge <?= $r['estado']==='confirmada'?'badge-green':'badge-orange' ?>">
              <?= ucfirst($r['estado']) ?>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <script src="../../js/nav.js"></script>
</body>
</html>
