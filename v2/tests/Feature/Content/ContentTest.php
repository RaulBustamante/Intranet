<?php

namespace Tests\Feature\Content;

use App\Domain\Content\Models\Announcement;
use App\Domain\Content\Models\DocumentCategory;
use App\Domain\Content\Models\Kudo;
use App\Domain\People\Models\Employee;
use App\Domain\People\Models\Location;
use App\Livewire\Content\KudosWall;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['employee', 'hr_editor', 'admin'] as $r) {
            Role::findOrCreate($r, 'web');
        }
    }

    private function userWithEmployee(): array
    {
        $emp = Employee::create(['first_name' => 'Ana', 'last_name' => 'S', 'email' => 'ana@arielpremium.com']);
        $user = User::create(['name' => 'Ana', 'email' => 'ana@arielpremium.com', 'password' => bcrypt('x'), 'is_active' => true, 'employee_id' => $emp->id]);
        $user->assignRole('employee');

        return [$user, $emp];
    }

    // ---- COM-02: anuncio programado no se ve antes de tiempo --------------

    public function test_un_anuncio_programado_a_futuro_no_esta_vivo(): void
    {
        Announcement::create(['title_es' => 'Futuro', 'slug' => 'futuro', 'published_at' => now()->addWeek()]);
        Announcement::create(['title_es' => 'Ahora', 'slug' => 'ahora', 'published_at' => now()->subDay()]);

        $vivos = Announcement::live()->pluck('title_es');
        $this->assertTrue($vivos->contains('Ahora'));
        $this->assertFalse($vivos->contains('Futuro'));
    }

    public function test_un_anuncio_expirado_no_esta_vivo(): void
    {
        Announcement::create(['title_es' => 'Viejo', 'slug' => 'viejo', 'published_at' => now()->subMonth(), 'expires_at' => now()->subDay()]);

        $this->assertFalse(Announcement::live()->pluck('title_es')->contains('Viejo'));
    }

    // ---- COM-03: audiencia por sede ---------------------------------------

    public function test_un_anuncio_dirigido_a_una_sede_no_lo_ve_otra(): void
    {
        $wc = Location::create(['code' => 'WC', 'name_es' => 'WC', 'name_en' => 'WC']);
        $stl = Location::create(['code' => 'STL', 'name_es' => 'STL', 'name_en' => 'STL']);

        $empWc = Employee::create(['first_name' => 'A', 'last_name' => 'A', 'email' => 'a@arielpremium.com', 'location_id' => $wc->id]);
        $empStl = Employee::create(['first_name' => 'B', 'last_name' => 'B', 'email' => 'b@arielpremium.com', 'location_id' => $stl->id]);

        $anuncio = Announcement::create(['title_es' => 'Solo WC', 'slug' => 'solo-wc', 'published_at' => now()]);
        $anuncio->audiences()->create(['location_id' => $wc->id]);

        $this->assertTrue($anuncio->visibleTo($empWc));
        $this->assertFalse($anuncio->visibleTo($empStl));
    }

    public function test_sin_audiencia_lo_ve_toda_la_empresa(): void
    {
        $emp = Employee::create(['first_name' => 'C', 'last_name' => 'C', 'email' => 'c@arielpremium.com']);
        $anuncio = Announcement::create(['title_es' => 'Todos', 'slug' => 'todos', 'published_at' => now()]);

        $this->assertTrue($anuncio->visibleTo($emp));
    }

    // ---- DOC-02: permisos de categoría ------------------------------------

    public function test_una_categoria_por_rol_no_la_ve_quien_no_tiene_el_rol(): void
    {
        [$user] = $this->userWithEmployee();   // rol employee

        $cat = DocumentCategory::create(['name_es' => 'RH', 'name_en' => 'HR', 'slug' => 'rh', 'visibility' => 'role', 'required_role' => 'hr_editor']);
        $publica = DocumentCategory::create(['name_es' => 'General', 'name_en' => 'General', 'slug' => 'general', 'visibility' => 'all']);

        $this->assertFalse($cat->visibleTo($user));
        $this->assertTrue($publica->visibleTo($user));
    }

    // ---- COM-08: kudos ----------------------------------------------------

    public function test_puedo_reconocer_a_un_companero(): void
    {
        [$user, $emp] = $this->userWithEmployee();
        $otro = Employee::create(['first_name' => 'Luis', 'last_name' => 'M', 'email' => 'luis@arielpremium.com']);

        Livewire::actingAs($user)->test(KudosWall::class)
            ->set('toEmployeeId', (string) $otro->id)
            ->set('message', 'Gracias por la ayuda con el reporte')
            ->call('send')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('kudos', ['from_employee_id' => $emp->id, 'to_employee_id' => $otro->id]);
    }

    public function test_no_puedo_reconocerme_a_mi_mismo(): void
    {
        [$user, $emp] = $this->userWithEmployee();

        Livewire::actingAs($user)->test(KudosWall::class)
            ->set('toEmployeeId', (string) $emp->id)
            ->set('message', 'Qué bueno soy')
            ->call('send')
            ->assertHasErrors('toEmployeeId');

        $this->assertDatabaseCount('kudos', 0);
    }
}
