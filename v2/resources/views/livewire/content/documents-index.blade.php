<div>
    <x-page-header :title="__('nav.documents')">
        @role('content_editor|admin')
            <x-slot:actions>
                <x-button variant="secondary" href="{{ route('admin.documents') }}" icon="edit">{{ __('docadmin.title') }}</x-button>
            </x-slot:actions>
        @endrole
    </x-page-header>
    <div class="mb-4 flex flex-wrap gap-2">
        <div class="relative min-w-[15rem] flex-1">
            <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 w-4 h-4 -translate-y-1/2 text-ink-50" />
            <input type="search" wire:model.live.debounce.300ms="search" class="ah-input pl-9" placeholder="{{ __('app.directory.search') }}">
        </div>
        <select wire:model.live="categoryId" class="ah-input w-auto">
            <option value="">{{ __('calendar.all') }}</option>
            @foreach ($this->categories as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <x-card :padded="false">
        @forelse ($this->documents as $doc)
            <a href="{{ $doc->url() }}" target="_blank" rel="noopener"
               class="flex items-center gap-3 px-4 py-3 hover:bg-paper-2 @if (! $loop->last) border-b @endif" style="border-color: var(--line);">
                <x-icon name="file" class="w-5 h-5 shrink-0 text-ink-50" />
                <div class="min-w-0 flex-1">
                    <p class="truncate font-medium text-ink">{{ $doc->title }}</p>
                    <p class="text-xs text-ink-50">{{ $doc->category?->name }} @if ($doc->is_private) · 🔒 @endif</p>
                </div>
                <x-icon name="chevron" class="w-4 h-4 shrink-0 text-ink-50" />
            </a>
        @empty
            <x-empty-state icon="file" :title="__('app.states.empty_title')" :body="false" />
        @endforelse
    </x-card>
</div>
