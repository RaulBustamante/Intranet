<div>
    <x-page-header :title="__('kudos.title')" :subtitle="__('kudos.subtitle')" />

    <x-guest-notice action="kudo" />

    <div class="grid gap-4 lg:grid-cols-3">
        <x-card class="lg:col-span-1 h-fit">
            <form wire:submit="send" class="space-y-3">
                <div>
                    <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('kudos.to') }}</label>
                    <select wire:model="toEmployeeId" class="ah-input">
                        <option value="">—</option>
                        @foreach ($this->colleagues as $c)
                            <option value="{{ $c->id }}">{{ $c->display_name }}</option>
                        @endforeach
                    </select>
                    @error('toEmployeeId') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('kudos.message') }}</label>
                    <textarea wire:model="message" rows="3" class="ah-input" placeholder="{{ __('kudos.placeholder') }}"></textarea>
                    @error('message') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                </div>
                <x-button variant="primary" type="submit" class="w-full">{{ __('kudos.send') }}</x-button>
            </form>
        </x-card>

        <div class="lg:col-span-2 space-y-3">
            @forelse ($this->wall as $k)
                <x-card>
                    <div class="flex items-start gap-3">
                        <x-avatar :name="$k->from?->full_name" size="sm" />
                        <div class="min-w-0 flex-1">
                            <p class="text-sm text-ink">
                                <span class="font-medium">{{ $k->from?->display_name }}</span>
                                → <span class="font-medium">{{ $k->to?->display_name }}</span>
                            </p>
                            <p class="mt-1 text-sm text-ink-70">{{ $k->message }}</p>
                            <p class="mt-1 text-xs text-ink-50">{{ $k->created_at->locale(app()->getLocale())->diffForHumans() }}</p>
                        </div>
                        <x-icon name="badge" class="w-4 h-4 shrink-0 text-brand" />
                    </div>
                </x-card>
            @empty
                <x-card><x-empty-state icon="badge" :title="__('kudos.empty')" :body="false" /></x-card>
            @endforelse
        </div>
    </div>
    <div x-data="{ show: false, msg: '' }" x-on:notify.window="msg = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
         x-show="show" x-cloak class="ah-card fixed bottom-5 left-1/2 z-50 -translate-x-1/2 px-4 py-2.5 shadow-lg">
        <p class="text-sm text-ink" x-text="msg"></p>
    </div>
</div>
