<?php

namespace App\Repositories;

use App\Models\Branch;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchRepository extends BaseRepository
{
    protected function model(): string
    {
        return Branch::class;
    }

    public function search(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->withCount('warehouses');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('branch_code', 'like', "%{$search}%")
                    ->orWhere('branch_name', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['trashed'])) {
            $query->onlyTrashed();
        }

        $query->orderBy('branch_id', 'asc');

        return $query->paginate($filters['per_page'] ?? 15);
    }
}
