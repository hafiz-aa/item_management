<?php

namespace App\Policies;

use App\Models\ItemHeader;
use App\Models\User;

class ItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('item.view');
    }

    public function view(User $user, ItemHeader $item): bool
    {
        if (! $user->can('item.view')) {
            return false;
        }

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        $warehouseIds = $item->details->pluck('warehouse_id')->filter()->toArray();

        return $user->warehouses->whereIn('id', $warehouseIds)->isNotEmpty();
    }

    public function create(User $user): bool
    {
        return $user->can('item.create');
    }

    public function update(User $user, ItemHeader $item): bool
    {
        if (! $user->can('item.edit')) {
            return false;
        }

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        $warehouseIds = $item->details->pluck('warehouse_id')->filter()->toArray();

        return $user->warehouses->whereIn('id', $warehouseIds)->isNotEmpty();
    }

    public function delete(User $user, ItemHeader $item): bool
    {
        if (! $user->can('item.delete')) {
            return false;
        }

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        $warehouseIds = $item->details->pluck('warehouse_id')->filter()->toArray();

        return $user->warehouses->whereIn('id', $warehouseIds)->isNotEmpty();
    }

    public function import(User $user): bool
    {
        return $user->can('item.import');
    }

    public function export(User $user): bool
    {
        return $user->can('item.export');
    }
}
