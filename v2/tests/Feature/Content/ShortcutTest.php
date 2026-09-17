<?php

namespace Tests\Feature\Content;

use App\Domain\Content\Models\Shortcut;
use App\Livewire\Admin\ShortcutManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ShortcutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['employee', 'content_editor', 'admin'] as $r) {
            Role::findOrCreate($r, 'web');
        }
    }

    private function user(string $role): User
    {
        $u = User::create(['name' => 'X', 'email' => "$role@arielpremium.com", 'password' => bcrypt('x'), 'is_active' => true]);
        $u->assignRole($role);

        return $u;
    }

    public function test_la_landing_muestra_los_accesos_activos(): void
    {
        Shortcut::create(['label_es' => 'Directorio', 'label_en' => 'Directory', 'icon' => 'users', 'tint' => 'blue', 'target_type' => 'route', 'target' => 'directory', 'is_active' => true, 'sort_order' => 1]);
        Shortcut::create(['label_es' => 'Oculto', 'label_en' => 'Hidden', 'icon' => 'link', 'tint' => 'slate', 'target_type' => 'route', 'target' => 'links', 'is_active' => false, 'sort_order' => 2]);

        $this->withSession(['locale' => 'es'])->get('/')->assertOk()->assertSee('Directorio')->assertDontSee('Oculto');
    }

    public function test_rh_puede_crear_un_acceso(): void
    {
        Livewire::actingAs($this->user('content_editor'))->test(ShortcutManager::class)
            ->call('create')
            ->set('label_es', 'Galería')
            ->set('label_en', 'Gallery')
            ->set('icon', 'image')
            ->set('tint', 'purple')
            ->set('target_type', 'route')
            ->set('target', 'documents')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('shortcuts', ['label_es' => 'Galería', 'tint' => 'purple']);
    }

    public function test_un_empleado_no_entra_al_panel_de_accesos(): void
    {
        $this->actingAs($this->user('employee'));
        $this->get(route('admin.shortcuts'))->assertForbidden();
    }

    public function test_ocultar_un_acceso_lo_quita_de_la_landing(): void
    {
        $s = Shortcut::create(['label_es' => 'Prueba', 'label_en' => 'Test', 'icon' => 'link', 'tint' => 'red', 'target_type' => 'route', 'target' => 'links', 'is_active' => true, 'sort_order' => 1]);

        Livewire::actingAs($this->user('admin'))->test(ShortcutManager::class)->call('toggle', $s->id);

        $this->assertFalse($s->fresh()->is_active);
        $this->withSession(['locale' => 'es'])->get('/')->assertDontSee('Prueba');
    }

    public function test_un_acceso_externo_no_requiere_auth(): void
    {
        Livewire::actingAs($this->user('admin'))->test(ShortcutManager::class)
            ->call('create')
            ->set('label_es', 'Soporte')->set('label_en', 'Support')
            ->set('target_type', 'url')->set('target', 'https://helpme.arielapps.net')
            ->set('requires_auth', true)   // aunque se marque, para url se fuerza a false
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('shortcuts', ['label_es' => 'Soporte', 'requires_auth' => false]);
    }
}
