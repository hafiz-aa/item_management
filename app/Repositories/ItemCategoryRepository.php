<?php

namespace App\Repositories;

use App\Models\ItemCategory;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemCategoryRepository extends BaseRepository
{
    protected function model(): string
    {
        return ItemCategory::class;
    }

    public function search(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->withCount('masterItems');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('cati_code', 'like', "%{$search}%")
                    ->orWhere('cati_name', 'like', "%{$search}%");
            });
        }

        $query->orderBy('cati_id', 'asc');

        return $query->paginate($filters['per_page'] ?? 15);
    }
}
