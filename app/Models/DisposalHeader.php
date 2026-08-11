<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DisposalHeader extends Model
{
    protected $table = 'dispossal_header';

    protected $primaryKey = 'disph_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'branch_id',
        'disph_code',
        'disph_date',
        'disph_sources',
        'brokh_id',
        'disph_reason',
        'cust_id',
        'emp_id_dispossed_by',
        'disph_is_canceled',
        'disph_canceled_date',
        'canceled_by',
        'disph_canceled_reason',
        'disph_notes',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
        'disph_date' => 'date',
        'disph_canceled_date' => 'date',
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function disposedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'emp_id_dispossed_by', 'emp_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'users_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DisposalDetail::class, 'disph_id', 'disph_id');
    }
}
