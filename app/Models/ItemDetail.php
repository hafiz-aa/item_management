<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model
{
    protected $table = 'item_detail';

    protected $primaryKey = 'itemd_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'branch_id',
        'masti_id',
        'itemd_code',
        'itemd_month',
        'itemd_year',
        'itemd_weight',
        'itemd_serial_no',
        'itemd_capacity',
        'uom_id',
        'itemd_acquired_date',
        'vend_id',
        'itemd_qty',
        'itemd_status',
        'itemd_position',
        'itemd_is_broken',
        'itemd_is_wo',
        'itemd_is_dispossed',
        'whsl_id',
        'original_branch_id',
    ];

    protected function casts(): array
    {
        return [
            'itemd_acquired_date' => 'date',
            'itemd_qty' => 'double',
            'itemd_weight' => 'double',
            'itemd_capacity' => 'double',
        ];
    }

    public function header()
    {
        return $this->belongsTo(MasterItem::class, 'masti_id', 'masti_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'whsl_id', 'whsl_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function originalBranch()
    {
        return $this->belongsTo(Branch::class, 'original_branch_id', 'branch_id');
    }

    public function scopeActive($query)
    {
        return $query->where('itemd_status', '0');
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('whsl_id', $warehouseId);
    }
}
