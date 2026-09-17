<div>
    <x-page-header :title="__('nav.links')" :subtitle="__('links.subtitle')" />

    @forelse ($this->groups as $category => $links)
        <div class="mb-2 mt-5 flex items-center gap-3">
            <p class="ah-eyebrow">{{ __('links.cat.' . $category, [], $category === 'general' ? null : $category) }}</p>
            <div class="h-px flex-1" style="background: var(--line);"></div>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($links as $link)
                @php $color = $link->accentColor(); @endphp
                <a href="{{ $link->url }}" target="_blank" rel="noopener"
                   class="ah-card ah-card-hover group relative flex items-center gap-3 p-4">
                    {{-- Logo del sitio, con fallback a inicial coloreada --}}
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl text-base font-semibold"
                          style="background: color-mix(in srgb, {{ $color }} 14%, transparent); color: {{ $color }};">
                        @if ($link->faviconUrl())
                            <img src="{{ $link->faviconUrl() }}" alt="" class="h-6 w-6"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='block'" loading="lazy">
                            <span style="display:none">{{ $link->initial() }}</span>
                        @else
                            {{ $link->initial() }}
                        @endif
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium text-ink">{{ $link->title }}</p>
                        @if ($link->is_internal_only)
                            <p class="truncate text-xs text-ink-50">{{ __('links.internal_only') }}</p>
                        @endif
                    </div>
                    <span class="absolute inset-x-0 bottom-0 h-0.5 origin-left scale-x-0 rounded-b transition group-hover:scale-x-100"
                          style="background: {{ $color }};"></span>
                </a>
            @endforeach
        </div>
    @empty
        <x-card><x-empty-state icon="link" :title="__('app.states.empty_title')" :body="false" /></x-card>
    @endforelse
</div>
