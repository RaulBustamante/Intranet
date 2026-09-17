<div>
    <x-page-header :title="__('nav.profile')" :subtitle="__('profile.subtitle')" />

    @php $e = auth()->user()->employee; @endphp

    <div class="grid gap-4 lg:grid-cols-3">
        {{-- Datos que edita el empleado --}}
        <x-card class="lg:col-span-2">
            <form wire:submit="save" class="space-y-4">
                <div class="flex items-center gap-4">
                    <x-avatar :name="$e?->full_name ?? 'Ariel'" :src="$photo ? $photo->temporaryUrl() : $e?->photo_path" size="lg" />
                    <label class="ah-btn ah-btn-secondary cursor-pointer">
                        <x-icon name="image" class="w-4 h-4" />
                        {{ __('admin.fields.photo') }}
                        <input type="file" wire:model="photo" accept="image/*" class="hidden">
                    </label>
                    <div wire:loading wire:target="photo" class="text-xs text-ink-50">{{ __('app.states.loading') }}</div>
                </div>
                @error('photo') <p class="text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('admin.fields.preferred_name') }}</label>
                        <input type="text" wire:model="preferred_name" class="ah-input">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('admin.fields.extension') }}</label>
                        <input type="text" wire:model="extension" class="ah-input">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('admin.fields.phone') }}</label>
                        <input type="text" wire:model="phone" class="ah-input">
                    </div>
                </div>

                <div class="flex justify-end">
                    <x-button variant="primary" type="submit">{{ __('admin.actions.save') }}</x-button>
                </div>
            </form>
        </x-card>

        {{-- Datos que solo RH cambia (solo lectura) --}}
        <x-card :eyebrow="__('profile.managed_by_hr')">
            <dl class="space-y-2 text-sm">
                <div><dt class="text-ink-50">{{ __('admin.fields.email') }}</dt><dd class="text-ink">{{ $e?->email ?? '—' }}</dd></div>
                <div><dt class="text-ink-50">{{ __('admin.fields.department') }}</dt><dd class="text-ink">{{ $e?->department?->name ?? '—' }}</dd></div>
                <div><dt class="text-ink-50">{{ __('admin.fields.location') }}</dt><dd class="text-ink">{{ $e?->location?->name ?? '—' }}</dd></div>
                <div><dt class="text-ink-50">{{ __('admin.fields.birthday') }}</dt><dd class="text-ink">{{ $e?->birthday_label ?? '—' }}</dd></div>
            </dl>
            <p class="mt-3 text-xs text-ink-50">{{ __('profile.hr_note') }}</p>
        </x-card>
    </div>

    <div x-data="{ show: false, msg: '' }" x-on:notify.window="msg = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
         x-show="show" x-cloak class="ah-card fixed bottom-5 left-1/2 z-50 -translate-x-1/2 px-4 py-2.5 shadow-lg">
        <p class="text-sm text-ink" x-text="msg"></p>
    </div>
</div>
