<?php
/**
 * Punto de entrada raíz — AlquilaTuCancha
 * Coloca este archivo en la raíz del proyecto (junto a /frontend, /php, /styles)
 * Redirige a frontend/index.php manteniendo query strings y funciona con la IPv4 pública.
 */
$query = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
header('Location: frontend/index.php' . $query, true, 302);
exit;
