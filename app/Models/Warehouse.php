<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'warehouse_location';

    protected $primaryKey = 'whsl_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'branch_id',
        'whsl_code',
        'whsl_name',
        'whsl_type',
        'whsl_parent_id',
        'whsl_parent_path',
        'whsl_status',
        'whsl_level',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'whsl_parent_id', 'whsl_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'whsl_parent_id', 'whsl_id');
    }

    public function itemDetails()
    {
        return $this->hasMany(ItemDetail::class, 'whsl_id', 'whsl_id');
    }

    public function scopeActive($query)
    {
        return $query->where('whsl_status', '0');
    }
}
