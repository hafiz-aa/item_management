<?php

namespace App\Services;

use App\Models\ItemDescription;
use App\Repositories\ActivityLogRepository;
use App\Repositories\ItemDescriptionRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ItemDescriptionService extends BaseService
{
    private ActivityLogRepository $logRepo;

    public function __construct(ItemDescriptionRepository $repository, ActivityLogRepository $logRepo)
    {
        parent::__construct($repository);
        $this->logRepo = $logRepo;
    }

    public function search(array $filters = []): LengthAwarePaginator
    {
        return $this->repository->search($filters);
    }

    public function findById(int $id): ItemDescription
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): ItemDescription
    {
        $itemDesc = $this->repository->create($data);

        $this->logRepo->log(
            Auth::id(),
            'item_description_created',
            "Membuat item description baru: {$itemDesc->item_code}",
            ItemDescription::class,
            $itemDesc->item_desc_id
        );

        return $itemDesc;
    }

    public function update(ItemDescription $itemDesc, array $data): bool
    {
        $updated = $this->repository->update($itemDesc, $data);

        if ($updated) {
            $this->logRepo->log(
                Auth::id(),
                'item_description_updated',
                "Mengupdate item description: {$itemDesc->item_code}",
                ItemDescription::class,
                $itemDesc->item_desc_id
            );
        }

        return $updated;
    }

    public function delete(ItemDescription $itemDesc): bool
    {
        $code = $itemDesc->item_code;
        $deleted = $this->repository->delete($itemDesc);

        if ($deleted) {
            $this->logRepo->log(
                Auth::id(),
                'item_description_deleted',
                "Menghapus item description: {$code}",
                ItemDescription::class,
                $itemDesc->item_desc_id
            );
        }

        return $deleted;
    }

    public function restore(int $id): bool
    {
        $itemDesc = ItemDescription::onlyTrashed()->findOrFail($id);
        $restored = $itemDesc->restore();

        if ($restored) {
            $this->logRepo->log(
                Auth::id(),
                'item_description_restored',
                "Memulihkan item description: {$itemDesc->item_code}",
                ItemDescription::class,
                $itemDesc->item_desc_id
            );
        }

        return $restored;
    }
}
