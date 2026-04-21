<?php
require_once __DIR__ . '/auth.php';

$base = $base ?? '';
$php_base = $php_base ?? '../php/';
?>
<nav class="navbar" id="mainNav">
  <div class="navbar-inner">
    <a href="<?= htmlspecialchars($base) ?>index.php" class="navbar-brand">
      <span class="brand-icon">⚽</span>
      <span class="brand-text">AlquilaTuCancha</span>
    </a>

    <ul class="navbar-links" id="navLinks">
      <li><a href="<?= htmlspecialchars($base) ?>index.php">Inicio</a></li>
      <li><a href="<?= htmlspecialchars($base) ?>buscar-cancha.php">Buscar canchas</a></li>

      <?php if ($session_usuario): ?>
        <?php if (($session_usuario['rol'] ?? '') === 'propietario'): ?>
          <li><a href="<?= htmlspecialchars($base) ?>propietario/inicio-propietario.php">Mi Panel</a></li>
          <li><a href="<?= htmlspecialchars($base) ?>propietario/mis-canchas.php">Mis Canchas</a></li>
          <li><a href="<?= htmlspecialchars($base) ?>propietario/gestion-reservas.php">Reservas</a></li>
          <li><a href="<?= htmlspecialchars($base) ?>propietario/publicar-cancha.php">Publicar</a></li>
        <?php else: ?>
          <li><a href="<?= htmlspecialchars($base) ?>usuario/mis-reservas.php">Mis Reservas</a></li>
        <?php endif; ?>

        <li class="nav-user">
          <span class="nav-avatar"><?= strtoupper(substr($session_usuario['nombre'] ?? 'U', 0, 1)) ?></span>
          <span class="nav-name"><?= htmlspecialchars($session_usuario['nombre'] ?? 'Usuario') ?></span>
          <a href="<?= htmlspecialchars($php_base) ?>logout.php" class="btn-nav-logout">Salir</a>
        </li>
      <?php else: ?>
        <li><a href="<?= htmlspecialchars($base) ?>registro-propietario.php">Publica tu cancha</a></li>
        <li><a href="<?= htmlspecialchars($base) ?>login.php" class="btn-nav-login">Ingresar</a></li>
      <?php endif; ?>
    </ul>

    <button class="navbar-burger" id="navBurger" aria-label="Menú">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>
