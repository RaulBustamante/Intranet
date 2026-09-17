<div>
    <x-page-header :title="__('calendar.title')" :subtitle="__('calendar.subtitle')">
        <x-slot:actions>
            <div class="flex items-center gap-1">
                <button wire:click="prev" class="ah-btn ah-btn-ghost px-2" aria-label="anterior">
                    <x-icon name="chevron" class="w-4 h-4 rotate-180" />
                </button>
                <button wire:click="today" class="ah-btn ah-btn-secondary">{{ __('calendar.today') }}</button>
                <button wire:click="next" class="ah-btn ah-btn-ghost px-2" aria-label="siguiente">
                    <x-icon name="chevron" class="w-4 h-4" />
                </button>
            </div>
        </x-slot:actions>
    </x-page-header>

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-lg capitalize">{{ $this->monthLabel }}</h2>

        <div class="flex items-center gap-2">
            {{-- Suscribir en Outlook/Google (CAL-07). El feed es por usuario, así
                 que solo se ofrece con sesión iniciada. --}}
            @auth
            <a href="{{ auth()->user()->calendarFeedUrl() }}"
               class="ah-btn ah-btn-ghost" title="{{ __('calendar.subscribe') }}">
                <x-icon name="link" class="w-4 h-4" />
                <span class="hidden sm:inline">iCal</span>
            </a>
            @endauth
            <select wire:model.live="country" class="ah-input w-auto" aria-label="{{ __('calendar.country') }}">
                <option value="BOTH">{{ __('calendar.all') }}</option>
                <option value="MX">🇲🇽 MX</option>
                <option value="US">🇺🇸 US</option>
            </select>
            <div class="flex rounded-lg border p-0.5" style="border-color: var(--line);">
                @foreach ([['month', 'calendar'], ['list', 'list']] as [$modo, $icono])
                    <button wire:click="$set('view', '{{ $modo }}')" class="ah-btn ah-btn-ghost px-2.5 py-1"
                            @if ($view === $modo) style="background: var(--paper-3); color: var(--ink-900)" @endif>
                        <x-icon :name="$icono" class="w-4 h-4" />
                        <span class="hidden sm:inline">{{ __('calendar.' . $modo) }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    @php
        $colores = [
            'holiday'     => 'var(--brand-text)',
            'birthday'    => 'var(--warn)',
            'anniversary' => 'var(--ok)',
            'event'       => 'var(--ink-500)',
        ];
    @endphp

    @if ($view === 'month')
        {{-- ============================ REJILLA ============================ --}}
        <x-card :padded="false" class="overflow-hidden">
            <div class="ah-scroll-x">
                <div class="min-w-[42rem]">
                    <div class="grid grid-cols-7 border-b" style="border-color: var(--line);">
                        @foreach (__('calendar.days') as $d)
                            <div class="px-2 py-2 text-center text-xs font-medium text-ink-50">{{ $d }}</div>
                        @endforeach
                    </div>

                    @foreach ($this->weeks as $week)
                        <div class="grid grid-cols-7">
                            @foreach ($week as $cell)
                                <div class="min-h-[5.5rem] border-b border-r p-1.5 @if (! $cell['in_month']) opacity-40 @endif"
                                     style="border-color: var(--line);">
                                    <div class="mb-1 flex justify-end">
                                        <span class="flex h-5 w-5 items-center justify-center rounded-full text-xs
                                                     @if ($cell['is_today']) font-semibold text-white @else text-ink-50 @endif"
                                              @if ($cell['is_today']) style="background: var(--brand)" @endif>
                                            {{ $cell['day'] }}
                                        </span>
                                    </div>
                                    @foreach (($this->items[$cell['date']] ?? collect()) as $it)
                                        <div class="mb-0.5 truncate rounded px-1 py-0.5 text-[0.6875rem]"
                                             style="background: color-mix(in srgb, {{ $colores[$it['type']] }} 12%, transparent); color: {{ $colores[$it['type']] }}"
                                             title="{{ $it['title'] }}">
                                            {{ $it['title'] }}
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </x-card>

    @else
        {{-- ============================== LISTA ============================ --}}
        <x-card :padded="false">
            @php $todos = $this->items->flatten(1)->sortBy('date'); @endphp
            @forelse ($todos as $it)
                <div class="flex items-center gap-3 px-4 py-3 @if (! $loop->last) border-b @endif" style="border-color: var(--line);">
                    <div class="w-20 shrink-0 text-xs tabular-nums text-ink-50">
                        {{ \Carbon\Carbon::parse($it['date'])->locale(app()->getLocale())->isoFormat('ddd D') }}
                    </div>
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full" style="background: {{ $colores[$it['type']] }}"></span>
                    <span class="min-w-0 flex-1 truncate text-ink">{{ $it['title'] }}</span>
                    <span class="ah-badge">{{ __('calendar.types.' . $it['type']) }}</span>
                </div>
            @empty
                <x-empty-state icon="calendar" :title="__('calendar.no_events')" :body="false" />
            @endforelse
        </x-card>
    @endif
</div>
