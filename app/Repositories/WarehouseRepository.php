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
        return $this->model->newQuery()->where('status', 'Aktif')->get();
    }

    public function getForUser($user): Collection
    {
        if ($user->hasRole('Super Admin')) {
            return $this->model->newQuery()->get();
        }
        return $user->warehouses;
    }
}
