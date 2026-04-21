<?php
require_once __DIR__ . '/auth.php';

$base = $base ?? '../';
$php_base = $php_base ?? '../../php/';
$nav_active = $nav_active ?? '';

if (!$session_usuario) {
    header('Location: ../login.php?error=' . urlencode('Debes iniciar sesión para entrar al panel.'));
    exit;
}
?>
<nav class="panel-nav" id="panelNav">
  <div class="panel-nav-inner">
    <a href="<?= htmlspecialchars($base) ?>index.php" class="panel-brand">
      <span>⚽</span> AlquilaTuCancha
    </a>

    <ul class="panel-nav-links" id="panelNavLinks">
      <?php if (($session_usuario['rol'] ?? '') === 'propietario'): ?>
        <li><a href="<?= htmlspecialchars($base) ?>propietario/inicio-propietario.php" class="<?= $nav_active === 'inicio' ? 'active' : '' ?>">Inicio</a></li>
        <li><a href="<?= htmlspecialchars($base) ?>buscar-cancha.php" class="<?= $nav_active === 'buscar' ? 'active' : '' ?>">Buscar</a></li>
        <li><a href="<?= htmlspecialchars($base) ?>propietario/mis-canchas.php" class="<?= $nav_active === 'mis-canchas' ? 'active' : '' ?>">Mis Canchas</a></li>
        <li><a href="<?= htmlspecialchars($base) ?>propietario/gestion-reservas.php" class="<?= $nav_active === 'reservas' ? 'active' : '' ?>">Reservas</a></li>
        <li><a href="<?= htmlspecialchars($base) ?>propietario/publicar-cancha.php" class="<?= $nav_active === 'publicar' ? 'active' : '' ?>">+ Publicar</a></li>
      <?php else: ?>
        <li><a href="<?= htmlspecialchars($base) ?>usuario/inicio-usuario.php" class="<?= $nav_active === 'inicio' ? 'active' : '' ?>">Inicio</a></li>
        <li><a href="<?= htmlspecialchars($base) ?>buscar-cancha.php" class="<?= $nav_active === 'buscar' ? 'active' : '' ?>">Buscar</a></li>
        <li><a href="<?= htmlspecialchars($base) ?>usuario/mis-reservas.php" class="<?= $nav_active === 'reservas' ? 'active' : '' ?>">Mis Reservas</a></li>
      <?php endif; ?>
    </ul>

    <div class="panel-nav-right">
      <span class="panel-avatar"><?= strtoupper(substr($session_usuario['nombre'] ?? 'U', 0, 1)) ?></span>
      <span class="panel-username"><?= htmlspecialchars($session_usuario['nombre'] ?? 'Usuario') ?></span>
      <a href="<?= htmlspecialchars($php_base) ?>logout.php" class="btn-panel-logout">Cerrar Sesión</a>
      <button class="panel-burger" id="panelBurger" aria-label="Menú">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</nav>
