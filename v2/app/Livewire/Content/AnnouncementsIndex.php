<?php

namespace App\Livewire\Content;

use App\Domain\Content\Models\Announcement;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AnnouncementsIndex extends Component
{
    #[Computed]
    public function announcements()
    {
        $employee = auth()->user()?->employee;   // invitado: null → solo ve lo público

        return Announcement::query()->live()
            ->orderByDesc('is_pinned')->orderByDesc('published_at')
            ->with('audiences', 'author')
            ->get()
            ->filter(fn ($a) => $a->visibleTo($employee))   // COM-03
            ->values();
    }

    public function render()
    {
        return view('livewire.content.announcements-index')
            ->layout('components.layouts.app', ['title' => __('nav.announcements')]);
    }
}
