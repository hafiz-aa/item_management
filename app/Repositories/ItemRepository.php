<?php

namespace App\Repositories;

use App\Models\ItemDetail;
use App\Models\MasterItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemRepository extends BaseRepository
{
    protected function model(): string
    {
        return MasterItem::class;
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function search(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['details.warehouse', 'category'])
            ->withCount('details');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('masti_code', 'like', "%{$search}%")
                    ->orWhere('masti_name', 'like', "%{$search}%")
                    ->orWhereHas('details', function (Builder $dq) use ($search) {
                        $dq->where('itemd_code', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['whsl_id'])) {
            $query->whereHas('details', function (Builder $q) use ($filters) {
                $q->where('whsl_id', $filters['whsl_id']);
            });
        }

        if (! empty($filters['status'])) {
            $query->whereHas('details', function (Builder $q) use ($filters) {
                $q->where('itemd_status', $filters['status']);
            });
        }

        if (! empty($filters['cati_id'])) {
            $query->where('cati_id', $filters['cati_id']);
        }

        if (! empty($filters['warehouse_ids'])) {
            $query->whereHas('details', function (Builder $q) use ($filters) {
                $q->whereIn('whsl_id', (array) $filters['warehouse_ids']);
            });
        }

        $sortField = $filters['sort_field'] ?? 'master_item.masti_id';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        $perPage = $filters['per_page'] ?? 15;

        return $query->paginate($perPage);
    }

    public function getCatIds(): array
    {
        return $this->model->newQuery()
            ->whereNotNull('cati_id')
            ->distinct()
            ->pluck('cati_id')
            ->toArray();
    }

    public function countByStatus(string $status): int
    {
        return ItemDetail::where('itemd_status', $status)->count();
    }

    public function countBroken(): int
    {
        return ItemDetail::where('itemd_is_broken', '1')->count();
    }

    public function countDisposed(): int
    {
        return ItemDetail::where('itemd_is_dispossed', '1')->count();
    }

    public function countByWarehouse(): array
    {
        return ItemDetail::selectRaw('whsl_id, count(*) as total')
            ->whereNotNull('whsl_id')
            ->groupBy('whsl_id')
            ->pluck('total', 'whsl_id')
            ->toArray();
    }

    public function countByKategori(): array
    {
        return $this->model->newQuery()
            ->selectRaw('cati_id, count(*) as total')
            ->whereNotNull('cati_id')
            ->groupBy('cati_id')
            ->pluck('total', 'cati_id')
            ->toArray();
    }

    public function countTotal(): int
    {
        return $this->model->newQuery()->count();
    }

    public function bulkDelete(array $ids): int
    {
        return $this->model->newQuery()->whereIn('masti_id', $ids)->delete();
    }
}
