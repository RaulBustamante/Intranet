<?php

namespace Tests\Feature\Content;

use App\Domain\Content\Models\Document;
use App\Domain\Content\Models\DocumentCategory;
use App\Livewire\Admin\DocumentManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DocumentManagerTest extends TestCase
{
    use RefreshDatabase;

    private DocumentCategory $cat;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
        foreach (['employee', 'content_editor', 'admin'] as $r) {
            Role::findOrCreate($r, 'web');
        }
        $this->cat = DocumentCategory::create([
            'name_es' => 'Políticas', 'name_en' => 'Policies', 'slug' => 'pol',
            'visibility' => 'all', 'sort_order' => 1, 'is_active' => true,
        ]);
    }

    private function editor(): User
    {
        $u = User::create(['name' => 'Ed', 'email' => 'ed@arielpremium.com', 'password' => bcrypt('x'), 'is_active' => true]);
        $u->assignRole('content_editor');

        return $u;
    }

    public function test_un_editor_sube_un_documento_y_crea_la_version_1(): void
    {
        Livewire::actingAs($this->editor())->test(DocumentManager::class)
            ->call('openCreate')
            ->set('titleEs', 'Manual del empleado')
            ->set('categoryId', $this->cat->id)
            ->set('file', UploadedFile::fake()->create('manual.pdf', 120, 'application/pdf'))
            ->call('save')
            ->assertHasNoErrors();

        $doc = Document::first();
        $this->assertNotNull($doc);
        $this->assertSame('Manual del empleado', $doc->title_es);
        $this->assertSame(1, $doc->version_no);
        $this->assertSame(1, $doc->versions()->count());
        Storage::disk('s3')->assertExists($doc->s3_key);
    }

    public function test_subir_una_nueva_version_conserva_el_historial(): void
    {
        $editor = $this->editor();

        $comp = Livewire::actingAs($editor)->test(DocumentManager::class)
            ->call('openCreate')
            ->set('titleEs', 'Política de viáticos')
            ->set('file', UploadedFile::fake()->create('v1.pdf', 100, 'application/pdf'))
            ->call('save');

        $doc = Document::first();
        $primeraClave = $doc->s3_key;

        $comp->call('startVersion', $doc->id)
            ->set('versionFile', UploadedFile::fake()->create('v2.pdf', 100, 'application/pdf'))
            ->set('versionNote', 'Actualiza montos 2026')
            ->call('saveVersion')
            ->assertHasNoErrors();

        $doc->refresh();
        $this->assertSame(2, $doc->version_no);
        $this->assertSame(2, $doc->versions()->count());
        $this->assertNotSame($primeraClave, $doc->s3_key);          // el documento apunta al nuevo archivo
        Storage::disk('s3')->assertExists($primeraClave);            // pero la versión anterior sigue existiendo
        $this->assertSame('Actualiza montos 2026', $doc->versions()->where('version_no', 2)->first()->note);
    }

    public function test_un_empleado_sin_rol_no_entra_al_panel(): void
    {
        $emp = User::create(['name' => 'E', 'email' => 'e@arielpremium.com', 'password' => bcrypt('x'), 'is_active' => true]);
        $emp->assignRole('employee');

        $this->actingAs($emp)->get(route('admin.documents'))->assertForbidden();
    }
}
