<?php

namespace App\Policies;

use App\Models\ItemDescription;
use App\Models\User;

class ItemDescriptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('item-description.view');
    }

    public function view(User $user, ItemDescription $itemDescription): bool
    {
        return $user->can('item-description.view');
    }

    public function create(User $user): bool
    {
        return $user->can('item-description.create');
    }

    public function update(User $user, ItemDescription $itemDescription): bool
    {
        return $user->can('item-description.edit');
    }

    public function delete(User $user, ItemDescription $itemDescription): bool
    {
        return $user->can('item-description.delete');
    }
}
