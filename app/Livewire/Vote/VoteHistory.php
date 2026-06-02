<?php

namespace App\Livewire\Vote;

use App\Models\Vote;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.vote')]
#[Title('Mes votes')]
class VoteHistory extends Component
{
    public function render(): View
    {
        $votes = Vote::query()
            ->where('user_id', auth()->id())
            ->with(['category', 'nominee'])
            ->latest()
            ->get();

        return view('livewire.vote.vote-history', [
            'votes' => $votes,
        ]);
    }
}
