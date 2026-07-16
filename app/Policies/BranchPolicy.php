<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('branch.view');
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->can('branch.view');
    }

    public function create(User $user): bool
    {
        return $user->can('branch.create');
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->can('branch.edit');
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->can('branch.delete');
    }

    public function restore(User $user, Branch $branch): bool
    {
        return $user->can('branch.delete');
    }
}
