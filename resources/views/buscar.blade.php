@extends('layouts.app')

@section('titulo', 'Buscar · Sonora')

@section('contenido')
    {{-- Cabecera y barra de búsqueda --}}
    <section class="rounded-lg bg-surface-raised p-6" aria-labelledby="buscar-titulo">
        <h1 id="buscar-titulo" class="mt-1 text-3xl font-extrabold tracking-tight text-ink">Explora tu música</h1>

        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <label for="campo-buscar" class="sr-only">Buscar canciones, artistas o listas</label>
                <input id="campo-buscar" type="search" autocomplete="off"
                    placeholder="Buscar por canción, artista o lista..."
                    class="w-full rounded-pill border-2 border-line-strong bg-surface-high py-3 pr-4 pl-11 text-sm text-ink placeholder:text-ink-muted focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus" />
                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-ink-muted">
                    <x-icon name="search" class="size-5" />
                </span>
            </div>

            <div class="flex items-center gap-3">
                <x-button variant="primary" data-accion="alternar">
                    <x-icon name="play" class="size-5" />
                    <span>Reproducir resultados</span>
                </x-button>

                <x-button variant="secondary" id="limpiar-busqueda">
                    <span>Limpiar</span>
                </x-button>
            </div>
        </div>

        {{-- Chips de filtro --}}
        <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Filtrar tipo de resultado">
                <x-chip :activo="true" data-filtro-tipo="todo">Todo</x-chip>
                <x-chip data-filtro-tipo="pistas">Canciones</x-chip>
                <x-chip data-filtro-tipo="listas">Listas</x-chip>
            </div>
        </div>
    </section>

    <div id="sin-resultados"
        class="mt-8 hidden items-center gap-3 rounded-md bg-surface-raised p-4 text-sm font-bold text-danger"
        role="status">
        <x-icon name="search" class="size-5" />
        <span>No encontramos canciones ni listas que coincidan con tu búsqueda</span>
    </div>

    {{-- Resultados de Playlists --}}
    <section id="seccion-listas" class="mt-8" aria-labelledby="resultados-listas">
        <div class="mb-4 flex items-baseline justify-between">
            <h2 id="resultados-listas" class="text-xl font-bold tracking-tight text-ink">Listas de reproducción</h2>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($playlists as $id => $p)
                <div data-item-busqueda="lista"
                    data-texto="{{ mb_strtolower($p['titulo'] . ' ' . $p['artista'] . ' ' . $p['descripcion']) }}">
                    <x-media-card :titulo="$p['titulo']" :artista="$p['artista']" :duracion="$p['duracion']" :barras="$p['barras']"
                        :href="route('playlist.show', $id)" />
                </div>
            @endforeach
        </div>
    </section>

    {{-- Resultados de Canciones --}}
    <section id="seccion-pistas" class="mt-8" aria-labelledby="resultados-pistas">
        <div class="mb-4 flex items-baseline justify-between">
            <h2 id="resultados-pistas" class="text-xl font-bold tracking-tight text-ink">Canciones del catálogo</h2>
        </div>

        <div class="rounded-md bg-surface-raised p-2 space-y-1">
            @foreach ($pistas as $i => $pista)
                <div data-item-busqueda="pista"
                    data-texto="{{ mb_strtolower($pista['titulo'] . ' ' . $pista['artista'] . ' ' . $pista['album']) }}">
                    <x-song-row :indice="$i" :pista="$pista" :actual="$i === 0" />
                </div>
            @endforeach
        </div>
    </section>
@endsection
