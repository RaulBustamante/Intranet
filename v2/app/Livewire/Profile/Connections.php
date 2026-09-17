<?php

namespace App\Livewire\Profile;

use App\Domain\Integrations\ProviderRegistry;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Página "Conexiones" del perfil (AUTH-09).
 *
 * Aquí el usuario ESCOGE cómo conectarse: puede vincular su cuenta de
 * Microsoft/Teams o de Google para sincronizar calendario y disponibilidad.
 * Es opcional: la intranet funciona igual sin vincular nada.
 *
 * Mientras TI no configure las credenciales OAuth de un proveedor, su tarjeta
 * aparece deshabilitada con un aviso, en vez de un botón que falla.
 */
class Connections extends Component
{
    #[Computed]
    public function providers(): array
    {
        $registry = app(ProviderRegistry::class);
        $user = auth()->user();
        $user->load('linkedAccounts');

        $out = [];
        foreach ($registry->all() as $key => $provider) {
            $out[] = [
                'key'      => $key,
                'label'    => $provider->label(),
                'enabled'  => $provider->isEnabled(),
                'linked'   => $user->linkedAccount($key),
            ];
        }

        return $out;
    }

    public function unlink(string $provider): void
    {
        auth()->user()->linkedAccounts()->where('provider', $provider)->delete();
        unset($this->providers);
        $this->dispatch('notify', message: __('connections.unlinked'));
    }

    public function render()
    {
        return view('livewire.profile.connections')
            ->layout('components.layouts.app', ['title' => __('connections.title')]);
    }
}
