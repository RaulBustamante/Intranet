<div>
    <x-page-header :title="__('nav.bulletins')" />
    @forelse ($this->bulletins as $year => $items)
        <div class="mb-2 mt-5 flex items-center gap-3">
            <p class="ah-eyebrow">{{ $year }}</p>
            <div class="h-px flex-1" style="background: var(--line);"></div>
        </div>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($items as $b)
                <a href="{{ $b->url() }}" target="_blank" rel="noopener" class="ah-card ah-card-hover p-4">
                    <div class="mb-3 flex h-16 items-center justify-center rounded-md" style="background: var(--paper-3); color: var(--ink-500);">
                        <x-icon name="news" class="w-6 h-6" />
                    </div>
                    <p class="truncate font-medium text-ink">{{ $b->title }}</p>
                    <p class="text-xs text-ink-50 capitalize">{{ $b->periodLabel() }}</p>
                </a>
            @endforeach
        </div>
    @empty
        <x-card><x-empty-state icon="news" :title="__('app.states.empty_title')" :body="false" /></x-card>
    @endforelse
</div>
