<div>
    @php
        $tintColors = ['blue'=>'#3b82f6','green'=>'#0f9d58','orange'=>'#ea8600','purple'=>'#7c3aed','red'=>'#ed2228','teal'=>'#0d9488','pink'=>'#db2777','indigo'=>'#4f46e5','slate'=>'#475569'];
    @endphp

    <x-page-header :title="__('shortcuts.title')" :subtitle="__('shortcuts.subtitle')">
        <x-slot:actions>
            <x-button variant="primary" icon="link" wire:click="create">{{ __('shortcuts.new') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Vista previa en vivo de cómo se verán en la landing --}}
    <p class="ah-eyebrow mb-2">{{ __('shortcuts.preview') }}</p>
    <div class="mb-6 grid grid-cols-2 gap-2 sm:grid-cols-4 lg:grid-cols-6">
        @foreach ($this->shortcuts->where('is_active', true) as $s)
            @php $c = $tintColors[$s->tint] ?? '#475569'; @endphp
            <div class="ah-card flex flex-col items-center gap-2 p-3 text-center">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl"
                      style="background: color-mix(in srgb, {{ $c }} 12%, transparent); color: {{ $c }};">
                    <x-icon :name="$s->icon" class="w-5 h-5" />
                </span>
                <span class="truncate text-xs font-medium text-ink">{{ $s->label }}</span>
            </div>
        @endforeach
    </div>

    {{-- Lista administrable --}}
    <x-card :padded="false">
        @foreach ($this->shortcuts as $s)
            @php $c = $tintColors[$s->tint] ?? '#475569'; @endphp
            <div class="flex items-center gap-3 px-4 py-2.5 @if (! $loop->last) border-b @endif @if (! $s->is_active) opacity-50 @endif" style="border-color: var(--line);">
                <div class="flex flex-col">
                    <button wire:click="move({{ $s->id }}, 'up')" class="text-ink-50 hover:text-ink" @if ($loop->first) disabled @endif>▲</button>
                    <button wire:click="move({{ $s->id }}, 'down')" class="text-ink-50 hover:text-ink" @if ($loop->last) disabled @endif>▼</button>
                </div>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg" style="background: color-mix(in srgb, {{ $c }} 12%, transparent); color: {{ $c }};">
                    <x-icon :name="$s->icon" class="w-4 h-4" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate font-medium text-ink">{{ $s->label_es }}</p>
                    <p class="truncate text-xs text-ink-50">
                        {{ $s->isExternal() ? $s->target : __('shortcuts.section') . ': ' . $s->target }}
                        @if ($s->requires_auth) · 🔒 @endif
                    </p>
                </div>
                <x-button variant="ghost" wire:click="toggle({{ $s->id }})">
                    {{ $s->is_active ? __('shortcuts.hide') : __('shortcuts.show') }}
                </x-button>
                <x-button variant="ghost" wire:click="edit({{ $s->id }})">{{ __('admin.actions.edit') }}</x-button>
                <x-button variant="ghost" wire:click="delete({{ $s->id }})" wire:confirm="?"><x-icon name="x" class="w-4 h-4" /></x-button>
            </div>
        @endforeach
    </x-card>

    {{-- Formulario --}}
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto px-4 py-[6vh]" role="dialog" aria-modal="true">
            <div class="absolute inset-0" wire:click="$set('showForm', false)" style="background: color-mix(in srgb, var(--ink-900) 40%, transparent);"></div>
            <div class="ah-card relative w-full max-w-lg p-5 shadow-2xl">
                <h2 class="mb-4 text-lg">{{ $editingId ? __('shortcuts.edit') : __('shortcuts.new') }}</h2>
                <form wire:submit="save" class="space-y-4">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('shortcuts.label_es') }}</label>
                            <input type="text" wire:model="label_es" class="ah-input">
                            @error('label_es') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('shortcuts.label_en') }}</label>
                            <input type="text" wire:model="label_en" class="ah-input">
                        </div>
                    </div>

                    {{-- Icono: selector visual --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('shortcuts.icon') }}</label>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($icons as $ic)
                                <button type="button" wire:click="$set('icon', '{{ $ic }}')"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border"
                                        style="border-color: {{ $icon === $ic ? 'var(--brand)' : 'var(--line)' }}; background: {{ $icon === $ic ? 'var(--brand-tint)' : 'var(--paper)' }};">
                                    <x-icon :name="$ic" class="w-4 h-4 text-ink-70" />
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Color --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('shortcuts.color') }}</label>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($tints as $t)
                                <button type="button" wire:click="$set('tint', '{{ $t }}')"
                                        class="h-8 w-8 rounded-lg border-2"
                                        style="background: {{ $tintColors[$t] }}; border-color: {{ $tint === $t ? 'var(--ink-900)' : 'transparent' }};"
                                        aria-label="{{ $t }}"></button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Destino --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('shortcuts.target') }}</label>
                        <div class="flex gap-2">
                            <select wire:model.live="target_type" class="ah-input w-auto">
                                <option value="route">{{ __('shortcuts.internal') }}</option>
                                <option value="url">{{ __('shortcuts.external') }}</option>
                            </select>
                            @if ($target_type === 'route')
                                <select wire:model="target" class="ah-input">
                                    <option value="">—</option>
                                    @foreach ($this->routes as $r)
                                        <option value="{{ $r }}">{{ __('nav.' . $r) }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="url" wire:model="target" class="ah-input" placeholder="https://...">
                            @endif
                        </div>
                        @error('target') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                    </div>

                    @if ($target_type === 'route')
                        <label class="flex items-center gap-2 text-sm text-ink-70">
                            <input type="checkbox" wire:model="requires_auth" class="rounded border" style="border-color: var(--line)">
                            {{ __('shortcuts.requires_auth') }}
                        </label>
                    @endif

                    <div class="flex justify-end gap-2 pt-1">
                        <x-button variant="secondary" type="button" wire:click="$set('showForm', false)">{{ __('admin.actions.cancel') }}</x-button>
                        <x-button variant="primary" type="submit">{{ __('admin.actions.save') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div x-data="{ show: false, msg: '' }" x-on:notify.window="msg = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
         x-show="show" x-cloak class="ah-card fixed bottom-5 left-1/2 z-50 -translate-x-1/2 px-4 py-2.5 shadow-lg">
        <p class="text-sm text-ink" x-text="msg"></p>
    </div>
</div>
