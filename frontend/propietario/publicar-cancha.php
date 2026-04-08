<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../php/obtener-cancha.php';
require_once __DIR__ . '/../../php/helpers.php';
$base = '../';
$php_base = '../../php/';
$nav_active = 'publicar';
require_login('propietario');

$cancha_id = (int)($_GET['id'] ?? 0);
$cancha = null;
if ($cancha_id > 0) {
    $cancha = obtener_cancha($cancha_id);
    // Obtener días configurados
    if ($cancha) {
        $h_stmt = db()->prepare('SELECT dia_semana, hora_inicio, hora_fin FROM horarios WHERE cancha_id=? LIMIT 1');
        $h_stmt->execute([$cancha_id]);
        $h = $h_stmt->fetch();
        $cancha['dias'] = [];
        if ($h) {
            $cancha['hora_inicio'] = date('g:i A', strtotime($h['hora_inicio']));
            $cancha['hora_fin']    = date('g:i A', strtotime($h['hora_fin']));
            // Obtener todos los días
            $d_stmt = db()->prepare('SELECT dia_semana FROM horarios WHERE cancha_id=?');
            $d_stmt->execute([$cancha_id]);
            $dias_bd = ['lunes'=>'lun','martes'=>'mar','miercoles'=>'mie','jueves'=>'jue','viernes'=>'vie','sabado'=>'sab','domingo'=>'dom'];
            foreach ($d_stmt->fetchAll() as $dr) {
                $cancha['dias'][] = $dias_bd[$dr['dia_semana']] ?? $dr['dia_semana'];
            }
        }
    }
}
$modo = $cancha ? 'editar' : 'nueva';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $modo==='editar'?'Editar':'Publicar' ?> Cancha | AlquilaTuCancha</title>
  <link rel="stylesheet" href="../../styles/global.css">
  <link rel="stylesheet" href="../../styles/publicar-cancha.css">
</head>
<body>
  <?php include '../includes/navbar-panel.php'; ?>

  <main class="list-page">
    <div class="list-wrap">
      <header class="panel-head">
        <div>
          <h1><?= $modo==='editar'?'Editar Cancha':'Publicar Cancha' ?></h1>
          <p>Completa el formulario para <?= $modo==='editar'?'actualizar tu':'publicar tu nueva' ?> cancha.</p>
        </div>
      </header>

      <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error" style="margin-bottom:16px;"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>

      <section class="main-panel">
        <form class="publish-grid" action="../../php/guardar-cancha.php" method="POST" enctype="multipart/form-data">
          <?php if ($cancha_id): ?>
            <input type="hidden" name="cancha_id" value="<?= $cancha_id ?>">
          <?php endif; ?>

          <!-- Columna izquierda -->
          <div>
            <article class="box">
              <h2>Datos Generales</h2>
              <div class="field-stack">
                <div>
                  <label class="form-label">Nombre del Complejo *</label>
                  <input class="form-control" type="text" name="nombre"
                         placeholder="Ej: Complejo Deportivo Los Olivos"
                         value="<?= htmlspecialchars($cancha['nombre'] ?? '') ?>" required>
                </div>
                <div>
                  <label class="form-label">Dirección *</label>
                  <input class="form-control" type="text" name="direccion"
                         placeholder="Av. Ejemplo 123, Tacna"
                         value="<?= htmlspecialchars($cancha['direccion'] ?? '') ?>" required>
                </div>
                <div>
                  <label class="form-label">Teléfono de Contacto</label>
                  <input class="form-control" type="tel" name="telefono"
                         placeholder="+51 999 999 999"
                         value="<?= htmlspecialchars($cancha['telefono'] ?? '') ?>">
                </div>
                <div>
                  <label class="form-label">Deportes Disponibles</label>
                  <div class="sports-checks">
                    <?php foreach (['futbol'=>'⚽ Fútbol','padel'=>'🎾 Pádel','tenis'=>'🟡 Tenis','voley'=>'🏐 Vóley','basquet'=>'🏀 Básquet'] as $val=>$label): ?>
                      <label>
                        <input type="checkbox" name="deportes[]" value="<?= $val ?>"
                               <?= in_array($val, $cancha['deportes'] ?? []) ? 'checked' : '' ?>>
                        <?= $label ?>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>
                <div>
                  <label class="form-label">Precio por Hora (S/) *</label>
                  <input class="form-control" type="number" name="precio" min="1" step="0.50"
                         placeholder="Ej: 80"
                         value="<?= htmlspecialchars($cancha['precio_por_hora'] ?? '') ?>" required>
                </div>
                <div>
                  <label class="form-label">Descripción</label>
                  <textarea class="form-textarea" name="descripcion"
                            placeholder="Describe tu cancha: características, servicios, acceso, etc."><?= htmlspecialchars($cancha['descripcion'] ?? '') ?></textarea>
                </div>
              </div>
            </article>
          </div>

          <!-- Columna derecha -->
          <div>
            <article class="box">
              <h2>Horarios de Disponibilidad</h2>
              <p>Selecciona los días y horarios en que estará disponible tu cancha.</p>
              <div class="days-wrap">
                <?php foreach (['lun'=>'Lun','mar'=>'Mar','mie'=>'Mié','jue'=>'Jue','vie'=>'Vie','sab'=>'Sáb','dom'=>'Dom'] as $val=>$label): ?>
                  <label>
                    <input type="checkbox" name="dias[]" value="<?= $val ?>"
                           <?= in_array($val, $cancha['dias'] ?? []) ? 'checked' : '' ?>>
                    <?= $label ?>
                  </label>
                <?php endforeach; ?>
              </div>
              <div class="two-cols">
                <div>
                  <label class="form-label">Desde</label>
                  <select class="form-select" name="hora_inicio">
                    <?php foreach (['6:00 AM','7:00 AM','8:00 AM','9:00 AM','10:00 AM','11:00 AM','12:00 PM','1:00 PM','2:00 PM','3:00 PM','4:00 PM','5:00 PM','6:00 PM'] as $h): ?>
                      <option <?= ($cancha['hora_inicio'] ?? '') === $h ? 'selected':'' ?>><?= $h ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="form-label">Hasta</label>
                  <select class="form-select" name="hora_fin">
                    <?php foreach (['7:00 PM','8:00 PM','9:00 PM','10:00 PM','11:00 PM','12:00 AM'] as $h): ?>
                      <option <?= ($cancha['hora_fin'] ?? '') === $h ? 'selected':'' ?>><?= $h ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </article>

            <article class="box" style="margin-top:16px">
              <h2>Foto de la Cancha</h2>
              <p>Sube una foto atractiva. Formatos: JPG, PNG. Máx. 5MB.</p>
              <?php if (!empty($cancha['imagen_url'])): ?>
                <div style="margin-bottom:12px;">
                  <img src="<?= htmlspecialchars(imagen_url($cancha['imagen_url'])) ?>"
                       style="width:100%;max-height:150px;object-fit:cover;border-radius:8px;" alt="Foto actual">
                  <small>Sube una nueva foto para reemplazar la actual.</small>
                </div>
              <?php endif; ?>
              <label class="upload-box" for="imageInput">
                <span class="big">☁️</span>
                Arrastra una imagen aquí<br>o <strong>haz clic para seleccionarla</strong>
                <small>JPG, PNG · Máx. 5MB</small>
                <input id="imageInput" type="file" name="imagenes[]"
                       accept="image/jpeg,image/png,image/webp" hidden>
              </label>
              <div class="mini-gallery" id="previewGallery" style="margin-top:14px"></div>

              <div class="actions-bottom">
                <button class="btn btn-green btn-lg" type="submit">
                  <?= $modo==='editar' ? '💾 Guardar Cambios' : '📣 Publicar Cancha' ?>
                </button>
                <a class="cancel-link" href="mis-canchas.php">Cancelar</a>
              </div>
            </article>
          </div>
        </form>
      </section>
    </div>
  </main>

  <script src="../../js/nav.js"></script>
  <script src="../../js/publicar-cancha.js"></script>
</body>
</html>
