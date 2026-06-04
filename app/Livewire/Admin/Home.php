<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Nominee;
use App\Models\User;
use App\Models\Vote;
use App\Support\Settings;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Administration')]
class Home extends Component
{
    public function openAllVotes(): void
    {
        $this->authorize('viewAny', Category::class);
        Category::query()->update(['is_active' => true]);
        $this->dispatch('toast', message: 'Tous les votes sont ouverts.');
    }

    public function closeAllVotes(): void
    {
        $this->authorize('viewAny', Category::class);
        Category::query()->update(['is_active' => false]);
        $this->dispatch('toast', message: 'Tous les votes sont fermés.');
    }

    public function toggleHiddenPublic(): void
    {
        $this->authorize('viewAny', Category::class);
        $new = ! Settings::votesHiddenPublic();
        Settings::put('votes_hidden_public', $new ? 1 : 0);
        $this->dispatch('toast', message: $new
            ? 'Les votes sont masqués au public.'
            : 'Les votes sont visibles au public.');
    }

    public function render(): View
    {
        $this->authorize('viewAny', Category::class);

        return view('livewire.admin.home', [
            'totalVotes' => Vote::count(),
            'participants' => Vote::distinct('user_id')->count('user_id'),
            'openCategories' => Category::active()->count(),
            'totalCategories' => Category::count(),
            'totalNominees' => Nominee::count(),
            'totalVoters' => User::whereIn('role', ['eleve', 'professeur'])->count(),
            'hiddenPublic' => Settings::votesHiddenPublic(),
        ]);
    }
}
