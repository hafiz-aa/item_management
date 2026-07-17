<?php

namespace App\Repositories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;

class WarehouseRepository extends BaseRepository
{
    protected function model(): string
    {
        return Warehouse::class;
    }

    public function getActive(): Collection
    {
        return $this->model->newQuery()->where('whsl_status', '0')->get();
    }

    public function getForUser($user): Collection
    {
        if ($user->hasRole('Super Admin')) {
            return $this->model->newQuery()->get();
        }

        $branchIds = $user->branches->pluck('branch_id')->toArray();

        return $this->model->newQuery()->whereIn('branch_id', $branchIds)->get();
    }
}
