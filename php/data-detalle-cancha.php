<?php
// Placeholder para detalle de cancha. El backend deberá reemplazar este archivo
// por la consulta real basada en el id de cancha recibido.
$cancha = null;

// Para pruebas locales de diseño solo, use ?preview=1 y complete el bloque de muestra
if (isset($_GET['preview']) && $_GET['preview'] === '1') {
    $cancha = [
        'id' => 1,
        'nombre' => 'Cancha Sport Fútbol',
        'ubicacion' => 'Miraflores',
        'rating' => 4.8,
        'reseñas' => 230,
        'deportes' => ['Fútbol', 'Pádel', 'Tenis'],
        'imagenes' => ['buscar-cancha.png', 'buscar-cancha.png', 'fondo-login.png'],
        'dias' => [
            [
                'fecha' => 'Lunes 22 de abril',
                'horarios' => [
                    ['hora' => '6:00 PM', 'estado' => 'Disponible'],
                    ['hora' => '6:30 PM', 'estado' => 'Disponible'],
                    ['hora' => '7:30 PM', 'estado' => 'Disponible'],
                    ['hora' => '9:00 PM', 'estado' => 'Reservado'],
                    ['hora' => '9:30 PM', 'estado' => 'Disponible'],
                ],
            ],
            [
                'fecha' => 'Martes 23 de abril',
                'horarios' => [
                    ['hora' => '5:00 PM', 'estado' => 'Disponible'],
                    ['hora' => '6:00 PM', 'estado' => 'Disponible'],
                    ['hora' => '7:30 PM', 'estado' => 'Reservado'],
                ],
            ],
        ],
        'precioHora' => '80',
        'pelotaPrecio' => '5',
    ];
}
?>
