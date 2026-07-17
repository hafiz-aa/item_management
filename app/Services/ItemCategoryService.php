<?php

namespace App\Services;

use App\Models\ItemCategory;
use App\Repositories\ActivityLogRepository;
use App\Repositories\ItemCategoryRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ItemCategoryService extends BaseService
{
    private ActivityLogRepository $logRepo;

    public function __construct(ItemCategoryRepository $repository, ActivityLogRepository $logRepo)
    {
        parent::__construct($repository);
        $this->logRepo = $logRepo;
    }

    public function search(array $filters = []): LengthAwarePaginator
    {
        return $this->repository->search($filters);
    }

    public function findById(int $id): ItemCategory
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): ItemCategory
    {
        $category = $this->repository->create($data);

        $this->logRepo->log(
            Auth::id(),
            'item_category_created',
            "Membuat item category baru: {$category->category_code}",
            ItemCategory::class,
            $category->category_id
        );

        return $category;
    }

    public function update(ItemCategory $category, array $data): bool
    {
        $updated = $this->repository->update($category, $data);

        if ($updated) {
            $this->logRepo->log(
                Auth::id(),
                'item_category_updated',
                "Mengupdate item category: {$category->category_code}",
                ItemCategory::class,
                $category->category_id
            );
        }

        return $updated;
    }

    public function delete(ItemCategory $category): bool
    {
        $code = $category->category_code;
        $deleted = $this->repository->delete($category);

        if ($deleted) {
            $this->logRepo->log(
                Auth::id(),
                'item_category_deleted',
                "Menghapus item category: {$code}",
                ItemCategory::class,
                $category->category_id
            );
        }

        return $deleted;
    }

    public function restore(int $id): bool
    {
        $category = ItemCategory::onlyTrashed()->findOrFail($id);
        $restored = $category->restore();

        if ($restored) {
            $this->logRepo->log(
                Auth::id(),
                'item_category_restored',
                "Memulihkan item category: {$category->category_code}",
                ItemCategory::class,
                $category->category_id
            );
        }

        return $restored;
    }
}
