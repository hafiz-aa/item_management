<?php

namespace App\Services;

use App\Models\ItemDetail;
use App\Models\ItemHeader;
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

    public function findById(int $id): ItemHeader
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): ItemHeader
    {
        return DB::transaction(function () use ($data) {
            $headerData = [
                'company_id' => $data['company_id'] ?? null,
                'item_code' => $data['item_code'],
                'item_name' => $data['item_name'] ?? null,
                'capacity' => $data['capacity'] ?? 0,
                'uom_id_1' => $data['uom_id_1'] ?? 'Kg',
                'uom_id_2' => $data['uom_id_2'] ?? null,
                'cat_id' => $data['cat_id'] ?? null,
                'created_by' => Auth::id(),
            ];

            $header = $this->repository->create($headerData);

            $detailData = [
                'itemh_id' => $header->itemh_id,
                'company_id' => $data['company_id'] ?? null,
                'branch_id' => $data['branch_id'] ?? null,
                'whsl_id' => $data['whsl_id'] ?? null,
                'acquired_date' => $data['acquired_date'] ?? null,
                'itemd_code' => $data['itemd_code'] ?? null,
                'qty' => $data['qty'] ?? 1,
                'status' => $data['status'] ?? 'Aktif',
                'position_id' => $data['position_id'] ?? null,
                'is_broken' => $data['is_broken'] ?? false,
                'is_dispossed' => $data['is_dispossed'] ?? false,
                'is_writeoff' => $data['is_writeoff'] ?? false,
                'warehouse_id' => $data['warehouse_id'] ?? null,
                'original_branch_id' => $data['original_branch_id'] ?? null,
                'created_by' => Auth::id(),
            ];

            ItemDetail::create($detailData);

            $this->logRepo->log(
                Auth::id(),
                'item_created',
                "Membuat item baru: {$header->item_code}",
                ItemHeader::class,
                $header->itemh_id,
                $header->toArray()
            );

            return $header;
        });
    }

    public function update(ItemHeader $header, array $data): bool
    {
        return DB::transaction(function () use ($header, $data) {
            $headerData = array_filter([
                'company_id' => $data['company_id'] ?? null,
                'item_code' => $data['item_code'] ?? null,
                'item_name' => $data['item_name'] ?? null,
                'capacity' => $data['capacity'] ?? null,
                'uom_id_1' => $data['uom_id_1'] ?? null,
                'uom_id_2' => $data['uom_id_2'] ?? null,
                'cat_id' => $data['cat_id'] ?? null,
                'updated_by' => Auth::id(),
            ], fn ($v) => $v !== null);

            $header->update($headerData);

            $detail = $header->details()->first();
            if ($detail) {
                $detailData = array_filter([
                    'company_id' => $data['company_id'] ?? null,
                    'branch_id' => $data['branch_id'] ?? null,
                    'whsl_id' => $data['whsl_id'] ?? null,
                    'acquired_date' => $data['acquired_date'] ?? null,
                    'itemd_code' => $data['itemd_code'] ?? null,
                    'qty' => $data['qty'] ?? null,
                    'status' => $data['status'] ?? null,
                    'position_id' => $data['position_id'] ?? null,
                    'is_broken' => $data['is_broken'] ?? null,
                    'is_dispossed' => $data['is_dispossed'] ?? null,
                    'is_writeoff' => $data['is_writeoff'] ?? null,
                    'warehouse_id' => $data['warehouse_id'] ?? null,
                    'original_branch_id' => $data['original_branch_id'] ?? null,
                    'updated_by' => Auth::id(),
                ], fn ($v) => $v !== null);

                $detail->update($detailData);
            }

            $this->logRepo->log(
                Auth::id(),
                'item_updated',
                "Mengupdate item: {$header->item_code}",
                ItemHeader::class,
                $header->itemh_id,
                $header->fresh()->toArray()
            );

            return true;
        });
    }

    public function delete(ItemHeader $header): bool
    {
        $code = $header->item_code;
        $deleted = $this->repository->delete($header);

        if ($deleted) {
            $this->logRepo->log(
                Auth::id(),
                'item_deleted',
                "Menghapus item: {$code}",
                ItemHeader::class,
                $header->itemh_id
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
