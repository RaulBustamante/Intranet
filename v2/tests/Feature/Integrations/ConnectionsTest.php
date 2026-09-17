<?php

namespace Tests\Feature\Integrations;

use App\Domain\Integrations\Models\LinkedAccount;
use App\Domain\Integrations\ProviderRegistry;
use App\Livewire\Profile\Connections;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ConnectionsTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::create([
            'name' => 'Prueba', 'email' => 'p@arielpremium.com',
            'password' => bcrypt('x'), 'is_active' => true,
        ]);
    }

    // ---- Apagado por defecto: nada configurado, nada se rompe -------------

    public function test_sin_credenciales_ningun_proveedor_esta_encendido(): void
    {
        config(['integrations.microsoft.client_id' => null, 'integrations.google.client_id' => null]);

        $registry = app(ProviderRegistry::class);

        $this->assertFalse($registry->anyEnabled());
        foreach ($registry->all() as $p) {
            $this->assertFalse($p->isEnabled());
        }
    }

    public function test_la_pagina_de_conexiones_carga_aunque_no_haya_proveedores(): void
    {
        $this->actingAs($this->user());

        // Con AUTH real, la ruta exige sesión y no debe fallar
        $this->get(route('profile.connections'))->assertOk();
    }

    public function test_muestra_los_proveedores_como_no_configurados(): void
    {
        Livewire::actingAs($this->user())
            ->test(Connections::class)
            ->assertSee(__('connections.not_configured'));
    }

    // ---- Vinculación por usuario y multi-proveedor ------------------------

    public function test_un_usuario_puede_tener_una_cuenta_de_cada_proveedor(): void
    {
        $u = $this->user();

        $u->linkedAccounts()->create(['provider' => 'microsoft', 'provider_user_id' => 'ms-1', 'email' => 'p@arielpremium.com']);
        $u->linkedAccounts()->create(['provider' => 'google', 'provider_user_id' => 'g-1', 'email' => 'p@gmail.com']);

        $this->assertNotNull($u->linkedAccount('microsoft'));
        $this->assertNotNull($u->linkedAccount('google'));
        $this->assertSame('Microsoft / Teams', $u->linkedAccount('microsoft')->providerLabel());
    }

    public function test_desvincular_borra_la_cuenta(): void
    {
        $u = $this->user();
        $u->linkedAccounts()->create(['provider' => 'microsoft', 'provider_user_id' => 'ms-1']);

        Livewire::actingAs($u)
            ->test(Connections::class)
            ->call('unlink', 'microsoft');

        $this->assertNull($u->fresh()->linkedAccount('microsoft'));
    }

    // ---- Los tokens se guardan cifrados -----------------------------------

    public function test_los_tokens_se_guardan_cifrados_en_la_base(): void
    {
        $u = $this->user();
        $u->linkedAccounts()->create([
            'provider' => 'microsoft', 'provider_user_id' => 'ms-1',
            'access_token' => 'token-secreto-en-claro',
        ]);

        // Leído por el modelo: descifrado
        $this->assertSame('token-secreto-en-claro', $u->linkedAccount('microsoft')->access_token);

        // Leído crudo de la base: NO está en claro
        $crudo = \DB::table('linked_accounts')->where('provider', 'microsoft')->value('access_token');
        $this->assertNotSame('token-secreto-en-claro', $crudo);
        $this->assertStringNotContainsString('token-secreto-en-claro', (string) $crudo);
    }

    // ---- Una cuenta externa no se vincula a dos usuarios ------------------

    public function test_una_cuenta_externa_no_puede_vincularse_a_dos_usuarios(): void
    {
        $a = $this->user();
        $b = User::create(['name' => 'B', 'email' => 'b@arielpremium.com', 'password' => bcrypt('x'), 'is_active' => true]);

        $a->linkedAccounts()->create(['provider' => 'microsoft', 'provider_user_id' => 'mismo-id']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        $b->linkedAccounts()->create(['provider' => 'microsoft', 'provider_user_id' => 'mismo-id']);
    }
}
