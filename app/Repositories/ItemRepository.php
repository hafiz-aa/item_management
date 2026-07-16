<?php

namespace App\Repositories;

use App\Models\ItemDetail;
use App\Models\ItemHeader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemRepository extends BaseRepository
{
    protected function model(): string
    {
        return ItemHeader::class;
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function search(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['details.warehouse', 'creator'])
            ->withCount('details');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                    ->orWhere('item_name', 'like', "%{$search}%")
                    ->orWhere('cat_id', 'like', "%{$search}%")
                    ->orWhereHas('details', function (Builder $dq) use ($search) {
                        $dq->where('itemd_code', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['warehouse_id'])) {
            $query->whereHas('details', function (Builder $q) use ($filters) {
                $q->where('warehouse_id', $filters['warehouse_id']);
            });
        }

        if (! empty($filters['status'])) {
            $query->whereHas('details', function (Builder $q) use ($filters) {
                $q->where('status', $filters['status']);
            });
        }

        if (! empty($filters['cat_id'])) {
            $query->where('cat_id', $filters['cat_id']);
        }

        if (! empty($filters['warehouse_ids'])) {
            $query->whereHas('details', function (Builder $q) use ($filters) {
                $q->whereIn('warehouse_id', (array) $filters['warehouse_ids']);
            });
        }

        $sortField = $filters['sort_field'] ?? 'item_headers.created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        $perPage = $filters['per_page'] ?? 15;

        return $query->paginate($perPage);
    }

    public function getCatIds(): array
    {
        return $this->model->newQuery()
            ->whereNotNull('cat_id')
            ->distinct()
            ->pluck('cat_id')
            ->toArray();
    }

    public function countByStatus(string $status): int
    {
        return ItemDetail::where('status', $status)->count();
    }

    public function countBroken(): int
    {
        return ItemDetail::where('is_broken', true)->count();
    }

    public function countDisposed(): int
    {
        return ItemDetail::where('is_dispossed', true)->count();
    }

    public function countByWarehouse(): array
    {
        return ItemDetail::selectRaw('warehouse_id, count(*) as total')
            ->whereNotNull('warehouse_id')
            ->groupBy('warehouse_id')
            ->pluck('total', 'warehouse_id')
            ->toArray();
    }

    public function countByKategori(): array
    {
        return $this->model->newQuery()
            ->selectRaw('cat_id, count(*) as total')
            ->whereNotNull('cat_id')
            ->groupBy('cat_id')
            ->pluck('total', 'cat_id')
            ->toArray();
    }

    public function countTotal(): int
    {
        return $this->model->newQuery()->count();
    }

    public function bulkDelete(array $ids): int
    {
        return $this->model->newQuery()->whereIn('itemh_id', $ids)->delete();
    }
}
