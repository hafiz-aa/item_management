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
        $query = $this->model->newQuery()->withCount('itemDescriptions');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('category_code', 'like', "%{$search}%")
                    ->orWhere('category_name', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['trashed'])) {
            $query->onlyTrashed();
        }

        $query->orderBy('category_id', 'asc');

        return $query->paginate($filters['per_page'] ?? 15);
    }
}
