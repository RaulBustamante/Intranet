<?php

namespace Database\Seeders;

use App\Domain\Content\Models\Poll;
use Illuminate\Database\Seeder;

class PollDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $poll = Poll::updateOrCreate(
            ['question_es' => '¿Qué actividad prefieres para el próximo evento?'],
            ['question_en' => 'Which activity do you prefer for the next event?', 'is_active' => true]
        );

        if ($poll->options()->count() === 0) {
            foreach ([['Comida al aire libre', 'Outdoor lunch'], ['Torneo deportivo', 'Sports tournament'], ['Noche de juegos', 'Game night']] as $i => [$es, $en]) {
                $poll->options()->create(['label_es' => $es, 'label_en' => $en, 'sort_order' => $i]);
            }
        }

        $this->command->info('Encuesta de muestra.');
    }
}
