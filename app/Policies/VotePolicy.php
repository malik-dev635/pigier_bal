<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class VotePolicy
{
    /**
     * L'utilisateur peut consulter l'historique de ses propres votes.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Règle métier centrale (côté serveur) : l'utilisateur peut-il voter
     * dans cette catégorie ?
     *
     *  - le type de votant doit correspondre (eleve / professeur / both)
     *  - la catégorie doit être ouverte (is_active)
     *  - l'utilisateur ne doit pas avoir déjà voté dans cette catégorie
     */
    public function create(User $user, Category $category): bool
    {
        if (! in_array($category->voter_type, $user->votableCategoryTypes(), true)) {
            return false;
        }

        if (! $category->is_active) {
            return false;
        }

        return ! $user->hasVotedIn($category->id);
    }
}
