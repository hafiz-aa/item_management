<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterItem extends Model
{
    protected $table = 'master_item';

    protected $primaryKey = 'masti_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'masti_code',
        'masti_name',
        'masti_capacity',
        'uom_id_1',
        'uom_id_2',
        'cati_id',
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'cati_id', 'cati_id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id_1', 'uom_id');
    }

    public function details()
    {
        return $this->hasMany(ItemDetail::class, 'masti_id', 'masti_id');
    }
}
