<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** La landing publica de bienvenida responde a cualquiera, sin sesion. */
    public function test_la_landing_publica_responde(): void
    {
        $this->get("/")->assertOk()->assertSee(__("welcome.quick_access"));
    }

    /** El inicio es navegable sin sesion (red interna); solo se personaliza si hay usuario. */
    public function test_el_inicio_es_publico(): void
    {
        $this->get(route("dashboard"))->assertOk();
    }
}
