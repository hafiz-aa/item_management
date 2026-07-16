<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemDetail extends Model
{
    use SoftDeletes;

    protected $table = 'item_details';

    protected $primaryKey = 'itemd_id';

    protected $fillable = [
        'itemh_id',
        'company_id',
        'branch_id',
        'whsl_id',
        'acquired_date',
        'itemd_code',
        'qty',
        'status',
        'position_id',
        'is_broken',
        'is_dispossed',
        'is_writeoff',
        'warehouse_id',
        'original_branch_id',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'acquired_date' => 'date',
            'qty' => 'integer',
            'is_broken' => 'boolean',
            'is_dispossed' => 'boolean',
            'is_writeoff' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function header()
    {
        return $this->belongsTo(ItemHeader::class, 'itemh_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function originalBranch()
    {
        return $this->belongsTo(Branch::class, 'original_branch_id', 'branch_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Aktif');
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }
}
