@props([
    'title'   => null,
    'eyebrow' => null,
    'href'    => null,
    'padded'  => true,
])

@php
    $tag = $href ? 'a' : 'div';
    $classes = 'ah-card block' . ($href ? ' ah-card-hover' : '') . ($padded ? ' p-4 sm:p-5' : '');
@endphp

<{{ $tag }} {{ $href ? 'href=' . $href : '' }} {{ $attributes->merge(['class' => $classes]) }}>
    @if ($eyebrow)
        <p class="ah-eyebrow mb-2">{{ $eyebrow }}</p>
    @endif

    @if ($title)
        <h3 class="text-base mb-1">{{ $title }}</h3>
    @endif

    {{ $slot }}
</{{ $tag }}>
