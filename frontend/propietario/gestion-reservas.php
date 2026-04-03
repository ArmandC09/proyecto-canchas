<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Reservas</title>
  <link rel="stylesheet" href="../../styles/global.css">
  <link rel="stylesheet" href="../../styles/gestion-reservas.css">
</head>
<body>
  <main class="list-page">
    <div class="list-wrap">
      <nav class="glass-top-nav" style="padding-top:24px;">
        <a href="inicio-propietario.php">Inicio</a>
        <a href="mis-canchas.php">Mis Canchas</a>
        <a href="gestion-reservas.php" class="active">Reservas</a>
        <a href="publicar-cancha.php">Publicar</a>
      </nav>

      <header class="panel-head"><div><h1>Gestión de Reservas</h1><p>Administra las reservas de tus canchas y mantente al tanto de la actividad de tus jugadores.</p></div><a class="btn btn-green" href="../../php/bloquear-fecha.php">📅 Bloquear Fecha</a></header>
      <section class="main-panel">
        <div class="tabs">
          <button class="tab active">Pendientes (5)</button>
          <button class="tab">Confirmadas (12)</button>
          <button class="tab">Completadas (34)</button>
          <button class="tab">Canceladas (9)</button>
        </div>

        <div class="filters">
          <select><option>Filtrar por Cancha: Todas</option></select>
          <select><option>Filtrar por Deporte: Todos</option></select>
          <input type="text" placeholder="Buscar por usuario...">
          <button class="btn btn-green">Buscar</button>
        </div>

        <article class="reservation-row">
          <img src="../../imagenes/cancha-hero.png" alt="Cancha">
          <div class="reservation-info"><div class="date">Lunes 25 Abril, 8:00 PM · 9:00 PM</div><h3>Complejo Deportivo Los Olivos</h3><p>Los Olivos, Lima<br>👤 Daniel Pérez</p></div>
          <div class="reservation-side">⚽ Fútbol<br><strong style="display:block;margin-top:8px;">S/80.00</strong></div>
          <div class="reservation-actions"><a class="btn btn-green" href="../../php/confirmar-reserva.php?id=1">Confirmar</a><a class="btn btn-red" href="../../php/cancelar-reserva.php?id=1">Cancelar</a></div>
        </article>

        <article class="reservation-row">
          <img src="../../imagenes/buscar-cancha.png" alt="Cancha">
          <div class="reservation-info"><div class="date">Lunes 25 Abril, 9:00 PM · 10:00 PM</div><h3>Centro Deportivo San Isidro</h3><p>San Isidro, Lima<br>👤 Javier Torres</p></div>
          <div class="reservation-side">🎾 Pádel<br><strong style="display:block;margin-top:8px;">S/100.00</strong></div>
          <div class="reservation-actions"><a class="btn btn-green" href="../../php/confirmar-reserva.php?id=2">Confirmar</a><a class="btn btn-red" href="../../php/cancelar-reserva.php?id=2">Cancelar</a></div>
        </article>

        <div class="pagination">1 - 5 de 5 reservaciones &nbsp; | &nbsp; Página 1</div>
      </section>
    </div>
  </main>
</body>
</html>
