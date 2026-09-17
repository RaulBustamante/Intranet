<?php

namespace App\Livewire\Content;

use App\Domain\Content\Models\Bulletin;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BulletinsIndex extends Component
{
    #[Computed]
    public function bulletins()
    {
        return Bulletin::query()
            ->orderByDesc('period_year')->orderByDesc('period_month')
            ->get()->groupBy('period_year');
    }

    public function render()
    {
        return view('livewire.content.bulletins-index')
            ->layout('components.layouts.app', ['title' => __('nav.bulletins')]);
    }
}
