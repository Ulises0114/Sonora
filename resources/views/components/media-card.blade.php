@props(['titulo', 'artista', 'duracion', 'barras' => [50, 80, 60, 90, 40], 'href' => null])
@php
    $clases =
        'group block rounded-md bg-surface-raised p-4 text-left transition duration-[120ms] hover:bg-surface-high hover:shadow-raise focus-visible:outline-[3px] focus-visible:outline-offset-2 focus-visible:outline-focus';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $clases]) }}>
        <div
            class="mb-4 flex aspect-square items-end gap-1 rounded-md bg-surface-high p-4 transition duration-[120ms] group-hover:bg-surface-base">
            @foreach ($barras as $h)
                <i class="flex-1 rounded-pill bg-accent" style="height: {{ $h }}%"></i>
            @endforeach
        </div>
        <p class="text-xl font-bold text-ink">{{ $titulo }}</p>
        <p class="mt-1 text-sm font-medium text-ink-muted">{{ $artista }}</p>
        <p class="mt-2 font-mono text-xs text-ink-muted">{{ $duracion }}</p>
    </a>
@else
    <div {{ $attributes->merge(['class' => $clases]) }}>
        <div
            class="mb-4 flex aspect-square items-end gap-1 rounded-md bg-surface-high p-4 transition duration-[120ms] group-hover:bg-surface-base">
            @foreach ($barras as $h)
                <i class="flex-1 rounded-pill bg-accent" style="height: {{ $h }}%"></i>
            @endforeach
        </div>
        <p class="text-xl font-bold text-ink">{{ $titulo }}</p>
        <p class="mt-1 text-sm font-medium text-ink-muted">{{ $artista }}</p>
        <p class="mt-2 font-mono text-xs text-ink-muted">{{ $duracion }}</p>
    </div>
@endif
