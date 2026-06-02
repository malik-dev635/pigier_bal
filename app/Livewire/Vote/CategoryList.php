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

        return view('livewire.vote.category-list', [
            'categories' => $categories,
            'votedCategoryIds' => $votedCategoryIds,
        ]);
    }
}
