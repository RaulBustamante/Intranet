<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\PlaceholderController;
use App\Livewire\Admin\DocumentManager;
use App\Livewire\Admin\EmployeeManager;
use App\Livewire\Admin\ShortcutManager;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\LoginForm;
use App\Livewire\Auth\ResetPassword;
use App\Http\Controllers\CalendarFeedController;
use App\Livewire\Calendar\CalendarView;
use App\Livewire\Calendar\EventManager;
use App\Livewire\Rooms\RoomScheduler;
use App\Livewire\Content\AnnouncementsIndex;
use App\Livewire\Content\BulletinsIndex;
use App\Livewire\Content\DocumentsIndex;
use App\Livewire\Content\KudosWall;
use App\Livewire\Content\LinksIndex;
use App\Livewire\Directory\DirectoryIndex;
use App\Livewire\Requests\RequestsIndex;

/*
|--------------------------------------------------------------------------
| Rutas web — Ariel Hub v2
|--------------------------------------------------------------------------
|
| La cara de la intranet es una landing PÚBLICA de bienvenida (/). El contenido
| sensible —directorio con datos de empleados, salas, solicitudes— exige sesión.
| Un empleado nuevo entra, ve información general, y se firma cuando lo necesita.
|
*/

// --- Landing pública de bienvenida -----------------------------------------
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// --- Idioma (público: hace falta para poder elegir idioma en el login) -----
Route::get('/idioma/{locale}', [LocaleController::class, 'switch'])
    ->whereIn('locale', ['es', 'en'])
    ->name('locale.switch');

// --- Autenticación (solo para invitados) -----------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', LoginForm::class)->name('login');
    Route::get('/recuperar', ForgotPassword::class)->name('password.request');
    Route::get('/restablecer/{token}', ResetPassword::class)->name('password.reset');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

// --- Zona pública de navegación --------------------------------------------
// Toda la intranet se puede NAVEGAR sin firmarse (es una red privada interna):
// directorio, calendario, boletines, documentos, enlaces, salas, solicitudes…
// se ven en modo lectura. Lo que exige cuenta es CAPTURAR: enviar una
// solicitud, reservar una sala o publicar un kudo. Ese candado vive dentro de
// cada componente (redirige al login al intentar la acción), no en la ruta, para
// que el invitado pueda mirar todo con normalidad.
Route::group([], function () {

    // Inicio (el "hub"; personalizado si hay sesión, general si no)
    Route::get('/inicio', [HomeController::class, 'index'])->name('dashboard');

    // Directorio (PPL-04, PPL-06, PPL-07)
    Route::get('/directory', DirectoryIndex::class)->name('directory');
    Route::get('/directory/{employee}', [DirectoryController::class, 'show'])->name('directory.show');

    // Calendario (CAL-01, CAL-03, CAL-06)
    Route::get('/calendar', CalendarView::class)->name('calendar');

    // Contenido (Fase 4)
    Route::get('/documents', DocumentsIndex::class)->name('documents');
    Route::get('/bulletins', BulletinsIndex::class)->name('bulletins');
    Route::get('/links', LinksIndex::class)->name('links');
    Route::get('/announcements', AnnouncementsIndex::class)->name('announcements');
    Route::get('/kudos', KudosWall::class)->name('kudos');            // ver muro: público; publicar: exige sesión
    Route::get('/requests', RequestsIndex::class)->name('requests');  // ver tipos: público; enviar: exige sesión

    // Busqueda global (SRCH-01) — alimenta la paleta Cmd+K
    Route::get('/buscar', App\Http\Controllers\SearchController::class)->name('search');

    // Salas de juntas (ROOM-01, ROOM-04, ROOM-05) — ver disponibilidad: público; reservar: exige sesión
    Route::get('/rooms', RoomScheduler::class)->name('rooms');

    // Secciones pendientes de construir. Cada una dice en qué fase llega.
    $pendientes = [
        'gallery' => 4, 'iso' => 4, 'about' => 4,
    ];
    foreach ($pendientes as $key => $fase) {
        Route::get('/' . $key, [PlaceholderController::class, 'show'])
            ->defaults('key', $key)->defaults('phase', $fase)->name($key);
    }
});

// --- Zona autenticada: solo lo estrictamente personal exige cuenta activa ----
Route::middleware(['auth', 'active'])->group(function () {

    // Perfil y conexiones de cuenta externa (AUTH-09)
    Route::get('/mi-perfil', App\Livewire\Profile\MyProfile::class)->name('profile.edit');
    Route::get('/conexiones', \App\Livewire\Profile\Connections::class)->name('profile.connections');

    // --- Administración: cada área exige su rol (HRADM-06) ------------------
    Route::prefix('admin')->name('admin.')->group(function () {
        // Panel de RH (HRADM-01). Invisible e inaccesible sin rol.
        Route::get('/people', EmployeeManager::class)
            ->middleware('role:hr_editor|admin')->name('people');

        Route::get('/import', App\Livewire\Admin\CsvImport::class)
            ->middleware('role:hr_editor|admin')->name('import');

        Route::get('/catalogs', [PlaceholderController::class, 'show'])
            ->middleware('role:hr_editor|admin')
            ->defaults('key', 'admin_catalogs')->defaults('phase', 2)->name('catalogs');

        // Gestor de documentos: subir + versionar (DOC-03, DOC-04)
        Route::get('/documents', DocumentManager::class)
            ->middleware('role:content_editor|admin')->name('documents');

        Route::get('/content', [PlaceholderController::class, 'show'])
            ->middleware('role:content_editor|admin')
            ->defaults('key', 'admin_content')->defaults('phase', 4)->name('content');

        // Accesos rapidos de la landing (content_editor/admin)
        Route::get('/shortcuts', ShortcutManager::class)
            ->middleware('role:content_editor|admin')->name('shortcuts');

        // Eventos de empresa (CAL-05)
        Route::get('/events', EventManager::class)
            ->middleware('role:content_editor|admin')->name('events');

        Route::get('/settings', [PlaceholderController::class, 'show'])
            ->middleware('role:admin')
            ->defaults('key', 'admin_settings')->defaults('phase', 4)->name('settings');

        Route::get('/audit', [PlaceholderController::class, 'show'])
            ->middleware('role:admin')
            ->defaults('key', 'admin_audit')->defaults('phase', 2)->name('audit');
    });
});

// --- Feed iCal (público con token por usuario, para Outlook/Google) --------
Route::get('/calendario/feed/{token}', CalendarFeedController::class)->name('calendar.feed');
