<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../php/listar-reservas-propietario.php';
$base = '../';
$php_base = '../../php/';
$nav_active = 'inicio';
require_login('propietario');

$propietario_id = get_propietario_id((int)$session_usuario['id']);
$stats = $propietario_id ? stats_propietario($propietario_id) : ['canchas'=>0,'reservas_pendientes'=>0,'ingresos_mes'=>0];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Propietario | AlquilaTuCancha</title>
  <link rel="stylesheet" href="../../styles/global.css">
  <link rel="stylesheet" href="../../styles/inicio-usuario.css">
  <link rel="stylesheet" href="../../styles/inicio-propietario.css">
</head>
<body>
  <?php include '../includes/navbar-panel.php'; ?>

  <header class="hero-banner owner-hero">
    <div class="hero-inner">
      <div class="hero-owner-bar">
        <div>
          <h1>Bienvenido, <?= htmlspecialchars($session_usuario['nombre']) ?></h1>
          <p>Gestiona tus canchas y genera ingresos fácilmente.</p>
        </div>
        <a href="publicar-cancha.php" class="btn btn-green">📣 Publicar Cancha</a>
      </div>
    </div>
  </header>

  <main class="container page-pad">
    <?php if (!empty($_GET['success'])): ?>
      <div class="alert alert-success" style="margin-bottom:16px;"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <div class="stats-row">
      <div class="stat-card">
        <span class="stat-icon">🏟️</span>
        <div>
          <span class="stat-num"><?= $stats['canchas'] ?></span>
          <span class="stat-label">Canchas Publicadas</span>
        </div>
      </div>
      <div class="stat-card">
        <span class="stat-icon">🗓️</span>
        <div>
          <span class="stat-num"><?= $stats['reservas_pendientes'] ?></span>
          <span class="stat-label">Reservas Pendientes</span>
        </div>
      </div>
      <div class="stat-card">
        <span class="stat-icon">💰</span>
        <div>
          <span class="stat-num">S/ <?= number_format($stats['ingresos_mes'], 0) ?></span>
          <span class="stat-label">Ingresos este mes</span>
        </div>
      </div>
    </div>

    <section class="quick-grid">
      <article class="quick-card">
        <div class="icon">📋</div>
        <h3>Mis Canchas</h3>
        <p>Revisa y administra las canchas que tienes publicadas.</p>
        <a href="mis-canchas.php" class="btn btn-green">Ver Mis Canchas</a>
      </article>
      <article class="quick-card">
        <div class="icon">🗓️</div>
        <h3>Gestión de Reservas</h3>
        <p>Confirma o cancela reservas y lleva el control de tus ingresos.</p>
        <a href="gestion-reservas.php" class="btn btn-green">Ver Reservas</a>
      </article>
      <article class="quick-card">
        <div class="icon">🏠</div>
        <h3>Publicar Cancha</h3>
        <p>Agrega una nueva cancha y empieza a recibir reservas hoy.</p>
        <a href="publicar-cancha.php" class="btn btn-green">Publicar Cancha</a>
      </article>
    </section>

    <section class="highlight-box">
      <div>
        <span class="badge badge-green" style="margin-bottom:12px;">Plan Destacado</span>
        <h2>Aumenta tus reservas siendo un propietario destacado</h2>
        <p>Aparece en los primeros lugares de búsqueda y recibe hasta 3x más reservas al mes.</p>
        <a href="#" class="btn btn-orange">Más Información</a>
      </div>
      <div class="money-art">💰📈</div>
    </section>
  </main>

  <script src="../../js/nav.js"></script>
</body>
</html>
