<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Resuelve el idioma de la petición (UX-06).
     *
     * Prioridad:
     *   1. Lo que el usuario eligió explícitamente (sesión; en Fase 2, users.locale)
     *   2. Lo que pide el navegador, si es uno de los soportados
     *   3. El valor por defecto de la aplicación
     */
    public function handle(Request $request, Closure $next): Response
    {
        $soportados = config('app.supported_locales', ['es', 'en']);

        $locale = session('locale');

        if (! in_array($locale, $soportados, true)) {
            $locale = $request->getPreferredLanguage($soportados) ?: config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
