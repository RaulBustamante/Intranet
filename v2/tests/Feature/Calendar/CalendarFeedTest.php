<?php

namespace Tests\Feature\Calendar;

use App\Models\User;
use Database\Seeders\HolidayRuleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_feed_ical_genera_un_calendario_valido_con_festivos(): void
    {
        $this->seed(HolidayRuleSeeder::class);
        $user = User::create(['name' => 'X', 'email' => 'x@arielpremium.com', 'password' => bcrypt('x'), 'is_active' => true, 'calendar_token' => 'token-de-prueba-123']);

        $res = $this->get(route('calendar.feed', 'token-de-prueba-123'));

        $res->assertOk();
        $res->assertHeader('Content-Type', 'text/calendar; charset=utf-8');
        $this->assertStringContainsString('BEGIN:VCALENDAR', $res->getContent());
        $this->assertStringContainsString('SUMMARY:Navidad', $res->getContent());
        $this->assertStringContainsString('END:VCALENDAR', $res->getContent());
    }

    public function test_el_feed_rechaza_un_token_invalido(): void
    {
        $this->get(route('calendar.feed', 'token-que-no-existe'))->assertNotFound();
    }
}
