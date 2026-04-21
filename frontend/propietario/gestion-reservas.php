<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../php/listar-reservas-propietario.php';
$base = '../';
$php_base = '../../php/';
$nav_active = 'reservas';
require_login('propietario');

$propietario_id = get_propietario_id((int)$session_usuario['id']);
$tab_activo = $_GET['estado'] ?? 'pendiente';
$cancha_filtro = (int)($_GET['cancha'] ?? 0);

$reservas = $propietario_id ? listar_reservas_propietario($propietario_id, $tab_activo, $cancha_filtro) : [];
$conteos  = $propietario_id ? contar_reservas_propietario($propietario_id) : ['pendiente'=>0,'confirmada'=>0,'cancelada'=>0,'completada'=>0];

// Canchas del propietario para el filtro
$mis_canchas = [];
if ($propietario_id) {
    $s = db()->prepare('SELECT id, nombre FROM canchas WHERE propietario_id=? ORDER BY nombre');
    $s->execute([$propietario_id]);
    $mis_canchas = $s->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Reservas | AlquilaTuCancha</title>
  <link rel="stylesheet" href="../../styles/global.css">
  <link rel="stylesheet" href="../../styles/mis-reservas.css">
</head>
<body>
  <?php include '../includes/navbar-panel.php'; ?>

  <main class="list-page">
    <div class="list-wrap">
      <header class="panel-head">
        <div>
          <h1>Gestión de Reservas</h1>
          <p>Administra las reservas de tus canchas.</p>
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
          <?php foreach (['pendiente'=>'Pendientes','confirmada'=>'Confirmadas','completada'=>'Completadas','cancelada'=>'Canceladas'] as $k=>$label): ?>
            <a href="?estado=<?= $k ?><?= $cancha_filtro ? '&cancha='.$cancha_filtro : '' ?>"
               class="tab <?= $tab_activo===$k?'active':'' ?>">
              <?= $label ?> (<?= $conteos[$k] ?? 0 ?>)
            </a>
          <?php endforeach; ?>
        </div>

        <div class="filters">
          <form method="GET" action="gestion-reservas.php" style="display:contents;">
            <input type="hidden" name="estado" value="<?= htmlspecialchars($tab_activo) ?>">
            <select name="cancha" class="form-select" onchange="this.form.submit()">
              <option value="">Todas mis canchas</option>
              <?php foreach ($mis_canchas as $mc): ?>
                <option value="<?= (int)$mc['id'] ?>" <?= $cancha_filtro===(int)$mc['id']?'selected':'' ?>>
                  <?= htmlspecialchars($mc['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </form>
        </div>

        <?php if (empty($reservas)): ?>
          <div class="empty-state">
            <div class="empty-icon">🗓️</div>
            <h3>No hay reservas <?= $tab_activo === 'pendiente' ? 'pendientes' : $tab_activo.'s' ?></h3>
            <p>Cuando lleguen reservas, aparecerán aquí.</p>
          </div>
        <?php else: ?>
          <div class="reservas-list">
            <?php foreach ($reservas as $r): ?>
              <article class="reservation-row" style="align-items:flex-start;">
                <div class="reservation-info" style="flex:1;">
                  <div class="date"><?= htmlspecialchars($r['fecha_display']) ?> · <?= htmlspecialchars($r['hora']) ?></div>
                  <h3><?= htmlspecialchars($r['cancha_nombre']) ?></h3>
                  <p>
                    👤 <?= htmlspecialchars($r['usuario_nombre']) ?>
                    <?php if ($r['usuario_telefono']): ?>
                      · 📞 <?= htmlspecialchars($r['usuario_telefono']) ?>
                    <?php endif; ?>
                  </p>
                  <?php if ($r['monto']): ?>
                    <p>💰 S/ <?= number_format((float)$r['monto'], 2) ?> — <?= ucfirst($r['pago_estado'] ?? 'pendiente') ?></p>
                  <?php endif; ?>
                </div>
                <div class="reservation-actions">
                  <?php if ($r['estado'] === 'pendiente'): ?>
                    <span class="badge badge-orange">Pendiente</span>
                    <a href="../../php/confirmar-reserva.php?id=<?= (int)$r['id'] ?>"
                       class="btn btn-green btn-sm"
                       onclick="return confirm('¿Confirmar esta reserva?')">✓ Confirmar</a>
                    <a href="../../php/cancelar-reserva.php?id=<?= (int)$r['id'] ?>"
                       class="btn btn-red btn-sm"
                       onclick="return confirm('¿Cancelar esta reserva?')">✗ Cancelar</a>
                  <?php elseif ($r['estado'] === 'confirmada'): ?>
                    <span class="badge badge-green">Confirmada</span>
                    <a href="../../php/cancelar-reserva.php?id=<?= (int)$r['id'] ?>"
                       class="btn btn-red btn-sm"
                       onclick="return confirm('¿Cancelar esta reserva?')">✗ Cancelar</a>
                  <?php elseif ($r['estado'] === 'completada'): ?>
                    <span class="badge badge-gray">Completada</span>
                  <?php else: ?>
                    <span class="badge badge-red">Cancelada</span>
                  <?php endif; ?>
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
