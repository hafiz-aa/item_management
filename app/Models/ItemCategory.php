<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $table = 'category_item';

    protected $primaryKey = 'cati_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'cati_code',
        'cati_name',
        'cati_notes',
    ];

    public function masterItems()
    {
        return $this->hasMany(MasterItem::class, 'cati_id');
    }
}
