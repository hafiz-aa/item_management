<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\ActivityLog;
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
                'total_items' => Item::count(),
                'active_items' => $this->itemRepo->countByStatus('Aktif'),
                'damaged_items' => $this->itemRepo->countRusak(),
                'sold_items' => $this->itemRepo->countDijual(),
                'total_warehouses' => Warehouse::count(),
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
        ];
    }

    private function getWarehouseChartData(): array
    {
        $warehouseCounts = $this->itemRepo->countByWarehouse();
        $warehouses = Warehouse::whereIn('id', array_keys($warehouseCounts))->pluck('nama_gudang', 'id');

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

        return [
            'labels' => array_keys($kategoriCounts),
            'data' => array_values($kategoriCounts),
        ];
    }

    private function getYearChartData(): array
    {
        $yearCounts = $this->itemRepo->countByYear();

        return [
            'labels' => array_keys($yearCounts),
            'data' => array_values($yearCounts),
        ];
    }

    public function getRecentActivities(int $limit = 10)
    {
        return ActivityLog::with('user')->latest()->limit($limit)->get();
    }
}
