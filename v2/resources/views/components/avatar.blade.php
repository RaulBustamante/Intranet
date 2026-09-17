@props([
    'name' => '',
    'src'  => null,
    'size' => 'md',    // sm | md | lg
])

@php
    $sizes = [
        'sm' => 'h-7 w-7 text-[0.625rem]',
        'md' => 'h-9 w-9 text-xs',
        'lg' => 'h-20 w-20 text-xl',
    ];
    $cls = $sizes[$size] ?? $sizes['md'];

    // Iniciales: primera letra del nombre y del apellido.
    $parts = preg_split('/\s+/', trim($name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $initials = mb_strtoupper(
        mb_substr($parts[0] ?? '', 0, 1) . mb_substr(count($parts) > 1 ? end($parts) : '', 0, 1)
    );
@endphp

<span {{ $attributes->merge(['class' => "inline-flex shrink-0 items-center justify-center rounded-full overflow-hidden font-medium $cls"]) }}
      style="background: var(--paper-3); color: var(--ink-500); border: 1px solid var(--line);"
      @if ($name) title="{{ $name }}" @endif>
    @if ($src)
        <img src="{{ $src }}" alt="{{ $name }}" class="h-full w-full object-cover" loading="lazy">
    @else
        {{ $initials ?: '·' }}
    @endif
</span>
