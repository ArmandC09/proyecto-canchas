<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Reservas</title>
  <link rel="stylesheet" href="../../styles/global.css">
  <link rel="stylesheet" href="../../styles/mis-reservas.css">
</head>
<body>
  <main class="list-page">
    <div class="list-wrap">
      <nav class="glass-top-nav" style="padding-top:24px;">
        <a href="inicio-usuario.php">Inicio</a>
        <a href="../buscar-cancha.php">Buscar Cancha</a>
        <a href="mis-reservas.php" class="active">Mis Reservas</a>
        <a href="../../php/logout.php">Cerrar Sesión</a>
      </nav>

      <header class="panel-head"><div><h1>Mis Reservas</h1><p>Revisa tus reservas activas y el historial de tus partidos.</p></div></header>
      <section class="main-panel">
        <div class="tabs">
          <button class="tab active">Pendientes (2)</button>
          <button class="tab">Confirmadas (3)</button>
          <button class="tab">Canceladas (1)</button>
        </div>

        <div class="filters">
          <select><option>Todas mis canchas</option></select>
          <select><option>Todos los deportes</option></select>
          <input type="text" placeholder="Buscar por nombre de cancha...">
          <button class="btn btn-green">Buscar</button>
        </div>

        <article class="reservation-row">
          <img src="../../imagenes/cancha-hero.png" alt="Cancha">
          <div class="reservation-info"><div class="date">Lunes 25 Abril, 8:00 PM</div><h3>Complejo Deportivo Los Olivos</h3><p>Los Olivos, Lima<br>⚽ Fútbol</p></div>
          <div class="reservation-side">S/80.00</div>
          <div class="reservation-actions"><span class="badge orange">Pendiente</span><a href="../../php/cancelar-reserva.php?id=1" class="btn btn-red">Cancelar</a></div>
        </article>

        <article class="reservation-row">
          <img src="../../imagenes/buscar-cancha.png" alt="Cancha">
          <div class="reservation-info"><div class="date">Martes 26 Abril, 7:30 PM</div><h3>Centro Deportivo San Isidro</h3><p>San Isidro, Lima<br>🎾 Pádel</p></div>
          <div class="reservation-side">S/100.00</div>
          <div class="reservation-actions"><span class="badge green">Confirmada</span><a href="../detalle-cancha.php" class="btn btn-light">Ver Cancha</a></div>
        </article>

        <div class="pagination">1 - 2 de 2 reservas &nbsp; | &nbsp; Página 1</div>
      </section>
    </div>
  </main>
</body>
</html>
