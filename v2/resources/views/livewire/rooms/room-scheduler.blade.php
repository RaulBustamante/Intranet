<div>
    <x-page-header :title="__('rooms.title')" :subtitle="__('rooms.subtitle')">
        <x-slot:actions>
            <div class="flex items-center gap-1">
                <button wire:click="prevDay" class="ah-btn ah-btn-ghost px-2" aria-label="anterior">
                    <x-icon name="chevron" class="w-4 h-4 rotate-180" />
                </button>
                <button wire:click="today" class="ah-btn ah-btn-secondary">{{ __('calendar.today') }}</button>
                <button wire:click="nextDay" class="ah-btn ah-btn-ghost px-2" aria-label="siguiente">
                    <x-icon name="chevron" class="w-4 h-4" />
                </button>
            </div>
        </x-slot:actions>
    </x-page-header>

    <x-guest-notice action="book" />

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-lg capitalize">
            {{ \Carbon\Carbon::parse($date)->locale(app()->getLocale())->isoFormat('dddd D [de] MMMM') }}
        </h2>
        @if ($this->locations->count() > 1)
            <select wire:model.live="locationId" class="ah-input w-auto">
                <option value="">{{ __('admin.filters.all_locations') }}</option>
                @foreach ($this->locations as $l)
                    <option value="{{ $l->id }}">{{ $l->code }}</option>
                @endforeach
            </select>
        @endif
    </div>

    {{-- ========================= REJILLA DE SALAS ========================= --}}
    @forelse ($this->rooms as $room)
        <x-card class="mb-3">
            <div class="mb-2 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $room->color }}"></span>
                    <span class="font-medium text-ink">{{ $room->name }}</span>
                    @if ($room->capacity)
                        <span class="text-xs text-ink-50">· {{ __('rooms.capacity', ['n' => $room->capacity]) }}</span>
                    @endif
                    @if ($room->has_video)
                        <x-icon name="badge" class="w-3.5 h-3.5 text-ink-50" title="video" />
                    @endif
                </div>
                <x-button variant="secondary" wire:click="openForm({{ $room->id }})">
                    {{ __('rooms.book') }}
                </x-button>
            </div>

            {{-- Franja horaria: barras ocupadas sobre la línea del día --}}
            <div class="ah-scroll-x">
                <div class="min-w-[36rem]">
                    <div class="relative h-9 rounded-md" style="background: var(--paper-3);">
                        @foreach ($this->busyBlocks($room->id) as $b)
                            <div class="absolute top-0 flex h-full items-center overflow-hidden rounded px-1.5 text-[0.6875rem] text-white"
                                 style="left: {{ $b['left'] }}%; width: {{ $b['width'] }}%; background: {{ $room->color }};"
                                 title="{{ $b['label'] }} · {{ $b['time'] }} · {{ $b['who'] }}">
                                <span class="truncate">{{ $b['label'] }}</span>
                            </div>
                        @endforeach
                        @if (count($this->busyBlocks($room->id)) === 0)
                            <div class="flex h-full items-center justify-center text-xs text-ink-50">
                                {{ __('rooms.free_all_day') }}
                            </div>
                        @endif
                    </div>
                    {{-- Eje de horas --}}
                    <div class="mt-1 flex justify-between text-[0.625rem] text-ink-50">
                        @foreach ($this->hours() as $h)
                            <span>{{ sprintf('%02d', $h) }}</span>
                        @endforeach
                        <span>19</span>
                    </div>
                </div>
            </div>
        </x-card>
    @empty
        <x-card><x-empty-state icon="door" :title="__('rooms.no_rooms')" :body="false" /></x-card>
    @endforelse

    {{-- =========================== MIS RESERVAS =========================== --}}
    @if ($this->myBookings->isNotEmpty())
        <div class="mb-2 mt-6 flex items-center gap-3">
            <p class="ah-eyebrow">{{ __('rooms.my_bookings') }}</p>
            <div class="h-px flex-1" style="background: var(--line);"></div>
        </div>
        <x-card :padded="false">
            @foreach ($this->myBookings as $b)
                <div class="flex items-center gap-3 px-4 py-3 @if (! $loop->last) border-b @endif" style="border-color: var(--line);">
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium text-ink">{{ $b->title }}</p>
                        <p class="text-xs text-ink-50">
                            {{ $b->room?->name }} ·
                            {{ $b->starts_at->locale(app()->getLocale())->isoFormat('ddd D MMM, HH:mm') }}–{{ $b->ends_at->format('H:i') }}
                        </p>
                    </div>
                    <x-button variant="ghost" wire:click="cancelBooking({{ $b->id }})"
                              wire:confirm="{{ __('rooms.cancel') }}?">
                        {{ __('rooms.cancel') }}
                    </x-button>
                </div>
            @endforeach
        </x-card>
    @endif

    {{-- =========================== MODAL RESERVA ========================== --}}
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-start justify-center px-4 py-[8vh]" role="dialog" aria-modal="true">
            <div class="absolute inset-0" wire:click="$set('showForm', false)"
                 style="background: color-mix(in srgb, var(--ink-900) 40%, transparent);"></div>
            <div class="ah-card relative w-full max-w-md p-5 shadow-2xl">
                <h2 class="mb-4 text-lg">
                    {{ __('rooms.book_in', ['room' => $this->rooms->firstWhere('id', $roomId)?->name]) }}
                </h2>

                <form wire:submit="book" class="space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('rooms.meeting_title') }}</label>
                        <input type="text" wire:model="title" class="ah-input" autofocus>
                        @error('title') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('rooms.from') }}</label>
                            <input type="time" wire:model="startTime" step="900" class="ah-input">
                        </div>
                        <span class="pb-2 text-ink-50">{{ __('rooms.to') }}</span>
                        <div class="flex-1">
                            <label class="mb-1 block text-xs font-medium text-ink-70">&nbsp;</label>
                            <input type="time" wire:model="endTime" step="900" class="ah-input">
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('rooms.attendees') }}</label>
                        <input type="number" min="1" wire:model="attendees" class="ah-input w-28">
                    </div>

                    @if ($bookingError)
                        <p class="rounded-md px-3 py-2 text-sm" style="background: color-mix(in srgb, var(--danger) 10%, transparent); color: var(--danger)">
                            {{ $bookingError }}
                        </p>
                    @endif

                    <div class="flex justify-end gap-2 pt-1">
                        <x-button variant="secondary" type="button" wire:click="$set('showForm', false)">
                            {{ __('admin.actions.cancel') }}
                        </x-button>
                        <x-button variant="primary" type="submit">{{ __('rooms.book') }}</x-button>
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
