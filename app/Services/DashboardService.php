<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\ItemCategory;
use App\Models\ItemDetail;
use App\Models\User;
use App\Models\Warehouse;
use App\Repositories\ItemRepository;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    private ItemRepository $itemRepo;

    public function __construct(ItemRepository $itemRepo)
    {
        $this->itemRepo = $itemRepo;
    }

    public function getStats(): array
    {
        return Cache::remember('dashboard_stats', 300, function () {
            return [
                'total_items' => $this->itemRepo->countTotal(),
                'active_items' => $this->itemRepo->countByStatus('0'),
                'damaged_items' => $this->itemRepo->countBroken(),
                'sold_items' => $this->itemRepo->countDisposed(),
                'total_warehouses' => Warehouse::count(),
                'total_branches' => Branch::count(),
                'total_users' => User::count(),
            ];
        });
    }

    public function getChartData(): array
    {
        return [
            'by_warehouse' => $this->getWarehouseChartData(),
            'by_kategori' => $this->getKategoriChartData(),
            'by_year' => $this->getYearChartData(),
            'warehouses_per_branch' => $this->getWarehousesPerBranch(),
        ];
    }

    private function getWarehouseChartData(): array
    {
        $warehouseCounts = $this->itemRepo->countByWarehouse();
        $warehouses = Warehouse::whereIn('whsl_id', array_keys($warehouseCounts))->pluck('whsl_name', 'whsl_id');

        $labels = [];
        $data = [];
        foreach ($warehouseCounts as $id => $count) {
            $labels[] = $warehouses[$id] ?? "Gudang #{$id}";
            $data[] = $count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getKategoriChartData(): array
    {
        $kategoriCounts = $this->itemRepo->countByKategori();
        $categories = ItemCategory::whereIn('cati_id', array_keys($kategoriCounts))->pluck('cati_code', 'cati_id');

        $labels = [];
        $data = [];
        foreach ($kategoriCounts as $id => $count) {
            $labels[] = $categories[$id] ?? "Kategori #{$id}";
            $data[] = $count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getYearChartData(): array
    {
        $yearCounts = ItemDetail::selectRaw('YEAR(itemd_acquired_date) as year, count(*) as total')
            ->whereNotNull('itemd_acquired_date')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('total', 'year')
            ->toArray();

        return [
            'labels' => array_keys($yearCounts),
            'data' => array_values($yearCounts),
        ];
    }

    private function getWarehousesPerBranch(): array
    {
        $branches = Branch::withCount('warehouses')->orderBy('branch_code')->get();

        return [
            'labels' => $branches->pluck('branch_code')->toArray(),
            'data' => $branches->pluck('warehouses_count')->toArray(),
        ];
    }

    public function getRecentActivities(int $limit = 10)
    {
        return ActivityLog::with('user')->latest()->limit($limit)->get();
    }
}
