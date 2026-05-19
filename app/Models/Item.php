<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode_tabung',
        'deskripsi_isi_tabung',
        'serial_no',
        'tahun_pembuatan',
        'berat',
        'kapasitas',
        'uom',
        'qty',
        'tanggal_perolehan',
        'kategori',
        'status',
        'rusak',
        'dijual',
        'lokasi_gudang_id',
        'vendor',
        'pemilik_tabung',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'tahun_pembuatan' => 'integer',
            'berat' => 'decimal:2',
            'kapasitas' => 'decimal:2',
            'qty' => 'integer',
            'tanggal_perolehan' => 'date',
            'rusak' => 'boolean',
            'dijual' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'lokasi_gudang_id');
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

    public function scopeRusak($query)
    {
        return $query->where('rusak', true);
    }

    public function scopeDijual($query)
    {
        return $query->where('dijual', true);
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('lokasi_gudang_id', $warehouseId);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
}
