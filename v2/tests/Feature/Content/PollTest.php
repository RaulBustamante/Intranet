<?php

namespace Tests\Feature\Content;

use App\Domain\Content\Models\Poll;
use App\Domain\People\Models\Employee;
use App\Livewire\Content\PollWidget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PollTest extends TestCase
{
    use RefreshDatabase;

    private function userEmp(): array
    {
        $emp = Employee::create(['first_name' => 'A', 'last_name' => 'A', 'email' => 'a'.uniqid().'@arielpremium.com']);
        $user = User::create(['name' => 'A', 'email' => 'a'.uniqid().'@arielpremium.com', 'password' => bcrypt('x'), 'is_active' => true, 'employee_id' => $emp->id]);

        return [$user, $emp];
    }

    private function poll(): Poll
    {
        $p = Poll::create(['question_es' => 'Q', 'question_en' => 'Q', 'is_active' => true]);
        $p->options()->create(['label_es' => 'A', 'label_en' => 'A']);
        $p->options()->create(['label_es' => 'B', 'label_en' => 'B']);

        return $p->fresh('options');
    }

    public function test_un_empleado_vota_y_ve_el_resultado(): void
    {
        [$user] = $this->userEmp();
        $poll = $this->poll();

        Livewire::actingAs($user)->test(PollWidget::class)
            ->set('optionId', $poll->options->first()->id)
            ->call('vote')
            ->assertSet('hasVoted', true);

        $this->assertDatabaseHas('poll_votes', ['poll_id' => $poll->id, 'employee_id' => $user->employee_id]);
    }

    public function test_no_puede_votar_dos_veces(): void
    {
        [$user] = $this->userEmp();
        $poll = $this->poll();
        $opt = $poll->options->first()->id;

        Livewire::actingAs($user)->test(PollWidget::class)->set('optionId', $opt)->call('vote');
        // Segundo intento
        Livewire::actingAs($user)->test(PollWidget::class)->set('optionId', $poll->options->last()->id)->call('vote');

        $this->assertDatabaseCount('poll_votes', 1);   // solo un voto
    }
}
