<x-layouts.public>

    {{-- ================================ HERO ================================ --}}
    <section class="relative overflow-hidden border-b" style="border-color: var(--line);">
        {{-- Fondo con un gradiente cálido y sutil de marca --}}
        <div class="absolute inset-0 -z-10" style="background:
             radial-gradient(60rem 30rem at 15% -10%, color-mix(in srgb, var(--brand) 10%, transparent), transparent),
             radial-gradient(50rem 30rem at 100% 0%, color-mix(in srgb, #3b82f6 8%, transparent), transparent);"></div>

        <div class="mx-auto grid max-w-[1200px] items-center gap-8 px-4 py-12 lg:grid-cols-2 lg:py-16">
            <div>
                <span class="ah-badge ah-badge-brand mb-4">{{ __('welcome.eyebrow') }}</span>
                <h1 class="text-4xl sm:text-5xl" style="line-height: 1.05;">
                    {{ __('welcome.title') }}
                </h1>
                <p class="mt-4 max-w-md text-ink-70">{{ __('welcome.subtitle') }}</p>

                <div class="mt-6 flex flex-wrap gap-3">
                    @auth
                        <x-button variant="primary" href="{{ route('dashboard') }}" icon="home">{{ __('welcome.my_hub') }}</x-button>
                    @else
                        {{-- Navegar es público: "Entrar" lleva directo al hub; firmarse es opcional --}}
                        <x-button variant="primary" href="{{ route('dashboard') }}" icon="home">{{ __('welcome.enter') }}</x-button>
                        <x-button variant="secondary" href="{{ route('login') }}">{{ __('auth.sign_in') }}</x-button>
                    @endauth
                    <x-button variant="secondary" href="https://wkf.ms/4bjg8Er" target="_blank">{{ __('welcome.hr_help') }}</x-button>
                    <x-button variant="ghost" href="https://forms.monday.com/forms/3eefa9487ae5ddb64a51904639bf6da5?r=use1" target="_blank">
                        {{ __('welcome.suggestions') }}
                    </x-button>
                </div>

                @if ($proximoFestivo)
                    <div class="mt-6 inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-sm"
                         style="border-color: var(--line); background: var(--paper);">
                        <x-icon name="calendar" class="w-4 h-4 text-brand" />
                        <span class="text-ink-70">{{ __('welcome.next_holiday') }}:</span>
                        <span class="font-medium text-ink">{{ $proximoFestivo['name'] }}</span>
                        <span class="text-ink-50">· {{ $proximoFestivo['date']->locale(app()->getLocale())->isoFormat('D MMM') }}</span>
                    </div>
                @endif
            </div>

            {{-- Video institucional --}}
            <div class="ah-card overflow-hidden p-0 shadow-lg">
                <video controls controlsList="nodownload" class="aspect-video w-full bg-black" poster="">
                    <source src="https://d11dzzmlbj38ir.cloudfront.net/ARIEL_ANNIVERSARYV02.mp4" type="video/mp4">
                </video>
                <p class="px-4 py-2.5 text-center text-sm text-ink-50">{{ __('welcome.video_caption') }}</p>
            </div>
        </div>
    </section>

    {{-- ============================ ACCESOS RÁPIDOS ========================= --}}
    <section class="mx-auto max-w-[1200px] px-4 py-10">
        <div class="mb-5 flex items-center gap-3">
            <h2 class="text-lg">{{ __('welcome.quick_access') }}</h2>
            <div class="h-px flex-1" style="background: var(--line);"></div>
        </div>

        @php
            // Tints suaves por categoría: colorido pero elegante, con texto AA.
            $tints = [
                'blue'   => ['#3b82f6'], 'green' => ['#0f9d58'], 'orange' => ['#ea8600'],
                'purple' => ['#7c3aed'], 'red' => ['#ed2228'],   'teal' => ['#0d9488'],
                'pink'   => ['#db2777'], 'indigo' => ['#4f46e5'], 'slate' => ['#475569'],
            ];
        @endphp

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($accesos as $a)
                @php
                    $color = $tints[$a->tint][0] ?? '#475569';
                    $external = $a->isExternal();
                @endphp
                <a href="{{ $a->href() }}" @if ($external) target="_blank" rel="noopener" @endif
                   class="ah-card ah-card-hover group relative flex flex-col gap-3 p-5">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl transition group-hover:scale-105"
                          style="background: color-mix(in srgb, {{ $color }} 12%, transparent); color: {{ $color }};">
                        <x-icon :name="$a->icon" class="w-6 h-6" />
                    </span>
                    <div>
                        <p class="font-medium text-ink">{{ $a->label }}</p>
                        <p class="mt-0.5 text-xs text-ink-50">
                            {{ $external ? __('welcome.opens_external') : __('welcome.opens_section') }}
                        </p>
                    </div>
                    <span class="absolute inset-x-0 bottom-0 h-0.5 origin-left scale-x-0 rounded-b transition group-hover:scale-x-100"
                          style="background: {{ $color }};"></span>
                </a>
            @endforeach
        </div>

        @guest
            <div class="ah-card mt-6 flex flex-wrap items-center justify-between gap-3 p-5"
                 style="background: var(--brand-tint); border-color: transparent;">
                <div>
                    <p class="font-medium" style="color: var(--brand-text);">{{ __('welcome.cta_title') }}</p>
                    <p class="text-sm text-ink-70">{{ __('welcome.cta_body') }}</p>
                </div>
                <x-button variant="primary" href="{{ route('login') }}">{{ __('auth.sign_in') }}</x-button>
            </div>
        @endguest
    </section>

</x-layouts.public>
