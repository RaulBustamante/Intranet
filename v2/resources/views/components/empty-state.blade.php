@props([
    'icon'  => 'info',
    'title' => null,
    'body'  => null,
])

{{-- Estado vacío diseñado (UX-10). Ninguna lista se queda en blanco:
     en la v1, cuando S3 fallaba, la página simplemente rebotaba sin decir nada. --}}
<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center py-12 px-6']) }}>
    <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-full"
         style="background: var(--paper-3); color: var(--ink-500);">
        <x-icon :name="$icon" class="w-5 h-5" />
    </div>

    <p class="text-ink font-medium">{{ $title ?? __('app.states.empty_title') }}</p>

    @if ($body !== false)
        <p class="text-ink-50 mt-1 max-w-sm text-sm">{{ $body ?? __('app.states.empty_body') }}</p>
    @endif

    @if (trim($slot) !== '')
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
