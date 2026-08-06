<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WriteOffHeader extends Model
{
    protected $table = 'write_off_header';

    protected $primaryKey = 'woh_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'branch_id',
        'woh_code',
        'woh_date',
        'woh_sources',
        'brokh_id',
        'woh_reason',
        'woh_is_canceled',
        'woh_canceled_date',
        'woh_canceled_reason',
        'canceled_by',
        'woh_notes',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
        'woh_date' => 'date',
        'woh_canceled_date' => 'datetime',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function brokenHeader(): BelongsTo
    {
        return $this->belongsTo(BrokenHeader::class, 'brokh_id', 'brokh_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(WriteOffDetail::class, 'woh_id', 'woh_id');
    }
}
