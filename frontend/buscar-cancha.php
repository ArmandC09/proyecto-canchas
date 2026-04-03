<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buscar Cancha</title>
  <link rel="stylesheet" href="../styles/global.css">
  <link rel="stylesheet" href="../styles/buscar-cancha.css">
</head>
<body>
  <header class="hero-banner search-hero">
    <div class="hero-inner">
      <h1>Busca la cancha ideal para tu partido!</h1>
      <form class="search-box" action="buscar-cancha.php" method="GET">
        <select name="ubicacion">
          <option value="">Ubicación</option>
          <option>Tacna</option><option>Miraflores</option><option>San Isidro</option><option>Surco</option><option>La Molina</option>
        </select>
        <select name="deporte">
          <option value="">Deporte</option>
          <option>Fútbol</option><option>Pádel</option><option>Tenis</option><option>Vóley</option>
        </select>
        <input type="date" name="fecha">
        <button class="btn btn-green" type="submit">Buscar</button>
      </form>
      <p>Para reservar una cancha debes registrarte o iniciar sesión gratis.</p>
    </div>
  </header>

  <main class="container page-pad">
    <div class="section-title"><h2>Canchas Disponibles</h2><span></span></div>

    <section class="cards-grid">
      <?php include '../php/listar-canchas.php'; ?>
    </section>

    <section class="lower-promos">
      <div class="promo-box green">
        <div class="promo-icon">👤</div>
        <div><h3>¿Quieres reservar?</h3><p><strong>Regístrate o inicia sesión GRATIS</strong><br>y reserva tu cancha.</p><a href="login.php" class="btn btn-green">Ingresar</a></div>
      </div>
      <div class="promo-box orange">
        <div class="promo-icon">🏠</div>
        <div><h3>¿Eres propietario?</h3><p>Publica tu cancha con un Plan Destacado<br>y gana dinero mensualmente.</p><a href="registro-propietario.php" class="btn btn-orange">Más Información</a></div>
      </div>
    </section>
  </main>
</body>
</html>
