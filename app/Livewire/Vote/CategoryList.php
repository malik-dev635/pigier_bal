<?php

namespace App\Livewire\Vote;

use App\Models\Category;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.vote')]
#[Title('Voter')]
class CategoryList extends Component
{
    public function render(): View
    {
        $user = auth()->user();

        $categories = Category::query()
            ->forVoterTypes($user->votableCategoryTypes())
            ->withCount(['votes', 'nominees'])
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        $votedCategoryIds = $user->votes()->pluck('category_id')->all();

        // On remonte les récompenses où l'utilisateur n'a pas encore voté,
        // et on fait descendre celles déjà votées (tri stable : conserve l'ordre
        // ouvert/nom à l'intérieur de chaque groupe).
        $categories = $categories
            ->sortBy(fn ($c) => in_array($c->id, $votedCategoryIds) ? 1 : 0)
            ->values();

        return view('livewire.vote.category-list', [
            'categories' => $categories,
            'votedCategoryIds' => $votedCategoryIds,
            'votedCount' => count(array_intersect($votedCategoryIds, $categories->pluck('id')->all())),
            'totalCount' => $categories->count(),
        ]);
    }
}
