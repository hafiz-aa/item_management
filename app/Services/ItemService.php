<?php

namespace App\Services;

use App\Models\ItemDetail;
use App\Models\MasterItem;
use App\Repositories\ActivityLogRepository;
use App\Repositories\ItemRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function findById(int $id): MasterItem
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): MasterItem
    {
        return DB::transaction(function () use ($data) {
            $headerData = [
                'comp_id' => $data['comp_id'] ?? 0,
                'masti_code' => $data['item_code'],
                'masti_name' => $data['item_name'] ?? null,
                'masti_capacity' => $data['capacity'] ?? null,
                'uom_id_1' => $data['uom_id_1'] ?? 1,
                'uom_id_2' => $data['uom_id_2'] ?? 0,
                'cati_id' => $data['cati_id'] ?? null,
            ];

            $header = $this->repository->create($headerData);

            $detailData = [
                'comp_id' => $data['comp_id'] ?? 0,
                'masti_id' => $header->masti_id,
                'branch_id' => $data['branch_id'] ?? null,
                'whsl_id' => $data['whsl_id'] ?? null,
                'itemd_acquired_date' => $data['itemd_acquired_date'] ?? null,
                'itemd_code' => $data['itemd_code'] ?? null,
                'itemd_qty' => $data['itemd_qty'] ?? 1,
                'itemd_status' => $data['itemd_status'] ?? '0',
                'itemd_position' => $data['itemd_position'] ?? 'Internal',
                'itemd_is_broken' => $data['itemd_is_broken'] ?? '0',
                'itemd_is_dispossed' => $data['itemd_is_dispossed'] ?? '0',
                'itemd_is_wo' => $data['itemd_is_wo'] ?? '0',
                'original_branch_id' => $data['original_branch_id'] ?? 0,
                'uom_id' => $data['uom_id'] ?? 1,
            ];

            ItemDetail::create($detailData);

            $this->logRepo->log(
                Auth::id(),
                'item_created',
                "Membuat item baru: {$header->masti_code}",
                MasterItem::class,
                $header->masti_id,
                $header->toArray()
            );

            return $header;
        });
    }

    public function update(MasterItem $header, array $data): bool
    {
        return DB::transaction(function () use ($header, $data) {
            $headerData = array_filter([
                'comp_id' => $data['comp_id'] ?? null,
                'masti_code' => $data['item_code'] ?? null,
                'masti_name' => $data['item_name'] ?? null,
                'masti_capacity' => $data['capacity'] ?? null,
                'uom_id_1' => $data['uom_id_1'] ?? null,
                'uom_id_2' => $data['uom_id_2'] ?? null,
                'cati_id' => $data['cati_id'] ?? null,
            ], fn ($v) => $v !== null);

            $header->update($headerData);

            $detail = $header->details()->first();
            if ($detail) {
                $detailData = array_filter([
                    'comp_id' => $data['comp_id'] ?? null,
                    'branch_id' => $data['branch_id'] ?? null,
                    'whsl_id' => $data['whsl_id'] ?? null,
                    'itemd_acquired_date' => $data['itemd_acquired_date'] ?? null,
                    'itemd_code' => $data['itemd_code'] ?? null,
                    'itemd_qty' => $data['itemd_qty'] ?? null,
                    'itemd_status' => $data['itemd_status'] ?? null,
                    'itemd_position' => $data['itemd_position'] ?? null,
                    'itemd_is_broken' => $data['itemd_is_broken'] ?? null,
                    'itemd_is_dispossed' => $data['itemd_is_dispossed'] ?? null,
                    'itemd_is_wo' => $data['itemd_is_wo'] ?? null,
                    'original_branch_id' => $data['original_branch_id'] ?? null,
                    'uom_id' => $data['uom_id'] ?? null,
                ], fn ($v) => $v !== null);

                $detail->update($detailData);
            }

            $this->logRepo->log(
                Auth::id(),
                'item_updated',
                "Mengupdate item: {$header->masti_code}",
                MasterItem::class,
                $header->masti_id,
                $header->fresh()->toArray()
            );

            return true;
        });
    }

    public function delete(MasterItem $header): bool
    {
        $code = $header->masti_code;
        $deleted = $this->repository->delete($header);

        if ($deleted) {
            $this->logRepo->log(
                Auth::id(),
                'item_deleted',
                "Menghapus item: {$code}",
                MasterItem::class,
                $header->masti_id
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
                "Menghapus {$deleted} item secara massal"
            );
        }

        return $deleted;
    }

    public function getCatIds(): array
    {
        return $this->repository->getCatIds();
    }
}
