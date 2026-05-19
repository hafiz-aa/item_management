<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode_gudang',
        'nama_gudang',
        'alamat',
        'kota',
        'provinsi',
        'telepon',
        'penanggung_jawab',
        'keterangan',
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

    public function items()
    {
        return $this->hasMany(Item::class, 'lokasi_gudang_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_warehouse');
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
}
