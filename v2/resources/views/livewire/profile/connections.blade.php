<div>
    <x-page-header :title="__('connections.title')" :subtitle="__('connections.subtitle')" />

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-3">
            @foreach ($this->providers as $p)
                <x-card>
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg"
                             style="background: var(--paper-3); color: var(--ink-70);">
                            <x-icon name="{{ $p['key'] === 'microsoft' ? 'badge' : 'globe' }}" class="w-5 h-5" />
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="font-medium text-ink">{{ $p['label'] }}</p>
                            @if ($p['linked'])
                                <p class="text-xs" style="color: var(--ok)">
                                    {{ __('connections.linked_as', ['email' => $p['linked']->email ?? '—']) }}
                                </p>
                            @elseif ($p['enabled'])
                                <p class="text-xs text-ink-50">{{ __('connections.sync_calendar') }}</p>
                            @else
                                <p class="text-xs text-ink-50">{{ __('connections.not_configured') }}</p>
                            @endif
                        </div>

                        <div class="shrink-0">
                            @if ($p['linked'])
                                <x-button variant="secondary" wire:click="unlink('{{ $p['key'] }}')"
                                          wire:confirm="{{ __('connections.unlink') }}?">
                                    {{ __('connections.unlink') }}
                                </x-button>
                            @elseif ($p['enabled'])
                                <x-button variant="primary" href="{{ url('/conexiones/' . $p['key'] . '/redirect') }}">
                                    {{ __('connections.link') }}
                                </x-button>
                            @else
                                <span class="ah-badge">{{ __('connections.not_configured') ? 'TI' : '' }}</span>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endforeach

            <p class="px-1 text-xs text-ink-50">{{ __('connections.optional') }}</p>
        </div>

        <x-card :eyebrow="__('connections.why')">
            <ul class="space-y-2.5 text-sm text-ink-70">
                @foreach (['why_1', 'why_2', 'why_3'] as $w)
                    <li class="flex gap-2">
                        <x-icon name="badge" class="mt-0.5 w-4 h-4 shrink-0 text-brand" />
                        {{ __('connections.' . $w) }}
                    </li>
                @endforeach
            </ul>
        </x-card>
    </div>

    <div x-data="{ show: false, msg: '' }"
         x-on:notify.window="msg = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
         x-show="show" x-cloak
         class="ah-card fixed bottom-5 left-1/2 z-50 -translate-x-1/2 px-4 py-2.5 shadow-lg">
        <p class="text-sm text-ink" x-text="msg"></p>
    </div>
</div>
