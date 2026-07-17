<?php

namespace App\Policies;

use App\Models\MasterItem;
use App\Models\User;

class ItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('item.view');
    }

    public function view(User $user, MasterItem $item): bool
    {
        if (! $user->can('item.view')) {
            return false;
        }

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        $branchIds = $item->details->pluck('branch_id')->filter()->toArray();

        return $user->branches->whereIn('branch_id', $branchIds)->isNotEmpty();
    }

    public function create(User $user): bool
    {
        return $user->can('item.create');
    }

    public function update(User $user, MasterItem $item): bool
    {
        if (! $user->can('item.edit')) {
            return false;
        }

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        $branchIds = $item->details->pluck('branch_id')->filter()->toArray();

        return $user->branches->whereIn('branch_id', $branchIds)->isNotEmpty();
    }

    public function delete(User $user, MasterItem $item): bool
    {
        if (! $user->can('item.delete')) {
            return false;
        }

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        $branchIds = $item->details->pluck('branch_id')->filter()->toArray();

        return $user->branches->whereIn('branch_id', $branchIds)->isNotEmpty();
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
