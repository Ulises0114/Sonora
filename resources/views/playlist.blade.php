@extends('layouts.app')

@section('titulo', $playlistActual['titulo'] . ' · Sonora')

@section('contenido')
    @php
        $totalSegundos = array_sum(array_column($pistas, 'dur'));
        $totalMinutos = intdiv($totalSegundos, 60);
        $restoSegundos = $totalSegundos % 60;
    @endphp

    <div class="mb-6 flex flex-wrap items-center gap-2" role="group" aria-label="Seleccionar lista de reproducción">
        @foreach ($playlists as $id => $pl)
            <a href="{{ route('playlist.show', $id) }}" aria-current="{{ $playlistId === $id ? 'page' : 'false' }}"
                class="rounded-pill border-2 border-transparent px-4 py-2 text-xs font-bold uppercase tracking-wider transition duration-[120ms] {{ $playlistId === $id ? 'bg-surface-high border-line-strong text-ink' : 'bg-surface-raised text-ink-muted hover:bg-surface-high hover:text-ink' }} focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus">
                {{ $pl['titulo'] }}
            </a>
        @endforeach
    </div>

    <section class="rounded-lg bg-surface-raised p-6" aria-labelledby="playlist-titulo">
        <div class="flex flex-col gap-6 md:flex-row md:items-end">
            <div class="flex size-40 shrink-0 items-end gap-1.5 rounded-md bg-surface-high p-5">
                @foreach ($playlistActual['barras'] as $h)
                    <i class="flex-1 rounded-pill bg-accent" style="height: {{ $h }}%"></i>
                @endforeach
            </div>

            <div class="min-w-0 flex-1">
                <p class="font-mono text-xs uppercase tracking-wider text-ink-muted">LISTA DE REPRODUCCIÓN PÚBLICA</p>
                <h1 id="playlist-titulo" class="mt-1 text-3xl font-extrabold tracking-tight text-ink md:text-4xl">
                    {{ $playlistActual['titulo'] }}
                </h1>
                <p class="mt-1 text-sm font-medium text-ink-muted">{{ $playlistActual['artista'] }} —
                    {{ $playlistActual['descripcion'] }}</p>

                <div class="mt-4 flex flex-wrap items-center gap-4">
                    <span class="font-mono text-xs text-ink-muted">
                        {{ str_pad(count($pistas), 2, '0', STR_PAD_LEFT) }} pistas ·
                        {{ sprintf('%02d:%02d', $totalMinutos, $restoSegundos) }} min
                    </span>
                    <span
                        class="inline-flex items-center gap-1.5 rounded-pill bg-surface-high px-3 py-1 text-xs font-bold text-success">
                        <x-icon name="library" class="size-4" />
                        <span>Disponible sin conexión</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Barra de acciones --}}
        <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-line pt-6">
            <x-button variant="primary" data-accion="alternar">
                <x-icon name="play" class="size-5" />
                <span>Reproducir lista</span>
            </x-button>

            <x-button variant="secondary" data-accion="aleatorio" aria-pressed="false">
                <x-icon name="shuffle" class="size-5" />
                <span>Orden aleatorio</span>
            </x-button>

            <x-button variant="ghost" data-accion="favorito" aria-pressed="false">
                <x-icon name="heart" class="size-5" />
                <span>Guardar en tu biblioteca</span>
            </x-button>
        </div>
    </section>

    {{-- Tabla --}}
    <section class="mt-8" aria-labelledby="pistas-encabezado">
        <div class="mb-4 flex items-baseline justify-between">
            <h2 id="pistas-encabezado" class="text-xl font-bold tracking-tight text-ink">Canciones de la mezcla</h2>
        </div>

        <div class="rounded-md bg-surface-raised p-2">
            <div
                class="grid grid-cols-[24px_1fr_auto] items-center gap-3 border-b border-line px-2 py-2 font-mono text-xs uppercase tracking-wider text-ink-muted">
                <span>#</span>
                <span>Título y artista</span>
                <span>Tiempo</span>
            </div>

            <div class="mt-1 space-y-1">
                @foreach ($pistas as $i => $pista)
                    <x-song-row :indice="$i" :pista="$pista" :actual="$i === 0" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- otras listas recomendadas --}}
    <section class="mt-8" aria-labelledby="otras-listas-titulo">
        <h2 id="otras-listas-titulo" class="mb-4 text-xl font-bold tracking-tight text-ink">Otras mezclas para ti</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            @foreach ($playlists as $id => $pl)
                @if ($id !== $playlistId)
                    <x-media-card :titulo="$pl['titulo']" :artista="$pl['artista']" :duracion="$pl['duracion']" :barras="$pl['barras']"
                        :href="route('playlist.show', $id)" />
                @endif
            @endforeach
        </div>
    </section>
@endsection
