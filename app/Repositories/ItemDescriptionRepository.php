<?php

namespace App\Repositories;

use App\Models\MasterItem;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemDescriptionRepository extends BaseRepository
{
    protected function model(): string
    {
        return MasterItem::class;
    }

    public function search(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('category');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('masti_code', 'like', "%{$search}%")
                    ->orWhere('masti_name', 'like', "%{$search}%");
            });
        }

        $query->orderBy('masti_id', 'asc');

        return $query->paginate($filters['per_page'] ?? 15);
    }
}
