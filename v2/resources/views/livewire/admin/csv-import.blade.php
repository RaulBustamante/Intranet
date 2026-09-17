<div>
    <x-page-header :title="__('csv.title')" :subtitle="__('csv.subtitle')">
        <x-slot:actions>
            <x-button variant="secondary" href="{{ route('admin.people') }}" icon="users">{{ __('nav.admin_people') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    @if (! $analyzed)
        {{-- Paso 1: subir --}}
        <x-card>
            <div class="mb-3 text-sm text-ink-70">
                {{ __('csv.help') }}
                <code class="rounded px-1.5 py-0.5 text-xs" style="background: var(--paper-3);">nombre, apellido, correo, extension, telefono, departamento, sede, puesto, mes_cumple, dia_cumple</code>
            </div>
            <form wire:submit="analyze" class="flex flex-wrap items-center gap-3">
                <input type="file" wire:model="file" accept=".csv,text/csv" class="ah-input flex-1">
                <x-button variant="primary" type="submit">{{ __('csv.analyze') }}</x-button>
            </form>
            <div wire:loading wire:target="file,analyze" class="mt-2 text-xs text-ink-50">{{ __('app.states.loading') }}</div>
            @error('file') <p class="mt-2 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
        </x-card>
    @else
        {{-- Paso 2: vista previa antes de aplicar --}}
        <div class="mb-4 flex flex-wrap items-center gap-3">
            <span class="ah-badge ah-badge-ok">{{ __('csv.will_create', ['n' => $summary['alta']]) }}</span>
            <span class="ah-badge ah-badge-brand">{{ __('csv.will_update', ['n' => $summary['update']]) }}</span>
            @if ($summary['error'] > 0)
                <span class="ah-badge ah-badge-danger">{{ __('csv.will_skip', ['n' => $summary['error']]) }}</span>
            @endif
            <div class="ml-auto flex gap-2">
                <x-button variant="secondary" wire:click="cancel">{{ __('admin.actions.cancel') }}</x-button>
                <x-button variant="primary" wire:click="apply"
                          wire:confirm="{{ __('csv.confirm', ['n' => $summary['alta'] + $summary['update']]) }}">
                    {{ __('csv.apply') }}
                </x-button>
            </div>
        </div>

        <x-card :padded="false">
            <div class="ah-scroll-x">
                <table class="ah-table">
                    <thead>
                        <tr>
                            <th>{{ __('csv.row') }}</th>
                            <th>{{ __('admin.fields.first_name') }}</th>
                            <th>{{ __('admin.fields.email') }}</th>
                            <th>{{ __('csv.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($preview as $p)
                            <tr>
                                <td class="tabular-nums text-ink-50">{{ $p['fila'] }}</td>
                                <td class="text-ink">{{ $p['nombre'] }}</td>
                                <td class="text-ink-70">{{ $p['email'] }}</td>
                                <td>
                                    @if ($p['accion'] === 'alta')
                                        <span class="ah-badge ah-badge-ok">{{ __('csv.new') }}</span>
                                    @elseif ($p['accion'] === 'update')
                                        <span class="ah-badge ah-badge-brand">{{ __('csv.update') }}</span>
                                    @else
                                        <span class="ah-badge ah-badge-danger">{{ __('csv.skip') }}</span>
                                        <span class="ml-1 text-xs text-ink-50">{{ $p['motivo'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    @endif

    <div x-data="{ show: false, msg: '' }" x-on:notify.window="msg = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
         x-show="show" x-cloak class="ah-card fixed bottom-5 left-1/2 z-50 -translate-x-1/2 px-4 py-2.5 shadow-lg">
        <p class="text-sm text-ink" x-text="msg"></p>
    </div>
</div>
