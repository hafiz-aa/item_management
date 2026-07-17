<?php

namespace App\Services;

use App\Models\Branch;
use App\Repositories\ActivityLogRepository;
use App\Repositories\BranchRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class BranchService extends BaseService
{
    private ActivityLogRepository $logRepo;

    public function __construct(BranchRepository $repository, ActivityLogRepository $logRepo)
    {
        parent::__construct($repository);
        $this->logRepo = $logRepo;
    }

    public function search(array $filters = []): LengthAwarePaginator
    {
        return $this->repository->search($filters);
    }

    public function findById(int $id): Branch
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): Branch
    {
        $branch = $this->repository->create($data);

        $this->logRepo->log(
            Auth::id(),
            'branch_created',
            "Membuat branch baru: {$branch->branch_code}",
            Branch::class,
            $branch->branch_id
        );

        return $branch;
    }

    public function update(Branch $branch, array $data): bool
    {
        $updated = $this->repository->update($branch, $data);

        if ($updated) {
            $this->logRepo->log(
                Auth::id(),
                'branch_updated',
                "Mengupdate branch: {$branch->branch_code}",
                Branch::class,
                $branch->branch_id
            );
        }

        return $updated;
    }

    public function delete(Branch $branch): bool
    {
        $code = $branch->branch_code;
        $deleted = $this->repository->delete($branch);

        if ($deleted) {
            $this->logRepo->log(
                Auth::id(),
                'branch_deleted',
                "Menghapus branch: {$code}",
                Branch::class,
                $branch->branch_id
            );
        }

        return $deleted;
    }
}
