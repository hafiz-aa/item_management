<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChangeItemDescriptionDetail extends Model
{
    protected $table = 'change_item_description_detail';

    protected $primaryKey = 'cidd_id';

    public $timestamps = false;

    protected $fillable = [
        'cidh_id',
        'itemd_id',
        'masti_id_old',
        'masti_id_new',
        'cidd_is_canceled',
        'cidd_canceled_reason',
        'cidd_notes',
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(ChangeItemDescriptionHeader::class, 'cidh_id', 'cidh_id');
    }

    public function itemDetail(): BelongsTo
    {
        return $this->belongsTo(ItemDetail::class, 'itemd_id', 'itemd_id');
    }
}
