@props([
    'title'    => '',
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-end justify-between gap-3 mb-5']) }}>
    <div class="min-w-0">
        <h1 class="text-2xl">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-ink-50 mt-1">{{ $subtitle }}</p>
        @endif
    </div>

    @if (isset($actions))
        <div class="flex items-center gap-2 shrink-0">{{ $actions }}</div>
    @endif
</div>
