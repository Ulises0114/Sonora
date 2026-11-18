<?php

use Illuminate\Support\Facades\Route;

function datosSonora(): array
{
    $pistas = [
        ['titulo' => 'Compilando en viernes', 'artista' => 'Los sin dormir', 'album' => 'Semestre eterno', 'dur' => 214, 'calidad' => '320 kbps'],
        ['titulo' => 'Café de medianoche', 'artista' => 'Bucle infinito', 'album' => 'Sesiones de madrugada', 'dur' => 198, 'calidad' => '320 kbps'],
        ['titulo' => 'Sin errores en consola', 'artista' => 'Teclado mecánico', 'album' => 'Código limpio', 'dur' => 245, 'calidad' => '320 kbps'],
        ['titulo' => 'Falta un punto y coma', 'artista' => 'Depuradores del sur', 'album' => 'Línea 42', 'dur' => 182, 'calidad' => '320 kbps'],
        ['titulo' => 'Variables en la niebla', 'artista' => 'Eco binario', 'album' => 'Memoria caché', 'dur' => 260, 'calidad' => '320 kbps'],
        ['titulo' => 'Último intento antes de dormir', 'artista' => 'Rama principal', 'album' => 'Conflicto resuelto', 'dur' => 228, 'calidad' => '320 kbps'],
        ['titulo' => 'Sombras del monitor', 'artista' => 'Frecuencia local', 'album' => 'Puerto 8000', 'dur' => 191, 'calidad' => '320 kbps'],
        ['titulo' => 'Promesa resuelta', 'artista' => 'Nube estática', 'album' => 'Tiempo de respuesta', 'dur' => 236, 'calidad' => '320 kbps'],
    ];

    $playlists = [
        [
            'titulo' => 'Profe ya pásenos',
            'artista' => 'Himnos de fin de parcial',
            'descripcion' => 'Pistas urgentes para entregar el proyecto a las 23:59 y alcanzar el cien en la rúbrica.',
            'duracion' => '28 min · 8 pistas',
            'barras' => [45, 85, 60, 95, 50],
        ],
        [
            'titulo' => 'Programando a solas',
            'artista' => 'Concentración profunda',
            'descripcion' => 'Sintetizadores oscuros y pulsos constantes para maquetar vistas sin mirar el reloj.',
            'duracion' => '32 min · 8 pistas',
            'barras' => [70, 40, 80, 55, 90],
        ],
        [
            'titulo' => 'Pedimos piedad',
            'artista' => 'Rescate de madrugada',
            'descripcion' => 'Melodías suaves para mantener la calma cuando algo no compila a la primera.',
            'duracion' => '25 min · 8 pistas',
            'barras' => [35, 65, 90, 45, 75],
        ],
        [
            'titulo' => 'Soñando con tailwind',
            'artista' => 'Tokens y utilidades',
            'descripcion' => 'Ritmos milimétricos sin un solo hexadecimal suelto para diseñar en tema oscuro.',
            'duracion' => '29 min · 8 pistas',
            'barras' => [80, 55, 40, 85, 65],
        ],
    ];

    return compact('pistas', 'playlists');
}

Route::get('/', function () {
    return view('inicio', datosSonora());
})->name('inicio');

Route::get('/playlist/{id?}', function (int $id = 0) {
    $datos = datosSonora();
    $playlistId = array_key_exists($id, $datos['playlists']) ? $id : 0;
    $playlist = $datos['playlists'][$playlistId];
    return view('playlist', array_merge($datos, [
        'playlistActual' => $playlist,
        'playlistId' => $playlistId,
    ]));
})->name('playlist.show');

Route::get('/reproductor', function () {
    return view('reproductor', datosSonora());
})->name('reproductor');

Route::get('/buscar', function () {
    return view('buscar', datosSonora());
})->name('buscar');
