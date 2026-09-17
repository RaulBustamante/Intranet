<div>
    <x-page-header :title="__('events.title')" :subtitle="__('events.subtitle')">
        <x-slot:actions>
            <x-button variant="primary" icon="calendar" wire:click="create">{{ __('events.new') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card :padded="false">
        @forelse ($this->events as $e)
            <div class="flex items-center gap-3 px-4 py-3 @if (! $loop->last) border-b @endif" style="border-color: var(--line);">
                <div class="w-24 shrink-0 text-xs tabular-nums text-ink-50">
                    {{ $e->starts_at->locale(app()->getLocale())->isoFormat('ddd D MMM') }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate font-medium text-ink">{{ $e->title }}</p>
                    <p class="truncate text-xs text-ink-50">
                        {{ __('events.types.' . $e->type) }}
                        @if ($e->location) · {{ $e->location->code }} @endif
                    </p>
                </div>
                <x-button variant="ghost" wire:click="edit({{ $e->id }})">{{ __('admin.actions.edit') }}</x-button>
                <x-button variant="ghost" wire:click="delete({{ $e->id }})" wire:confirm="?">
                    <x-icon name="x" class="w-4 h-4" />
                </x-button>
            </div>
        @empty
            <x-empty-state icon="calendar" :title="__('events.none')" :body="false" />
        @endforelse
    </x-card>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-start justify-center px-4 py-[8vh]" role="dialog" aria-modal="true">
            <div class="absolute inset-0" wire:click="$set('showForm', false)"
                 style="background: color-mix(in srgb, var(--ink-900) 40%, transparent);"></div>
            <div class="ah-card relative w-full max-w-lg p-5 shadow-2xl">
                <h2 class="mb-4 text-lg">{{ $editingId ? __('events.edit') : __('events.new') }}</h2>
                <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('events.title_es') }}</label>
                        <input type="text" wire:model="title_es" class="ah-input">
                        @error('title_es') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('events.title_en') }}</label>
                        <input type="text" wire:model="title_en" class="ah-input">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('events.type') }}</label>
                        <select wire:model="type" class="ah-input">
                            @foreach (['company','training','maintenance','other'] as $t)
                                <option value="{{ $t }}">{{ __('events.types.' . $t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('events.location') }}</label>
                        <select wire:model="location_id" class="ah-input">
                            <option value="">—</option>
                            @foreach ($this->locations as $l)
                                <option value="{{ $l->id }}">{{ $l->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('events.starts') }}</label>
                        <input type="datetime-local" wire:model="starts_at" class="ah-input">
                        @error('starts_at') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('events.ends') }}</label>
                        <input type="datetime-local" wire:model="ends_at" class="ah-input">
                        @error('ends_at') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end gap-2 sm:col-span-2">
                        <x-button variant="secondary" type="button" wire:click="$set('showForm', false)">{{ __('admin.actions.cancel') }}</x-button>
                        <x-button variant="primary" type="submit">{{ __('admin.actions.save') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div x-data="{ show: false, msg: '' }"
         x-on:notify.window="msg = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
         x-show="show" x-cloak
         class="ah-card fixed bottom-5 left-1/2 z-50 -translate-x-1/2 px-4 py-2.5 shadow-lg">
        <p class="text-sm text-ink" x-text="msg"></p>
    </div>
</div>
