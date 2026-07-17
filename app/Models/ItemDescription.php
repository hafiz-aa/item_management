<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemDescription extends Model
{
    use SoftDeletes;

    protected $table = 'item_descriptions';

    protected $primaryKey = 'item_desc_id';

    protected $fillable = [
        'item_code',
        'item_description',
        'capacity',
        'uom',
        'category_id',
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

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
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
