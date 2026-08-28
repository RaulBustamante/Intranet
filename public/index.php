<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Compatibilidad de version de PHP
|--------------------------------------------------------------------------
|
| Laravel 8.83 es anterior a PHP 8.4, que deprecó los parámetros nullable
| implícitos. Sin esta linea, cada request imprime cientos de avisos
| "Deprecated" al inicio del HTML (antes de que Laravel arranque su manejador
| de errores), lo que corrompe la respuesta. Ver tambien el archivo artisan.
|
*/

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
| Precargar el stack de logging mientras E_DEPRECATED sigue apagado.
| Ver la explicacion completa en el archivo artisan: sin esto, en PHP 8.4 se
| filtran avisos "Deprecated" al inicio del HTML de cada request.
*/
class_exists(\Monolog\Logger::class);
class_exists(\Illuminate\Log\Logger::class);
class_exists(\Illuminate\Log\LogManager::class);

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
