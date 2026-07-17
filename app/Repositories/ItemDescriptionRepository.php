<?php

namespace App\Repositories;

use App\Models\ItemDescription;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemDescriptionRepository extends BaseRepository
{
    protected function model(): string
    {
        return ItemDescription::class;
    }

    public function search(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('category');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                    ->orWhere('item_description', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['trashed'])) {
            $query->onlyTrashed();
        }

        $query->orderBy('item_desc_id', 'asc');

        return $query->paginate($filters['per_page'] ?? 15);
    }
}
