<div>
    <x-page-header
        :title="__('nav.directory')"
        :subtitle="__('app.directory.subtitle', ['count' => $employees->total(), 'locations' => $this->locations->count()])">
        <x-slot:actions>
            {{-- Alternar vista. La preferencia vive en la URL, así que
                 sobrevive a recargar y se puede compartir (PPL-05). --}}
            <div class="flex rounded-lg border p-0.5" style="border-color: var(--line);">
                @foreach ([['cards', 'image'], ['table', 'list']] as [$modo, $icono])
                    <button type="button" wire:click="setView('{{ $modo }}')"
                            class="ah-btn ah-btn-ghost px-2.5 py-1"
                            @if ($view === $modo) style="background: var(--paper-3); color: var(--ink-900)" @endif
                            aria-pressed="{{ $view === $modo ? 'true' : 'false' }}">
                        <x-icon :name="$icono" class="w-4 h-4" />
                        <span class="hidden sm:inline">{{ __('app.directory.view_' . $modo) }}</span>
                    </button>
                @endforeach
            </div>

            {{-- Exportar el directorio filtrado a CSV (PPL-09) --}}
            <button type="button" wire:click="export" class="ah-btn ah-btn-ghost"
                    title="{{ __('app.directory.export') }}">
                <x-icon name="file" class="w-4 h-4" />
                <span class="hidden sm:inline">{{ __('app.directory.export') }}</span>
            </button>
        </x-slot:actions>
    </x-page-header>

    {{-- ============================= FILTROS ============================= --}}
    <div class="mb-4 flex flex-wrap items-center gap-2">
        <div class="relative min-w-[16rem] flex-1">
            <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 w-4 h-4 -translate-y-1/2 text-ink-50" />
            <input type="search" wire:model.live.debounce.300ms="search"
                   class="ah-input pl-9" placeholder="{{ __('app.directory.search') }}"
                   aria-label="{{ __('app.directory.search') }}">
            <div wire:loading wire:target="search"
                 class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-ink-50">
                {{ __('app.states.loading') }}
            </div>
        </div>

        <select wire:model.live="locationId" class="ah-input w-auto" aria-label="{{ __('admin.fields.location') }}">
            <option value="">{{ __('app.directory.all_locations') }}</option>
            @foreach ($this->locations as $l)
                <option value="{{ $l->id }}">{{ $l->code }}</option>
            @endforeach
        </select>

        <select wire:model.live="departmentId" class="ah-input w-auto" aria-label="{{ __('admin.fields.department') }}">
            <option value="">{{ __('app.directory.all_departments') }}</option>
            @foreach ($this->departments as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
            @endforeach
        </select>

        @if ($search !== '' || $locationId !== '' || $departmentId !== '')
            <x-button variant="ghost" wire:click="clearFilters">{{ __('app.directory.clear') }}</x-button>
        @endif
    </div>

    @if ($employees->total() === 0)
        <x-card>
            <x-empty-state icon="users"
                :title="$search !== '' ? __('app.states.no_results', ['term' => $search]) : __('app.states.empty_title')"
                :body="__('app.states.no_results_hint')">
                <x-button variant="secondary" wire:click="clearFilters">{{ __('app.directory.clear') }}</x-button>
            </x-empty-state>
        </x-card>

    {{-- ============================= TARJETAS ============================ --}}
    @elseif ($view === 'cards')
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($employees as $e)
                <a href="{{ route('directory.show', $e) }}" wire:key="p-{{ $e->id }}"
                   class="ah-card ah-card-hover flex items-start gap-3 p-4">
                    <x-avatar :name="$e->full_name" :src="$e->photo_path" size="md" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium text-ink">{{ $e->display_name }}</p>
                        <p class="truncate text-xs text-ink-50">
                            {{ $e->department?->name ?? '—' }}
                            @if ($e->location) <span class="mx-1">·</span>{{ $e->location->code }} @endif
                        </p>
                        <div class="mt-2 space-y-0.5 text-xs text-ink-70">
                            @if ($e->extension)
                                <p class="truncate">ext {{ $e->extension }}</p>
                            @endif
                            @if ($e->email)
                                <p class="truncate">{{ $e->email }}</p>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    {{-- ============================== TABLA ============================== --}}
    @else
        <x-card :padded="false">
            <div class="ah-scroll-x">
                <table class="ah-table">
                    <thead>
                        <tr>
                            <th>{{ __('admin.fields.first_name') }}</th>
                            <th>{{ __('admin.fields.department') }}</th>
                            <th>{{ __('admin.fields.location') }}</th>
                            <th>{{ __('admin.fields.extension') }}</th>
                            <th>{{ __('admin.fields.phone') }}</th>
                            <th>{{ __('admin.fields.email') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $e)
                            <tr wire:key="r-{{ $e->id }}">
                                <td>
                                    <a href="{{ route('directory.show', $e) }}" class="flex items-center gap-2.5">
                                        <x-avatar :name="$e->full_name" :src="$e->photo_path" size="sm" />
                                        <span class="font-medium text-ink">{{ $e->display_name }}</span>
                                    </a>
                                </td>
                                <td class="text-ink-70">{{ $e->department?->name ?? '—' }}</td>
                                <td class="text-ink-70">{{ $e->location?->code ?? '—' }}</td>
                                <td class="tabular-nums text-ink-70">{{ $e->extension ?: '—' }}</td>
                                <td class="tabular-nums text-ink-70">{{ $e->phone ?: '—' }}</td>
                                <td>
                                    @if ($e->email)
                                        <a href="mailto:{{ $e->email }}" class="ah-link">{{ $e->email }}</a>
                                    @else
                                        <span class="text-ink-50">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    @endif

    @if ($employees->hasPages())
        <div class="mt-4">{{ $employees->links() }}</div>
    @endif
</div>
