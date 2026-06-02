<?php

namespace App\Policies;

use App\Models\Nominee;
use App\Models\User;

class NomineePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Nominee $nominee): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Nominee $nominee): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Nominee $nominee): bool
    {
        return $user->isAdmin();
    }
}
