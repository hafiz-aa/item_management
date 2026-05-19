<?php

namespace App\Services;

use App\Models\Item;
use App\Repositories\ItemRepository;
use App\Repositories\ActivityLogRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ItemService extends BaseService
{
    private ActivityLogRepository $logRepo;

    public function __construct(ItemRepository $repository, ActivityLogRepository $logRepo)
    {
        parent::__construct($repository);
        $this->logRepo = $logRepo;
    }

    public function search(array $filters = []): LengthAwarePaginator
    {
        return $this->repository->search($filters);
    }

    public function findById(int $id): Item
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): Item
    {
        $data['created_by'] = Auth::id();
        $item = $this->repository->create($data);

        $this->logRepo->log(
            Auth::id(),
            'item_created',
            "Membuat tabung baru: {$item->kode_tabung}",
            Item::class,
            $item->id,
            $item->toArray()
        );

        return $item;
    }

    public function update(Item $item, array $data): bool
    {
        $data['updated_by'] = Auth::id();
        $updated = $this->repository->update($item, $data);

        if ($updated) {
            $this->logRepo->log(
                Auth::id(),
                'item_updated',
                "Mengupdate tabung: {$item->kode_tabung}",
                Item::class,
                $item->id,
                $item->fresh()->toArray()
            );
        }

        return $updated;
    }

    public function delete(Item $item): bool
    {
        $kode = $item->kode_tabung;
        $deleted = $this->repository->delete($item);

        if ($deleted) {
            $this->logRepo->log(
                Auth::id(),
                'item_deleted',
                "Menghapus tabung: {$kode}",
                Item::class,
                $item->id
            );
        }

        return $deleted;
    }

    public function bulkDelete(array $ids): int
    {
        $deleted = $this->repository->bulkDelete($ids);

        if ($deleted > 0) {
            $this->logRepo->log(
                Auth::id(),
                'items_bulk_deleted',
                "Menghapus {$deleted} tabung secara massal"
            );
        }

        return $deleted;
    }

    public function getKategoris(): array
    {
        return $this->repository->getKategoris();
    }

    public function getVendors(): array
    {
        return $this->repository->getVendors();
    }
}
