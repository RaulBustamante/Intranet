<?php

namespace App\Domain\Content\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Ajustes de la intranet (COM-06, COM-07): banner de aviso urgente y tema de
 * temporada, editables desde configuración sin desplegar código.
 *
 * Esto es lo que reemplaza las 5 copias de welcome.blade.php de la v1: en vez
 * de duplicar la página para cambiar el tema, se cambia un ajuste.
 */
class Settings
{
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = Cache::rememberForever('settings.all', function () {
            return DB::table('settings')->pluck('value', 'key')
                ->map(fn ($v) => json_decode($v, true))->all();
        });

        return $all[$key] ?? $default;
    }

    public static function set(string $key, mixed $value, ?int $userId = null): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => json_encode($value), 'updated_by' => $userId, 'updated_at' => now()]
        );
        Cache::forget('settings.all');
    }

    /** El aviso urgente que se pinta en la barra superior, si está activo. */
    public static function banner(): ?array
    {
        $b = self::get('banner');

        return ($b && ($b['active'] ?? false)) ? $b : null;
    }

    public static function seasonTheme(): string
    {
        return self::get('season_theme', 'default');
    }
}
