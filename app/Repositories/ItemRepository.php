<?php

namespace App\Repositories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemRepository extends BaseRepository
{
    protected function model(): string
    {
        return Item::class;
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function search(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['warehouse', 'creator']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('kode_tabung', 'like', "%{$search}%")
                  ->orWhere('serial_no', 'like', "%{$search}%")
                  ->orWhere('deskripsi_isi_tabung', 'like', "%{$search}%")
                  ->orWhere('vendor', 'like', "%{$search}%")
                  ->orWhere('pemilik_tabung', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['warehouse_id'])) {
            $query->where('lokasi_gudang_id', $filters['warehouse_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        if (!empty($filters['vendor'])) {
            $query->where('vendor', $filters['vendor']);
        }

        if (!empty($filters['tahun_pembuatan'])) {
            $query->where('tahun_pembuatan', $filters['tahun_pembuatan']);
        }

        if (isset($filters['rusak'])) {
            $query->where('rusak', filter_var($filters['rusak'], FILTER_VALIDATE_BOOLEAN));
        }

        if (isset($filters['dijual'])) {
            $query->where('dijual', filter_var($filters['dijual'], FILTER_VALIDATE_BOOLEAN));
        }

        if (!empty($filters['warehouse_ids'])) {
            $query->whereIn('lokasi_gudang_id', (array) $filters['warehouse_ids']);
        }

        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        $perPage = $filters['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    public function getKategoris(): array
    {
        return $this->model->newQuery()
            ->whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori')
            ->toArray();
    }

    public function getVendors(): array
    {
        return $this->model->newQuery()
            ->whereNotNull('vendor')
            ->distinct()
            ->pluck('vendor')
            ->toArray();
    }

    public function countByStatus(string $status): int
    {
        return $this->model->newQuery()->where('status', $status)->count();
    }

    public function countRusak(): int
    {
        return $this->model->newQuery()->where('rusak', true)->count();
    }

    public function countDijual(): int
    {
        return $this->model->newQuery()->where('dijual', true)->count();
    }

    public function countByWarehouse(): array
    {
        return $this->model->newQuery()
            ->selectRaw('lokasi_gudang_id, count(*) as total')
            ->whereNotNull('lokasi_gudang_id')
            ->groupBy('lokasi_gudang_id')
            ->pluck('total', 'lokasi_gudang_id')
            ->toArray();
    }

    public function countByKategori(): array
    {
        return $this->model->newQuery()
            ->selectRaw('kategori, count(*) as total')
            ->whereNotNull('kategori')
            ->groupBy('kategori')
            ->pluck('total', 'kategori')
            ->toArray();
    }

    public function countByYear(): array
    {
        return $this->model->newQuery()
            ->selectRaw('YEAR(tanggal_perolehan) as year, count(*) as total')
            ->whereNotNull('tanggal_perolehan')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('total', 'year')
            ->toArray();
    }

    public function bulkDelete(array $ids): int
    {
        return $this->model->newQuery()->whereIn('id', $ids)->delete();
    }
}
