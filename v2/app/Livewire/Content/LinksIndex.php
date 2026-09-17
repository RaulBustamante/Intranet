<?php

namespace App\Livewire\Content;

use App\Domain\Content\Models\Link;
use Livewire\Attributes\Computed;
use Livewire\Component;

class LinksIndex extends Component
{
    #[Computed]
    public function groups()
    {
        return Link::where('is_active', true)
            ->orderBy('sort_order')->orderBy('title_es')->get()
            ->groupBy(fn ($l) => $l->category ?: 'general');
    }

    public function render()
    {
        return view('livewire.content.links-index')
            ->layout('components.layouts.app', ['title' => __('nav.links')]);
    }
}
