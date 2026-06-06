<?php

namespace App\Livewire\Vote;

use App\Models\Category;
use App\Support\Settings;
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

        // Votes masqués au public : seul l'admin garde l'accès.
        if (Settings::votesHiddenPublic() && ! $user->isAdmin()) {
            return view('livewire.vote.category-list', [
                'hidden' => true,
                'categories' => collect(),
                'votedCategoryIds' => [],
                'votedCount' => 0,
                'totalCount' => 0,
            ]);
        }

        $categories = Category::query()
            ->forVoterTypes($user->votableCategoryTypes())
            ->withCount('votes')
            ->with('nominees')
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        // Une récompense n'est visible qu'avec au moins 2 nominés votables.
        $categories->each(function (Category $cat) {
            $cat->votable_count = $cat->nominees->filter(fn ($n) =>
                $n->is_active && $n->is_approved && $n->is_votable && $n->hasRequiredProof($cat)
            )->count();
        });
        $categories = $categories->filter(fn (Category $c) => $c->votable_count >= 2)->values();

        $votedCategoryIds = $user->votes()->pluck('category_id')->all();

        // On remonte les récompenses où l'utilisateur n'a pas encore voté,
        // et on fait descendre celles déjà votées (tri stable : conserve l'ordre
        // ouvert/nom à l'intérieur de chaque groupe).
        $categories = $categories
            ->sortBy(fn ($c) => in_array($c->id, $votedCategoryIds) ? 1 : 0)
            ->values();

        return view('livewire.vote.category-list', [
            'hidden' => false,
            'categories' => $categories,
            'votedCategoryIds' => $votedCategoryIds,
            'votedCount' => count(array_intersect($votedCategoryIds, $categories->pluck('id')->all())),
            'totalCount' => $categories->count(),
        ]);
    }
}
