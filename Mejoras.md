1. ¿Qué componentes reutilizamos?
Se construyeron 5 componentes Blade anónimos en resources/views/components/ utilizando únicamente tokens semánticos del sistema:

<x-icon>: centraliza el isotipo de 5 barras (logo) y los iconos vectoriales de 24px con currentColor y aria-hidden="true", adaptándose automáticamente al tema oscuro y claro.

<x-button>: encapsula las 3 variantes (primary, secondary, ghost) con forma de píldora (rounded-pill), transición de 120ms y anillo de enfoque de 3px (focus-visible:outline-focus).

<x-chip>: gestiona los filtros interactivos mediante el atributo aria-pressed="true|false" para aplicar bg-accent y text-on-accent al elemento activo.

<x-media-card>: reutilizado en Inicio, Playlist y Buscar. Se extendió con un prop :href opcional para que funcione como enlace navegable con foco de teclado hacia el detalle de cada playlist.

<x-song-row>: reutilizado en las 4 vistas para listar canciones con índice y duración en JetBrains Mono (font-mono), marcando la pista activa con aria-current="true".

2. ¿Qué mejoraria respecto al proyecto de ejemplo y qué cambiaria a futuro?

Layout compartido (layouts/app.blade.php): en lugar de dejar el menú lateral y la barra inferior dentro de inicio.blade.php, se movieron al layout maestro con @yield('contenido') para no duplicar código entre pantallas.

Persistencia de la reproducción simulada: se utilizó sessionStorage en resources/js/app.js para conservar la pista activa y el segundo exacto al navegar entre Inicio, Buscar, Playlist y Reproductor.

Respeto estricto de un solo botón primario ámbar por vista: el botón de reproducir de la barra inferior usa contraste de superficie (bg-surface-high con border-line-strong) para reservar el único botón bg-accent a la acción principal de cada pantalla.

Qué cambiaríamos en una siguiente iteración:

Migrar el catálogo estático de routes/web.php a modelos Eloquent (Track y Playlist) con migraciones y seeders en SQLite/MySQL.

Incorporar SPA/navegación sin recarga (con Livewire o Inertia) para que el temporizador del reproductor no dependa de sessionStorage entre cambios de ruta.

Conectar el estado actual de resources/js/app.js a una etiqueta <audio> HTML5 real manteniendo exactamente la misma interfaz accesible.
