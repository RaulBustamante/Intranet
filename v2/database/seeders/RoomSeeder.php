<?php

namespace Database\Seeders;

use App\Domain\People\Models\Location;
use App\Domain\Rooms\Models\Room;
use Illuminate\Database\Seeder;

/**
 * Migra las 3 salas de la v1 y les añade sede, capacidad y equipo
 * (que la v1 no tenía). Idempotente.
 */
class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $wc = Location::where('code', 'WC')->value('id');
        $stl = Location::where('code', '8825')->value('id');

        $salas = [
            ['name' => 'Sala A', 'location_id' => $stl, 'capacity' => 12, 'has_video' => true,  'color' => '#1E90FF'],
            ['name' => 'Sala B', 'location_id' => $stl, 'capacity' => 6,  'has_video' => false, 'color' => '#32CD32'],
            ['name' => 'Sala C', 'location_id' => $wc,  'capacity' => 20, 'has_video' => true,  'color' => '#FF8C00'],
        ];

        foreach ($salas as $i => $s) {
            Room::updateOrCreate(['name' => $s['name']], $s + ['sort_order' => $i, 'is_active' => true]);
        }

        $this->command->info(count($salas) . ' salas de juntas.');
    }
}
