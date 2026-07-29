<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnHeader extends Model
{
    protected $table = 'return_header';

    protected $primaryKey = 'reth_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'branch_id',
        'reth_code',
        'issuingh_id',
        'reth_date',
        'reth_ref_no',
        'reth_by',
        'reth_vehicle_no',
        'reth_returned_by',
        'emp_id_receiver',
        'whsl_id',
        'reth_is_canceled',
        'reth_canceled_date',
        'reth_canceled_reason',
        'canceled_by',
        'reth_ba_no',
        'reth_po_no',
        'reth_notes',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
        'reth_date' => 'date',
        'reth_canceled_date' => 'date',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function issuingHeader(): BelongsTo
    {
        return $this->belongsTo(IssuingHeader::class, 'issuingh_id', 'issuingh_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'whsl_id', 'whsl_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'emp_id_receiver', 'emp_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'users_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ReturnDetail::class, 'reth_id', 'reth_id');
    }
}
