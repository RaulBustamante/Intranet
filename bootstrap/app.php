<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Compatibilidad de version de PHP
|--------------------------------------------------------------------------
|
| artisan y public/index.php apagan E_DEPRECATED antes del autoloader, pero el
| bootstrapper HandleExceptions restaura error_reporting(-1). Todo lo que se
| autocarga despues (Collections, Eloquent, el canal flare de Ignition) vuelve a
| disparar avisos de PHP 8.4, y como saltan mientras Laravel ya esta dentro de su
| manejador de errores, PHP no re-entra y los imprime el manejador nativo: en la
| consola de "artisan serve". Reapagarlo aqui cierra esa ventana apenas se abre;
| AppServiceProvider::register llega varios providers tarde.
|
*/

$app->afterBootstrapping(
    Illuminate\Foundation\Bootstrap\HandleExceptions::class,
    function () {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
    }
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
