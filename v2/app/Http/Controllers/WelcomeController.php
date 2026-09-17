<?php

namespace App\Http\Controllers;

use App\Domain\Content\Models\Shortcut;
use Illuminate\View\View;

/**
 * Landing pública de bienvenida (la "cara" de la intranet).
 *
 * A diferencia del resto de la v2, esta página NO exige sesión: un empleado
 * que entra ve información general, el video institucional y accesos rápidos.
 * Los accesos a secciones privadas (directorio, salas, solicitudes) llevan al
 * login cuando hace falta; los públicos (soporte, enlaces externos, buzón de
 * sugerencias) abren directo.
 *
 * Es lo colorido/visual que reemplaza el login seco como primera pantalla.
 */
class WelcomeController extends Controller
{
    public function index(): View
    {
        // Próximo festivo (dato general, sin PII), del servicio cacheado
        $proximo = app(\App\Domain\Calendar\Services\HolidayService::class)->upcoming(1)->first();

        return view('pages.welcome', [
            'proximoFestivo' => $proximo,
            // Accesos rápidos, administrables por RH desde el panel (BD)
            'accesos' => Shortcut::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
}
