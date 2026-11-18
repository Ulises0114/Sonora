@extends('layouts.app')

@section('titulo', 'Inicio · Sonora')

@section('contenido')
    <div class="mb-6 flex flex-wrap items-center gap-2" role="group" aria-label="Filtrar contenido">
        <x-chip :activo="true">Todo</x-chip>
        <x-chip>Música</x-chip>
        <x-chip>Concentración</x-chip>
        <x-chip>Instrumental</x-chip>
    </div>

    <section class="rounded-lg bg-surface-raised p-6" aria-labelledby="destacado-titulo">
        <p class="font-mono text-xs uppercase tracking-wider text-ink-muted">SESIÓN RECOMENDADA</p>
        <div class="mt-2 flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
            <div>
                <h1 id="destacado-titulo" class="text-3xl font-extrabold tracking-tight text-ink">Tu música para programar
                </h1>
                <p class="mt-1 text-sm font-medium text-ink-muted">Selección continua de sintetizadores, bajos suaves y
                    ritmos sin distracciones para entregar a tiempo.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <x-button variant="primary" data-accion="alternar">
                    <x-icon name="play" class="size-5" />
                    <span>Reproducir mezcla</span>
                </x-button>
                <a href="{{ route('playlist.show', 0) }}"
                    class="inline-flex items-center justify-center rounded-pill border-2 border-line-strong px-6 py-3 text-sm font-bold text-ink transition duration-[120ms] hover:bg-surface-high focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus">
                    Ver tu biblioteca
                </a>
            </div>
        </div>
    </section>

    {{-- Cuadrícula con 4 playlists --}}
    <section class="mt-8" aria-labelledby="playlists-titulo">
        <div class="mb-4 flex items-baseline justify-between">
            <h2 id="playlists-titulo" class="text-xl font-bold tracking-tight text-ink">Tus mezclas de supervivencia</h2>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($playlists as $id => $p)
                <x-media-card :titulo="$p['titulo']" :artista="$p['artista']" :duracion="$p['duracion']" :barras="$p['barras']" :href="route('playlist.show', $id)" />
            @endforeach
        </div>
    </section>

    {{-- Pistas en cola --}}
    <section class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2" aria-label="Listas rápidas de canciones">
        <div>
            <div class="mb-4 flex items-baseline justify-between">
                <h2 class="text-xl font-bold tracking-tight text-ink">En cola</h2>
            </div>
            <div class="rounded-md bg-surface-raised p-2">
                @foreach (array_slice($pistas, 0, 4, true) as $i => $pista)
                    <x-song-row :indice="$i" :pista="$pista" :actual="$i === 0" />
                @endforeach
            </div>
        </div>

        <div>
            <div class="mb-4 flex items-baseline justify-between">
                <h2 class="text-xl font-bold tracking-tight text-ink">Escuchado recientemente</h2>
            </div>
            <div class="rounded-md bg-surface-raised p-2">
                @foreach (array_slice($pistas, 4, 4, true) as $i => $pista)
                    <x-song-row :indice="$i" :pista="$pista" :actual="false" />
                @endforeach
            </div>
        </div>
    </section>
@endsection
