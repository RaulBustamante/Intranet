<div>
    <x-page-header
        :title="__('admin.people.title')"
        :subtitle="__('admin.people.subtitle', ['count' => $employees->total(), 'locations' => $this->locations->count()])">
        <x-slot:actions>
            <x-button variant="secondary" icon="inbox" href="{{ route('admin.import') }}">{{ __('csv.title') }}</x-button>
            <x-button variant="primary" icon="users" wire:click="create">
                {{ __('admin.people.new') }}
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Avisos de calidad de datos: convierten la importación sucia en una
         lista de pendientes accionable en vez de un problema invisible --}}
    @php $w = $this->dataWarnings; @endphp
    @if (array_sum($w) > 0)
        <div class="ah-card mb-4 p-3.5" style="background: var(--brand-tint); border-color: transparent;">
            <p class="mb-1 text-sm font-medium" style="color: var(--brand-text);">
                {{ __('admin.warnings.title') }}
            </p>
            <ul class="text-sm text-ink-70">
                @if ($w['sin_cumpleanos']) <li>· {{ __('admin.warnings.sin_cumpleanos', ['count' => $w['sin_cumpleanos']]) }}</li> @endif
                @if ($w['sin_departamento']) <li>· {{ __('admin.warnings.sin_departamento', ['count' => $w['sin_departamento']]) }}</li> @endif
                @if ($w['sin_sede']) <li>· {{ __('admin.warnings.sin_sede', ['count' => $w['sin_sede']]) }}</li> @endif
            </ul>
            <p class="mt-1.5 text-xs text-ink-50">{{ __('admin.warnings.hint') }}</p>
        </div>
    @endif

    {{-- ============================= FILTROS ============================= --}}
    <div class="mb-4 flex flex-wrap items-center gap-2">
        <div class="relative min-w-[15rem] flex-1">
            <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 w-4 h-4 -translate-y-1/2 text-ink-50" />
            <input type="search" wire:model.live.debounce.300ms="search"
                   class="ah-input pl-9" placeholder="{{ __('admin.people.search') }}">
        </div>

        <select wire:model.live="locationId" class="ah-input w-auto">
            <option value="">{{ __('admin.filters.all_locations') }}</option>
            @foreach ($this->locations as $l)
                <option value="{{ $l->id }}">{{ $l->code }}</option>
            @endforeach
        </select>

        <select wire:model.live="departmentId" class="ah-input w-auto">
            <option value="">{{ __('admin.filters.all_departments') }}</option>
            @foreach ($this->departments as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="status" class="ah-input w-auto">
            <option value="active">{{ __('admin.filters.active') }}</option>
            <option value="inactive">{{ __('admin.filters.inactive') }}</option>
            <option value="all">{{ __('admin.filters.all') }}</option>
        </select>
    </div>

    {{-- ============================== TABLA ============================== --}}
    <x-card :padded="false">
        <div class="ah-scroll-x">
            <table class="ah-table">
                <thead>
                    <tr>
                        <th>{{ __('admin.fields.first_name') }}</th>
                        <th>{{ __('admin.fields.department') }}</th>
                        <th>{{ __('admin.fields.location') }}</th>
                        <th>{{ __('admin.fields.extension') }}</th>
                        <th>{{ __('admin.fields.birthday') }}</th>
                        <th>{{ __('admin.fields.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $e)
                        <tr wire:key="emp-{{ $e->id }}">
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <x-avatar :name="$e->full_name" :src="$e->photo_path" size="sm" />
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-ink">{{ $e->full_name }}</p>
                                        <p class="truncate text-xs text-ink-50">{{ $e->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-ink-70">{{ $e->department?->name ?? '—' }}</td>
                            <td class="text-ink-70">{{ $e->location?->code ?? '—' }}</td>
                            <td class="tabular-nums text-ink-70">{{ $e->extension ?: '—' }}</td>
                            <td class="text-ink-70">
                                @if ($e->birthday_label)
                                    {{ $e->birthday_label }}
                                @else
                                    <span class="ah-badge ah-badge-warn">{{ __('admin.warnings.title') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="ah-badge {{ $e->trashed() ? '' : 'ah-badge-ok' }}">
                                    {{ $e->trashed() ? __('admin.status.inactive') : __('admin.status.active') }}
                                </span>
                            </td>
                            <td class="text-right whitespace-nowrap">
                                @if ($e->trashed())
                                    <x-button variant="ghost" wire:click="restore({{ $e->id }})">
                                        {{ __('admin.actions.restore') }}
                                    </x-button>
                                @else
                                    <x-button variant="ghost" wire:click="edit({{ $e->id }})">
                                        {{ __('admin.actions.edit') }}
                                    </x-button>
                                    <x-button variant="ghost"
                                              wire:click="deactivate({{ $e->id }})"
                                              wire:confirm="{{ __('admin.people.confirm_off') }}">
                                        {{ __('admin.actions.deactivate') }}
                                    </x-button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state icon="users"
                                    :title="__('admin.people.none_title')"
                                    :body="__('admin.people.none_body')" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    @if ($employees->hasPages())
        <div class="mt-4">{{ $employees->links() }}</div>
    @endif

    {{-- ============================ FORMULARIO =========================== --}}
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto px-4 py-[6vh]"
             role="dialog" aria-modal="true">
            <div class="absolute inset-0" wire:click="cancel"
                 style="background: color-mix(in srgb, var(--ink-900) 40%, transparent);"></div>

            <div class="ah-card relative w-full max-w-2xl p-5 shadow-2xl">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg">
                        {{ $editingId ? __('admin.people.edit') : __('admin.people.new') }}
                    </h2>
                    <button type="button" wire:click="cancel" class="ah-btn ah-btn-ghost px-1.5 py-1"
                            aria-label="{{ __('admin.actions.cancel') }}">
                        <x-icon name="x" class="w-4 h-4" />
                    </button>
                </div>

                <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">

                    {{-- Foto del empleado (HRADM-03) --}}
                    <div class="sm:col-span-2 flex items-center gap-4">
                        <x-avatar
                            :name="trim($first_name . ' ' . $last_name) ?: 'Ariel'"
                            :src="$photo ? $photo->temporaryUrl() : (($editingId && ($e = \App\Domain\People\Models\Employee::find($editingId))) ? $e->photo_path : null)"
                            size="lg" />
                        <div>
                            <label class="ah-btn ah-btn-secondary cursor-pointer">
                                <x-icon name="image" class="w-4 h-4" />
                                {{ __('admin.fields.photo') }}
                                <input type="file" wire:model="photo" accept="image/*" class="hidden">
                            </label>
                            <div wire:loading wire:target="photo" class="mt-1 text-xs text-ink-50">{{ __('app.states.loading') }}</div>
                            @error('photo') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    @foreach ([['first_name', true], ['last_name', true], ['preferred_name', false], ['email', true]] as [$campo, $obligatorio])
                        <div>
                            <label for="{{ $campo }}" class="mb-1 block text-xs font-medium text-ink-70">
                                {{ __('admin.fields.' . $campo) }}
                                @if ($obligatorio) <span style="color: var(--brand-text)">*</span> @endif
                            </label>
                            <input id="{{ $campo }}" type="{{ $campo === 'email' ? 'email' : 'text' }}"
                                   wire:model.blur="{{ $campo }}" class="ah-input"
                                   @error($campo) style="border-color: var(--danger)" @enderror>
                            @error($campo)
                                <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach

                    <div>
                        <label for="location_id" class="mb-1 block text-xs font-medium text-ink-70">{{ __('admin.fields.location') }}</label>
                        <select id="location_id" wire:model.blur="location_id" class="ah-input">
                            <option value="">—</option>
                            @foreach ($this->locations as $l)
                                <option value="{{ $l->id }}">{{ $l->code }} — {{ $l->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="department_id" class="mb-1 block text-xs font-medium text-ink-70">{{ __('admin.fields.department') }}</label>
                        <select id="department_id" wire:model.blur="department_id" class="ah-input">
                            <option value="">—</option>
                            @foreach ($this->departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="extension" class="mb-1 block text-xs font-medium text-ink-70">{{ __('admin.fields.extension') }}</label>
                        <input id="extension" type="text" wire:model.blur="extension" class="ah-input">
                    </div>

                    <div>
                        <label for="phone" class="mb-1 block text-xs font-medium text-ink-70">{{ __('admin.fields.phone') }}</label>
                        <input id="phone" type="text" wire:model.blur="phone" class="ah-input">
                    </div>

                    <div>
                        <label for="job_title_es" class="mb-1 block text-xs font-medium text-ink-70">{{ __('admin.fields.job_title_es') }}</label>
                        <input id="job_title_es" type="text" wire:model.blur="job_title_es" class="ah-input">
                    </div>

                    <div>
                        <label for="job_title_en" class="mb-1 block text-xs font-medium text-ink-70">{{ __('admin.fields.job_title_en') }}</label>
                        <input id="job_title_en" type="text" wire:model.blur="job_title_en" class="ah-input">
                    </div>

                    {{-- Cumpleaños SIN año (PPL-10). El aviso está a la vista
                         para que nadie se pregunte por qué falta el campo. --}}
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-xs font-medium text-ink-70">{{ __('admin.fields.birthday') }}</label>
                        <div class="flex gap-2">
                            <select wire:model.blur="birth_month" class="ah-input w-auto" aria-label="{{ __('admin.fields.birth_month') }}">
                                <option value="">{{ __('admin.fields.birth_month') }}</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}">
                                        {{ ucfirst(\Carbon\Carbon::create(2000, $m, 1)->locale(app()->getLocale())->isoFormat('MMMM')) }}
                                    </option>
                                @endforeach
                            </select>
                            <select wire:model.blur="birth_day" class="ah-input w-auto" aria-label="{{ __('admin.fields.birth_day') }}">
                                <option value="">{{ __('admin.fields.birth_day') }}</option>
                                @foreach (range(1, 31) as $d)
                                    <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach
                            </select>
                            <p class="self-center text-xs text-ink-50">{{ __('admin.hints.no_year') }}</p>
                        </div>
                        @error('birth_month') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                        @error('birth_day') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-1 flex justify-end gap-2 sm:col-span-2">
                        <x-button variant="secondary" type="button" wire:click="cancel">
                            {{ __('admin.actions.cancel') }}
                        </x-button>
                        <x-button variant="primary" type="submit">
                            {{ __('admin.actions.save') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Aviso de resultado --}}
    <div x-data="{ show: false, msg: '' }"
         x-on:notify.window="msg = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
         x-show="show" x-cloak
         class="ah-card fixed bottom-5 left-1/2 z-50 -translate-x-1/2 px-4 py-2.5 shadow-lg">
        <p class="text-sm text-ink" x-text="msg"></p>
    </div>
</div>
