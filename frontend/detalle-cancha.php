<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../php/obtener-cancha.php';
require_once __DIR__ . '/../php/helpers.php';

$base = '';
$cancha_id = (int)($_GET['id'] ?? 0);
$fecha_sel = $_GET['fecha'] ?? date('Y-m-d');

$cancha  = $cancha_id > 0 ? obtener_cancha($cancha_id) : null;
$horarios = ($cancha && $fecha_sel) ? obtener_slots_disponibles($cancha_id, $fecha_sel) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $cancha ? htmlspecialchars($cancha['nombre']).' | AlquilaTuCancha' : 'Cancha no encontrada' ?></title>
  <link rel="stylesheet" href="../styles/global.css">
  <link rel="stylesheet" href="../styles/detalle-cancha.css">
</head>
<body>
  <?php include 'includes/navbar-public.php'; ?>
  <div class="navbar-spacer"></div>

  <main class="detail-page">
    <div class="detail-wrap">

      <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error" style="margin-bottom:16px;"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>
      <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success" style="margin-bottom:16px;"><?= htmlspecialchars($_GET['success']) ?></div>
      <?php endif; ?>

      <?php if (!$cancha): ?>
        <div class="detail-empty">
          <div class="empty-icon">🏟️</div>
          <h2>Cancha no encontrada</h2>
          <p>Esta cancha no existe o ya no está disponible.</p>
          <a href="buscar-cancha.php" class="btn btn-green">Volver a buscar</a>
        </div>

      <?php else: ?>
        <nav class="breadcrumbs" aria-label="Ruta">
          <a href="buscar-cancha.php">← Buscar</a>
          <span><?= htmlspecialchars($cancha['nombre']) ?></span>
        </nav>

        <div class="detail-grid">
          <!-- Columna izquierda -->
          <section class="detail-left">
            <article class="gallery-card card">
              <?php if ($cancha['imagen_url']): ?>
                <?php $img = imagen_url($cancha['imagen_url']); ?>
                <img class="gallery-main" src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($cancha['nombre']) ?>">
              <?php else: ?>
                <div class="gallery-placeholder">
                  <span>📷</span>
                  <p>Sin imágenes disponibles</p>
                </div>
              <?php endif; ?>
            </article>

            <!-- Info adicional -->
            <article class="card" style="padding:20px; margin-top:16px;">
              <h3 style="margin-bottom:12px;">Información</h3>
              <?php if ($cancha['descripcion']): ?>
                <p style="margin-bottom:10px;"><?= nl2br(htmlspecialchars($cancha['descripcion'])) ?></p>
              <?php endif; ?>
              <?php if ($cancha['telefono']): ?>
                <p>📞 <?= htmlspecialchars($cancha['telefono']) ?></p>
              <?php endif; ?>
              <?php if (!empty($cancha['deportes'])): ?>
                <p style="margin-top:10px;">🏅 <strong>Deportes:</strong> <?= htmlspecialchars(implode(', ', array_map('ucfirst', $cancha['deportes']))) ?></p>
              <?php endif; ?>
            </article>

            <article class="schedule-card card">
              <div class="schedule-head">
                <h3>Horarios Disponibles</h3>
                <form method="GET" action="detalle-cancha.php" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $cancha_id ?>">
                  <input type="date" name="fecha" class="form-control schedule-date-pick"
                         value="<?= htmlspecialchars($fecha_sel) ?>"
                         min="<?= date('Y-m-d') ?>"
                         onchange="this.form.submit()">
                </form>
              </div>

              <?php if (empty($horarios)): ?>
                <div class="slots-empty">
                  <span>📅</span>
                  <p>No hay horarios disponibles para esta fecha.</p>
                </div>
              <?php else: ?>
                <div class="slots">
                  <?php foreach ($horarios as $h): ?>
                    <div class="slot <?= $h['disponible'] ? 'slot-free' : 'slot-busy' ?>">
                      <span class="slot-time"><?= htmlspecialchars($h['hora']) ?></span>
                      <span class="slot-status"><?= $h['disponible'] ? 'Disponible' : 'Reservado' ?></span>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </article>
          </section>

          <!-- Columna derecha: reserva -->
          <aside class="reserve-card card">
            <h2><?= htmlspecialchars($cancha['nombre']) ?></h2>
            <div class="court-meta">
              <span class="court-location">📍 <?= htmlspecialchars($cancha['direccion']) ?></span>
            </div>
            <div class="reserve-price">
              <strong>S/ <?= number_format((float)$cancha['precio_por_hora'], 0) ?></strong> / hora
            </div>

            <?php if ($session_usuario): ?>
              <form action="../php/reservar-cancha.php" method="POST" class="reserve-form">
                <input type="hidden" name="cancha_id" value="<?= (int)$cancha['id'] ?>">

                <div class="reserve-field">
                  <label class="form-label" for="r-fecha">Fecha</label>
                  <input type="date" id="r-fecha" name="fecha" class="form-control"
                         value="<?= htmlspecialchars($fecha_sel) ?>"
                         min="<?= date('Y-m-d') ?>" required>
                </div>

                <?php $slots_libres = array_filter($horarios, fn($s) => $s['disponible']); ?>
                <?php if (!empty($slots_libres)): ?>
                  <div class="reserve-field">
                    <label class="form-label" for="r-hora">Hora disponible</label>
                    <select id="r-hora" name="hora" class="form-select" required>
                      <option value="">Selecciona una hora</option>
                      <?php foreach ($slots_libres as $h): ?>
                        <option value="<?= htmlspecialchars($h['hora']) ?>"><?= htmlspecialchars($h['hora']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                <?php else: ?>
                  <p style="color:var(--color-error,#e53935);margin:12px 0;">No hay horarios libres para esta fecha.</p>
                <?php endif; ?>

                <button class="btn btn-green btn-full btn-lg" type="submit"
                  <?= empty($slots_libres) ? 'disabled' : '' ?>>
                  Reservar Cancha
                </button>
              </form>
            <?php else: ?>
              <div class="reserve-login-prompt">
                <p>Para reservar esta cancha necesitas iniciar sesión</p>
                <a href="login.php" class="btn btn-green btn-full btn-lg">Iniciar Sesión para Reservar</a>
                <a href="registro-usuario.php" class="register-prompt-link">¿No tienes cuenta? Regístrate gratis</a>
              </div>
            <?php endif; ?>
          </aside>
        </div>
      <?php endif; ?>

    </div>
  </main>

  <script src="../js/nav.js"></script>
</body>
</html>
