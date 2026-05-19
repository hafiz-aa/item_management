<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'model_type',
        'model_id',
        'description',
        'data',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $limit = 50)
    {
        return $query->latest()->limit($limit);
    }
}
