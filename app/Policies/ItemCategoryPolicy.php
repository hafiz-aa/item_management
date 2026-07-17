<?php

namespace App\Policies;

use App\Models\ItemCategory;
use App\Models\User;

class ItemCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('item-category.view');
    }

    public function view(User $user, ItemCategory $itemCategory): bool
    {
        return $user->can('item-category.view');
    }

    public function create(User $user): bool
    {
        return $user->can('item-category.create');
    }

    public function update(User $user, ItemCategory $itemCategory): bool
    {
        return $user->can('item-category.edit');
    }

    public function delete(User $user, ItemCategory $itemCategory): bool
    {
        return $user->can('item-category.delete');
    }
}
