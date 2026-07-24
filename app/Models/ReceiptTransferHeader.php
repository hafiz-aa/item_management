<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptTransferHeader extends Model
{
    protected $table = 'receipt_transfer_header';

    protected $primaryKey = 'rth_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'branch_id',
        'tth_id',
        'rth_code',
        'rth_date',
        'branch_id_from',
        'emp_id_receiver',
        'rth_is_canceled',
        'rth_canceled_date',
        'rth_canceled_reason',
        'canceled_by',
        'rth_ba_no',
        'rth_po_no',
        'rth_notes',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
        'rth_date' => 'date',
        'rth_canceled_date' => 'datetime',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(ReceiptTransferDetail::class, 'rth_id', 'rth_id');
    }

    public function transferHeader()
    {
        return $this->belongsTo(TransferHeader::class, 'tth_id', 'tth_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function branchFrom()
    {
        return $this->belongsTo(Branch::class, 'branch_id_from', 'branch_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Employee::class, 'emp_id_receiver', 'emp_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'users_id');
    }
}
