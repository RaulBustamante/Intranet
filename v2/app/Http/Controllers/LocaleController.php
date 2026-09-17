<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    /**
     * Cambia el idioma activo y regresa a donde estaba el usuario.
     *
     * En la Fase 2, cuando existan cuentas, la preferencia se guardará en
     * users.locale y la sesión dejará de ser la fuente de verdad.
     */
    public function switch(string $locale): RedirectResponse
    {
        if (in_array($locale, config('app.supported_locales', ['es', 'en']), true)) {
            session(['locale' => $locale]);
        }

        return back();
    }
}
