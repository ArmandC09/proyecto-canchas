<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../php/listar-canchas.php';
require_once __DIR__ . '/../php/helpers.php';

$base = '';
$canchas = listar_canchas($_GET);
$total = count($canchas);

// Deportes disponibles para el filtro (desde BD)
$deportes_db = [];
try {
    $dep_stmt = db()->query('SELECT nombre FROM deportes ORDER BY nombre');
    $deportes_db = $dep_stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (Throwable $e) { /* silencioso */ }

$deporte_sel  = htmlspecialchars($_GET['deporte']   ?? '');
$ubicacion_sel = htmlspecialchars($_GET['ubicacion'] ?? '');
$fecha_sel    = htmlspecialchars($_GET['fecha']     ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buscar Cancha | AlquilaTuCancha</title>
  <link rel="stylesheet" href="../styles/global.css">
  <link rel="stylesheet" href="../styles/buscar-cancha.css">
</head>
<body>
  <?php include 'includes/navbar-public.php'; ?>
  <div class="navbar-spacer"></div>

  <header class="hero-banner search-hero">
    <div class="hero-inner">
      <h1>Encuentra una cancha para hoy, mañana o el fin de semana</h1>
      <p>Explora opciones por zona, deporte y fecha con una vista más clara y amigable.</p>

      <form class="search-box" action="buscar-cancha.php" method="GET">
        <select name="ubicacion" class="form-select">
          <option value="">📍 Ubicación</option>
          <?php foreach (['Tacna','Miraflores','San Isidro','Surco','La Molina'] as $u): ?>
            <option value="<?= htmlspecialchars($u) ?>" <?= $ubicacion_sel === htmlspecialchars($u) ? 'selected' : '' ?>>
              <?= htmlspecialchars($u) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <select name="deporte" class="form-select">
          <option value="">🏅 Deporte</option>
          <?php $deportes_lista = !empty($deportes_db) ? $deportes_db : ['futbol','voley','basquet','tenis','padel']; ?>
          <?php foreach ($deportes_lista as $d): ?>
            <option value="<?= htmlspecialchars($d) ?>" <?= $deporte_sel === htmlspecialchars($d) ? 'selected' : '' ?>>
              <?= ucfirst(htmlspecialchars($d)) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <input type="date" name="fecha" class="form-control"
               value="<?= $fecha_sel ?>"
               min="<?= date('Y-m-d') ?>">
        <button class="btn btn-green" type="submit">Buscar</button>
      </form>
    </div>
  </header>

  <main class="container page-pad">
    <div class="section-title">
      <h2>Opciones disponibles</h2>
      <span></span>
      <?php if ($total > 0): ?>
        <small class="results-count"><?= $total ?> resultado<?= $total !== 1 ? 's' : '' ?></small>
      <?php endif; ?>
    </div>

    <?php if (!empty($_GET['success'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <?php if (empty($canchas)): ?>
      <div class="empty-state">
        <div class="empty-icon">🏟️</div>
        <h3>No se encontraron canchas</h3>
        <p>Prueba con otra zona, deporte o fecha.</p>
        <a href="buscar-cancha.php" class="btn btn-green">Quitar filtros</a>
      </div>
    <?php else: ?>
      <section class="cards-grid">
        <?php foreach ($canchas as $c): ?>
          <article class="court-card">
            <div class="court-card-img">
              <?php $img = imagen_url($c['imagen_url']); ?>
              <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($c['nombre']) ?>">
              <?php if ($c['deportes_str']): ?>
                <span class="court-sport-badge"><?= htmlspecialchars(ucfirst(explode(',', $c['deportes_str'])[0])) ?></span>
              <?php endif; ?>
            </div>
            <div class="body">
              <h3><?= htmlspecialchars($c['nombre']) ?></h3>
              <p class="court-location">📍 <?= htmlspecialchars($c['direccion']) ?></p>
              <div class="price">Desde <strong>S/ <?= number_format((float)$c['precio_por_hora'], 0) ?></strong> / hora</div>
              <a class="btn btn-green btn-full" href="detalle-cancha.php?id=<?= (int)$c['id'] ?>">Ver Detalles</a>
            </div>
          </article>
        <?php endforeach; ?>
      </section>
    <?php endif; ?>

    <section class="lower-promos">
      <div class="promo-box green">
        <div class="promo-icon">👤</div>
        <div>
          <h3>¿Quieres reservar?</h3>
          <p>Regístrate o inicia sesión <strong>GRATIS</strong> y reserva tu cancha favorita.</p>
          <?php if ($session_usuario): ?>
            <a href="<?= $session_usuario['rol']==='propietario' ? 'propietario/inicio-propietario.php' : 'usuario/inicio-usuario.php' ?>" class="btn btn-green">Mi Panel</a>
          <?php else: ?>
            <a href="login.php" class="btn btn-green">Ingresar</a>
          <?php endif; ?>
        </div>
      </div>
      <div class="promo-box orange">
        <div class="promo-icon">🏠</div>
        <div>
          <h3>¿Eres propietario?</h3>
          <p>Publica tu cancha con un Plan Destacado y genera ingresos mensuales.</p>
          <a href="registro-propietario.php" class="btn btn-orange">Más Información</a>
        </div>
      </div>
    </section>
  </main>

  <script src="../js/nav.js"></script>
</body>
</html>
