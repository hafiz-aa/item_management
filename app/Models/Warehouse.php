<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'warehouse_id';

    protected $fillable = [
        'branch_id',
        'parent_id',
        'kode_gudang',
        'nama_gudang',
        'tipe',
        'alamat',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'warehouse_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'warehouse_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function itemDetails()
    {
        return $this->hasMany(ItemDetail::class, 'warehouse_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_warehouse', 'warehouse_id', 'user_id', 'warehouse_id', 'id');
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

    public function scopePusat($query)
    {
        return $query->where('tipe', 'Kantor Pusat');
    }

    public function scopeCabang($query)
    {
        return $query->where('tipe', 'Kantor Cabang');
    }
}
