<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // artisan y public/index.php ya apagan E_DEPRECATED antes del autoloader,
        // pero el bootstrapper HandleExceptions de Laravel vuelve a poner
        // error_reporting(-1), asi que las clases que se cargan despues (Monolog,
        // Illuminate\Log\Logger) reabren el chorro de avisos en PHP 8.4+.
        // Volver a apagarlo aqui lo deja cerrado durante todo el ciclo de vida.
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
