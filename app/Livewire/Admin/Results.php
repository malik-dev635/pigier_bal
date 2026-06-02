<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Résultats')]
class Results extends Component
{
    public function resetVotes(int $categoryId): void
    {
        $category = Category::findOrFail($categoryId);
        $this->authorize('resetVotes', $category);

        $category->votes()->delete();

        $this->dispatch('toast', message: 'Votes réinitialisés pour « '.$category->name.' ».');
    }

    public function render(): View
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::query()
            ->with(['nominees' => function ($q) {
                $q->withCount('votes')->orderByDesc('votes_count');
            }])
            ->withCount('votes')
            ->orderBy('voter_type')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.results', [
            'categories' => $categories,
        ]);
    }
}
