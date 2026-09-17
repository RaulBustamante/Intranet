@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
<body class="flex min-h-full items-center justify-center px-4 py-10" style="background: var(--paper-2);">

    <div class="w-full max-w-sm">
        <div class="mb-6 flex flex-col items-center text-center">
            <span class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl text-lg font-semibold text-white"
                  style="background: var(--brand);">A</span>
            <h1 class="text-xl">{{ __('app.app_name') }}</h1>
        </div>

        {{ $slot }}

        <p class="mt-6 text-center text-xs text-ink-50">
            &copy; {{ date('Y') }} Ariel Premium Supply
        </p>
    </div>

</body>
</html>
