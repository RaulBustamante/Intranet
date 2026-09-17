@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' · ' : '' }}{{ __('app.app_name') }}</title>

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
<body class="min-h-full" style="background: var(--paper-2);">

{{-- ============================ BARRA SUPERIOR ============================ --}}
<header class="sticky top-0 z-30 border-b" style="background: var(--paper); border-color: var(--line);">
    <div class="mx-auto flex h-16 max-w-[1200px] items-center gap-4 px-4">
        <a href="{{ route('welcome') }}" class="flex items-center gap-2.5">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl text-base font-semibold text-white"
                  style="background: var(--brand);">A</span>
            <span class="text-lg font-semibold text-ink">{{ __('app.app_name') }}</span>
        </a>

        <div class="ml-auto flex items-center gap-1.5">
            <a href="{{ route('locale.switch', ['locale' => app()->getLocale() === 'es' ? 'en' : 'es']) }}"
               class="ah-btn ah-btn-ghost px-2" aria-label="{{ __('nav.language') }}">
                <x-icon name="globe" class="w-4 h-4" />
                <span class="text-xs font-medium uppercase">{{ app()->getLocale() }}</span>
            </a>
            <button type="button" data-theme-toggle class="ah-btn ah-btn-ghost px-2" aria-label="{{ __('nav.theme_toggle') }}">
                <x-icon name="sun"  class="w-4 h-4 hidden dark:block" />
                <x-icon name="moon" class="w-4 h-4 block dark:hidden" />
            </button>

            @auth
                <x-button variant="primary" href="{{ route('dashboard') }}" icon="home">
                    {{ __('welcome.my_hub') }}
                </x-button>
            @else
                <x-button variant="primary" href="{{ route('login') }}">
                    {{ __('auth.sign_in') }}
                </x-button>
            @endauth
        </div>
    </div>
</header>

<main>
    {{ $slot }}
</main>

<footer class="mt-12 border-t" style="border-color: var(--line);">
    <div class="mx-auto flex max-w-[1200px] flex-wrap items-center justify-between gap-2 px-4 py-6 text-ink-50">
        <p class="text-xs">&copy; {{ date('Y') }} Ariel Premium Supply</p>
        <p class="text-xs">
            {{ __('app.footer.contact') }}:
            <a class="ah-link" href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a>
        </p>
    </div>
</footer>

</body>
</html>
