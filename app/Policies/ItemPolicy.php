<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('item.view');
    }

    public function view(User $user, Item $item): bool
    {
        if (!$user->can('item.view')) {
            return false;
        }

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->warehouses->contains('id', $item->lokasi_gudang_id);
    }

    public function create(User $user): bool
    {
        return $user->can('item.create');
    }

    public function update(User $user, Item $item): bool
    {
        if (!$user->can('item.edit')) {
            return false;
        }

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->warehouses->contains('id', $item->lokasi_gudang_id);
    }

    public function delete(User $user, Item $item): bool
    {
        if (!$user->can('item.delete')) {
            return false;
        }

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->warehouses->contains('id', $item->lokasi_gudang_id);
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
