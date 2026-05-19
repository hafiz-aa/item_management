<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse;

class WarehousePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('warehouse.manage');
    }

    public function view(User $user, Warehouse $warehouse): bool
    {
        if (!$user->can('warehouse.manage')) {
            return false;
        }

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->warehouses->contains('id', $warehouse->id);
    }

    public function create(User $user): bool
    {
        return $user->can('warehouse.manage');
    }

    public function update(User $user, Warehouse $warehouse): bool
    {
        return $user->can('warehouse.manage');
    }

    public function delete(User $user, Warehouse $warehouse): bool
    {
        return $user->can('warehouse.manage');
    }
}
