<x-layouts.app :title="$employee->display_name">

    <a href="{{ route('directory') }}" class="ah-link mb-5 inline-flex items-center gap-1.5 text-sm">
        <x-icon name="chevron" class="w-3.5 h-3.5 rotate-180" />
        {{ __('app.directory.back') }}
    </a>

    {{-- ============================ IDENTIDAD ============================ --}}
    <x-card class="mb-4">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
            <x-avatar :name="$employee->full_name" :src="$employee->photo_path" size="lg" class="shrink-0" />

            <div class="min-w-0 flex-1">
                <h1 class="text-2xl">{{ $employee->display_name }}</h1>

                @if ($employee->preferred_name && $employee->preferred_name !== $employee->first_name)
                    <p class="text-sm text-ink-50">{{ $employee->full_name }}</p>
                @endif

                <p class="mt-1 text-ink-70">
                    {{ $employee->{'job_title_' . app()->getLocale()} ?: $employee->job_title_es ?: '—' }}
                    @if ($employee->department)
                        <span class="mx-1.5 text-ink-50">·</span>{{ $employee->department->name }}
                    @endif
                </p>

                @if ($employee->location)
                    <p class="mt-0.5 text-sm text-ink-50">
                        {{ $employee->location->code }}
                        @if ($employee->location->city) — {{ $employee->location->city }} @endif
                    </p>
                @endif

                <div class="mt-4 grid gap-2 text-sm sm:grid-cols-2">
                    <div class="flex items-center gap-2">
                        <x-icon name="inbox" class="w-4 h-4 shrink-0 text-ink-50" />
                        @if ($employee->email)
                            <a href="mailto:{{ $employee->email }}" class="ah-link truncate">{{ $employee->email }}</a>
                        @else
                            <span class="text-ink-50">{{ __('app.directory.no_email') }}</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <x-icon name="lifebuoy" class="w-4 h-4 shrink-0 text-ink-50" />
                        @if ($employee->extension || $employee->phone)
                            <span class="text-ink-70 tabular-nums">
                                @if ($employee->extension) ext {{ $employee->extension }} @endif
                                @if ($employee->extension && $employee->phone) <span class="mx-1 text-ink-50">·</span> @endif
                                @if ($employee->phone) {{ $employee->phone }} @endif
                            </span>
                        @else
                            <span class="text-ink-50">{{ __('app.directory.no_extension') }}</span>
                        @endif
                    </div>

                    {{-- Cumpleaños sin año, siempre (PPL-10) --}}
                    <div class="flex items-center gap-2">
                        <x-icon name="cake" class="w-4 h-4 shrink-0 text-ink-50" />
                        @if ($employee->birthday_label)
                            <span class="text-ink-70">{{ $employee->birthday_label }}</span>
                        @else
                            <span class="text-ink-50">{{ __('app.directory.no_birthday') }}</span>
                        @endif
                    </div>

                    @if ($employee->hire_date)
                        <div class="flex items-center gap-2">
                            <x-icon name="badge" class="w-4 h-4 shrink-0 text-ink-50" />
                            <span class="text-ink-70">
                                {{ $employee->hire_date->locale(app()->getLocale())->isoFormat('MMMM YYYY') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-card>

    {{-- =========================== ORGANIGRAMA =========================== --}}
    <div class="grid gap-4 sm:grid-cols-2">

        <x-card :eyebrow="__('app.directory.reports_to')">
            @if ($employee->manager)
                <a href="{{ route('directory.show', $employee->manager) }}"
                   class="-mx-2 flex items-center gap-3 rounded-md px-2 py-2 hover:bg-paper-2">
                    <x-avatar :name="$employee->manager->full_name" :src="$employee->manager->photo_path" size="md" />
                    <div class="min-w-0">
                        <p class="truncate font-medium text-ink">{{ $employee->manager->display_name }}</p>
                        <p class="truncate text-xs text-ink-50">{{ $employee->manager->department?->name ?? '—' }}</p>
                    </div>
                    <x-icon name="chevron" class="ml-auto w-4 h-4 shrink-0 text-ink-50" />
                </a>
            @else
                <p class="py-3 text-sm text-ink-50">{{ __('app.directory.no_manager') }}</p>
            @endif
        </x-card>

        <x-card :eyebrow="__('app.directory.team_count', ['count' => $employee->reports->count()])">
            @forelse ($employee->reports as $r)
                <a href="{{ route('directory.show', $r) }}"
                   class="-mx-2 flex items-center gap-2.5 rounded-md px-2 py-1.5 hover:bg-paper-2">
                    <x-avatar :name="$r->full_name" :src="$r->photo_path" size="sm" />
                    <div class="min-w-0">
                        <p class="truncate text-ink">{{ $r->display_name }}</p>
                        <p class="truncate text-xs text-ink-50">{{ $r->department?->name ?? '—' }}</p>
                    </div>
                </a>
            @empty
                <p class="py-3 text-sm text-ink-50">{{ __('app.directory.no_team') }}</p>
            @endforelse
        </x-card>
    </div>

    {{-- Nota honesta: el organigrama solo existe donde RH haya capturado el
         jefe directo. Ninguno de los 169 importados lo trae, porque el HTML
         de la v1 no tenía esa columna. --}}
    @if (! $employee->manager && $employee->reports->isEmpty())
        <p class="mt-3 text-xs text-ink-50">
            {{ __('app.directory.no_manager') }}. {{ __('admin.warnings.hint') }}
        </p>
    @endif

</x-layouts.app>
