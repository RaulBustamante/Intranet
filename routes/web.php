<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return view('welcome');});
Route::get('/calendar', function () { return view('calendar');});
Route::get('/header', function () { return view('header');});
Route::get('/sidebar', function () { return view('sidebar');});
Route::get('/calendars', function () { return view('calendars');});
Route::get('/document', function () { return view('document');});
Route::get('/directory', function () { return view('directory');});
Route::get('/boletines', function () { return view('boletines');});
Route::get('/iso', function () { return view('iso');});
Route::get('/enlaces', function () { return view('enlaces');});
Route::get('/aboutus', function () { return view('aboutus');});
Route::get('/gallery', function () { return view('gallery');});
Route::get('/humanResources', function () { return view('humanResources');});
Route::get('/birthdays', function () { return view('birthdays');});

use App\Http\Controllers\ReservationController;

Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservationsummary', [ReservationController::class, 'summary'])->name('reservations.summary');

Route::get('/db-check', function () {
    try {
        DB::connection()->getPdo();
        return 'Conexión a la base de datos exitosa';
    } catch (\Exception $e) {
        return 'No se pudo conectar a la base de datos. Error: ' . $e->getMessage();
    }
});


