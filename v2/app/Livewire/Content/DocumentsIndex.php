<?php

namespace App\Livewire\Content;

use App\Domain\Content\Models\Document;
use App\Domain\Content\Models\DocumentCategory;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DocumentsIndex extends Component
{
    public string $search = '';
    public ?int $categoryId = null;

    #[Computed]
    public function categories()
    {
        return DocumentCategory::where('is_active', true)
            ->orderBy('sort_order')->get()
            ->filter(fn ($c) => $c->visibleTo(auth()->user()));
    }

    #[Computed]
    public function documents()
    {
        $visibles = $this->categories->pluck('id');

        return Document::query()
            ->whereIn('category_id', $visibles)
            ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
            ->when($this->search !== '', fn ($q) => $q->where(fn ($w) =>
                $w->where('title_es', 'like', "%{$this->search}%")->orWhere('title_en', 'like', "%{$this->search}%")))
            ->with('category')
            ->orderByDesc('published_at')->get();
    }

    public function render()
    {
        return view('livewire.content.documents-index')
            ->layout('components.layouts.app', ['title' => __('nav.documents')]);
    }
}
