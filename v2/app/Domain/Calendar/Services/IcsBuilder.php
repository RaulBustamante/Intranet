<?php

namespace App\Domain\Calendar\Services;

use Carbon\CarbonInterface;

/**
 * Genera un archivo iCalendar (.ics) — el formato estándar que Outlook,
 * Google Calendar y Apple entienden (CAL-07, ROOM-07).
 *
 * Se usa para dos cosas: el feed suscribible del calendario, y el adjunto
 * del correo de confirmación de reserva.
 */
class IcsBuilder
{
    /** @var array<int, array{uid:string, title:string, start:CarbonInterface, end:?CarbonInterface, all_day?:bool, description?:string}> */
    private array $events = [];

    public function __construct(private string $calendarName = 'Ariel Hub') {}

    public function add(string $uid, string $title, CarbonInterface $start, ?CarbonInterface $end = null, bool $allDay = false, string $description = ''): self
    {
        $this->events[] = compact('uid', 'title', 'start', 'end', 'allDay', 'description');

        return $this;
    }

    public function build(): string
    {
        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Ariel Premium Supply//Ariel Hub//ES',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'X-WR-CALNAME:' . $this->escape($this->calendarName),
        ];

        foreach ($this->events as $e) {
            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:' . $e['uid'];
            $lines[] = 'DTSTAMP:' . now()->utc()->format('Ymd\THis\Z');

            if ($e['allDay']) {
                $lines[] = 'DTSTART;VALUE=DATE:' . $e['start']->format('Ymd');
                $end = $e['end'] ?? $e['start']->copy()->addDay();
                $lines[] = 'DTEND;VALUE=DATE:' . $end->format('Ymd');
            } else {
                $lines[] = 'DTSTART:' . $e['start']->utc()->format('Ymd\THis\Z');
                $end = $e['end'] ?? $e['start']->copy()->addHour();
                $lines[] = 'DTEND:' . $end->utc()->format('Ymd\THis\Z');
            }

            $lines[] = 'SUMMARY:' . $this->escape($e['title']);
            if ($e['description'] !== '') {
                $lines[] = 'DESCRIPTION:' . $this->escape($e['description']);
            }
            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        // iCal exige CRLF entre líneas
        return implode("\r\n", $lines) . "\r\n";
    }

    private function escape(string $text): string
    {
        return str_replace(["\\", ';', ',', "\n"], ['\\', '\;', '\,', '\n'], $text);
    }
}
