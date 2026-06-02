<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * L'admin a tous les droits de gestion.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin() && $ability !== 'participate') {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Category $category): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Category $category): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->isAdmin();
    }

    public function toggle(User $user, Category $category): bool
    {
        return $user->isAdmin();
    }

    public function resetVotes(User $user, Category $category): bool
    {
        return $user->isAdmin();
    }

    /**
     * L'utilisateur peut-il voir/participer à cette catégorie sur /vote ?
     * (Vérification du type de votant — côté serveur.)
     */
    public function participate(User $user, Category $category): bool
    {
        return in_array($category->voter_type, $user->votableCategoryTypes(), true);
    }
}
