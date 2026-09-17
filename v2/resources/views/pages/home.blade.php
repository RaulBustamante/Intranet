<x-layouts.app>

    {{-- Saludo personalizado (COM-11): la v1 mostraba lo mismo a las 200 personas --}}
    <div class="mb-7">
        <h1 class="text-[1.75rem]">{{ __('app.greeting.' . $saludo, ['name' => $nombre]) }}</h1>
        <p class="text-ink-50 mt-1">
            {{ ucfirst(now()->locale(app()->getLocale())->isoFormat('dddd D [de] MMMM')) }}
            <span class="mx-1.5">·</span>{{ $sede }}
        </p>
    </div>

    <div class="mb-3 flex items-center gap-3">
        <p class="ah-eyebrow">{{ __('app.home.today') }}</p>
        <div class="h-px flex-1" style="background: var(--line);"></div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">

        {{-- Cumpleaños: se derivan de employees, sin lista aparte que mantener (CAL-03) --}}
        <x-card>
            <div class="mb-3 flex items-center gap-2">
                <x-icon name="cake" class="w-4 h-4 text-brand" />
                <h2 class="text-sm font-medium text-ink">{{ __('app.home.birthdays_today') }}</h2>
                <span class="ah-badge ah-badge-brand ml-auto">{{ count($cumpleanos) }}</span>
            </div>

            @forelse ($cumpleanos as $p)
                <div class="flex items-center gap-2.5 py-1.5">
                    <x-avatar :name="$p['nombre']" size="sm" />
                    <div class="min-w-0">
                        <p class="truncate text-ink">{{ $p['nombre'] }}</p>
                        <p class="truncate text-xs text-ink-50">{{ $p['depto'] }}</p>
                    </div>
                </div>
            @empty
                <p class="py-4 text-center text-ink-50">{{ __('app.home.no_birthdays') }}</p>
            @endforelse

            @if (count($cumpleanos))
                <a href="{{ route('calendar') }}" class="ah-link mt-3 inline-flex items-center gap-1 text-sm">
                    {{ __('app.home.congratulate') }}
                    <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                </a>
            @endif
        </x-card>

        {{-- Mi día: reservas + solicitudes del empleado (Fases 3 y 5) --}}
        <x-card>
            <div class="mb-3 flex items-center gap-2">
                <x-icon name="clock" class="w-4 h-4 text-ink-50" />
                <h2 class="text-sm font-medium text-ink">{{ __('app.home.my_day') }}</h2>
            </div>

            @forelse ($miDia as $i)
                <div class="flex items-center gap-2.5 py-1.5">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full"
                          style="background: {{ $i['estado'] === 'warn' ? 'var(--warn)' : 'var(--ok)' }}"></span>
                    @if ($i['hora'])
                        <span class="shrink-0 text-xs tabular-nums text-ink-50">{{ $i['hora'] }}</span>
                    @endif
                    <span class="truncate text-ink">{{ $i['texto'] }}</span>
                </div>
            @empty
                <p class="py-4 text-center text-ink-50">{{ __('app.home.nothing_today') }}</p>
            @endforelse

            <a href="{{ route('requests') }}" class="ah-link mt-3 inline-flex items-center gap-1 text-sm">
                {{ __('app.home.see_all') }}
                <x-icon name="arrow-right" class="w-3.5 h-3.5" />
            </a>
        </x-card>
    </div>

    {{-- Último publicado --}}
    <div class="mb-3 mt-7 flex items-center gap-3">
        <p class="ah-eyebrow">{{ __('app.home.latest') }}</p>
        <div class="h-px flex-1" style="background: var(--line);"></div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <x-card class="sm:col-span-2">
            @foreach ($anuncios as $a)
                <a href="#" class="-mx-2 flex items-start gap-2.5 rounded-md px-2 py-2.5 hover:bg-paper-2">
                    <x-icon name="chevron" class="mt-0.5 w-3.5 h-3.5 shrink-0 text-brand" />
                    <div class="min-w-0">
                        <p class="truncate font-medium text-ink">{{ $a['titulo'] }}</p>
                        <p class="text-xs text-ink-50">{{ $a['autor'] }} · {{ $a['cuando'] }}</p>
                    </div>
                </a>
            @endforeach
        </x-card>

        <x-card :eyebrow="__('app.home.latest_bulletin')">
            <p class="font-medium text-ink capitalize">
                {{ $ultimoBoletin?->periodLabel() ?? __('app.states.empty_title') }}
            </p>
            <div class="my-3 flex h-20 items-center justify-center rounded-md"
                 style="background: var(--paper-3); color: var(--ink-500);">
                <x-icon name="news" class="w-7 h-7" />
            </div>
            <x-button variant="secondary"
                      href="{{ $ultimoBoletin ? $ultimoBoletin->url() : route('bulletins') }}"
                      target="{{ $ultimoBoletin ? '_blank' : '_self' }}" class="w-full">
                {{ __('app.home.open') }}
            </x-button>
        </x-card>
    </div>

    {{-- Accesos con dato en vivo, no 16 cuadros idénticos como en la v1 --}}
    <div class="mb-3 mt-7 flex items-center gap-3">
        <p class="ah-eyebrow">{{ __('app.home.quick_access') }}</p>
        <div class="h-px flex-1" style="background: var(--line);"></div>
    </div>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
        @foreach ($accesos as $a)
            <a href="{{ route($a['key']) }}" class="ah-card ah-card-hover group p-3.5">
                <x-icon :name="$a['icon']" class="w-5 h-5 mb-6 text-ink-50 transition group-hover:text-[var(--brand-text)]" />
                <p class="font-medium text-ink">{{ __('nav.' . $a['key']) }}</p>
                <p class="mt-0.5 truncate text-xs text-ink-50">{{ $a['dato'] }}</p>
            </a>
        @endforeach
    </div>

    {{-- Próximo: festivos calculados por regla + eventos (Fase 3) --}}
    <div class="mb-3 mt-7 flex items-center gap-3">
        <p class="ah-eyebrow">{{ __('app.home.upcoming') }}</p>
        <div class="h-px flex-1" style="background: var(--line);"></div>
    </div>

    <x-card :padded="false">
        @foreach ($proximos as $p)
            <div class="flex items-center gap-3 px-4 py-3 @if (! $loop->last) border-b @endif"
                 style="border-color: var(--line);">
                <span class="w-14 shrink-0 text-xs tabular-nums text-ink-50">{{ $p['fecha'] }}</span>
                <span class="min-w-0 flex-1 truncate text-ink">{{ $p['texto'] }}</span>
                <span class="ah-badge">{{ $p['tipo'] }}</span>
            </div>
        @endforeach
    </x-card>

    {{-- Encuesta activa, si la hay (COM-10) --}}
    <div class="mt-4">
        @livewire('content.poll-widget')
    </div>

</x-layouts.app>
