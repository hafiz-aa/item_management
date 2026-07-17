<?php

namespace App\Policies;

use App\Models\MasterItem;
use App\Models\User;

class ItemDescriptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('item-description.view');
    }

    public function view(User $user, MasterItem $itemDescription): bool
    {
        return $user->can('item-description.view');
    }

    public function create(User $user): bool
    {
        return $user->can('item-description.create');
    }

    public function update(User $user, MasterItem $itemDescription): bool
    {
        return $user->can('item-description.edit');
    }

    public function delete(User $user, MasterItem $itemDescription): bool
    {
        return $user->can('item-description.delete');
    }
}
