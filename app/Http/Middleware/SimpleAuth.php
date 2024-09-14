<?php

namespace App\Http\Middleware;

use Closure;

class SimpleAuth
{
    public function handle($request, Closure $next)
{
    // Verificar si el usuario está autenticado
    if (!$request->session()->has('authenticated')) {
        // Si no está autenticado, guardar la URL que estaba intentando visitar
        $request->session()->put('redirect_to', $request->fullUrl());

        // Redirigir al login
        return redirect()->route('login');
    }

    return $next($request);
}

}
