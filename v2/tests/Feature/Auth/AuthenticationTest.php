<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\LoginForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['employee', 'hr_editor', 'content_editor', 'approver', 'admin'] as $rol) {
            Role::findOrCreate($rol, 'web');
        }
    }

    private function user(array $attrs = [], string $rol = 'employee'): User
    {
        $u = User::create(array_merge([
            'name'      => 'Prueba',
            'email'     => 'prueba@arielpremium.com',
            'password'  => Hash::make('password-correcta'),
            'is_active' => true,
        ], $attrs));

        $u->assignRole($rol);

        return $u;
    }

    // ---- AUTH-01: login real, ya no la contraseña compartida --------------

    public function test_entra_con_credenciales_correctas(): void
    {
        $this->user();

        Livewire::test(LoginForm::class)
            ->set('email', 'prueba@arielpremium.com')
            ->set('password', 'password-correcta')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_la_contrasena_compartida_de_la_v1_ya_no_sirve(): void
    {
        // El usuario compartido de la v1 no existe como cuenta en la v2.
        // La contraseña real no se escribe aquí: cualquier credencial v1 debe fallar.
        Livewire::test(LoginForm::class)
            ->set('email', 'admin')
            ->set('password', 'contrasena-compartida-v1')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
    }

    public function test_rechaza_contrasena_incorrecta_sin_revelar_si_el_correo_existe(): void
    {
        $this->user();

        $component = Livewire::test(LoginForm::class)
            ->set('email', 'prueba@arielpremium.com')
            ->set('password', 'password-mala')
            ->call('login')
            ->assertHasErrors('email');

        // El mensaje es genérico: el mismo que para un correo inexistente
        $this->assertSame(__('auth.failed'), $component->errors()->first('email'));
        $this->assertGuest();
    }

    // ---- AUTH-03: bloqueo por fuerza bruta --------------------------------

    public function test_bloquea_tras_cinco_intentos_fallidos(): void
    {
        $this->user();

        for ($i = 0; $i < 5; $i++) {
            Livewire::test(LoginForm::class)
                ->set('email', 'prueba@arielpremium.com')
                ->set('password', 'mala')
                ->call('login')
                ->assertHasErrors('email');
        }

        // El sexto intento, aun con la contraseña CORRECTA, queda bloqueado
        $component = Livewire::test(LoginForm::class)
            ->set('email', 'prueba@arielpremium.com')
            ->set('password', 'password-correcta')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertStringContainsString('intento', mb_strtolower($component->errors()->first('email')));
        $this->assertGuest();
    }

    // ---- AUTH-08: cuenta desactivada no entra -----------------------------

    public function test_una_cuenta_desactivada_no_puede_entrar(): void
    {
        $this->user(['is_active' => false]);

        Livewire::test(LoginForm::class)
            ->set('email', 'prueba@arielpremium.com')
            ->set('password', 'password-correcta')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
    }

    // ---- Modelo de acceso: navegar es público, lo personal exige sesión ----

    public function test_navegar_es_publico_para_invitados(): void
    {
        // La red es interna: cualquiera puede NAVEGAR sin firmarse.
        foreach (['dashboard', 'directory', 'calendar', 'bulletins', 'links', 'requests', 'rooms', 'kudos'] as $ruta) {
            $this->get(route($ruta))->assertOk();
        }
    }

    public function test_lo_personal_y_admin_si_exige_sesion(): void
    {
        // Lo estrictamente personal (perfil/conexiones) y el panel siguen protegidos.
        $this->get(route('profile.edit'))->assertRedirect(route('login'));
        $this->get(route('profile.connections'))->assertRedirect(route('login'));
        $this->get(route('admin.people'))->assertRedirect(route('login'));
    }

    public function test_un_usuario_autenticado_si_entra(): void
    {
        $this->actingAs($this->user());

        $this->get(route('dashboard'))->assertOk();
        $this->get(route('directory'))->assertOk();
    }

    // ---- HRADM-06: /admin exige el rol correcto ---------------------------

    public function test_un_empleado_no_entra_al_panel_de_rh(): void
    {
        $this->actingAs($this->user(rol: 'employee'));

        $this->get(route('admin.people'))->assertForbidden();
    }

    public function test_rh_si_entra_al_panel_de_rh(): void
    {
        $this->actingAs($this->user(rol: 'hr_editor'));

        $this->get(route('admin.people'))->assertOk();
    }

    public function test_un_empleado_no_entra_a_ajustes_ni_auditoria(): void
    {
        $this->actingAs($this->user(rol: 'employee'));

        $this->get(route('admin.settings'))->assertForbidden();
        $this->get(route('admin.audit'))->assertForbidden();
    }

    public function test_el_admin_entra_a_todo(): void
    {
        $this->actingAs($this->user(rol: 'admin'));

        $this->get(route('admin.people'))->assertOk();
        $this->get(route('admin.settings'))->assertOk();
        $this->get(route('admin.audit'))->assertOk();
    }

    // ---- Sesión -----------------------------------------------------------

    public function test_cerrar_sesion(): void
    {
        $this->actingAs($this->user());

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    protected function tearDown(): void
    {
        RateLimiter::clear('prueba@arielpremium.com|127.0.0.1');
        parent::tearDown();
    }
}
