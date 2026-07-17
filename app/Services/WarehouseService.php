<?php

namespace App\Services;

use App\Repositories\ActivityLogRepository;
use App\Repositories\WarehouseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class WarehouseService extends BaseService
{
    private ActivityLogRepository $logRepo;

    public function __construct(WarehouseRepository $repository, ActivityLogRepository $logRepo)
    {
        parent::__construct($repository);
        $this->logRepo = $logRepo;
    }

    public function getForUser($user): Collection
    {
        return $this->repository->getForUser($user);
    }

    public function getActive(): Collection
    {
        return $this->repository->getActive();
    }

    public function create(array $data)
    {
        $warehouse = $this->repository->create($data);

        $this->logRepo->log(
            Auth::id(),
            'warehouse_created',
            "Membuat gudang baru: {$warehouse->whsl_name}",
            get_class($warehouse),
            $warehouse->whsl_id
        );

        return $warehouse;
    }

    public function update($warehouse, array $data): bool
    {
        return $this->repository->update($warehouse, $data);
    }

    public function delete($warehouse): bool
    {
        return $this->repository->delete($warehouse);
    }
}
