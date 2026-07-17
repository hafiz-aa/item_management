<?php

namespace App\Services;

use App\Models\MasterItem;
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

    public function findById(int $id): MasterItem
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): MasterItem
    {
        $itemDesc = $this->repository->create($data);

        $this->logRepo->log(
            Auth::id(),
            'item_description_created',
            "Membuat item description baru: {$itemDesc->masti_code}",
            MasterItem::class,
            $itemDesc->masti_id
        );

        return $itemDesc;
    }

    public function update(MasterItem $itemDesc, array $data): bool
    {
        $updated = $this->repository->update($itemDesc, $data);

        if ($updated) {
            $this->logRepo->log(
                Auth::id(),
                'item_description_updated',
                "Mengupdate item description: {$itemDesc->masti_code}",
                MasterItem::class,
                $itemDesc->masti_id
            );
        }

        return $updated;
    }

    public function delete(MasterItem $itemDesc): bool
    {
        $code = $itemDesc->masti_code;
        $deleted = $this->repository->delete($itemDesc);

        if ($deleted) {
            $this->logRepo->log(
                Auth::id(),
                'item_description_deleted',
                "Menghapus item description: {$code}",
                MasterItem::class,
                $itemDesc->masti_id
            );
        }

        return $deleted;
    }

    public function restore(int $id): bool
    {
        return false;
    }
}
