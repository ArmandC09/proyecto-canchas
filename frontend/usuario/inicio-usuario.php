<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio Usuario</title>
  <link rel="stylesheet" href="../../styles/global.css">
  <link rel="stylesheet" href="../../styles/inicio-usuario.css">
</head>
<body>
  <header class="hero-banner user-hero">
    <nav class="glass-top-nav" style="padding-top:0;margin-top:-10px;">
      <a href="../index.php">Inicio</a>
      <a href="../buscar-cancha.php" class="active">Buscar Cancha</a>
      <a href="mis-reservas.php">Mis Reservas</a>
      <a href="../../php/logout.php">Cerrar Sesión</a>
    </nav>
    <div class="hero-inner">
      <h1>Busca la cancha ideal para tu partido!</h1>
      <p>Encuentra canchas, revisa tus reservas y juega sin complicaciones.</p>
    </div>
  </header>

  <main class="container page-pad">
    <section class="quick-grid">
      <article class="quick-card"><div class="icon">🔎</div><h3>Buscar Canchas</h3><p>Encuentra la mejor cancha según ubicación, deporte y fecha.</p><a href="../buscar-cancha.php" class="btn btn-green">Buscar Ahora</a></article>
      <article class="quick-card"><div class="icon">📅</div><h3>Mis Reservas</h3><p>Revisa tus reservas activas, pendientes y tus partidos anteriores.</p><a href="mis-reservas.php" class="btn btn-green">Ver Reservas</a></article>
      <article class="quick-card"><div class="icon">🏟️</div><h3>Reservar Cancha</h3><p>Reserva rápido tu horario favorito y asegura tu partido.</p><a href="../detalle-cancha.php" class="btn btn-green">Reservar</a></article>
    </section>
  </main>
</body>
</html>
