<?php

namespace App\Repositories;

use App\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityLogRepository extends BaseRepository
{
    protected function model(): string
    {
        return ActivityLog::class;
    }

    public function search(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('user');

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $query->where('description', 'like', "%{$filters['search']}%");
        }

        $query->latest();
        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function log($userId, string $type, string $description, ?string $modelType = null, ?int $modelId = null, ?array $data = null): ActivityLog
    {
        return $this->model->create([
            'user_id' => $userId,
            'type' => $type,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
