<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle de Cancha</title>
  <link rel="stylesheet" href="../styles/global.css">
  <link rel="stylesheet" href="../styles/detalle-cancha.css">
</head>
<body>
  <main class="detail-page">
    <div class="detail-wrap">
      <div class="breadcrumbs"><a href="index.php">Inicio</a><span>Cancha Sport Fútbol</span></div>

      <div class="detail-grid">
        <section>
          <article class="gallery-card">
            <img class="gallery-main" src="../imagenes/cancha-hero.png" alt="Cancha principal">
            <div class="gallery-thumbs">
              <img src="../imagenes/cancha-hero.png" alt="thumb 1">
              <img src="../imagenes/buscar-cancha.png" alt="thumb 2">
              <img src="../imagenes/fondo-login.png" alt="thumb 3">
            </div>
          </article>

          <article class="schedule-card">
            <h3>Horarios Disponibles</h3>
            <select class="form-select"><option>Lunes 22 de abril</option><option>Martes 23 de abril</option></select>
            <div class="slots">
              <div class="slot"><span>6:00 PM</span><span class="available">Disponible</span></div>
              <div class="slot"><span>7:30 PM</span><span class="available">Disponible</span></div>
              <div class="slot"><span>6:30 PM</span><span class="available">Disponible</span></div>
              <div class="slot"><span>9:00 PM</span><span class="busy">Reservado</span></div>
              <div class="slot"><span>9:30 PM</span><span class="available">Disponible</span></div>
            </div>
          </article>
        </section>

        <aside class="reserve-card">
          <h2>Reserva esta cancha</h2>
          <div class="rating">★★★★★ <span>4.8 (230 reseñas)</span></div>

          <form action="../php/reservar-cancha.php" method="POST">
            <div class="reserve-line"><label>Ubicación: Miraflores</label></div>
            <div class="reserve-line"><label for="fecha">Fecha</label><select id="fecha" name="fecha" class="form-select"><option>Lunes 22 de abril</option><option>Martes 23 de abril</option></select></div>
            <div class="reserve-line"><label for="deporte">Deporte</label><select id="deporte" name="deporte" class="form-select"><option>Fútbol</option><option>Pádel</option><option>Tenis</option></select></div>
            <div class="reserve-line"><label for="hora">Hora</label><select id="hora" name="hora" class="form-select"><option>Selecciona una hora</option><option>6:00 PM</option><option>6:30 PM</option><option>7:30 PM</option></select></div>
            <div class="reserve-line reserve-inline">
              <div><label for="nombre">Nombre completo</label><input class="form-control" id="nombre" name="nombre" type="text"></div>
              <div><label for="telefono">Teléfono</label><input class="form-control" id="telefono" name="telefono" type="text"></div>
            </div>
            <label class="check-row"><input type="checkbox" name="pelota" value="1"> Alquilar pelota por S/ 5</label>
            <button class="btn btn-green" style="width:100%" type="submit">Reservar Cancha</button>
          </form>
        </aside>
      </div>
    </div>
  </main>
</body>
</html>
