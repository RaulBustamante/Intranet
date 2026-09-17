<x-layouts.app :title="__('nav.' . $key)">

    <x-page-header :title="__('nav.' . $key)" />

    <x-card :padded="false">
        <x-empty-state
            icon="info"
            :title="__('app.placeholder.title')"
            :body="__('app.placeholder.body', ['phase' => __('app.placeholder.phase', ['n' => $phase])])">
            <x-button variant="secondary" href="{{ route('dashboard') }}" icon="home">
                {{ __('nav.home') }}
            </x-button>
        </x-empty-state>
    </x-card>

</x-layouts.app>
