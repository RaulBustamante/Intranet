<?php

namespace App\Http\Controllers;

use App\Domain\Calendar\Models\HolidayRule;
use App\Domain\Calendar\Models\Event;
use App\Domain\Calendar\Services\IcsBuilder;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Response;

/**
 * Feed iCal suscribible (CAL-07).
 *
 * La URL lleva un token por usuario, así que se puede pegar en Outlook/Google
 * sin exponer nada: no requiere sesión (los clientes de calendario no envían
 * cookies), pero el token identifica y autoriza. Incluye festivos y eventos
 * de empresa del año en curso y el siguiente.
 */
class CalendarFeedController extends Controller
{
    public function __invoke(string $token): Response
    {
        $user = User::where('calendar_token', $token)->firstOrFail();

        $ics = new IcsBuilder('Ariel Hub');
        $años = [now()->year, now()->year + 1];

        foreach ($años as $year) {
            foreach (HolidayRule::where('is_active', true)->get() as $rule) {
                if (! $rule->appliesInYear($year)) {
                    continue;
                }
                $date = $rule->dateFor($year);
                $ics->add(
                    uid: "holiday-{$rule->id}-{$year}@arielhub",
                    title: $rule->name_es,
                    start: $date,
                    allDay: true
                );
            }
        }

        Event::query()
            ->whereBetween('starts_at', [CarbonImmutable::create($años[0], 1, 1), CarbonImmutable::create($años[1], 12, 31)])
            ->get()
            ->each(fn (Event $e) => $ics->add(
                uid: "event-{$e->id}@arielhub",
                title: $e->title_es,
                start: $e->starts_at,
                end: $e->ends_at,
                allDay: $e->all_day,
            ));

        return response($ics->build(), 200, [
            'Content-Type'        => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename="ariel-hub.ics"',
        ]);
    }
}
