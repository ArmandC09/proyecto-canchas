<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../php/listar-reservas-propietario.php';
require_once __DIR__ . '/../../php/helpers.php';
$base = '../';
$php_base = '../../php/';
$nav_active = 'mis-canchas';
require_login('propietario');

$propietario_id = get_propietario_id((int)$session_usuario['id']);
$canchas = $propietario_id ? listar_canchas_propietario($propietario_id) : [];
$reservas_pendientes = array_sum(array_column($canchas, 'reservas_pendientes'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Canchas | AlquilaTuCancha</title>
  <link rel="stylesheet" href="../../styles/global.css">
  <link rel="stylesheet" href="../../styles/mis-canchas.css">
</head>
<body>
  <?php include '../includes/navbar-panel.php'; ?>

  <main class="list-page">
    <div class="list-wrap">
      <header class="panel-head">
        <div>
          <h1>Mis Canchas</h1>
          <p>Gestiona tus canchas publicadas y controla tus reservas.</p>
        </div>
        <a class="btn btn-green" href="publicar-cancha.php">📣 Publicar Cancha</a>
      </header>

      <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success" style="margin-bottom:16px;"><?= htmlspecialchars($_GET['success']) ?></div>
      <?php endif; ?>
      <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error" style="margin-bottom:16px;"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>

      <section class="main-panel">
        <div class="tabs">
          <button class="tab active">Mis Canchas (<?= count($canchas) ?>)</button>
          <a href="gestion-reservas.php?estado=pendiente" class="tab">Reservas Pendientes (<?= $reservas_pendientes ?>)</a>
        </div>

        <?php if (empty($canchas)): ?>
          <div class="empty-state">
            <div class="empty-icon">🏟️</div>
            <h3>Aún no tienes canchas publicadas</h3>
            <p>Publica tu primera cancha para empezar a recibir reservas y generar ingresos.</p>
            <a href="publicar-cancha.php" class="btn btn-green">📣 Publicar mi primera cancha</a>
          </div>
        <?php else: ?>
          <div class="courts-list">
            <?php foreach ($canchas as $c): ?>
              <article class="court-row">
                <div class="court-media">
                  <?php $img = imagen_url($c['imagen_url']); ?>
                  <img class="main" src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($c['nombre']) ?>">
                </div>
                <div class="court-data">
                  <h3><?= htmlspecialchars($c['nombre']) ?></h3>
                  <p><?= htmlspecialchars($c['direccion']) ?></p>
                  <div class="sports-line"><?= htmlspecialchars($c['deportes_str'] ?? 'Sin deportes asignados') ?></div>
                  <div class="price-line">Desde S/ <?= number_format((float)$c['precio_por_hora'], 0) ?> / hora</div>
                </div>
                <div class="court-side">
                  <span class="badge <?= $c['activa'] ? 'badge-green' : 'badge-gray' ?>">
                    <?= $c['activa'] ? 'Publicada' : 'Pausada' ?>
                  </span>
                  <div class="side-actions">
                    <a href="publicar-cancha.php?id=<?= (int)$c['id'] ?>" class="btn btn-orange btn-sm">Editar</a>
                    <a href="../../php/eliminar-cancha.php?id=<?= (int)$c['id'] ?>" class="btn btn-red btn-sm"
                       onclick="return confirm('¿Eliminar esta cancha? Esta acción no se puede deshacer.')">🗑</a>
                  </div>
                  <a class="count-badge" href="gestion-reservas.php?cancha=<?= (int)$c['id'] ?>">
                    Reservas pendientes <span><?= (int)$c['reservas_pendientes'] ?></span>
                  </a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>
    </div>
  </main>

  <script src="../../js/nav.js"></script>
</body>
</html>
