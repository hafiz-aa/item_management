<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssuingHeader extends Model
{
    protected $table = 'issuing_header';

    protected $primaryKey = 'issuingh_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'branch_id',
        'issuingh_code',
        'issuingh_date',
        'issuingh_do_no',
        'issuingh_sent_by',
        'issuingh_vehicle_no',
        'emp_id',
        'issuingh_type',
        'cust_id',
        'issuingh_receiver_name',
        'issuingh_status',
        'issuingh_is_canceled',
        'issuingh_canceled_date',
        'issuingh_canceled_reason',
        'canceled_by',
        'issuingh_ba_no',
        'issuingh_po_no',
        'issuingh_notes',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
        'issuingh_date' => 'date',
        'issuingh_canceled_date' => 'date',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'emp_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'users_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(IssuingDetail::class, 'issuingh_id', 'issuingh_id');
    }
}
