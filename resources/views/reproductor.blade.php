@extends('layouts.app')

@section('titulo', 'En reproducción · Sonora')

@section('contenido')
    <div class="mb-6 flex flex-wrap items-center justify-end gap-4">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('inicio') }}"
            class="inline-flex items-center gap-2 rounded-pill border-2 border-line-strong px-4 py-2 text-xs font-bold text-ink transition duration-[120ms] hover:bg-surface-high focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus">
            <span>Cerrar reproductor</span>
        </a>
    </div>

    {{-- Diseño en 2 columnas --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-[1.1fr_1fr]">

        {{-- Panel principal de la canción --}}
        <section class="flex flex-col justify-between rounded-lg bg-surface-raised p-6" aria-labelledby="reproductor-titulo">
            <div>
                <div class="mx-auto my-6 flex aspect-square max-w-xs items-end gap-2 rounded-md bg-surface-high p-6">
                    <i class="flex-1 rounded-pill bg-accent" style="height: 45%"></i>
                    <i class="flex-1 rounded-pill bg-accent" style="height: 85%"></i>
                    <i class="flex-1 rounded-pill bg-accent" style="height: 60%"></i>
                    <i class="flex-1 rounded-pill bg-accent" style="height: 95%"></i>
                    <i class="flex-1 rounded-pill bg-accent" style="height: 50%"></i>
                </div>

                <div class="min-w-0 text-center">
                    <h1 id="reproductor-titulo" data-titulo-actual
                        class="truncate text-2xl font-extrabold tracking-tight text-ink md:text-3xl">
                        {{ $pistas[0]['titulo'] }}
                    </h1>
                    <p class="mt-1 text-sm font-medium text-ink-muted">
                        <span data-artista-actual>{{ $pistas[0]['artista'] }}</span> · <span
                            data-album-actual>{{ $pistas[0]['album'] }}</span>
                    </p>
                </div>
            </div>

            {{-- Barra de progreso --}}
            <div class="mt-6 border-t border-line pt-6">
                <div class="flex items-center justify-between font-mono text-xs text-ink-muted">
                    <span data-tiempo-actual>00:00</span>
                    <span
                        data-duracion-actual>{{ sprintf('%02d:%02d', intdiv($pistas[0]['dur'], 60), $pistas[0]['dur'] % 60) }}</span>
                </div>

                <div class="mt-2 h-2 w-full rounded-pill bg-surface-high cursor-pointer" role="slider"
                    aria-label="Posición de reproducción" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"
                    tabindex="0" data-barra-progreso>
                    <div data-relleno-actual class="h-2 rounded-pill bg-accent transition-all duration-[120ms]"
                        style="width: 0%"></div>
                </div>

                {{-- Controles --}}
                <div class="mt-6 flex items-center justify-center gap-6">
                    <button type="button" data-accion="aleatorio" aria-label="Activar orden aleatorio" aria-pressed="false"
                        class="rounded-pill p-2.5 text-ink-muted transition duration-[120ms] hover:bg-surface-high hover:text-ink aria-pressed:text-accent focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus cursor-pointer">
                        <x-icon name="shuffle" />
                    </button>

                    <button type="button" data-accion="anterior" aria-label="Canción anterior"
                        class="rounded-pill border-2 border-line-strong p-3 text-ink transition duration-[120ms] hover:bg-surface-high focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus cursor-pointer">
                        <x-icon name="prev" />
                    </button>

                    <button type="button" data-accion="alternar" aria-label="Reproducir"
                        class="flex size-14 items-center justify-center rounded-pill bg-accent text-on-accent transition duration-[120ms] hover:bg-accent-hover focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus cursor-pointer">
                        <x-icon name="play" class="size-7" data-icono-play />
                        <x-icon name="pause" class="hidden size-7" data-icono-pausa />
                    </button>

                    <button type="button" data-accion="siguiente" aria-label="Siguiente canción"
                        class="rounded-pill border-2 border-line-strong p-3 text-ink transition duration-[120ms] hover:bg-surface-high focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus cursor-pointer">
                        <x-icon name="next" />
                    </button>

                    <button type="button" data-accion="favorito" aria-label="Guardar en tu biblioteca" aria-pressed="false"
                        class="rounded-pill p-2.5 text-ink-muted transition duration-[120ms] hover:bg-surface-high hover:text-ink aria-pressed:text-accent focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus cursor-pointer">
                        <x-icon name="heart" />
                    </button>
                </div>
            </div>
        </section>

        {{-- Cola completa de reproducción --}}
        <section class="flex flex-col justify-between rounded-lg bg-surface-raised p-6" aria-labelledby="cola-titulo">
            <div>
                <div class="mb-4 flex items-baseline justify-between">
                    <div>
                        <h2 id="cola-titulo" class="mt-1 text-xl font-bold tracking-tight text-ink">Cola de reproducción
                        </h2>
                    </div>
                </div>

                <div class="space-y-1">
                    @foreach ($pistas as $i => $pista)
                        <x-song-row :indice="$i" :pista="$pista" :actual="$i === 0" />
                    @endforeach
                </div>
            </div>

        </section>
    </div>
@endsection
