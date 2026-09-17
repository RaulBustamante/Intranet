<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PlaceholderController extends Controller
{
    /**
     * Marcador honesto para las secciones que todavía no se construyen.
     *
     * La alternativa —dejar el enlace fuera del menú hasta que exista— hace
     * que la navegación cambie de forma en cada fase y que nadie pueda
     * revisar la estructura completa. Aquí el enlace existe, lleva a algo
     * con el diseño real, y dice en qué fase llega lo de verdad.
     */
    public function show(Request $request): View
    {
        return view('pages.placeholder', [
            'key'   => $request->route()->defaults['key'] ?? 'home',
            'phase' => $request->route()->defaults['phase'] ?? null,
        ]);
    }
}
