@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' · ' : '' }}{{ __('app.app_name') }}</title>

    {{-- Tema antes de pintar: evita el destello blanco al cargar en modo oscuro --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('ah-theme');
                if (t === 'dark' || (!t && matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full min-h-full">

<a href="#contenido"
   class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:m-3 focus:rounded focus:px-3 focus:py-2"
   style="background: var(--paper); border: 1px solid var(--line); color: var(--ink-900);">
    {{ __('nav.skip_to_content') }}
</a>

{{-- ============================ BARRA SUPERIOR ============================ --}}
<header class="sticky top-0 z-30 border-b" style="background: var(--paper); border-color: var(--line);">
    <div class="mx-auto flex h-14 max-w-[1200px] items-center gap-4 px-4">

        <a href="{{ route('dashboard') }}" class="flex shrink-0 items-center gap-2.5">
            <span class="flex h-7 w-7 items-center justify-center rounded-md text-[0.8125rem] font-semibold text-white"
                  style="background: var(--brand);">A</span>
            <span class="hidden text-ink font-semibold sm:inline">{{ __('app.app_name') }}</span>
        </a>

        {{-- Navegación principal: viene de config/navigation.php, definida una sola vez --}}
        <nav class="hidden flex-1 items-center gap-5 md:flex" aria-label="{{ __('nav.more') }}">
            @foreach (config('navigation.main') as $item)
                @continue($item['key'] === 'home')
                <a href="{{ route($item['route']) }}"
                   class="ah-nav-link"
                   @if (request()->routeIs($item['route'])) aria-current="page" @endif>
                    {{ __('nav.' . $item['key']) }}
                </a>
            @endforeach

            <div class="relative" data-dropdown>
                <button type="button" class="ah-nav-link inline-flex items-center gap-1" data-dropdown-trigger
                        aria-haspopup="true" aria-expanded="false">
                    {{ __('nav.more') }}
                    <x-icon name="chevron-down" class="w-3.5 h-3.5" />
                </button>
                <div class="ah-card absolute left-0 top-full mt-2 hidden w-52 p-1.5 shadow-lg" data-dropdown-panel>
                    @foreach (config('navigation.more') as $item)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-2.5 rounded-md px-2.5 py-2 text-ink-70 hover:bg-paper-3 hover:text-ink">
                            <x-icon :name="$item['icon']" class="w-4 h-4 text-ink-50" />
                            {{ __('nav.' . $item['key']) }}
                        </a>
                    @endforeach
                    <div class="my-1.5 border-t" style="border-color: var(--line);"></div>
                    @foreach (config('navigation.external') as $item)
                        <a href="{{ $item['url'] }}" target="_blank" rel="noopener"
                           class="flex items-center gap-2.5 rounded-md px-2.5 py-2 text-ink-70 hover:bg-paper-3 hover:text-ink">
                            <x-icon :name="$item['icon']" class="w-4 h-4 text-ink-50" />
                            {{ __('nav.' . $item['key']) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </nav>

        <div class="ml-auto flex items-center gap-1.5">

            {{-- Disparador de la búsqueda global --}}
            <button type="button" data-palette-open
                    class="hidden items-center gap-2 rounded-lg border px-2.5 py-1.5 text-ink-50 transition hover:text-ink sm:flex"
                    style="border-color: var(--line); background: var(--paper-2);"
                    aria-label="{{ __('nav.search') }}">
                <x-icon name="search" class="w-4 h-4" />
                <span class="text-xs">{{ __('nav.search') }}</span>
                <kbd class="rounded border px-1.5 py-0.5 font-sans text-[0.625rem]"
                     style="border-color: var(--line); background: var(--paper);">⌘K</kbd>
            </button>

            {{-- Idioma (UX-06) --}}
            <a href="{{ route('locale.switch', ['locale' => app()->getLocale() === 'es' ? 'en' : 'es']) }}"
               class="ah-btn ah-btn-ghost px-2"
               title="{{ __('nav.language') }}"
               aria-label="{{ __('nav.language') }}">
                <x-icon name="globe" class="w-4 h-4" />
                <span class="text-xs font-medium uppercase">{{ app()->getLocale() }}</span>
            </a>

            {{-- Tema (UX-05) --}}
            <button type="button" data-theme-toggle class="ah-btn ah-btn-ghost px-2"
                    title="{{ __('nav.theme_toggle') }}" aria-label="{{ __('nav.theme_toggle') }}">
                <x-icon name="sun"  class="w-4 h-4 hidden dark:block" />
                <x-icon name="moon" class="w-4 h-4 block dark:hidden" />
            </button>

            {{-- Invitado: un botón para firmarse. La navegación es pública, pero
                 capturar (solicitudes, salas, kudos) pide sesión. --}}
            @guest
                <a href="{{ route('login') }}" class="ah-btn ah-btn-primary ml-1">
                    <x-icon name="door" class="w-4 h-4" />
                    <span class="hidden text-sm font-medium sm:inline">{{ __('auth.sign_in') }}</span>
                </a>
            @endguest

            {{-- Perfil y cierre de sesión (solo con sesión) --}}
            @auth
            <div class="relative" data-dropdown>
                <button type="button" data-dropdown-trigger class="ml-1 flex items-center rounded-full"
                        aria-haspopup="true" aria-expanded="false" aria-label="{{ __('nav.profile') }}">
                    <x-avatar :name="auth()->user()?->display_name ?? 'Ariel'" size="sm" />
                </button>
                <div class="ah-card absolute right-0 top-full mt-2 hidden w-52 p-1.5 shadow-lg" data-dropdown-panel>
                    <div class="border-b px-2.5 pb-2 pt-1" style="border-color: var(--line);">
                        <p class="truncate text-sm font-medium text-ink">{{ auth()->user()?->display_name }}</p>
                        <p class="truncate text-xs text-ink-50">{{ auth()->user()?->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="mt-1 flex items-center gap-2.5 rounded-md px-2.5 py-2 text-ink-70 hover:bg-paper-3 hover:text-ink">
                        <x-icon name="users" class="w-4 h-4 text-ink-50" />
                        {{ __('nav.profile') }}
                    </a>
                    <a href="{{ route('profile.connections') }}" class="flex items-center gap-2.5 rounded-md px-2.5 py-2 text-ink-70 hover:bg-paper-3 hover:text-ink">
                        <x-icon name="link" class="w-4 h-4 text-ink-50" />
                        {{ __('connections.title') }}
                    </a>
                    @role('hr_editor|admin')
                        <a href="{{ route('admin.people') }}" class="flex items-center gap-2.5 rounded-md px-2.5 py-2 text-ink-70 hover:bg-paper-3 hover:text-ink">
                            <x-icon name="shield" class="w-4 h-4 text-ink-50" />
                            {{ __('nav.administration') }}
                        </a>
                    @endrole
                    @role('content_editor|admin')
                        <a href="{{ route('admin.documents') }}" class="flex items-center gap-2.5 rounded-md px-2.5 py-2 text-ink-70 hover:bg-paper-3 hover:text-ink">
                            <x-icon name="file" class="w-4 h-4 text-ink-50" />
                            {{ __('docadmin.title') }}
                        </a>
                    @endrole
                    <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t pt-1" style="border-color: var(--line);">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2.5 rounded-md px-2.5 py-2 text-left text-ink-70 hover:bg-paper-3 hover:text-ink">
                            <x-icon name="door" class="w-4 h-4 text-ink-50" />
                            {{ __('nav.logout') }}
                        </button>
                    </form>
                </div>
            </div>
            @endauth

            <button type="button" data-mobile-toggle class="ah-btn ah-btn-ghost px-2 md:hidden"
                    aria-label="{{ __('nav.open_menu') }}" aria-expanded="false">
                <x-icon name="menu" class="w-5 h-5" />
            </button>
        </div>
    </div>

    {{-- Navegación móvil --}}
    <nav class="hidden border-t md:!hidden" data-mobile-panel style="border-color: var(--line); background: var(--paper);">
        <div class="mx-auto max-w-[1200px] px-4 py-2">
            @foreach (array_merge(config('navigation.main'), config('navigation.more')) as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 rounded-md px-2 py-2.5 text-ink-70 hover:bg-paper-3 hover:text-ink"
                   @if (request()->routeIs($item['route'])) aria-current="page" @endif>
                    <x-icon :name="$item['icon']" class="w-4 h-4 text-ink-50" />
                    {{ __('nav.' . $item['key']) }}
                </a>
            @endforeach
        </div>
    </nav>
</header>

{{-- Banner de aviso urgente (COM-06): un admin lo activa desde ajustes --}}
@php $banner = \App\Domain\Content\Services\Settings::banner(); @endphp
@if ($banner)
    <div class="px-4 py-2.5 text-center text-sm font-medium text-white" style="background: var(--brand);">
        {{ $banner['text_' . app()->getLocale()] ?? $banner['text_es'] ?? '' }}
    </div>
@endif

{{-- ================================ CUERPO =============================== --}}
<main id="contenido" class="mx-auto max-w-[1200px] px-4 py-6 sm:py-8">
    {{ $slot }}
</main>

{{-- ================================ PIE ================================== --}}
<footer class="mt-8 border-t" style="border-color: var(--line);">
    <div class="mx-auto flex max-w-[1200px] flex-wrap items-center justify-between gap-2 px-4 py-5 text-ink-50">
        <p class="text-xs">&copy; {{ date('Y') }} Ariel Premium Supply</p>
        <p class="text-xs">
            {{ __('app.footer.contact') }}:
            <a class="ah-link" href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a>
        </p>
    </div>
</footer>

@include('partials.command-palette')

</body>
</html>
