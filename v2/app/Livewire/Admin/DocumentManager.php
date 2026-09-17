<?php

namespace App\Livewire\Admin;

use App\Domain\Content\Models\Document;
use App\Domain\Content\Models\DocumentCategory;
use App\Domain\Content\Services\PdfTextExtractor;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

/**
 * Panel de documentos para editores de contenido (DOC-03, DOC-04).
 *
 * Sube documentos a S3, los versiona (cada reemplazo guarda la versión anterior
 * y se puede descargar), y extrae el texto de los PDFs para que la búsqueda
 * global mire dentro del contenido (SRCH-02). Es el equivalente para documentos
 * del panel de RH: contenido mantenible sin tocar código.
 */
class DocumentManager extends Component
{
    use WithFileUploads, WithPagination;

    // Formulario de nuevo documento
    public bool $showForm = false;
    public string $titleEs = '';
    public string $titleEn = '';
    public ?int $categoryId = null;
    public bool $isPrivate = false;
    public $file = null;

    // Nueva versión de un documento existente
    public ?int $versioningId = null;
    public $versionFile = null;
    public string $versionNote = '';

    // Historial expandido (id del documento cuyas versiones se muestran)
    public ?int $expandedId = null;

    protected array $fileRules = ['file|max:20480'];   // 20 MB

    #[Computed]
    public function categories()
    {
        return DocumentCategory::where('is_active', true)->orderBy('sort_order')->get();
    }

    #[Computed]
    public function documents()
    {
        return Document::query()
            ->with('category:id,name_es,name_en')
            ->withCount('versions')
            ->orderByDesc('published_at')->orderByDesc('id')
            ->paginate(15);
    }

    public function openCreate(): void
    {
        $this->reset(['titleEs', 'titleEn', 'categoryId', 'isPrivate', 'file', 'versioningId', 'expandedId']);
        $this->resetErrorBag();
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'titleEs'    => 'required|string|max:200',
            'titleEn'    => 'nullable|string|max:200',
            'categoryId' => 'nullable|exists:document_categories,id',
            'file'       => 'required|file|max:20480',
        ]);

        // Se crea la fila primero para tener el id y ordenar el archivo por documento.
        $doc = Document::create([
            'title_es'     => $this->titleEs,
            'title_en'     => $this->titleEn ?: null,
            'category_id'  => $this->categoryId,
            'is_private'   => $this->isPrivate,
            's3_key'       => '',            // se llena al guardar el archivo
            'version_no'   => 0,
            'uploaded_by'  => auth()->id(),
            'published_at' => now(),
        ]);

        $this->putVersion($doc, $this->file, note: null);

        $this->reset(['titleEs', 'titleEn', 'categoryId', 'isPrivate', 'file']);
        $this->showForm = false;
        unset($this->documents);
        $this->dispatch('notify', message: __('docadmin.saved'));
    }

    public function startVersion(int $documentId): void
    {
        $this->reset(['versionFile', 'versionNote']);
        $this->resetErrorBag();
        $this->versioningId = $documentId;
        $this->showForm = false;
    }

    public function saveVersion(): void
    {
        $this->validate([
            'versionFile' => 'required|file|max:20480',
            'versionNote' => 'nullable|string|max:500',
        ]);

        $doc = Document::findOrFail($this->versioningId);
        $this->putVersion($doc, $this->versionFile, note: $this->versionNote ?: null);

        $this->reset(['versionFile', 'versionNote']);
        $this->versioningId = null;
        unset($this->documents);
        $this->dispatch('notify', message: __('docadmin.version_saved'));
    }

    public function toggleVersions(int $documentId): void
    {
        $this->expandedId = $this->expandedId === $documentId ? null : $documentId;
    }

    public function delete(int $documentId): void
    {
        Document::findOrFail($documentId)->delete();   // soft delete; las versiones quedan por si se restaura
        if ($this->expandedId === $documentId) {
            $this->expandedId = null;
        }
        unset($this->documents);
        $this->dispatch('notify', message: __('docadmin.deleted'));
    }

    /**
     * Sube un archivo a S3, crea la fila de versión y apunta el documento a ella.
     * Extrae el texto si es PDF (SRCH-02). Comparte create y nueva versión.
     */
    private function putVersion(Document $doc, $upload, ?string $note): void
    {
        $next = ((int) $doc->versions()->max('version_no')) + 1;

        $ext  = strtolower($upload->getClientOriginalExtension() ?: 'bin');
        $mime = $upload->getMimeType();
        $size = $upload->getSize();
        $orig = $upload->getClientOriginalName();
        $key  = "documents/{$doc->id}/v{$next}-" . uniqid() . '.' . $ext;

        $visibility = $doc->is_private ? 'private' : 'public';
        Storage::disk('s3')->put($key, file_get_contents($upload->getRealPath()), $visibility);

        $doc->versions()->create([
            'version_no'    => $next,
            's3_key'        => $key,
            'original_name' => $orig,
            'mime_type'     => $mime,
            'size_bytes'    => $size,
            'note'          => $note,
            'uploaded_by'   => auth()->id(),
        ]);

        // El texto se extrae del archivo local antes de que Livewire lo borre.
        $content = app(PdfTextExtractor::class)->fromPath($upload->getRealPath(), $mime);

        $doc->update([
            's3_key'        => $key,
            'version_no'    => $next,
            'mime_type'     => $mime,
            'original_name' => $orig,
            'size_bytes'    => $size,
            'content_text'  => $content,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.document-manager')
            ->layout('components.layouts.app', ['title' => __('docadmin.title')]);
    }
}
