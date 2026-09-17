<?php

namespace App\Http\Controllers;

use App\Domain\Calendar\Services\CalendarService;
use App\Domain\Content\Models\Announcement;
use App\Domain\Content\Models\Bulletin;
use App\Domain\Content\Models\Document;
use App\Domain\Content\Models\Link;
use App\Domain\People\Models\Employee;
use App\Domain\Rooms\Models\Room;
use App\Domain\Rooms\Models\RoomBooking;
use Carbon\CarbonImmutable;
use Illuminate\View\View;

/**
 * Página de inicio personalizada (COM-11), ahora con datos REALES.
 *
 * Todo lo que en la Fase 1 eran datos de muestra, aquí sale de la base:
 * cumpleaños de hoy, mis reservas y solicitudes, anuncios vivos, y los
 * próximos festivos calculados por regla.
 */
class HomeController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $empleado = $user?->employee;
        $today = CarbonImmutable::now();

        $hora = (int) $today->format('G');
        $saludo = match (true) {
            $hora < 12 => 'morning',
            $hora < 19 => 'afternoon',
            default    => 'evening',
        };

        // Cumpleaños de hoy (CAL-03), derivados de employees
        $cumpleanos = Employee::query()->active()
            ->birthdayOn($today->month, $today->day)
            ->with('department:id,name_es,name_en')
            ->get()
            ->map(fn (Employee $e) => ['nombre' => $e->display_name, 'depto' => $e->department?->name ?? '—']);

        // Mi día: mis reservas de hoy
        $miDia = collect();
        if ($empleado) {
            RoomBooking::confirmed()
                ->where('employee_id', $empleado->id)
                ->whereBetween('starts_at', [$today->startOfDay(), $today->endOfDay()])
                ->with('room')->orderBy('starts_at')->get()
                ->each(fn ($b) => $miDia->push([
                    'hora' => $b->starts_at->format('H:i'),
                    'texto' => $b->title . ' · ' . $b->room?->name,
                    'estado' => 'ok',
                ]));
        }

        // Últimos anuncios visibles (COM-03)
        $anuncios = Announcement::query()->live()
            ->orderByDesc('is_pinned')->orderByDesc('published_at')
            ->with('audiences', 'author')->limit(6)->get()
            ->filter(fn ($a) => $a->visibleTo($empleado))
            ->take(3)
            ->map(fn ($a) => ['titulo' => $a->title, 'autor' => $a->author?->display_name ?? 'Ariel', 'cuando' => $a->published_at?->locale(app()->getLocale())->diffForHumans()]);

        // Próximos festivos, del servicio cacheado
        $proximos = app(\App\Domain\Calendar\Services\HolidayService::class)->upcoming(4)
            ->map(fn ($h) => ['fecha' => $h['date']->locale(app()->getLocale())->isoFormat('D MMM'), 'texto' => $h['name'], 'tipo' => 'festivo']);

        return view('pages.home', [
            'saludo'     => $saludo,
            'nombre'     => $empleado?->first_name ?? $user?->name ?? 'Ariel',
            'sede'       => $empleado?->location?->name ?? 'Ariel',
            'cumpleanos' => $cumpleanos,
            'miDia'      => $miDia,
            'anuncios'   => $anuncios,
            'ultimoBoletin' => Bulletin::query()->orderByDesc('period_year')->orderByDesc('period_month')->first(),
            'accesos' => [
                ['key' => 'directory', 'icon' => 'users', 'dato' => __('app.stats.people', ['count' => Employee::active()->count()])],
                ['key' => 'rooms',     'icon' => 'door',  'dato' => __('app.stats.rooms_free', ['count' => Room::where('is_active', true)->count()])],
                ['key' => 'documents', 'icon' => 'file',  'dato' => __('app.stats.documents', ['count' => Document::count()])],
                ['key' => 'calendar',  'icon' => 'calendar', 'dato' => ''],
                ['key' => 'bulletins', 'icon' => 'news',  'dato' => ''],
                ['key' => 'links',     'icon' => 'link',  'dato' => __('app.stats.apps', ['count' => Link::where('is_active', true)->count()])],
            ],
            'proximos' => $proximos,
        ]);
    }
}
