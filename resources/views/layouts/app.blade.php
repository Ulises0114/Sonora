<!doctype html>
<html lang="es" data-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Sonora')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400&family=Manrope:wght@400;500;700;800&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-surface-base font-sans text-ink antialiased overflow-hidden">
    @isset($pistas)
        <script>
            window.pistas = @json($pistas);
        </script>
    @endisset

    <div class="grid h-dvh grid-cols-1 md:grid-cols-[220px_1fr] grid-rows-[auto_1fr_auto_auto] md:grid-rows-[1fr_auto]">

        {{-- header móvil (visible solo en < 768px / 390px) --}}
        <header class="flex md:hidden items-center justify-between border-b border-line bg-surface-raised px-4 py-3">
            <a href="{{ route('inicio') }}"
                class="flex items-center gap-2 rounded-md py-1 text-lg font-extrabold tracking-tight text-ink focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus">
                <x-icon name="logo" class="size-6 text-accent" />
                <span>Sonora</span>
            </a>

            <x-button variant="ghost" data-accion="tema" class="px-3 py-1.5 text-xs">
                Cambiar tema
            </x-button>
        </header>

        {{-- Navegación lateral --}}
        <nav class="hidden md:flex flex-col gap-2 border-r border-line bg-surface-raised px-4 py-6"
            aria-label="Principal">
            <a href="{{ route('inicio') }}"
                class="mb-6 flex items-center gap-2 rounded-md px-2 py-1 text-xl font-extrabold tracking-tight text-ink focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus">
                <x-icon name="logo" class="size-7 text-accent" />
                <span>Sonora</span>
            </a>

            <a href="{{ route('inicio') }}" @if (request()->routeIs('inicio')) aria-current="page" @endif
                class="flex items-center gap-3 rounded-md p-3 text-sm font-bold transition duration-[120ms] {{ request()->routeIs('inicio') ? 'bg-surface-high text-ink' : 'text-ink-muted hover:bg-surface-high hover:text-ink' }} focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus">
                <x-icon name="home" />
                <span>Inicio</span>
            </a>

            <a href="{{ route('buscar') }}" @if (request()->routeIs('buscar')) aria-current="page" @endif
                class="flex items-center gap-3 rounded-md p-3 text-sm font-bold transition duration-[120ms] {{ request()->routeIs('buscar') ? 'bg-surface-high text-ink' : 'text-ink-muted hover:bg-surface-high hover:text-ink' }} focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus">
                <x-icon name="search" />
                <span>Buscar</span>
            </a>

            <a href="{{ route('playlist.show', 0) }}" @if (request()->routeIs('playlist.*')) aria-current="page" @endif
                class="flex items-center gap-3 rounded-md p-3 text-sm font-bold transition duration-[120ms] {{ request()->routeIs('playlist.*') ? 'bg-surface-high text-ink' : 'text-ink-muted hover:bg-surface-high hover:text-ink' }} focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus">
                <x-icon name="library" />
                <span>Tu biblioteca</span>
            </a>

            <div class="mt-auto border-t border-line pt-4">
                <x-button variant="ghost" id="tema" data-accion="tema" class="w-full justify-start px-3">
                    Cambiar tema
                </x-button>
            </div>
        </nav>

        {{-- Contenido de cada pantalla --}}
        <main class="overflow-y-auto px-4 py-5 sm:px-6 md:px-8 md:py-6">
            @yield('contenido')
        </main>

        {{-- Barra inferior del reproductor --}}
        <footer
            class="col-span-1 md:col-span-2 grid grid-cols-[1fr_auto] md:grid-cols-[1fr_2fr_1fr] items-center gap-3 md:gap-4 border-t border-line bg-surface-raised px-4 py-2.5 md:py-3"
            aria-label="Reproductor en curso">
            <div class="flex items-center min-w-0">
                <a href="{{ route('reproductor') }}" aria-label="Abrir vista de canción en reproducción"
                    class="group flex items-center gap-3 min-w-0 rounded-md p-1.5 transition duration-[120ms] hover:bg-surface-high focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus">
                    <div
                        class="flex size-10 md:size-11 shrink-0 items-end gap-0.5 rounded-sm bg-surface-high p-2 group-hover:bg-surface-base">
                        <i class="flex-1 rounded-pill bg-accent" style="height: 60%"></i>
                        <i class="flex-1 rounded-pill bg-accent" style="height: 90%"></i>
                        <i class="flex-1 rounded-pill bg-accent" style="height: 45%"></i>
                    </div>
                    <div class="min-w-0">
                        <p id="titulo" class="truncate text-sm font-bold text-ink group-hover:text-accent">Compilando
                            en viernes</p>
                        <p id="artista" class="truncate text-xs font-medium text-ink-muted">Los sin dormir</p>
                    </div>
                </a>
            </div>

            {{-- Derecha en móvil --}}
            <div class="flex items-center justify-end gap-3 md:order-3">
                <button type="button" data-accion="favorito" aria-label="Guardar en tu biblioteca" aria-pressed="false"
                    class="rounded-pill p-2 text-ink-muted transition duration-[120ms] hover:bg-surface-high hover:text-ink aria-pressed:text-accent focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus cursor-pointer">
                    <x-icon name="volume" class="size-5" />
                </button>
            </div>

            {{-- Controles de reproducción --}}
            <div class="col-span-2 md:col-span-1 md:order-2 flex flex-col items-center gap-1.5 md:gap-2">
                <div class="flex items-center gap-4 md:gap-5">
                    <button type="button" data-accion="aleatorio" aria-label="Activar orden aleatorio"
                        aria-pressed="false"
                        class="rounded-pill p-1.5 text-ink-muted transition duration-[120ms] hover:bg-surface-high hover:text-ink aria-pressed:text-accent focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus cursor-pointer">
                        <x-icon name="shuffle" class="size-5" />
                    </button>
                    <button type="button" data-accion="anterior" aria-label="Canción anterior"
                        class="rounded-pill p-1.5 text-ink transition duration-[120ms] hover:bg-surface-high focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus cursor-pointer">
                        <x-icon name="prev" />
                    </button>
                    <button type="button" data-accion="alternar" aria-label="Reproducir"
                        class="flex size-9 md:size-10 items-center justify-center rounded-pill border-2 border-line-strong bg-surface-high text-ink transition duration-[120ms] hover:border-ink focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus cursor-pointer">
                        <x-icon name="play" id="icono-play" />
                        <x-icon name="pause" id="icono-pausa" class="hidden" />
                    </button>
                    <button type="button" data-accion="siguiente" aria-label="Siguiente canción"
                        class="rounded-pill p-1.5 text-ink transition duration-[120ms] hover:bg-surface-high focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus cursor-pointer">
                        <x-icon name="next" />
                    </button>
                </div>

                <div class="flex w-full max-w-xl items-center gap-2.5 font-mono text-xs text-ink-muted">
                    <span id="tiempo">00:00</span>
                    <div class="h-1 flex-1 rounded-pill bg-surface-high cursor-pointer" role="slider"
                        aria-label="Progreso de la canción" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"
                        tabindex="0" id="barra">
                        <div id="relleno" class="h-1 rounded-pill bg-accent" style="width: 0%"></div>
                    </div>
                    <span id="duracion">03:34</span>
                </div>
            </div>
        </footer>

        {{-- Navegación inferior móvil (3 pestañas: Inicio, Buscar y Biblioteca) --}}
        <nav class="grid md:hidden grid-cols-3 border-t border-line bg-surface-raised px-2 py-1.5"
            aria-label="Navegación móvil">
            <a href="{{ route('inicio') }}" @if (request()->routeIs('inicio')) aria-current="page" @endif
                class="flex flex-col items-center justify-center gap-1 rounded-md py-1.5 text-xs font-bold transition duration-[120ms] {{ request()->routeIs('inicio') ? 'bg-surface-high text-ink' : 'text-ink-muted hover:text-ink' }} focus-visible:outline-[3px] focus-visible:outline-focus">
                <x-icon name="home" class="size-5" />
                <span>Inicio</span>
            </a>

            <a href="{{ route('buscar') }}" @if (request()->routeIs('buscar')) aria-current="page" @endif
                class="flex flex-col items-center justify-center gap-1 rounded-md py-1.5 text-xs font-bold transition duration-[120ms] {{ request()->routeIs('buscar') ? 'bg-surface-high text-ink' : 'text-ink-muted hover:text-ink' }} focus-visible:outline-[3px] focus-visible:outline-focus">
                <x-icon name="search" class="size-5" />
                <span>Buscar</span>
            </a>

            <a href="{{ route('playlist.show', 0) }}" @if (request()->routeIs('playlist.*')) aria-current="page" @endif
                class="flex flex-col items-center justify-center gap-1 rounded-md py-1.5 text-xs font-bold transition duration-[120ms] {{ request()->routeIs('playlist.*') ? 'bg-surface-high text-ink' : 'text-ink-muted hover:text-ink' }} focus-visible:outline-[3px] focus-visible:outline-focus">
                <x-icon name="library" class="size-5" />
                <span>Biblioteca</span>
            </a>
        </nav>
    </div>
</body>

</html>
