<?php

namespace Database\Seeders;

use App\Domain\Content\Models\Announcement;
use App\Domain\Content\Services\Settings;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** Contenido de muestra para desarrollo (anuncios). No corre en producción. */
class ContentDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $autor = User::where('email', 'rh@localhost')->value('id');

        $demos = [
            ['Nuevo plan de salud 2027', 'Ya está disponible el nuevo esquema de gastos médicos.', true],
            ['Croquetón 2026', 'Nos vemos el sábado en el parque para el croquetón anual.', false],
            ['Noche Mexicana', 'Celebremos las fiestas patrias juntos el 15 de septiembre.', false],
        ];

        foreach ($demos as $i => [$titulo, $extracto, $pin]) {
            Announcement::updateOrCreate(
                ['slug' => Str::slug($titulo)],
                [
                    'title_es' => $titulo, 'title_en' => $titulo,
                    'excerpt_es' => $extracto, 'excerpt_en' => $extracto,
                    'author_id' => $autor, 'is_pinned' => $pin,
                    'published_at' => now()->subDays($i * 2),
                ]
            );
        }

        $this->command->info('3 anuncios de muestra.');
    }
}
