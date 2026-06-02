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
#[Title('Administration')]
class Home extends Component
{
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
        ]);
    }
}
