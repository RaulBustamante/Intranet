<?php

namespace App\Http\Controllers;

use App\Domain\Search\Ai\AiProviderFactory;
use App\Domain\Search\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Endpoint JSON que alimenta la paleta de comandos ⌘K (SRCH-01, SRCH-05).
 * Devuelve resultados agrupados, acciones ejecutables, y —si el asistente AI
 * está encendido— una fila para preguntar en lenguaje natural.
 */
class SearchController extends Controller
{
    public function __invoke(Request $request, SearchService $search): JsonResponse
    {
        $term = (string) $request->query('q', '');
        $user = $request->user();

        $results = $search->search($term, $user);

        // Acciones ejecutables desde la búsqueda (SRCH-05)
        $actions = collect($this->actions())
            ->filter(fn ($a) => $this->matches($a['keywords'], $term))
            ->map(fn ($a) => ['group' => 'actions', 'title' => $a['title'], 'url' => $a['url'], 'icon' => $a['icon']])
            ->values();

        // Fila del asistente AI, solo si está encendido (SRCH-06, SRCH-10)
        $ai = app(AiProviderFactory::class)->make();

        return response()->json([
            'results' => $results->values(),
            'actions' => $actions,
            'ai'      => [
                'enabled'  => $ai->isEnabled(),
                'question' => $term,
            ],
        ]);
    }

    private function actions(): array
    {
        return [
            ['title' => __('search.action_book'),    'url' => route('rooms'),     'icon' => 'door',  'keywords' => ['sala', 'room', 'reservar', 'book', 'junta', 'meeting']],
            ['title' => __('search.action_request'), 'url' => route('requests'),  'icon' => 'inbox', 'keywords' => ['solicitud', 'request', 'vacacion', 'permiso', 'ti', 'compra', 'mantenimiento']],
            ['title' => __('search.action_kudos'),   'url' => route('kudos'),     'icon' => 'badge', 'keywords' => ['reconoc', 'kudo', 'gracias', 'thanks']],
            ['title' => __('search.action_calendar'),'url' => route('calendar'),  'icon' => 'calendar', 'keywords' => ['calendario', 'calendar', 'festivo', 'holiday', 'cumple', 'evento', 'event']],
        ];
    }

    private function matches(array $keywords, string $term): bool
    {
        $t = $this->normalize($term);
        if (mb_strlen($t) < 2) {
            return false;
        }
        foreach ($keywords as $k) {
            if (str_contains($this->normalize($k), $t) || str_contains($t, $this->normalize($k))) {
                return true;
            }
        }

        return false;
    }

    private function normalize(string $s): string
    {
        $s = mb_strtolower(trim($s));

        return strtr($s, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n']);
    }
}
