<?php

namespace App\Livewire\Vote;

use App\Models\Category;
use App\Models\Nominee;
use App\Models\Vote;
use Illuminate\Database\QueryException;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.vote')]
class CategoryVote extends Component
{
    public Category $category;

    /** Nominé choisi par l'utilisateur (null si pas encore voté). */
    public ?int $votedNomineeId = null;

    public function mount(Category $category): void
    {
        $user = auth()->user();

        // Vérification serveur du type de votant.
        abort_unless($user->can('participate', $category), 403);

        $this->category = $category;
        $this->votedNomineeId = $user->votes()
            ->where('category_id', $category->id)
            ->value('nominee_id');
    }

    public function vote(int $nomineeId): void
    {
        $user = auth()->user();

        // Gate métier centrale (côté serveur) : type, ouverture, unicité.
        if (! $user->can('create', [Vote::class, $this->category])) {
            $this->dispatch('vote-error', message: "Ce vote n'est pas autorisé (catégorie clôturée, déjà voté, ou non concerné).");
            return;
        }

        // Le nominé doit appartenir à la catégorie et être éligible (preuve incluse).
        $nominee = $this->category->votableNominees()->whereKey($nomineeId)->first();

        if (! $nominee instanceof Nominee) {
            $this->dispatch('vote-error', message: 'Nominé invalide ou non éligible au vote.');
            return;
        }

        try {
            Vote::create([
                'user_id' => $user->id,
                'category_id' => $this->category->id,
                'nominee_id' => $nominee->id,
                'ip_address' => request()->ip(),
            ]);
        } catch (QueryException $e) {
            // Contrainte UNIQUE(user_id, category_id) : déjà voté.
            $this->votedNomineeId = $user->votes()
                ->where('category_id', $this->category->id)
                ->value('nominee_id');
            $this->dispatch('vote-error', message: 'Vous avez déjà voté dans cette catégorie.');
            return;
        }

        $this->votedNomineeId = $nominee->id;
        $this->dispatch('vote-success', message: 'Votre vote a bien été enregistré. Merci !');
    }

    public function render(): View
    {
        $nominees = $this->category->votableNominees()
            ->withCount('votes')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('livewire.vote.category-vote', [
            'nominees' => $nominees,
            'hasVoted' => ! is_null($this->votedNomineeId),
            'isOpen' => $this->category->is_active,
        ]);
    }
}
