<?php

namespace App\Domain\Search;

use App\Domain\Content\Models\Bulletin;
use App\Domain\Content\Models\Document;
use App\Domain\Content\Models\DocumentCategory;
use App\Domain\Content\Models\Link;
use App\Domain\People\Models\Employee;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Búsqueda global (SRCH-01, SRCH-03, SRCH-04).
 *
 * Consulta varias tablas y unifica los resultados. Para el volumen real de
 * Ariel (cientos de registros) esto es suficiente y no necesita un índice
 * aparte; la lógica vive detrás de esta clase, así que cambiar a Meilisearch
 * después no toca las vistas.
 *
 * Respeta permisos (SRCH-04): un empleado nunca ve en resultados algo que no
 * podría abrir. Tolera acentos y mayúsculas (SRCH-03) normalizando el término.
 */
class SearchService
{
    public function search(string $term, ?User $user, int $perGroup = 5): Collection
    {
        $term = trim($term);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        $results = collect();

        // --- Personas ---
        Employee::query()->active()->search($term)
            ->with('department:id,name_es,name_en', 'location:id,code')
            ->limit($perGroup)->get()
            ->each(fn (Employee $e) => $results->push([
                'group'    => 'people',
                'title'    => $e->display_name,
                'subtitle' => trim(($e->department?->name ?? '') . ($e->extension ? ' · ext ' . $e->extension : '')),
                'url'      => route('directory.show', $e),
                'icon'     => 'users',
            ]));

        // --- Documentos (solo categorías visibles al usuario) ---
        $catsVisibles = DocumentCategory::where('is_active', true)->get()
            ->filter(fn ($c) => $c->visibleTo($user))->pluck('id');

        Document::query()
            ->whereIn('category_id', $catsVisibles)
            ->where(fn ($q) => $q->where('title_es', 'like', "%{$term}%")
                ->orWhere('title_en', 'like', "%{$term}%")
                ->orWhere('content_text', 'like', "%{$term}%"))   // SRCH-02: dentro del PDF
            ->with('category')
            ->limit($perGroup)->get()
            ->each(function (Document $d) use ($results, $term) {
                // Si el término solo aparece en el contenido (no en el título),
                // lo señalamos para que el usuario sepa por qué salió.
                $enTitulo = mb_stripos($d->title, $term) !== false;
                $results->push([
                    'group'    => 'documents',
                    'title'    => $d->title,
                    'subtitle' => $enTitulo
                        ? ($d->category?->name ?? '')
                        : trim(($d->category?->name ?? '') . ' · ' . __('search.in_content')),
                    'url'      => route('documents'),
                    'icon'     => 'file',
                ]);
            });

        // --- Boletines ---
        Bulletin::query()
            ->where(fn ($q) => $q->where('title_es', 'like', "%{$term}%")->orWhere('title_en', 'like', "%{$term}%"))
            ->limit($perGroup)->get()
            ->each(fn (Bulletin $b) => $results->push([
                'group'    => 'documents',
                'title'    => $b->title,
                'subtitle' => ucfirst($b->periodLabel()),
                'url'      => route('bulletins'),
                'icon'     => 'news',
            ]));

        // --- Enlaces ---
        Link::query()->where('is_active', true)
            ->where(fn ($q) => $q->where('title_es', 'like', "%{$term}%")->orWhere('title_en', 'like', "%{$term}%"))
            ->limit($perGroup)->get()
            ->each(fn (Link $l) => $results->push([
                'group'    => 'links',
                'title'    => $l->title,
                'subtitle' => '',
                'url'      => $l->url,
                'icon'     => 'link',
                'external' => true,
            ]));

        return $results;
    }
}
