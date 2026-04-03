<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Canchas</title>
  <link rel="stylesheet" href="../../styles/global.css">
  <link rel="stylesheet" href="../../styles/mis-reservas.css">
  <link rel="stylesheet" href="../../styles/mis-canchas.css">
</head>
<body>
  <main class="list-page">
    <div class="list-wrap">
      <nav class="glass-top-nav" style="padding-top:24px;">
        <a href="inicio-propietario.php">Inicio</a>
        <a href="mis-canchas.php" class="active">Mis Canchas</a>
        <a href="gestion-reservas.php">Reservas</a>
        <a href="publicar-cancha.php">Publicar</a>
      </nav>

      <header class="panel-head"><div><h1>Mis Canchas</h1><p>Gestiona tus canchas publicadas y controla tus reservas pendientes.</p></div><a class="btn btn-green" href="publicar-cancha.php">📣 Publicar Cancha</a></header>
      <section class="main-panel">
        <div class="tabs"><button class="tab active">Mis Canchas (2)</button><button class="tab">Reservas Pendientes (5)</button></div>
        <div class="filters" style="grid-template-columns:340px;"><select><option>Filtrar por Deporte: Todos</option></select></div>

        <div class="courts-list">
          <article class="court-row">
            <div class="court-media"><img class="main" src="../../imagenes/cancha-hero.png" alt="Cancha"><div class="thumbs"><img src="../../imagenes/cancha-hero.png"><img src="../../imagenes/buscar-cancha.png"><img src="../../imagenes/fondo-login.png"></div></div>
            <div class="court-data"><h3>Complejo Deportivo Los Olivos</h3><p>Av. Las Palmeras 1234, Los Olivos, Lima</p><div class="sports-line">⚽ Fútbol &nbsp; | &nbsp; 🎾 Pádel &nbsp; | &nbsp; 🟡 Tenis</div><div class="price-line" style="margin-top:28px">Desde S/80 por hora</div></div>
            <div class="court-side"><span class="badge green">Publicado</span><div class="side-actions"><a href="publicar-cancha.php" class="btn btn-orange">Editar</a><a href="../../php/eliminar-cancha.php?id=1" class="btn btn-red">🗑</a></div><a class="count-badge" href="gestion-reservas.php">Reservas <span>3</span></a></div>
          </article>

          <article class="court-row">
            <div class="court-media"><img class="main" src="../../imagenes/buscar-cancha.png" alt="Cancha"><div class="thumbs"><img src="../../imagenes/cancha-hero.png"><img src="../../imagenes/buscar-cancha.png"><img src="../../imagenes/fondo_index.png"></div></div>
            <div class="court-data"><h3>Centro Deportivo San Isidro</h3><p>Calle Miguel Dasso 900, San Isidro, Lima</p><div class="sports-line">⚽ Fútbol &nbsp; | &nbsp; 🎾 Pádel</div><div class="price-line" style="margin-top:28px">Desde S/100 por hora</div></div>
            <div class="court-side"><span class="badge green">Publicado</span><div class="side-actions"><a href="publicar-cancha.php" class="btn btn-orange">Editar</a><a href="../../php/eliminar-cancha.php?id=2" class="btn btn-red">🗑</a></div><a class="count-badge" href="gestion-reservas.php">Reservas <span>2</span></a></div>
          </article>
        </div>

        <div class="pagination">1 - 2 de 2 canchas &nbsp; | &nbsp; Página 1</div>
      </section>
    </div>
  </main>
</body>
</html>
