<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../php/listar-reservas-usuario.php';
require_once __DIR__ . '/../../php/helpers.php';
$base = '../';
$php_base = '../../php/';
$nav_active = 'reservas';
require_login('usuario');

$tab_activo = $_GET['estado'] ?? 'pendiente';
$reservas = listar_reservas_usuario((int)$session_usuario['id'], $tab_activo);
$conteos  = contar_reservas_usuario((int)$session_usuario['id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Reservas | AlquilaTuCancha</title>
  <link rel="stylesheet" href="../../styles/global.css">
  <link rel="stylesheet" href="../../styles/mis-reservas.css">
</head>
<body>
  <?php include '../includes/navbar-panel.php'; ?>

  <main class="list-page">
    <div class="list-wrap">
      <header class="panel-head">
        <div>
          <h1>Mis Reservas</h1>
          <p>Revisa tus reservas activas y el historial de tus partidos.</p>
        </div>
      </header>

      <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success" style="margin-bottom:16px;"><?= htmlspecialchars($_GET['success']) ?></div>
      <?php endif; ?>
      <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error" style="margin-bottom:16px;"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>

      <section class="main-panel">
        <div class="tabs">
          <a href="?estado=pendiente"  class="tab <?= $tab_activo==='pendiente' ?'active':'' ?>">Pendientes (<?= $conteos['pendiente'] ?>)</a>
          <a href="?estado=confirmada" class="tab <?= $tab_activo==='confirmada'?'active':'' ?>">Confirmadas (<?= $conteos['confirmada'] ?>)</a>
          <a href="?estado=completada" class="tab <?= $tab_activo==='completada'?'active':'' ?>">Completadas (<?= $conteos['completada'] ?>)</a>
          <a href="?estado=cancelada"  class="tab <?= $tab_activo==='cancelada' ?'active':'' ?>">Canceladas (<?= $conteos['cancelada'] ?>)</a>
        </div>

        <?php if (empty($reservas)): ?>
          <div class="empty-state">
            <div class="empty-icon">📅</div>
            <h3>No tienes reservas <?= $tab_activo === 'pendiente' ? 'pendientes' : $tab_activo.'s' ?></h3>
            <p>
              <?php if ($tab_activo === 'pendiente'): ?>
                Cuando reserves una cancha, aparecerá aquí mientras el propietario la confirma.
              <?php else: ?>
                No hay reservas en esta categoría.
              <?php endif; ?>
            </p>
            <a href="../buscar-cancha.php" class="btn btn-green">🔍 Buscar una cancha</a>
          </div>
        <?php else: ?>
          <?php foreach ($reservas as $r): ?>
            <article class="reservation-row">
              <?php $img = imagen_url($r['imagen_url']); ?>
              <img src="<?= htmlspecialchars($img) ?>" alt="Cancha" style="width:80px;height:60px;object-fit:cover;border-radius:8px;flex-shrink:0;">
              <div class="reservation-info">
                <div class="date"><?= htmlspecialchars($r['fecha_display']) ?>, <?= htmlspecialchars($r['hora']) ?></div>
                <h3><?= htmlspecialchars($r['cancha_nombre']) ?></h3>
                <p><?= htmlspecialchars($r['direccion']) ?></p>
                <?php if ($r['monto']): ?>
                  <p>💰 S/ <?= number_format((float)$r['monto'], 2) ?> — Pago: <?= ucfirst($r['pago_estado'] ?? 'pendiente') ?></p>
                <?php endif; ?>
              </div>
              <div class="reservation-actions">
                <?php if ($r['estado'] === 'pendiente'): ?>
                  <span class="badge badge-orange">Pendiente</span>
                  <a href="../../php/cancelar-reserva.php?id=<?= (int)$r['id'] ?>"
                     class="btn btn-red btn-sm"
                     onclick="return confirm('¿Cancelar esta reserva?')">Cancelar</a>
                <?php elseif ($r['estado'] === 'confirmada'): ?>
                  <span class="badge badge-green">Confirmada</span>
                  <a href="../detalle-cancha.php?id=<?= (int)$r['cancha_id'] ?>" class="btn btn-light btn-sm">Ver Cancha</a>
                <?php elseif ($r['estado'] === 'completada'): ?>
                  <span class="badge badge-gray">Completada</span>
                <?php else: ?>
                  <span class="badge badge-red">Cancelada</span>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>
    </div>
  </main>

  <script src="../../js/nav.js"></script>
</body>
</html>
