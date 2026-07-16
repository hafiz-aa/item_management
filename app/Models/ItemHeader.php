<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemHeader extends Model
{
    use SoftDeletes;

    protected $table = 'item_headers';

    protected $primaryKey = 'itemh_id';

    protected $fillable = [
        'company_id',
        'item_code',
        'item_name',
        'capacity',
        'uom_id_1',
        'uom_id_2',
        'cat_id',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function details()
    {
        return $this->hasMany(ItemDetail::class, 'itemh_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
