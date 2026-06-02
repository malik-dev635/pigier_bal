<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Nominee;
use App\Models\User;
use App\Models\Vote;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Tableau de bord')]
class VoteDashboard extends Component
{
    public function render(): View
    {
        $this->authorize('viewAny', Category::class);

        $totalVotes = Vote::count();
        $totalCategories = Category::count();
        $openCategories = Category::active()->count();
        $totalNominees = Nominee::count();
        $totalVoters = User::whereIn('role', ['eleve', 'professeur'])->count();
        $participants = Vote::distinct('user_id')->count('user_id');

        $categories = Category::query()
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->orderBy('name')
            ->get();

        $maxVotes = max(1, $categories->max('votes_count') ?? 0);

        // Top nominé par catégorie.
        $topNominees = Nominee::query()
            ->select('nominees.*')
            ->withCount('votes')
            ->whereIn('category_id', $categories->pluck('id'))
            ->orderByDesc('votes_count')
            ->get()
            ->groupBy('category_id')
            ->map->first();

        return view('livewire.admin.vote-dashboard', [
            'totalVotes' => $totalVotes,
            'totalCategories' => $totalCategories,
            'openCategories' => $openCategories,
            'totalNominees' => $totalNominees,
            'totalVoters' => $totalVoters,
            'participants' => $participants,
            'categories' => $categories,
            'maxVotes' => $maxVotes,
            'topNominees' => $topNominees,
        ]);
    }
}
