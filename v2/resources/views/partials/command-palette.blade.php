{{--
    Paleta de comandos ⌘K (SRCH-01, SRCH-05)

    En la Fase 1 es la carcasa: se abre, filtra sobre los datos de muestra,
    navega con teclado y ejecuta acciones. La búsqueda real contra el índice
    unificado —y la búsqueda dentro del contenido de los PDF— entra en la Fase 6.
--}}
<div data-palette hidden
     class="fixed inset-0 z-50 flex items-start justify-center px-4 pt-[12vh]"
     role="dialog" aria-modal="true" aria-label="{{ __('nav.search') }}">

    <div class="absolute inset-0" data-palette-backdrop
         style="background: color-mix(in srgb, var(--ink-900) 40%, transparent);"></div>

    <div class="ah-card relative w-full max-w-xl overflow-hidden shadow-2xl">
        <div class="flex items-center gap-2.5 border-b px-3.5" style="border-color: var(--line);">
            <x-icon name="search" class="w-4 h-4 text-ink-50 shrink-0" />
            <input type="text" data-palette-input autocomplete="off" spellcheck="false"
                   class="w-full bg-transparent py-3.5 text-sm text-ink outline-none placeholder:text-[var(--ink-500)]"
                   placeholder="{{ __('app.search.placeholder') }}">
            <button type="button" data-palette-close class="ah-btn ah-btn-ghost px-1.5 py-1 shrink-0"
                    aria-label="{{ __('app.search.close') }}">
                <x-icon name="x" class="w-4 h-4" />
            </button>
        </div>

        <div data-palette-results class="max-h-[50vh] overflow-y-auto p-1.5"></div>

        <div class="flex items-center gap-3 border-t px-3.5 py-2 text-[0.6875rem] text-ink-50"
             style="border-color: var(--line); background: var(--paper-2);">
            <span><kbd class="font-sans">↑↓</kbd> {{ __('app.search.navigate') }}</span>
            <span><kbd class="font-sans">⏎</kbd> {{ __('app.search.select') }}</span>
            <span><kbd class="font-sans">esc</kbd> {{ __('app.search.close') }}</span>
        </div>
    </div>
</div>

@php
    // Se arma aquí y no dentro de @json(): un array anidado y multilínea
    // confunde al parser de argumentos de las directivas de Blade.
    $ahLabels = [
        'hint' => __('app.search.placeholder'),
        'ask' => __('search.ask'),
        'people'    => __('app.search.people'),
        'documents' => __('app.search.documents'),
        'links'     => __('app.search.links'),
        'actions'   => __('app.search.actions'),
        'noResults' => __('app.states.no_results', ['term' => '__TERM__']),
    ];
@endphp

<script>
    window.ahPaletteData = @json($paletteItems ?? []);
    window.ahPaletteLabels = @json($ahLabels);
</script>
