<div>
    <x-page-header :title="__('nav.announcements')" />
    <div class="space-y-3">
        @forelse ($this->announcements as $a)
            <x-card>
                <div class="flex items-start gap-3">
                    @if ($a->is_pinned)
                        <x-icon name="badge" class="mt-0.5 w-4 h-4 shrink-0 text-brand" title="fijado" />
                    @endif
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base font-medium text-ink">{{ $a->title }}</h3>
                        <p class="mt-0.5 text-xs text-ink-50">
                            {{ $a->author?->display_name }} · {{ $a->published_at?->locale(app()->getLocale())->diffForHumans() }}
                        </p>
                        @if ($a->excerpt)
                            <p class="mt-2 text-sm text-ink-70">{{ $a->excerpt }}</p>
                        @endif
                    </div>
                </div>
            </x-card>
        @empty
            <x-card><x-empty-state icon="news" :title="__('app.states.empty_title')" :body="false" /></x-card>
        @endforelse
    </div>
</div>
