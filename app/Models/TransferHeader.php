<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferHeader extends Model
{
    protected $table = 'transfer_header';

    protected $primaryKey = 'tth_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'branch_id',
        'tth_code',
        'tth_date',
        'branch_id_to',
        'tth_status',
        'emp_id_sender',
        'tth_is_canceled',
        'tth_canceled_date',
        'tth_canceled_reason',
        'canceled_by',
        'tth_ba_no',
        'tth_po_no',
        'tth_notes',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
        'tth_date' => 'date',
        'tth_canceled_date' => 'datetime',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(TransferDetail::class, 'tth_id', 'tth_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function branchTo()
    {
        return $this->belongsTo(Branch::class, 'branch_id_to', 'branch_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id_sender', 'emp_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'users_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->tth_status) {
            '0' => 'Belum Diterima',
            '1' => 'Diterima Sebagian',
            '2' => 'Diterima Semua',
            default => '-',
        };
    }
}
