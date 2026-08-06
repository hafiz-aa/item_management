<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrokenDetail extends Model
{
    protected $table = 'broken_detail';

    protected $primaryKey = 'brokd_id';

    public $timestamps = false;

    protected $fillable = [
        'brokh_id',
        'itemd_id',
        'brokd_qty',
        'brokd_is_canceled',
        'brokd_is_dispossed',
        'brokd_is_wo',
        'brokd_canceled_reason',
        'brokd_notes',
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(BrokenHeader::class, 'brokh_id', 'brokh_id');
    }

    public function itemDetail(): BelongsTo
    {
        return $this->belongsTo(ItemDetail::class, 'itemd_id', 'itemd_id');
    }
}
