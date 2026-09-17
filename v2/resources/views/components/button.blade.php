@props([
    'variant' => 'secondary',   // primary | secondary | ghost
    'href'    => null,
    'icon'    => null,
    'type'    => 'button',
])

@php
    $tag = $href ? 'a' : 'button';
    $classes = 'ah-btn ah-btn-' . $variant;
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="{{ $type }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)
        <x-icon :name="$icon" class="w-4 h-4 shrink-0" />
    @endif
    {{ $slot }}
</{{ $tag }}>
