<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cierra la sesión de una cuenta que fue desactivada mientras estaba dentro.
 *
 * Resuelve la parte "revoca el acceso al instante" de AUTH-08: dar de baja a
 * alguien no espera a que caduque su sesión; su siguiente petición lo saca.
 */
class EnsureActiveUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isActive()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => __('auth.account_disabled')]);
        }

        return $next($request);
    }
}
