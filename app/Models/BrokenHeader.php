<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BrokenHeader extends Model
{
    protected $table = 'broken_header';

    protected $primaryKey = 'brokh_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'branch_id',
        'brokh_code',
        'brokh_date',
        'brokh_status',
        'brokh_is_canceled',
        'brokh_canceled_date',
        'brokh_canceled_reason',
        'canceled_by',
        'brokh_notes',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
        'brokh_date' => 'date',
        'brokh_canceled_date' => 'date',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'users_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(BrokenDetail::class, 'brokh_id', 'brokh_id');
    }
}
