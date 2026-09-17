<?php

namespace Database\Seeders;

use App\Domain\Content\Models\Shortcut;
use Illuminate\Database\Seeder;

/**
 * Los accesos rápidos de la landing, mapeando los 16 cuadros de la v1 a la v2.
 * RH los edita después desde el panel.
 */
class ShortcutSeeder extends Seeder
{
    public function run(): void
    {
        // [label_es, label_en, icon, tint, target_type, target, requires_auth]
        $items = [
            ['Directorio', 'Directory', 'users', 'blue', 'route', 'directory', true],
            ['Calendario y Eventos', 'Calendar & Events', 'calendar', 'green', 'route', 'calendar', true],
            ['Salas de juntas', 'Meeting rooms', 'door', 'orange', 'route', 'rooms', true],
            ['Documentos', 'Documents', 'file', 'purple', 'route', 'documents', true],
            ['Boletines', 'Bulletins', 'news', 'red', 'route', 'bulletins', true],
            ['Solicitudes', 'Requests', 'inbox', 'teal', 'route', 'requests', true],
            ['Reconocimientos', 'Recognition', 'badge', 'pink', 'route', 'kudos', true],
            ['Enlaces', 'Links', 'link', 'indigo', 'route', 'links', true],
            ['Soporte Técnico', 'IT Support', 'lifebuoy', 'slate', 'url', 'https://helpme.arielapps.net/open.php', false],
            ['+ Orden', '+ Order', 'list', 'green', 'url', 'https://masorden.com/', false],
            ['Requisición de Compra', 'Purchase request', 'inbox', 'orange', 'url', 'https://wkf.ms/4lkF9W3', false],
            ['Mantenimiento', 'Maintenance', 'settings', 'slate', 'url', 'https://wkf.ms/4nhl2Jt', false],
        ];

        foreach ($items as $i => [$es, $en, $icon, $tint, $type, $target, $auth]) {
            Shortcut::updateOrCreate(
                ['label_es' => $es],
                ['label_en' => $en, 'icon' => $icon, 'tint' => $tint, 'target_type' => $type,
                 'target' => $target, 'requires_auth' => $auth, 'sort_order' => $i, 'is_active' => true]
            );
        }

        $this->command->info(count($items) . ' accesos rápidos.');
    }
}
