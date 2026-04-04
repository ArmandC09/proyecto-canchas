<?php
include __DIR__ . '/data-mis-canchas.php';

$canchas = $canchas ?? $canchas_propietario ?? [];
$selectedSport = $_GET['deporte'] ?? 'Todos';

if ($selectedSport !== 'Todos') {
    $canchas = array_filter($canchas, function ($cancha) use ($selectedSport) {
        return stripos($cancha['deportes'] ?? '', $selectedSport) !== false;
    });
}

if (empty($canchas)) {
    echo '
    <div class="empty-state">
        <div class="empty-icon">⚽</div>
        <h3>Aún no has publicado canchas</h3>
        <p>Publica tu primera cancha para empezar a recibir reservas.</p>
    </div>';
} else {
    echo '<div class="courts-list">';
    foreach ($canchas as $cancha) {
        echo '
        <article class="court-row">
          <div class="court-media">
            <img class="main" src="../../imagenes/' . htmlspecialchars($cancha["imagen_principal"]) . '" alt="Cancha">
            <div class="thumbs">
              <img src="../../imagenes/' . htmlspecialchars($cancha["imagen1"]) . '">
              <img src="../../imagenes/' . htmlspecialchars($cancha["imagen2"]) . '">
              <img src="../../imagenes/' . htmlspecialchars($cancha["imagen3"]) . '">
            </div>
          </div>
          <div class="court-data">
            <h3>' . htmlspecialchars($cancha["nombre"]) . '</h3>
            <p>' . htmlspecialchars($cancha["ubicacion"]) . '</p>
            <div class="sports-line">' . htmlspecialchars($cancha["deportes"]) . '</div>
            <div class="price-line" style="margin-top:28px">Desde S/' . htmlspecialchars($cancha["precio"]) . ' por hora</div>
          </div>
          <div class="court-side">
            <span class="badge green">Publicado</span>
            <div class="side-actions">
              <a href="publicar-cancha.php" class="btn btn-orange">Editar</a>
              <a href="../../php/eliminar-cancha.php?id=' . $cancha["id"] . '" class="btn btn-red">🗑</a>
            </div>
            <a class="count-badge" href="gestion-reservas.php">Reservas <span>' . htmlspecialchars($cancha["reservas"]) . '</span></a>
          </div>
        </article>';
    }
    echo '</div>';
}
?>