<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VideoController;

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

Route::get('/', function () { return view('welcome');})->name('welcome');
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


// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por el middleware simpleauth
Route::middleware('simpleauth')->group(function () {
    
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    // Ruta para mostrar el formulario de subida de archivos
    Route::get('/upload-video', [VideoController::class, 'showUploadForm'])->name('upload.form');

});

Route::get('/reservationsummary', [ReservationController::class, 'summary'])->name('reservations.summary');

Route::get('/video', function (Illuminate\Http\Request $request) {
    $videoTitle = $request->input('title');
    $videoPath = $request->input('path');
    return view('video', ['videoTitle' => $videoTitle, 'videoPath' => $videoPath]);
})->name('video');

Route::get('/gallery', [VideoController::class, 'showGallery'])->name('gallery');
Route::get('/boletines', [VideoController::class, 'showBoletines'])->name('boletines');
Route::get('/document', [VideoController::class, 'showDocumentos'])->name('document');
Route::post('/upload-galeria', [VideoController::class, 'uploadGaleria'])->name('upload.galeria');
Route::post('/upload-boletines', [VideoController::class, 'uploadBoletines'])->name('upload.boletines');
Route::post('/upload-documentos', [VideoController::class, 'uploadDocumentos'])->name('upload.documentos');

// Ruta para mostrar la página de reproducción de video
Route::get('/video', [VideoController::class, 'showVideo'])->name('video');






