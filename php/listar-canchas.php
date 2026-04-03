<?php
// Aquí luego irá la consulta real a base de datos

$canchas = []; // por ahora vacío

if (empty($canchas)) {
    echo '
    <div class="empty-state">
        <div class="empty-icon">⚽</div>
        <h3>Aún no hay canchas publicadas</h3>
        <p>Cuando un propietario registre una cancha, aparecerá aquí.</p>
    </div>';
} else {
    foreach ($canchas as $cancha) {
        echo '
        <article class="court-card">
            <img src="../imagenes/' . $cancha["imagen"] . '" alt="' . htmlspecialchars($cancha["nombre"]) . '">
            <div class="body">
                <h3>' . htmlspecialchars($cancha["nombre"]) . '</h3>
                <p>📍 Ubicación: ' . htmlspecialchars($cancha["ubicacion"]) . '</p>
                <div class="price">Desde: S/ ' . htmlspecialchars($cancha["precio"]) . ' por hora</div>
                <a class="btn btn-green" href="detalle-cancha.php?id=' . $cancha["id"] . '">Ver Detalles</a>
            </div>
        </article>';
    }
}
?>