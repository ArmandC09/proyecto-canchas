<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Publicar Cancha</title>
  <link rel="stylesheet" href="../../styles/global.css">
  <link rel="stylesheet" href="../../styles/mis-reservas.css">
  <link rel="stylesheet" href="../../styles/publicar-cancha.css">
</head>
<body>
  <main class="list-page">
    <div class="list-wrap">
      <nav class="glass-top-nav" style="padding-top:24px;">
        <a href="inicio-propietario.php">Inicio</a>
        <a href="mis-canchas.php">Mis Canchas</a>
        <a href="gestion-reservas.php">Reservas</a>
        <a href="publicar-cancha.php" class="active">Publicar</a>
      </nav>

      <header class="panel-head"><div><h1>Publicar Cancha</h1><p>Completa el formulario para publicar tu cancha y atraer jugadores interesados en reservar.</p></div></header>
      <section class="main-panel">
        <form class="publish-grid" action="../../php/guardar-cancha.php" method="POST" enctype="multipart/form-data">
          <div>
            <article class="box">
              <h2>Datos Generales</h2>
              <div class="field-stack">
                <input class="form-control" type="text" name="nombre" placeholder="Nombre del Complejo">
                <input class="form-control" type="text" name="direccion" placeholder="Dirección">
                <input class="form-control" type="text" name="telefono" placeholder="Teléfono">
                <div>
                  <strong>Deportes Disponibles</strong>
                  <div class="sports-checks">
                    <label><input type="checkbox" name="deportes[]" value="futbol"> Fútbol</label>
                    <label><input type="checkbox" name="deportes[]" value="padel"> Pádel</label>
                    <label><input type="checkbox" name="deportes[]" value="tenis"> Tenis</label>
                    <label><input type="checkbox" name="deportes[]" value="voley"> Vóley</label>
                  </div>
                </div>
                <input class="form-control" type="number" name="precio" placeholder="Precio por Hora (S/)">
                <textarea class="form-textarea" name="descripcion" placeholder="Descripción"></textarea>
              </div>
            </article>

            <div class="mini-gallery" id="previewGallery">
              <div class="mini-thumb"><button type="button">✕</button><img src="../../imagenes/cancha-hero.png"></div>
              <div class="mini-thumb"><button type="button">✕</button><img src="../../imagenes/buscar-cancha.png"></div>
              <div class="mini-thumb"><button type="button">✕</button><img src="../../imagenes/fondo-login.png"></div>
            </div>
          </div>

          <div>
            <article class="box">
              <h2>Horarios de Disponibilidad</h2>
              <p>Selecciona los días y horarios en que estará disponible tu cancha.</p>
              <div class="days-wrap">
                <label><input type="checkbox" name="dias[]" value="lun"> Lun</label>
                <label><input type="checkbox" name="dias[]" value="mar"> Mar</label>
                <label><input type="checkbox" name="dias[]" value="mie"> Mié</label>
                <label><input type="checkbox" name="dias[]" value="jue"> Jue</label>
                <label><input type="checkbox" name="dias[]" value="sab"> Sáb</label>
                <label><input type="checkbox" name="dias[]" value="dom"> Dom</label>
              </div>
              <div class="two-cols">
                <div><label>Desde</label><select class="form-select" name="hora_inicio"><option>9:00 AM</option><option>6:00 PM</option></select></div>
                <div><label>Hasta</label><select class="form-select" name="hora_fin"><option>10:00 PM</option><option>11:00 PM</option></select></div>
                <button class="btn btn-light" type="button">＋</button>
              </div>
              <div class="actions-bottom"><button type="button" class="btn btn-green">+ Añadir Horario</button></div>
            </article>

            <article class="box" style="margin-top:18px;">
              <h2>Fotos de la Cancha</h2>
              <p>Sube fotos de tu cancha para que los jugadores puedan verla.</p>
              <label class="upload-box">
                <div class="big">☁️</div>
                <div>Arrastra y suelta tus imágenes aquí o haz clic para subirlas</div>
                <small>Máximo 6 imágenes. Formatos permitidos: JPG, PNG</small>
                <input id="imageInput" type="file" name="imagenes[]" accept="image/*" multiple hidden>
              </label>
              <div class="actions-bottom">
                <button class="btn btn-green" type="submit">📣 Publicar Cancha</button>
                <a class="cancel-link" href="mis-canchas.php">Cancelar</a>
              </div>
            </article>
          </div>
        </form>
      </section>
    </div>
  </main>
  <script src="../../js/publicar-cancha.js"></script>
</body>
</html>
