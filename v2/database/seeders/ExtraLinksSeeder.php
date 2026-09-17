<?php

namespace Database\Seeders;

use App\Domain\Content\Models\Link;
use Illuminate\Database\Seeder;

/**
 * Enlaces nuevos pedidos por Raúl (herramientas de IA y Ariel QMS), y
 * categorización de los existentes para agruparlos en la vista.
 */
class ExtraLinksSeeder extends Seeder
{
    public function run(): void
    {
        // Nuevos
        $nuevos = [
            ['Gemini', 'https://gemini.google.com', 'ai', false],
            ['Claude', 'https://claude.ai', 'ai', false],
            ['NotebookLM', 'https://notebooklm.google.com', 'ai', false],
            ['Perplexity', 'https://www.perplexity.ai', 'ai', false],
            ['Ariel QMS', 'https://191.168.50.33:8000/', 'ariel', true],
        ];
        foreach ($nuevos as $i => [$title, $url, $cat, $internal]) {
            Link::updateOrCreate(['url' => $url], [
                'title_es' => $title, 'title_en' => $title,
                'category' => $cat, 'is_internal_only' => $internal,
                'sort_order' => $i, 'is_active' => true,
            ]);
        }

        // Categorizar los existentes (ChatGPT también es IA)
        Link::where('title_es', 'ChatGPT')->update(['category' => 'ai']);
        Link::whereIn('title_es', ['Ariel Premium', 'Ariel Asset Management', 'Ariel Dashboards', 'Ariel Helpdesk', 'Ariel Knowledge', 'Ariel Wiki', 'Qualityweb 360'])
            ->update(['category' => 'ariel']);
        Link::whereIn('title_es', ['Goblin Tools', 'Language Tool'])->update(['category' => 'tools']);

        $this->command->info('5 enlaces nuevos + categorías.');
    }
}
